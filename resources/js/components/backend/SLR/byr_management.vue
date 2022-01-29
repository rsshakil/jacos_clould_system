<template>
  <div v-can="['byr_management']">
    <div class="row">
      <div class="col-12">
        <h4 class="top_title text-center" style="margin-top: 10px">
          {{ myLang.buyer_management_head }}
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
                        @click="add_new_buyer"
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
                <th style="cursor: pointer">{{ myLang.company_name }}</th>
                <th style="cursor: pointer">{{ myLang.super_code }}</th>
                <th style="cursor: pointer">{{ myLang.status }}</th>
                <th style="cursor: pointer">{{ myLang.user_management }}</th>
                <th style="cursor: pointer">{{ myLang.shop_management }}</th>
                <th style="cursor: pointer">{{ myLang.partner_management }}</th>
                <th style="cursor: pointer">{{ myLang.ordering_data }}</th>
                <th style="cursor: pointer">{{ myLang.details }}</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(company_list, index) in company_lists"
                :key="company_list.cmn_company_id"
              >
                <td>{{ index + 1 }}</td>
                <td>{{ company_list.company_name }}</td>
                <td>{{ company_list.super_code }}</td>
                <td>{{ myLang.status_in_operation }}</td>
                <td>
                  <router-link
                    :to="{
                      name: 'byr_company_user_list',
                      query: { cmn_company_id: company_list.cmn_company_id },
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
                      name: 'adm_partner_list_manage', //byr_company_partner_list
                      query: { cmn_company_id: company_list.cmn_company_id },
                    }"
                    class="btn btn-danger"
                    >{{ myLang.partner_management }}</router-link
                  >
                </td>
                <td>
                  <button class="btn btn-success">
                    {{ myLang.ordering_data }}
                  </button>
                </td>
                <td>
                  <button
                    @click="edit_byr_data(company_list)"
                    class="btn btn-primary"
                  >
                    {{ myLang.details }}
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
      :title="user_create_title"
      :ok-title="create_update_button"
      :cancel-title="myLang.cancel"
      @ok.prevent="save_new_buyer()"
      v-model="add_cmn_company_modal"
      :no-enforce-focus="true"
    >
      <!-- <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
      <div class="modal-body">-->
      <div class="panel-body add_item_body">
        <form>
          <input type="hidden" v-model="form.cmn_company_id" />
          <!-- <div class="form-group row">
            <label for="buyer_name" class="col-sm-4 col-form-label">Buyer Name</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('buyer_name') }" v-model="form.buyer_name"/>
              <has-error :form="form" field="buyer_name"></has-error>
            </div>
          </div>
          <div class="form-group row">
            <label for="buyer_email" class="col-sm-4 col-form-label">Email</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('buyer_email') }" v-model="form.buyer_email"/>
              <has-error :form="form" field="buyer_email"></has-error>
            </div>
          </div>
          <div class="form-group row">
            <label for="buyer_password" class="col-sm-4 col-form-label">Password</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('buyer_password') }" v-model="form.buyer_password"/>
              <has-error :form="form" field="buyer_password"></has-error>
            </div>
          </div> -->
          <div class="form-group row">
            <label for="staticEmail" class="col-sm-4 col-form-label">{{myLang.company_name}}</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('company_name') }" v-model="form.company_name"/>
              <has-error :form="form" field="company_name"></has-error>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-4 col-form-label">{{
              myLang.jan_code
            }}</label>
            <div class="col-sm-8">
              <input
                type="text"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('jcode') }"
                v-model="form.jcode"
              />
              <has-error :form="form" field="jcode"></has-error>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-4 col-form-label">{{
              myLang.super_code
            }}</label>
            <div class="col-sm-8">
              <input
                type="text"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('super_code') }"
                v-model="form.super_code"
              />
              <has-error :form="form" field="super_code"></has-error>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-4 col-form-label">{{
              myLang.postal_code
            }}</label>
            <div class="col-sm-8">
              <input
                type="text"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('postal_code') }"
                v-model="form.postal_code"
              />
              <has-error :form="form" field="postal_code"></has-error>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-4 col-form-label">{{
              myLang.street
            }}</label>
            <div class="col-sm-8">
              <input
                type="text"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('address') }"
                v-model="form.address"
              />
              <has-error :form="form" field="address"></has-error>
            </div>
          </div>
          <div class="row form-group">
              <label for="permission_for_user" class="col-md-4 col-form-label">Select permissions</label>
              <b-form-group>
                  <b-form-checkbox-group id="checkbox-group" v-model="form.selected_permissions">
                  <b-form-checkbox v-for="(permission, index) in permissions" :value="permission.permission_id" :key="index" switch>{{permission.permission_name}}</b-form-checkbox>
                  </b-form-checkbox-group>
              </b-form-group>
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
      company_lists: {},
      add_cmn_company_modal: false,
      user_create_title:'',
      create_update_button:'',
      editmode: false,
      permissions: [],
      form: new Form({
        cmn_company_id: null,
        // buyer_name: "",
        // buyer_email: "",
        // buyer_password: "",
        company_name: "",
        jcode: "",
        super_code: "",
        postal_code: "",
        address: "",
        selected_permissions: [],
      }),
    };
  },
  methods: {
    add_new_buyer() {
      this.user_create_title=this.myLang.add_new_buyer_title,
      this.create_update_button=this.myLang.add_new,
      this.add_cmn_company_modal = true;
      this.editmode = false;
      this.form.reset();
      axios.post(this.BASE_URL + "api/get_permissions_for_buyer",{cmn_company_id:null})
      .then(({data})=>{
        this.permissions=data.permission_array
        // console.log(data)
      })
    },
    edit_byr_data(form_data) {
      // console.log(form_data);
      var cmn_company_id=form_data.cmn_company_id
      axios.post(this.BASE_URL + "api/get_permissions_for_buyer",{cmn_company_id:cmn_company_id})
      .then(({data})=>{
        this.permissions=data.permission_array
        var sp_array=[];
        (data.selected_permission_array).forEach(element => {
          sp_array.push(element.id)
        });
        this.form.selected_permissions=sp_array;
      })
      this.user_create_title="Update Buyer",
      this.create_update_button=this.myLang.update,
      this.add_cmn_company_modal = true;
      this.editmode = true;
      this.form.reset();
      this.form.fill(form_data);
    },
    save_new_buyer() {

      this.form
        .post(this.BASE_URL + "api/create_buyer")
        .then(({data}) => {

          Fire.$emit("AfterCreateCompany");
          if (this.form.cmn_company_id != "") {
              this.add_cmn_company_modal = false;
              this.alert_text = "You have successfully updated buyer";
          } else {
              this.add_cmn_company_modal = false;
              this.alert_text = "You have successfully added buyer";
          }
          this.alert_title=data.title
          this.alert_icon=data.class_name
          this.sweet_normal_alert();
          // console.log(data);
        })
        .catch((error) => {
          // console.log(error);
          this.alert_title="Invalid company info"
          this.alert_icon="warning"
          this.alert_text = "check company info!";
          this.sweet_advance_alert();
        });
    },
    get_all_company() {

      axios.get(
          this.BASE_URL + "api/get_all_company_list/" + Globals.user_info_id
        ).then(({data}) => {
          this.company_lists = data.companies;
          // console.log(this.company_lists);
        });
    },
  },

  created() {
    this.get_all_company();
    Fire.$on("AfterCreateCompany", () => {
      this.get_all_company();
    });

  },
  mounted() {

  },
};
</script>
