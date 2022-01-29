<template>
  <div>
    <div class="row">
                <div class="col-12">
                    <h4 class="top_title text-center" style="margin-top:10px;">{{myLang.job_management_heading}}</h4>
                </div>
                <div class="col-12">
                    <div class="">
                      <table class="table table-striped table-bordered data_table">
                            <thead>
                                <tr>
                                <th colspan="3" style="border: none;">
                                <multiselect v-model="form.select_byr_company_id" id="j_code" placeholder="Company name" label="company_name" track-by="cmn_company_id" :options="slr_company_list" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true"  open-direction="bottom" ><span slot="noOptions">候補がありません</span> <span slot="noResult">候補がありません</span></multiselect>

                                    </th>
                                    <th colspan="3" style="border: none;">
                                    <multiselect v-model="form.select_slr_company_id" id="super_code" placeholder="Company name" label="company_name" track-by="cmn_company_id" :options="byr_company_list" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" open-direction="bottom" ><span slot="noOptions">候補がありません</span> <span slot="noResult">候補がありません</span></multiselect>

                                    </th>
                                    <th colspan="2" style="border: none;">
                                    <select name="job_status" class="form-control">
                                      <option value="稼働中" selected>{{myLang.status_in_operation}}</option>
                                      <option value="稼働">{{myLang.status_operation}}</option>
                                    </select>
                                    </th>
                                    <th colspan="3" style="border-right:0;border-left:0;"></th>
                                </tr>
                                <tr>
                                    <th style="cursor: pointer">No</th>
                                    <th style="cursor: pointer">Job ID</th>
                                    <th style="cursor: pointer">{{myLang.byr_company_name}}</th>
                                    <th style="cursor: pointer">{{myLang.slr_company_name}}</th>
                                    <th style="cursor: pointer">{{myLang.byr_super_code}}</th>
                                    <th style="cursor: pointer">{{myLang.slr_jcode}}</th>
                                    <th style="cursor: pointer">{{myLang.user_type}}</th>
                                    <th style="cursor: pointer">{{myLang.route}}</th>
                                    <th style="cursor: pointer">{{myLang.scenario}}</th>
                                    <th style="cursor: pointer">{{myLang.schedule_setting}}</th>
                                    <td>Action</td>
                                </tr>

                            </thead>
                            <tbody>
                                <tr v-for="(value,index) in slr_job_lists" :key="value.cmn_job_id">
                                    <td>{{index+1}}</td>
                                    <td>{{value.cmn_job_id}}</td>
                                    <td>{{value.byr_company}}</td>
                                    <td>{{value.slr_company}}</td>
                                    <td>{{value.super_code}}</td>
                                    <td>{{value.jcode}}</td>
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
                    </div>
                </div>
            </div>
  </div>
</template>
<script>
// import tabList from "../CMN/tabList";

export default {
  name:'app',
components:{
// tabList,
},
  data() {
    return {
        'scenario_lists':{},
        'slr_job_lists':{},
        'byr_company_list':[],
        'slr_company_list':[],
        'byr_buyer_id':'',
        form: new Form({
        select_byr_company_id: [],
        select_slr_company_id: [],
      }),
    };
  },
  methods: {
      get_all_scenarios(){
        axios.get(this.BASE_URL +"api/get_scenario_list").then(({data}) => {
            this.scenario_lists = data.data.scenario_list;

        });
    },
    get_all_slr_job_lists(){
        axios.get(this.BASE_URL +"api/slr_job_list_all").then(({data}) => {
            this.slr_job_lists = data.job_list;
            this.byr_company_list = data.byr_company_list;
            this.slr_company_list = data.slr_company_list;
        });
    }
  },

  created() {
      //this.get_all_scenarios();
      this.get_all_slr_job_lists();

  },
  mounted() {

  }
};
</script>
