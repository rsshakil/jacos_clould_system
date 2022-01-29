<template>
  <div v-can="['slr_view']">
    <div class="row">
      <div class="col-12">
        <h4 class="top_title text-center" style="margin-top: 10px">
          {{ myLang.saler_management_head }}
        </h4>
      </div>

      <div class="col-12">
        <div class="">
          <table class="table table-striped table-bordered data_table">
            <thead>
              <tr>
                <th colspan="100%" style="border: none">
                  <div class="row">
                    <div class="col-6">
                      <form class="form-inline">
                        <input
                          class="form-control"
                          type="text"
                          :placeholder="myLang.search"
                          aria-label="Search"
                        />
                        <button class="btn btn-primary">
                          {{ myLang.search }}
                        </button>
                      </form>
                    </div>
                    <div class="col-6">
                      <button
                        @click="add_new_company_cmn"
                        class="btn custom_right btn-primary"
                      >
                        {{ myLang.add_new }}
                      </button>
                    </div>
                  </div>
                </th>
              </tr>
              <tr>
                <th style="cursor: pointer">No</th>
                <th style="cursor: pointer">{{ myLang.wholesaler_name }}</th>
                <th style="cursor: pointer">{{ myLang.wholesaler_code }}</th>
                <th style="cursor: pointer">{{ myLang.status }}</th>
                <th style="cursor: pointer">{{ myLang.user_management }}</th>
                <th style="cursor: pointer">{{ myLang.shop_management }}</th>
                <th style="cursor: pointer">{{ myLang.partner_management }}</th>
                <th style="cursor: pointer">{{ myLang.ordering_data }}</th>
                <th style="cursor: pointer">{{ myLang.details }}</th>
                <th style="cursor: pointer">{{ myLang.delete }}</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(slr_list, index) in slr_lists"
                :key="slr_list.slr_seller_id"
              >
                <td>{{ index + 1 }}</td>
                <td>{{ slr_list.company_name }}</td>
                <td>{{ slr_list.jcode }}</td>
                <td>{{ myLang.status_in_operation }}</td>
                <td>
                  <router-link
                    :to="{
                      name: 'slr_company_user_list',
                      query: { cmn_company_id: slr_list.cmn_company_id },
                    }"
                    class="btn btn-primary"
                    >{{ myLang.user_management }}</router-link
                  >
                </td>
                <td>
                  <button class="btn btn-info">
                    {{ myLang.shop_management }}
                  </button>
                </td>
                <td>
                  <router-link
                    :to="{
                      name: 'slr_company_partner_list',
                      query: { cmn_company_id: slr_list.cmn_company_id },
                    }"
                    class="btn btn-danger"
                    >{{ myLang.partner_management }}</router-link
                  >
                </td>
                <!-- <td><button class="btn btn-danger">{{myLang.partner_management}}</button></td> -->
                <td>
                  <button class="btn btn-success">
                    {{ myLang.ordering_data }}
                  </button>
                </td>
                <td>
                  <button
                    @click="edit_slr_data(slr_list)"
                    class="btn btn-primary"
                  >
                    {{ myLang.details }}
                  </button>
                </td>
                <td>
                    <button @click="delete_slr_data(slr_list)" class="btn btn-danger">
                    {{ myLang.delete }}
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <b-modal
      size="md"
      :hide-backdrop="true"
      :title="myLang.wholesaler_modal_title"
      :ok-title="myLang.add"
      :cancel-title="myLang.cancel"
      @ok.prevent="save_new_slr()"
      v-model="add_cmn_company_modal"
      :no-enforce-focus="true">
      <div class="panel-body add_item_body" v-can="['company_create']">
        <form>
          <input type="hidden" v-model="form.cmn_company_id" />
          <div class="form-group row">
            <label for="wholesaler_name" class="col-sm-4 col-form-label">{{
              myLang.wholesaler_name
            }}</label>
            <div class="col-sm-8">
              <input
                type="text"
                id="wholesaler_name"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('company_name') }"
                v-model="form.company_name"
              />
              <has-error :form="form" field="company_name"></has-error>
            </div>
          </div>
          <div class="form-group row">
            <label for="wholesaler_code" class="col-sm-4 col-form-label">{{
              myLang.wholesaler_code
            }}</label>
            <div class="col-sm-8">
              <input
                type="text"
                id="wholesaler_code"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('jcode') }"
                v-model="form.jcode"
              />
              <has-error :form="form" field="jcode"></has-error>
            </div>
          </div>
          <div class="form-group row">
            <label for="postal_code" class="col-sm-4 col-form-label">{{
              myLang.postal_code
            }}</label>
            <div class="col-sm-8">
              <input
                type="text"
                id="postal_code"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('postal_code') }"
                v-model="form.postal_code"
              />
              <has-error :form="form" field="postal_code"></has-error>
            </div>
          </div>
          <div class="form-group row">
            <label for="street" class="col-sm-4 col-form-label">{{
              myLang.street
            }}</label>
            <div class="col-sm-8">
              <input
                type="text"
                id="street"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('address') }"
                v-model="form.address"
              />
              <has-error :form="form" field="address"></has-error>
            </div>
          </div>
          <div class="form-group row">
            <label for="phone_number" class="col-sm-4 col-form-label">{{
              myLang.phone_number
            }}</label>
            <div class="col-sm-8">
              <input
                type="text"
                id="phone_number"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('phone') }"
                v-model="form.phone"
              />
              <has-error :form="form" field="phone"></has-error>
            </div>
          </div>
          <div class="form-group row">
            <label for="fax" class="col-sm-4 col-form-label">{{
              myLang.fax
            }}</label>
            <div class="col-sm-8">
              <input
                type="text"
                id="fax"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('fax') }"
                v-model="form.fax"
              />
              <has-error :form="form" field="fax"></has-error>
            </div>
          </div>
        </form>
      </div>
      <!-- </div>
        </div>
      </div>-->
    </b-modal>
  </div>
</template>
<script>
export default {
  data() {
    return {
      slr_lists: {},
      add_cmn_company_modal: false,
      form: new Form({
        cmn_company_id: "",
        company_name: "",
        jcode: "",
        postal_code: "",
        address: "",
        phone: "",
        fax: "",
      }),
    };
  },
  methods: {
    add_new_company_cmn() {
      this.form.reset();
      this.form.errors.clear();
      this.add_cmn_company_modal = true;
    },
    edit_slr_data(form_data) {
      this.add_cmn_company_modal = true;
      this.form.reset();
      this.form.errors.clear();
      this.form.fill(form_data);
    },
    delete_slr_data(form_data){
        // console.log(form_data)
        // return 0;
        this.delete_sweet().then((result) => {
              if (result.value) {
                  axios.post(this.BASE_URL + "api/seller_delete", form_data).then(({ data }) => {
                        console.log(data)
                        this.alert_text = data.message;
                        this.alert_title = data.title;
                        this.alert_icon = data.class_name;
                        this.get_all_slr();
                        this.sweet_normal_alert();
                    })
              }
        })
    },
    save_new_slr() {
      axios.post(this.BASE_URL + "api/slr_company_create",this.form)
        .then((data) => {
          this.add_cmn_company_modal = false;
          Fire.$emit("AfterCreatesellerCompany");
          if (this.form.cmn_company_id != "") {
            var tittles = "Company Update success";
            var msg_text = "You have successfully updated company";
          } else {
            var tittles = "Company added success";
            var msg_text = "You have successfully added company";
          }
          Swal.fire({
            icon: "success",
            title: tittles,
            text: msg_text,
          });

        })
        .catch((error) => {

          Swal.fire({
            icon: "warning",
            title: "Invalid company info",
            text: "check company info!",
          });
        });
    },
    get_all_slr() {
      axios
        .get(this.BASE_URL + "api/slr_management/" + Globals.user_info_id)
        .then(({data}) => {
          this.slr_lists = data.slr_list;

        });
    },
  },

  created() {
    this.get_all_slr();
    Fire.$on("AfterCreatesellerCompany", () => {
      this.get_all_slr();
    });

  },
  mounted() {

  },
};
</script>
