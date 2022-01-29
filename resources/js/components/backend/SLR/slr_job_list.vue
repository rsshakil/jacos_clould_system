<template>
  <div>
    <div class="row">
                <div class="col-12">
                    <h4 class="top_title text-center" style="margin-top:10px;">{{myLang.super_value_head}}</h4>
                </div>

                <div class="col-3"></div>
                <div class="col-6">
                    <!-- <tabList></tabList> -->
                </div>
                <div class="col-3"></div>

<div class="col-12">
                    <h4 class="top_title text-center" style="margin-top:10px;">株式会社サノテック</h4>
                    <p class="text-center">問屋コード:00001 取引先コード:00001</p>
                </div>

                <div class="col-12">
                                       <button class="btn pull-right text-right btn-primary" style="float:right">{{myLang.add_new}}</button>
                    <div class="">
                       <table class="table table-striped table-bordered data_table">
                            <thead>
                                <tr>
                                    <th colspan="100%" style="border: none;width:150px;">
                                    <select name="job_status" class="form-control">
                                      <option value="稼働中" selected>{{myLang.status_in_operation}}</option>
                                      <option value="稼働">{{myLang.status_operation}}</option>
                                    </select>
                                    </th>
                                </tr>
                                <tr>
                                    <th style="cursor: pointer">No</th>
                                    <th style="cursor: pointer">Job ID</th>
                                    <th style="cursor: pointer">{{myLang.user_type}}</th>
                                    <th style="cursor: pointer">{{myLang.route}}</th>
                                    <th style="cursor: pointer">{{myLang.scenario}}</th>
                                    <th style="cursor: pointer">{{myLang.schedule_setting}}</th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr v-for="(value,index) in slr_job_lists" :key="value.cmn_job_id">
                                    <td>{{index+1}}</td>
                                    <td>{{value.cmn_job_id}}</td>
                                    <td>{{value.class}}</td>
                                    <td>{{value.vector}}</td>
                                    <td>{{value.name}}</td>
                                    <td><select name="user_status" class="form-control">
                                      <option value="稼働中" selected>{{myLang.status_in_operation}}</option>
                                      <option value="稼働">{{myLang.status_operation}}</option>
                                    </select></td>
                                    <td><button @click="job_exe_modal_show(value)" class="btn btn-info">{{myLang.schedule_setting}}</button></td>

                                </tr>

                            </tbody>
                        </table>
                        <button class="btn btn-primary" style="float:right">{{myLang.save_changes}}</button>
                    </div>
                </div>
            </div>
            <b-modal
      size="md"
      :hide-backdrop="true"
      :title="myLang.folder_monitoring"
      :ok-title="myLang.save"
      :cancel-title="myLang.cancel"
      @ok.prevent="save_edit_job()"
      v-model="job_exe_modal"
      :no-enforce-focus="true"
    >
      <!-- <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
      <div class="modal-body">-->
      <div class="panel-body add_item_body">
        <table class="table table-striped table-bordered data_table">
                            <thead>
                                <tr>
                                    <th style="cursor: pointer">Job ID</th>
                                    <th style="cursor: pointer">{{myLang.user_type}}</th>
                                    <th style="cursor: pointer">{{myLang.route}}</th>
                                    <th style="cursor: pointer">{{myLang.scenario}}</th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>test</td>
                                    <td>発注</td>
                                    <td>小売 → Jacos</td>
                                    <td>OUK_BMS_ORDER</td>
                                </tr>

                            </tbody>
                        </table>
            <form>

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
  name:'app',
  data() {
    return {
        slr_job_lists:{},
        slr_seller_id:'',
        job_exe_modal:false,
        byr_buyer_id:null,
    };
  },
  methods: {
       get_all_slr_job_lists(){
        axios.get(this.BASE_URL +"api/slr_job_list_by_seller_id/"+this.slr_seller_id).then(({data}) => {
            this.slr_job_lists = data.slr_job_list;
        });
    },
    save_edit_job(){

    },
    job_exe_modal_show(value){
      this.job_exe_modal = true;
    },
  },

  created() {
    this.slr_seller_id = this.$route.query.slr_seller_id;
    this.byr_buyer_id = this.$route.query.byr_buyer_id;
      this.get_all_slr_job_lists();


  },
  mounted() {

  }
};
</script>
