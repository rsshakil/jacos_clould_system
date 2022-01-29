<template>
  <div>
    <div class="row">
      <div class="col-12" v-if="!filter_select_box">
        <h4 class="top_title text-center" style="margin-top: 10px">
          {{ company_name }}
        </h4>
      </div>

      <div class="col-2"></div>
      <div class="col-8">
        <!-- <jacostabList v-if="filter_select_box"></jacostabList>
        <tabList v-else></tabList> -->

      </div>
      <div class="col-2"></div>

      <div class="col-12">
        <div class="">
          <table class="table table-striped table-bordered data_table">
            <thead>
              <tr>
                <th colspan="100%" style="border: none">
                  <button
                    class="btn pull-right text-right btn-primary"
                    style="float: right"
                    @click="new_partner_create_modal"
                  >
                    {{ myLang.add_new }}
                  </button>
                </th>
              </tr>
              <tr v-if="filter_select_box==true">
                <th colspan="2">
                  <multiselect
                    v-model="selected_buyer"
                    id="buyer_name"
                    placeholder="Select Buyer"
                    label="buyer_name"
                    track-by="cmn_company_id"
                    :options="buyers"
                    :multiple="false"
                    :close-on-select="true"
                    :clear-on-select="false"
                    :preserve-search="true"
                    open-direction="bottom"
                    @select="user_filter_by_buyer"
                  ><span slot="noOptions">候補がありません</span> <span slot="noResult">候補がありません</span></multiselect>
                </th>
                <!-- <th colspan="2">
                  <multiselect
                    v-model="selected_seller"
                    id="seller_name"
                    placeholder="Select Seller"
                    label="seller_name"
                    track-by="cmn_company_id"
                    :options="sellers"
                    :multiple="false"
                    :close-on-select="true"
                    :clear-on-select="false"
                    :preserve-search="true"
                    open-direction="bottom"
                    @select="user_filter_by_seller"
                  ></multiselect>
                </th> -->
              </tr>
              <tr>
                <th style="cursor: pointer">No</th>
                <th style="cursor: pointer" @click="sorting('company_name')">{{ myLang.wholesaler_name }} <span class="float-right" :class="iconSet('company_name')"></span></th>
                <th style="cursor: pointer" @click="sorting('jcode')">{{ myLang.wholesaler_code }} <span class="float-right" :class="iconSet('jcode')"></span></th>
                <th style="cursor: pointer" @click="sorting('partner_code')">{{ myLang.customer_code }} <span class="float-right" :class="iconSet('partner_code')"></span></th>
                <th style="cursor: pointer">{{ myLang.status }}</th>
                <th style="cursor: pointer">Edit</th>
                <th style="cursor: pointer">{{ myLang.delete }}</th>
                <th style="cursor: pointer">{{ myLang.details }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(value, index) in company_partner_lists" :key="index">
                <td>{{ index + 1 }}</td>
                <td>{{ value.company_name }}</td>
                <td>{{ value.jcode }}</td>
                <td>{{ value.partner_code }}</td>
                <td>
                  <select
                    name="user_status"
                    v-model="value.is_active"
                    class="form-control"
                  >
                    <option :value="1">{{ myLang.status_in_operation }}</option>
                    <option :value="0">{{ myLang.status_operation }}</option>
                  </select>
                </td>
                <td>
                  <button class="btn pull-right text-right btn-warning" style="float: right" @click="partner_update_modal(value)">
                    Edit
                  </button>
                </td>
                <td>
                  <button class="btn pull-right text-right btn-danger" style="float: right" @click="partner_delete(value)">
                    {{ myLang.delete }}
                  </button>
                </td>
                <td>
                  <router-link
                    :to="{
                      name: 'slr_job_list',
                      query: { slr_seller_id: value.slr_seller_id,byr_buyer_id:value.byr_buyer_id },
                    }"
                    class="btn btn-info"
                    >{{ myLang.details }}</router-link
                  >
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <b-modal
      size="lg"
      :hide-backdrop="true"
      title="Create Partner"
      :ok-title="save_button"
      :cancel-title="myLang.cancel"
      v-model="partner_create_modal"
      @ok.prevent="create_new_partner"
      :no-enforce-focus="true"
    >
      <div class="panel-body add_item_body">
        <form>
          <div class="form-group row">
            <label for="seller_name" class="col-sm-3 col-form-label">Wholesaller Name</label>
            <div class="col-sm-9">
              <multiselect
                v-model="form.selected_sellers"
                id="seller_name"
                placeholder="Select Seller"
                label="seller_name"
                track-by="slr_seller_id"
                :options="sellers"
                :multiple="false"
                :close-on-select="true"
                :clear-on-select="false"
                :preserve-search="true"
                open-direction="bottom"
              ><span slot="noOptions">候補がありません</span> <span slot="noResult">候補がありません</span></multiselect>
              <has-error :form="form" field="seller_name"></has-error>
            </div>
          </div>
          <div class="form-group row">
            <label for="j_code" class="col-sm-3 col-form-label">J Code</label>
            <div class="col-sm-9">
              <multiselect
                v-model="form.selected_sellers"
                id="j_code"
                placeholder="Jcode"
                label="jcode"
                track-by="slr_seller_id"
                :options="sellers"
                :multiple="false"
                :close-on-select="true"
                :clear-on-select="false"
                :preserve-search="true"
                open-direction="bottom"
              ><span slot="noOptions">候補がありません</span> <span slot="noResult">候補がありません</span></multiselect>
              <has-error :form="form" field="jan_code"></has-error>
            </div>
          </div>
          <div class="form-group row">
            <label for="partner_code" class="col-sm-3 col-form-label"
              >Partner Code</label
            >
            <div class="col-sm-9">
              <input
                type="text"
                id="partner_code"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('partner_code') }"
                placeholder="Partner Code"
                v-model="form.partner_code"
              />
              <has-error :form="form" field="partner_code"></has-error>
            </div>
          </div>
        </form>
      </div>
    </b-modal>
  </div>
</template>
<script>
// import tabList from "../CMN/tabList";
// import jacostabList from "../CMN/jacos_tab_List";

export default {
  name: "app",
  components: {
    // tabList,jacostabList
  },
  data() {
    return {
      company_name:null,
      partner_create_modal: false,
      save_button: "",
      company_partner_lists: {},
      cmn_company_id: "",
      sellers: [],
      // buyers: [],
      // selected_buyer: [],
      form: new Form({
        partner_code: "",
        selected_sellers: [],
        cmn_company_id: null,
        cmn_connect_id: null,
        sort_by:'company_name ',
        sort_type:"DESC",
      }),
    };
  },
  methods: {
    company_partner_list() {
        // console.log(this.form);
        // console.log(this.cmn_company_id);
      axios.post(this.BASE_URL + "api/company_partner_list", this.form)
        .then(({ data }) => {
            // console.log(data);
          this.company_partner_lists = data.partner_list;
          this.company_name = data.company_name;
        });
    },
    user_filter_by_buyer(value){
        // console.log(this.form)
      this.selected_seller=[];
      this.cmn_company_id=value.cmn_company_id;
      this.form.cmn_company_id=value.cmn_company_id;
      this.company_partner_list();
    },
    user_filter_by_seller(value){
      this.selected_buyer=[];
      this.cmn_company_id=value.cmn_company_id;
      this.form.cmn_company_id=value.cmn_company_id;
      this.company_partner_list();
    },
    new_partner_create_modal() {
      this.form.reset();
      this.form.cmn_company_id = this.cmn_company_id;
      this.partner_create_modal = true;
      this.save_button = this.myLang.add_new;
      axios.post(this.BASE_URL + "api/get_seller_list", { cmn_connect_id: null })
        .then(({ data }) => {
          this.sellers = data.sellers;
        });
    },
    create_new_partner() {
        // console.log(this.form)
        // return 0;
      axios.post(this.BASE_URL + "api/buyer_partner_create",this.form)
        .then(({ data }) => {
          // console.log(data)
          this.alert_icon = data.class_name;
          this.alert_title = data.title;
          if (data.message == "created") {
            this.partner_create_modal = false;
            this.alert_text = "You have successfully added partner";
            this.company_partner_list();
          } else if (data.message == "updated") {
            this.partner_create_modal = false;
            this.alert_text = "You have successfully updated partner";
            this.company_partner_list();
          } else {
              this.alert_text = "Some Error";
            this.alert_title = 'error';
            this.alert_icon = 'error';
          }


          this.sweet_normal_alert();
        })
        .catch((error) => {
            // console.log(error)
            if (this.form.cmn_company_id=="") {
                  this.alert_text = "Please select buyer";
              }else{
                  this.alert_text = error;
              }
        //   this.alert_text = error;
          this.sweet_advance_alert();
        });
    },
    partner_update_modal(value) {
      console.log(value)
      axios.post(this.BASE_URL + "api/get_seller_list", {
          cmn_connect_id: value.cmn_connect_id,
        }).then(({ data }) => {
          this.sellers = data.sellers;
          this.form.selected_sellers = data.selected_sellers;
          this.form.partner_code = value.partner_code;
          this.form.cmn_company_id = this.cmn_company_id;
          this.form.cmn_connect_id = value.cmn_connect_id;
          this.save_button = this.myLang.update;
          this.partner_create_modal = true;
        });
    },
    partner_delete(value){
        console.log(value);
        this.delete_sweet().then((result) => {
              if (result.value) {
                  axios.post(this.BASE_URL + "api/buyer_partner_delete", {
                    cmn_connect_id: value.cmn_connect_id,
                    }).then(({ data }) => {
                        console.log(data)
                        this.alert_text = data.message;
                        this.alert_title = data.title;
                        this.alert_icon = data.class_name;
                        this.company_partner_list();
                        this.sweet_normal_alert();
                    })
              }
        })
    },
    sorting(sorted_field){
          this.form.sort_by=sorted_field;
          this.form.sort_type=this.form.sort_type=="ASC"?"DESC":"ASC";
          this.company_partner_list();

      },
  },

  created() {
      if (this.$route.query.cmn_company_id) {
         this.cmn_company_id = this.$route.query.cmn_company_id;
         this.form.cmn_company_id=this.cmn_company_id;
      }else{
          this.cmn_company_id ="";
      }
    //   console.log('cmn: ',this.cmn_company_id);
    this.company_partner_list();
    this.get_byr_slr_company(this.cmn_company_id)
    Fire.$on("company_partner_list_emit", (cmn_company_id) => {
      this.cmn_company_id=cmn_company_id;
        this.company_partner_list();
    });
    if (this.cmn_company_id=="") {
      this.filter_select_box=true
    // var company_info=this.get_byr_slr_company(this.cmn_company_id);

    }
    axios.get(this.BASE_URL + "api/get_byr_slr_company/" + this.cmn_company_id)
      .then(({ data }) => {
        this.buyers=data.buyer_info;
        // console.log(data)
      });

  },
  mounted() {

  },
};
</script>
