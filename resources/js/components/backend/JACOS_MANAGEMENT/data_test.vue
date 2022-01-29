<template>
  <div>
    
    <div class="row">
    
               <div class="col-12">
              
               </div>
                <div class="col-12">
                    <div class="">
                      <table class="table table-striped order_item_details_table table-bordered">
                            <thead>
                                
                                <tr>
                                    <th style="cursor: pointer">No</th>
                                    
                                    <th style="cursor: pointer">種別</th>
                                    <th style="cursor: pointer">件数</th>
                                    <th style="cursor: pointer">データ投入</th>
                                    <th style="cursor: pointer">全削除</th>
                                </tr>

                            </thead>
                            <tbody>

                                <tr v-for="(value,index) in test_lists">
                                    <td>{{index+1}}</td>
                                    <td>{{value.jp }}</td>
                                    <td style="text-right">{{value.count }}</td>
                                    <td><button class="btn btn-primary">アップロード</button></td>
                                    <td><button class="btn btn-danger" @click="delete_data(value)">削除</button></td>
                                    
                                </tr>
                                 <tr v-if="test_lists && test_lists.length==0">
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
        'byr_buyer_id':'',
        test_lists:[],
        form: new Form({
        select_field_per_page_num: 10,
        page: 1,
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,

      }),
    };
  },
  methods: {
    delete_data(value){
      let _this = this;
      let loaderrrsss = Vue.$loading.show();
        axios.post(this.BASE_URL +"api/super_admin_test_data_delete",value).then(({data}) => {
            _this.get_all_tests();
          // console.log(this.test_lists);
             _this.alert_icon = "success";
            _this.alert_title = "";
            _this.alert_text = value.jp+"データを削除しました。";
            _this.sweet_normal_alert();
        });
        loaderrrsss.hide();
    },
      get_all_tests(){
        let loaderrrsss = Vue.$loading.show();
        axios.post(this.BASE_URL +"api/super_admin_data_count_list",this.form).then(({data}) => {
            this.test_lists = data.result;
          // console.log(this.test_lists);
            
        });
        loaderrrsss.hide();
    },
  },

  created() {
      this.get_all_tests();
       Fire.$emit("loadPageTitle", "データテスト");
  },
  mounted() {

  }
};
</script>
