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
        <!-- <tabList></tabList> -->
      </div>
      <div class="col-2"></div>

      <div class="col-12">
        <div class="">
          <table class="table table-striped table-bordered data_table">
            <thead>
              <tr>
                <th colspan="100%" style="border: none">
                  <button
                    @click="new_user_create_modal"
                    class="btn pull-right text-right btn-primary"
                    style="float: right"
                  >
                    {{ myLang.add_new }}
                  </button>
                </th>
              </tr>
              <tr v-if="filter_select_box">
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
                <th colspan="2">
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
                  ><span slot="noOptions">候補がありません</span> <span slot="noResult">候補がありません</span></multiselect>
                </th>
              </tr>
              <tr>
                <th style="cursor: pointer">No</th>
                <!-- <th style="cursor: pointer">{{ myLang.user_type }}</th> -->
                <th style="cursor: pointer">{{ myLang.name }}</th>
                <th style="cursor: pointer">{{ myLang.email }}</th>
                <th style="cursor: pointer">Super Code</th>
                <th style="cursor: pointer">J Code</th>
                <th style="cursor: pointer">{{ myLang.status_in_operation }}</th>
                <!-- <th style="cursor: pointer">{{ myLang.status }}</th> -->
                <th style="cursor: pointer">{{ myLang.details }}</th>
                <th style="cursor: pointer">{{ myLang.delete }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(value, index) in company_user_lists" :key="value.id">
                <td>{{ index + 1 }}</td>
                <!-- <td>一般</td> -->
                <td>{{ value.name }}</td>
                <td>{{ value.email }}</td>
                <td>{{ value.super_code }}</td>
                <td>{{ value.jcode }}</td>
                <td>
                  <select name="user_status" class="form-control">
                    <option value="稼働中" selected>
                      {{ myLang.status_in_operation }}
                    </option>
                    <option value="稼働">{{ myLang.status_operation }}</option>
                  </select>
                </td>
                <td>
                  <button class="btn btn-info" @click="seller_user_update_modal(value)">{{ myLang.details }}</button>
                </td>
                <td>
                  <button class="btn btn-danger" @click="sellerUserDelete(value)">{{ myLang.delete }}</button>
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
      :title="myLang.add_user"
      :ok-title="save_button"
      :cancel-title="myLang.cancel"
      @ok.prevent="create_new_user()"
      v-model="user_create_modal"
      :no-enforce-focus="true"
    >
      <!-- <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
      <div class="modal-body">-->
      <div class="panel-body add_item_body">
        <form>
          <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">{{
              myLang.name
            }}</label>
            <div class="col-sm-10">
              <input
                type="text"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('name') }"
                v-model="form.name"
              />
              <has-error :form="form" field="name"></has-error>
            </div>
          </div>
          <!--<div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">Super code</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('super_code') }" v-model="form.super_code">
      <has-error :form="form" field="super_code"></has-error>
    </div>
  </div>-->
          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">{{
              myLang.email
            }}</label>
            <div class="col-sm-10">
              <input
                type="text"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('email') }"
                v-model="form.email"
              />
              <has-error :form="form" field="email"></has-error>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-2 col-form-label">{{
              myLang.password
            }}</label>
            <div class="col-sm-10">
              <input
                type="password"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('password') }"
                placeholder="Password"
                v-model="form.password"
              />
              <has-error :form="form" field="password"></has-error>
            </div>
          </div>
        </form>
      </div>
    </b-modal>
  </div>
</template>
<script>
// import tabList from "../CMN/tabList";
export default {
  name: "app",
  components: {
    // tabList,
  },
  data() {
    return {
      company_name:null,
      save_button:"",
      company_user_lists: {},
      cmn_company_id: "",
      // selected_buyer:[],
      // password_field: true,
      user_create_modal: false,
      form: new Form({
        name: "",
        email: "",
        password: "",
        cmn_company_id: null,
        adm_user_id: null,
        // user_type:'slr'
      }),
    };
  },
  methods: {
    get_all_company_users() {
      // console.log(this.cmn_company_id)
      axios
        .get(this.BASE_URL + "api/cmn_company_user_list/" + this.cmn_company_id)
        .then(({ data }) => {
          this.company_user_lists = data.user_list;
          this.company_name = data.company_name;
        });
    },
    user_filter_by_buyer(value){
      this.selected_seller=[];
      this.cmn_company_id=value.cmn_company_id;
      this.get_all_company_users();
    },
    user_filter_by_seller(value){
      this.selected_buyer=[];
      this.cmn_company_id=value.cmn_company_id;
      this.get_all_company_users();
    },
    new_user_create_modal() {
      this.form.reset();
      this.form.cmn_company_id = this.cmn_company_id;
      this.user_create_modal = true;
      this.save_button=this.myLang.add_new;
      // this.password_field = true;
    },
    create_new_user() {
      axios.post(this.BASE_URL + "api/cmn_user_create",this.form)
        .then(({ data }) => {
          Fire.$emit("AfterCreateUser");
          if (data.message == "created") {
            this.user_create_modal = false;
            this.alert_text = "You have successfully added buyer user";
          } else if (data.message == "updated") {
            this.user_create_modal = false;
            this.alert_text = "You have successfully updated buyer user";
          } else {
            this.alert_text = "User duplicated";
          }
          this.alert_title = data.title;
          this.alert_icon = data.class_name;
          this.sweet_normal_alert();
        })
        .catch((error) => {
          this.alert_text = error;
          this.sweet_advance_alert();
        });
    },
    seller_user_update_modal(user) {

      this.form.reset();
      this.form.cmn_company_id = this.cmn_company_id;
      this.form.adm_user_id = user.id;
      this.form.name = user.name;
      this.form.email = user.email;
      this.form.password = null;
      this.save_button=this.myLang.update;
      // this.password_field = false;
      this.user_create_modal = true;
    },
    sellerUserDelete(user){
        // console.log(user);
        this.delete_sweet().then((result) => {
              if (result.value) {
                  axios.post(this.BASE_URL + "api/seller_user_delete", {adm_user_id:user.id}).then(({ data }) => {
                        console.log(data)
                        this.alert_text = data.message;
                        this.alert_title = data.title;
                        this.alert_icon = data.class_name;
                        this.get_all_company_users();
                        this.sweet_normal_alert();
                    })
              }
        })
    }
  },

  created() {
    this.cmn_company_id = this.$route.query.cmn_company_id;
    this.form.cmn_company_id = this.$route.query.cmn_company_id;

    this.get_all_company_users();
    this.get_byr_slr_company(this.cmn_company_id)
    Fire.$on("get_all_company_users_emit", (cmn_company_id) => {
      this.cmn_company_id=cmn_company_id;
        this.get_all_company_users();
    });
    Fire.$on("AfterCreateUser", () => {
      this.get_all_company_users();
    });

  },
  mounted() {

  },
};
</script>
