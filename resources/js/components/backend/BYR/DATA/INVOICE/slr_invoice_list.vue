<template>
  <div class="row">
    <div class="col-12">
    <div class="col-12" style="background: #d8e3f0; padding: 10px">
     <table
          class="table orderDetailTable cmnWidthTable table-bordered"
          style="width: 100%"
        >
          <tr>
          <td class="cl_custom_color">締日</td>
            <td>
                <div class="input-group">
                    <input type="date" class="form-control" v-model="form.mes_lis_inv_per_begin_date">
                    <div class="input-group-prepend">
                        <span class="input-group-text">~</span>
                    </div>
                    <input type="date" class="form-control" v-model="form.mes_lis_inv_per_end_date">
                </div>
        </td>
            <td class="cl_custom_color">取引先コード</td>
            <td><input type="text" class="form-control topHeaderInputFieldBtn" v-model="form.mes_lis_inv_pay_code">
            <button @click="showAllCustomerCode" class="btn btn-primary active">
                参照
            </button>
            </td>
          </tr>
          <tr>
             <td class="cl_custom_color">確定状況</td>
          <td>
            <select class="form-control" v-model="form.decission_cnt">
              <option v-for="(dcnt, i) in decission_cnt" :key="i" :value="Object.keys(dcnt)[0]">
                {{ Object.values(dcnt)[0] }}
              </option>
            </select>
          </td>
          <td class="cl_custom_color">送信状況</td>
          <td>
            <select class="form-control" v-model="form.send_cnt">
             <option v-for="(dcnt, i) in send_cnt" :key="i" :value="Object.keys(dcnt)[0]">
                {{ Object.values(dcnt)[0] }}
              </option>
            </select>
          </td>
          </tr>
          <tr>
          <td class="cl_custom_color">伝票番号</td>
          <td>
            <div class="input-group">
                <input type="text" class="form-control" v-model="form.trade_number" placeholder="伝票番号">
            </div>
          </td>
        </tr>
        </table>
    </div>
    </div>
    <div class="col-12 text-center">
      <button class="btn btn-primary active srchBtn" type="button" @click="get_all_invoice_list">
          {{ myLang.search }}
        </button>
    </div>
    <div class="col-12">
        <br />
        <h4 class="page_custom_title">{{ myLang.search_result }}</h4>
      </div>
        <div class="col-12 text-center">
         <button class="btn btn-outline-primary" type="button" :disabled="is_disabled(invoice_lists_length>=1?true:false)" @click="invoice_download(1)">
        <b-icon icon="download" animation="fade" font-scale="1.2"></b-icon>
        {{ myLang.download }}
      </button>
    </div>
    <div class="col-12">
            <p>
              <span class="tableRowsInfo">{{ invoice_lists.from }}〜{{ invoice_lists.to }} 件表示中／全：{{ invoice_lists.total }}件</span>
              <span class="pagi">
              <advanced-laravel-vue-paginate :data="invoice_lists"
              :onEachSide="2"
              previousText="<"
              nextText=">"
              alignment="center"
              @paginateTo="get_all_invoice_list"/>
              </span>
              <span class="selectPagi">
                <select class="form-control selectPage" @change="get_all_invoice_list"
                  v-model="form.per_page" >
                <option value="10">10行</option>
                <option value="20">20行</option>
                <option value="50">50行</option>
                <option value="100">100行</option>
                </select>
              </span>
            </p>
      <div>
        <table class="table table-striped table-bordered order_item_details_table" style="overflow-x: scroll">
          <thead>
            <tr>
              <th>No</th>
              <th class="pointer_class" @click="sorting('mes_lis_inv_per_end_date')">締日 <span class="float-right" :class="iconSet('mes_lis_inv_per_end_date')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_inv_pay_code')">取引先コード <span class="float-right" :class="iconSet('mes_lis_inv_pay_code')"></span></th>
              <th class="pointer_class">伝票　総数</th>
              <th class="pointer_class">未確定　伝票数</th>
              <th class="pointer_class">未送信　伝票数</th>
              <th class="pointer_class" @click="sorting('mes_lis_inv_lin_det_amo_requested_amount')">請求金額 <span class="float-right" :class="iconSet('mes_lis_inv_lin_det_amo_requested_amount')"></span></th>
              <th class="pointer_class" @click="sorting('deleted_at')">削除日時 <span class="float-right" :class="iconSet('deleted_at')"></span></th>
              <th class="pointer_class" v-role="['Super Admin']">削除実行</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(value, index) in invoice_lists.data" :key="index">
              <td>{{ invoice_lists.current_page * form.per_page - form.per_page + index + 1 }}</td>
              <td>
                  <button @click="goToDetailsPage('slr_invoice_details',value)" class="btn btn-link btn-link-custom">
                      {{ value.mes_lis_inv_per_end_date }}
                  </button>
              </td>
              <td>{{ value.mes_lis_inv_pay_code }}</td>
              <td>{{ value.cnt }}</td>
              <td>{{ value.decision_cnt }}</td>
              <td>{{ value.send_cnt }}</td>
              <td class="text-right">{{value.total_amount | priceFormat }}</td>
              <td>{{ value.deleted_at | momentDateTimeFormat }}</td>
              <span v-role="['Super Admin']">
              <td v-if="value.deleted_at==null">
                  <b-button pill variant="danger" @click="deleteOrRetrive(value,'d')">削除</b-button>
              </td>
              <td v-else><b-button pill variant="info" @click="deleteOrRetrive(value,'r')">削除取消</b-button></td>
              </span>
            </tr>
            <tr v-if="invoice_lists.data && invoice_lists.data.length==0">
            <td class="text-center" colspan="100%">データがありません</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

<b-modal
      size="lg"
      :hide-backdrop="true"
      title="取引先コード一覧"
      cancel-title="閉じる"
      v-model="showAllCustomerCodeListModal"
      :hide-footer="true"
      :no-enforce-focus="true">
      <div class="panel-body add_item_body">
          <div class="row">
        <table class="table table-striped order_item_details_table table-bordered data_table">
          <thead>
            <tr>
              <th style="cursor: pointer">No</th>
              <th>取引先コード</th>
              <th>取引先名</th>
              <th>請求先コード</th>
              <th>請求取引先名</th>
              <th>取引先形態区分</th>
            </tr>
          </thead>
          <tbody>
          <tr v-for="(value,index) in order_customer_code_lists" @click="onRowClicked(value)" :key="index">
          <td>{{index+1}}</td>
          <td>{{value.mes_lis_inv_pay_code}}</td>
          <td>{{value.mes_lis_inv_pay_name}}</td>
          <td>{{value.mes_lis_buy_code}}</td>
          <td>{{value.mes_lis_buy_code}}</td>
          <td></td>
          </tr>
          </tbody>
          </table>
          </div>
      </div>
    </b-modal>
  </div>
</template>
<script>
export default {
  data() {
    return {
      invoice_lists: {},
      invoice_lists_length:0,
      order_customer_code_lists: {},
      showAllCustomerCodeListModal:false,
      send_cnt: [{ "*": "全て" }, { "!0": "未送信あり" }, { 0: "送信済" }],
      decission_cnt: [{ "*": "全て" }, { "!0": "未確定あり" }, { 0: "確定済" }],
      send_datetime_status: ["未請求", "請求済","再請求あり"],
      form: new Form({
        per_page:10,
        page:1,
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        mes_lis_inv_pay_code: '',
        send_cnt:'*',
        decission_cnt:'*',
        trade_number: null,
        mes_lis_inv_per_begin_date:'',
        mes_lis_inv_per_end_date:'',
        send_datetime_status: "*",
        sort_by:'mes_lis_inv_per_end_date ',
        sort_type:"DESC",
        page_title:'invoice_list',
        downloadType:1
      }),
    };
  },
  methods: {
    onRowClicked (item) {
        this.form.mes_lis_inv_pay_code = item.mes_lis_inv_pay_code;
       this.showAllCustomerCodeListModal = false;
    },
    //get Table data
    showAllCustomerCode(){
    let loaders = Vue.$loading.show();
      this.showAllCustomerCodeListModal = true;
      axios.post(this.BASE_URL + "api/slr_get_invoice_customer_code_list", this.form)
        .then(({ data }) => {
          this.order_customer_code_lists = data.order_customer_code_lists;
          loaders.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loaders.hide();
        // });
    },

    //get Table data
    get_all_invoice_list(page = 1) {
        this.form.page=page;
         let loader = Vue.$loading.show();
      axios.post(this.BASE_URL + "api/slr_get_all_invoice_list",this.form)
        .then(({data}) => {
          this.invoice_lists = data.invoice_list;
          this.invoice_lists_length=this.invoice_lists.data.length;
          loader.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loader.hide();
        // });
    },
    sorting(sorted_field){
          this.form.sort_by=sorted_field;
          this.form.sort_type=this.form.sort_type=="ASC"?"DESC":"ASC";
          this.get_all_invoice_list();

      },
      invoice_download(downloadType = 1) {
      //downloadcsvshipment_confirm
      this.form.downloadType=downloadType
      axios.post(this.BASE_URL + "api/slr_download_invoice", this.form)
        .then(({ data }) => {
           this.downloadFromUrl(data);
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loader.hide();
        // });
    },
    deleteOrRetrive(value,type){
        // console.log(value)
        this.alert_icon='warning';
        this.alert_title=(type==='d'?'対象データを削除しますか？':'対象データの削除を取り消しますか？');
        this.alert_text=(type==='d'?'削除取消より取り消しができます':'');
        this.yes_btn=(type==='d'?'削除します':'削除を取り消します')
        this.confirm_sweet().then((result) => {
            if (result.value) {
                axios.post(this.BASE_URL + "api/invoice_delete_or_retrive", value).then(({ data }) => {
                    // console.log(data)
                    if (data.status==1) {
                        this.alert_icon='success';
                        this.alert_title=(type==='d'?'削除しました':'削除取り消しました');
                        this.alert_text= '';
                        this.sweet_normal_alert()
                        this.get_all_invoice_list(this.form.page)
                    }
                })
                // .catch(() => {
                //     this.sweet_advance_alert();
                //     // loader.hide();
                // });
            }
        })
    },
    goToDetailsPage(page_name,invoice_list){
        this.$store.commit('slrInvoiceDetailsModule/reset')
        // this.$store.commit('reset')
        var query_array = { data_invoice_id: invoice_list.data_invoice_id,
                end_date: invoice_list.mes_lis_inv_per_end_date.valueOf(),
                pay_code: invoice_list.mes_lis_inv_pay_code,
                pay_name: invoice_list.mes_lis_inv_pay_name,
                buy_code:invoice_list.mes_lis_buy_code,
                buy_name: invoice_list.mes_lis_buy_name,
                requested_amount: invoice_list.total_amount,
            };
        this.$router.push({name:page_name,query:query_array})
    },
  },

  created() {
      this.form = this.$store.getters['slrInvoiceListModule/getFormData'];
    this.form.byr_buyer_id = this.$session.get("slr_byr_buyer_id");
    this.get_all_invoice_list();
    Fire.$on("LoadByrinvoice", () => {
      this.get_all_invoice_list();
    });
    Fire.$emit("loadPageTitle", "請求データ一覧");
  },
  mounted() {
  },
   computed: {
  },
};
</script>
