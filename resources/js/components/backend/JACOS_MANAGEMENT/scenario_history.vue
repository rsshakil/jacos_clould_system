<template>
  <div>
    <div class="row">
      <div class="col-12">
    <div class="col-12" style="background: #d8e3f0; padding: 10px">
      <table class="table orderDetailTable cmnWidthTable table-bordered" style="width: 100%">
        <tr>
          <td class="cl_custom_color">
           実行日時
          </td>
          <td>
            <div class="input-group">
                <input type="date" class="form-control" v-model="form.scenario_date_from">
                <div class="input-group-prepend">
                    <span class="input-group-text">~</span>
                </div>
                <input type="date" class="form-control" v-model="form.scenario_date_to">
            </div>
          </td>

          <td class="cl_custom_color">
            種別
          </td>
          <td>
             <select class="form-control" v-model="form.data">
              <option
                v-for="(val, i) in actionTypeList"
                :key="i"

                :value="i"
              >
                {{ val}}
              </option>
            </select>
          </td>

          <td class="cl_custom_color">ステータス</td>
          <td>
            <select class="form-control" v-model="form.status">
              <option
                v-for="(val, i) in statusList"
                :key="i"

                :value="i"
              >
                {{ val}}
              </option>
            </select>

          </td>
        </tr>
        <tr>
          <td class="cl_custom_color">シナリオ名</td>
          <td>
            <input type="text" class="form-control" v-model="form.name">
          </td>
          <td class="cl_custom_color">小売名</td>
          <td>
           <input type="text" class="form-control" v-model="form.super_code">

          </td>
          <td class="cl_custom_color">取引先コード</td>
          <td>
            <input type="text" class="form-control" v-model="form.partner_code">
          </td>
        </tr>
       

      </table>
    </div>
    </div>
    <br />
    <div class="col-12" style="text-align: center">
      <button class="btn btn-primary active srchBtn" type="button" @click="get_all_scenarios()">
        {{ myLang.search }}
      </button>
    </div>
    </div>
    <div class="row">
     <div class="col-12 text-center page_c_title_bar text-sm-left mb-0">
      <h4 class="page_custom_title">シナリオ履歴：一覧</h4>

    </div>
               <div class="col-12">
               <div class="row">
                  <div class="col-5">
            <p>
              <span class="tableRowsInfo"
                >{{ scenario_lists.from }}〜{{
                  scenario_lists.to
                }}
                件表示中／全：{{ scenario_lists.total }}件</span
              >
              <span class="pagi">
                <advanced-laravel-vue-paginate
                  :data="scenario_lists"
                  :onEachSide="2"
                  previousText="<"
                  nextText=">"
                  alignment="center"
                  @paginateTo="get_all_scenarios"
                />
              </span>
              <span class="selectPagi">
                <select
                  @change="get_all_scenarios()"
                  v-model="form.per_page"
                  class="form-control selectPage"
                >
                  <option value="10">10行</option>
                  <option value="20">20行</option>
                  <option value="50">50行</option>
                  <option value="100">100行</option>
                </select>
              </span>
            </p>
          </div>
               </div>
               </div>
                <div class="col-12">
                    <div class="">
                      <table class="table table-striped order_item_details_table table-bordered">
                            <thead>
                                
                                <tr>
                                    <th style="cursor: pointer">No</th>
                                    <th style="cursor: pointer" @click="sorting('super_code')">{{myLang.buyer_name}} <span class="float-right" :class="iconSet('super_code')"></span></th>
                                    <th style="cursor: pointer" @click="sorting('partner_code')">取引先コード <span class="float-right" :class="iconSet('partner_code')"></span></th>
                                    <th style="cursor: pointer" @click="sorting('company_name')">問屋名 <span class="float-right" :class="iconSet('company_name')"></span></th>
                                    <th style="cursor: pointer" @click="sorting('data')">{{myLang.history_data}} <span class="float-right" :class="iconSet('data')"></span></th>
                                    <th style="cursor: pointer" @click="sorting('name')">{{myLang.scenario_name}} <span class="float-right" :class="iconSet('name')"></span></th>
                                    <th style="cursor: pointer" @click="sorting('status')">{{myLang.history_status}} <span class="float-right" :class="iconSet('status')"></span></th>
                                    <th style="cursor: pointer" @click="sorting('exec_datetime')">実行日時 <span class="float-right" :class="iconSet('exec_datetime')"></span></th>
                                </tr>

                            </thead>
                            <tbody>

                                <tr v-for="(value,index) in scenario_lists.data" :key="value.id">
                                    <td>{{index+1}}</td>
                                    <td>{{value.super_code}}</td>
                                    <td>{{value.partner_code}}</td>
                                    <td>{{value.company_name}}</td>
                                    <td>{{value.data}}</td>
                                    <td>{{value.name}}</td>
                                    <td>{{value.status}}</td>
                                    <td>{{value.exec_datetime}}</td>
                                    
                                </tr>
                                 <tr v-if="scenario_lists.data && scenario_lists.data.length==0">
                                <td class="text-center" colspan="100%">データがありません</td>
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
        'byr_buyer_id':'',
        statusList:{ "*": "全て" ,"success": "success" ,'error': "error" },
        actionTypeList:{ "*": "全て" , "order": "order" ,'receive': "receive" , "return": "return" , 'invoice': "invoice",'payment': "payment" },
        form: new Form({
        per_page: 10,
        page: 1,
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        scenario_date_from:null,
        scenario_date_to:null,
        data:'*',
        status:'*',
        name:null,
        super_code:null,
        partner_code:null,
        page_title: "scenario_history",
        sort_by:'exec_datetime ',
        sort_type:"DESC",
        downloadType:1,

      }),
    };
  },
  methods: {
    sorting(sorted_field){
        this.form.sort_by=sorted_field;
        this.form.sort_type=this.form.sort_type=="ASC"?"DESC":"ASC";
        this.get_all_scenarios(this.form.page);

      },
      get_all_scenarios(page=1){
        let loaderrrsss = Vue.$loading.show();
        this.form.page=page;
        axios.post(this.BASE_URL +"api/get_scenario_history_list",this.form).then(({data}) => {
            this.scenario_lists = data.scenario_list;
            loaderrrsss.hide();
        });
    },
  },

  created() {
    var page = this.form.page;
      this.get_all_scenarios(page);
       Fire.$emit("loadPageTitle", "シナリオ履歴");
  },
  mounted() {

  }
};
</script>
