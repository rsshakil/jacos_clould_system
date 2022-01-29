<template>
  <div>
    <div class="row">
                <div class="col-12">
                    <h4 class="top_title text-center" style="margin-top:10px;">{{myLang.super_value_head}}</h4>
                </div>

                <div class="col-2"></div>
                <div class="col-8">
                    <tabList></tabList>
                </div>
                <div class="col-2"></div>


                <div class="col-12">
                    <div class="">
                       <table class="table table-striped table-bordered data_table">
                            <thead>
                                <tr>
                                    <th colspan="100%" style="border: none;">
                                       <button @click="new_user_create_modal" class="btn pull-right text-right btn-primary" style="float:right">{{myLang.add_new}}</button>
                                    </th>
                                </tr>
                                <tr>
                                    <th style="cursor: pointer">No</th>
                                    <th style="cursor: pointer">{{myLang.user_type}}</th>
                                    <th style="cursor: pointer">{{myLang.name}}</th>
                                    <th style="cursor: pointer">{{myLang.email}}</th>
                                    <th style="cursor: pointer">{{myLang.status}}</th>
                                    <th style="cursor: pointer">{{myLang.details}}</th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr v-for="(value,index) in company_user_lists" :key="value.id">
                                    <td>{{index+1}}</td>
                                    <td>一般</td>
                                    <td>{{value.name}}</td>
                                    <td>{{value.email}}</td>
                                    <td><select name="user_status" class="form-control">
                                      <option value="稼働中" selected>{{myLang.status_in_operation}}</option>
                                      <option value="稼働">{{myLang.status_operation}}</option>
                                    </select></td>
                                    <td><button class="btn btn-info">{{myLang.details}}</button></td>

                                </tr>

                            </tbody>
                        </table>
                        <button class="btn btn-danger" style="float:right">{{myLang.cancel}}</button>
                        <button class="btn btn-primary" style="float:right">{{myLang.save_changes}}</button>
                    </div>
                </div>
            </div>

          <b-modal
      size="lg"
      :hide-backdrop="true"
      :title="myLang.add_user"
      :ok-title="myLang.save"
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
        <input type="hidden" v-model="form.cmn_company_id">
          <div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">{{myLang.name}}</label>
    <div class="col-sm-10">
      <input type="text" id="name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" v-model="form.name">
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
    <label for="email" class="col-sm-2 col-form-label">{{myLang.email}}</label>
    <div class="col-sm-10">
      <input type="text" id="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" v-model="form.email">
      <has-error :form="form" field="email"></has-error>
    </div>
  </div>
  <div class="form-group row">
    <label for="password" class="col-sm-2 col-form-label">{{myLang.password}}</label>
    <div class="col-sm-10">
      <input type="password" id="password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" placeholder="Password" v-model="form.password">
      <has-error :form="form" field="password"></has-error>
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
const tabList = () =>
    import ( /* webpackChunkName: "tabList" */ '../tabList_seller')
// import tabList from '../tabList_seller'
export default {
  name:'app',
components:{
tabList,
},
  data() {
    return {
        'company_user_lists':{},
        'cmn_company_id':'',
        'user_create_modal':false,
        form: new Form({
                    name : '',
                    email: '',
                    password: '',
                    cmn_company_id: '',
                })
    };
  },
  methods: {
       get_all_company_users(){
        axios.get(this.BASE_URL +"api/company_user_list/"+this.cmn_company_id).then(({data}) => {
            this.company_user_lists = data.user_list;
        });
    },
    new_user_create_modal(){
      this.form.reset();
      this.form.cmn_company_id = this.$route.params.cmn_company_id;
      this.user_create_modal = true;


    },
    create_new_user(){
      axios.post(this.BASE_URL +'api/slr_seller_user_create',this.form)
                .then((data)=>{
                  this.user_create_modal = false;
                    Fire.$emit('AfterCreateUser');
                    Swal.fire({
            icon: 'success',
            title: 'User added success',
            text: 'You have successfully added user'
        });

                })
                .catch((error)=>{

                  Swal.fire({
            icon: 'warning',
            title: 'Invalid user info',
            text: 'duplicated email found!'
        });
                })
    },
  },

  created() {
    this.cmn_company_id = this.$route.params.cmn_company_id;
    this.form.cmn_company_id = this.$route.params.cmn_company_id;

      this.get_all_company_users();
      Fire.$on("AfterCreateUser", () => {
        this.get_all_company_users();
    });

  },
  mounted() {

  }
};
</script>
