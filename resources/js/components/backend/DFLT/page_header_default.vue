<template>
<div style="clear:both">
<div class="row" v-if="page_slug_url!='selected_buyer'">
           <div class="col-12 text-center page_c_title_bar text-sm-left mb-0">
            <h4 class="page_custom_title"> <span v-html="page_heading"></span></h4>

        </div>
    </div>
</div>

</template>

<script>
    export default {

        data(){
            return {
                page_heading:'<i class="fas fa-wrench custom_wrench"></i> 得意先一覧',
                page_slug_url: '',
            }
        },
        methods:{

        },
          created() {
             this.page_slug_url =  this.$route.name;
             if(this.$route.name=='home'){
                  this.page_heading = '得意先一覧';
             }else if(this.$route.name=='order_list'){
          this.page_heading = '受注受信一覧';
      }else if(this.$route.name=='receive_list'){
          this.page_heading = '受領受信一覧';
      }else if(this.$route.name=='payment_list'){
          this.page_heading = '支払受信一覧';
      }else if(this.$route.name=='payment_detail'){
          this.page_heading = '支払合計';
      }else if(this.$route.name=='invoice_list'){
          this.page_heading = '請求一覧';
      }else if(this.$route.name=='invoice_detail'){
          this.page_heading = '請求伝票一覧';
      }else if(this.$route.name=='item_search'){
          this.page_heading = '受注商品別一覧';
      }else if(this.$route.name=='item_search_detail'){
          this.page_heading = '受注商品別明細';
      }else if(this.$route.name=='blog'){
          this.page_heading = 'お知らせ一覧';
      }else if(this.$route.name=='scenario_history'){
          this.page_heading = 'シナリオ履歴';
      }else if(this.$route.name=='stock_item_list' || this.$route.name=='slr_stock_item_list'){
          this.page_heading = '欠品率';
      }
        Fire.$on("loadPageTitle", (PageTitle) => {
            this.page_heading = PageTitle;
        });
  },
        mounted() {
        }
    }
</script>
