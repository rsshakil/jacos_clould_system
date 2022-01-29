<template>
  <div class="row">
  <div class="col-12">
    <div class="col-12" style="background: #d8e3f0; padding: 10px">
      <!--<h4 class="top_title text-center" style="margin-top:10px;">{{myLang.payment_data}}</h4>-->
      <table class="table orderDetailTable table-bordered" style="width: 100%">
        <tr>
          <td class="cl_custom_color" >受信日時</td>
          <td>{{payment_detail_header.receive_datetime}}</td>

          <td class="cl_custom_color">請求取引先</td>
          <td>{{payment_detail_header.mes_lis_pay_pay_code}} {{payment_detail_header.mes_lis_pay_pay_name}}</td>

        </tr>
        <tr>
          <td class="cl_custom_color">発注者</td>
          <td>{{payment_detail_header.mes_lis_buy_code}} {{payment_detail_header.mes_lis_buy_name}}

          </td>

          <td class="cl_custom_color" >締日</td>
          <td>{{payment_detail_header.mes_lis_pay_per_end_date}}</td>

        </tr>
        <tr>
          <td class="cl_custom_color">支払日</td>
          <td>{{payment_detail_header.mes_lis_pay_lin_det_pay_out_date}}</td>

          <td class="cl_custom_color" >支払金額</td>
          <td class="text-right">{{payment_detail_header.total_amount | priceFormat}}</td>
        </tr>
      </table>
    </div>
    </div>
    <div class="col-12">
    <div class="row">
 <div class="col-2"></div>
<div class="col-6 p-3" style="background-color:#d8e3f0; border-radius:1rem;margin-top:15px;margin-bottom:15px;">
            <div class="row">
              <div class="col-6">
                <p class="mb-0">検索結果のダウンロードはこちら</p>
                <div class="btn-group">
                  <button
                    type="button"
                    class="btn btn-primary active dropdown-toggle"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                    :disabled="is_disabled(pay_code_lists.length>0?true:false)"
                  >
                    <b-icon
                      icon="download"
                      animation="fade"
                      font-scale="1.2"
                    ></b-icon>
                    ダウンロード
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item" @click="download(1)" type="button">
                      CSV
                    </button>
                    <!--<button class="dropdown-item" @click="receive_download(2)" type="button">
                      JCA
                    </button>-->
                  </div>
                </div>
              </div>

              <div class="col-6">
                <p class="mb-0">支払案内書のダウンロードはこちら</p>
                <div class="btn-group">
                  <button
                    type="button"
                    class="btn btn-primary active dropdown-toggle"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                    :disabled="is_disabled(pay_code_lists.length>0?true:false)">
                    <!-- :disabled="is_disabled(false)" -->
                    <b-icon
                      icon="download"
                      animation="fade"
                      font-scale="1.2"
                    ></b-icon>
                    ダウンロード
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item" @click="paymentPdfDownload()" type="button">
                      PDF
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

     <div class="col-4 p-3">
        <p class="mb-0" style="margin-top:15px;">未払伝票の確認はこちら</p>
        <button class="btn btn-primary" type="button" @click="openSlipModal" :disabled="is_disabled(pay_code_lists.length>0?true:false)">
            未払伝票確認
        </button>
     </div>
    </div>
    </div>

    <div class="col-12">
      <div class="row">
      <div class="col-12" style="margin-bottom:40px;">
      <h4 class="page_custom_title">取引先別支払合計（仕入）</h4>
        <table
          class="table table-striped order_item_details_table table-bordered data_table"
        >
          <thead>
            <tr>
              <th style="cursor: pointer">No</th>
              <th style="cursor: pointer">取引先</th>
              <th></th>
              <th style="cursor: pointer">支払合計金額</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(pay_code_list,index) in pay_code_lists" :key="index">
              <td>{{index+1}}</td>
              <td>
                  {{pay_code_list.mes_lis_pay_lin_sel_code}} {{pay_code_list.mes_lis_pay_pay_name}}
                </td>
                <td class="text-center">
                <button @click="goToItemDetailsPage('payment_item_detail',pay_code_list)" class="btn btn-primary">
                      伝票一覧
                  </button>
                </td>
              <td class="text-right">{{pay_code_list.total_amount | priceFormat}}</td>
            </tr>
            <tr v-if="pay_code_lists && pay_code_lists.length==0">
            <td class="text-center" colspan="4">データがありません</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-6">
        <h4 class="page_custom_title">支払合計（仕入合計ー相殺合計）</h4>
          <table
          class="table table-striped order_item_details_table table-bordered data_table"
        >
          <thead>
            <tr>
              <th style="cursor: pointer">No</th>
              <th style="cursor: pointer">内容</th>
              <th style="cursor: pointer">金額</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item,index) in pdtableleft" :key="index" :style="{ color: item.sumation_type == '2' ? 'red' : '#000' }">
              <td>{{index+1}}</td>
              <td>{{item.p_title}}</td>
              <td class="text-right">{{item.amount | priceFormat}}</td>
            </tr>
             <tr v-if="pdtableleft && pdtableleft.length==0">
            <td class="text-center" colspan="3">データがありません</td>
            </tr>
            <tr>
            <td colspan="2">支払合計金額</td>
            <td class="text-right">{{totalAmountVal | priceFormat}}</td>
            </tr>
          </tbody>
        </table>
        </div>
        <div class="col-6">

        <h4 class="page_custom_title">相殺合計（相殺）</h4>
          <table
          class="table table-striped order_item_details_table table-bordered data_table"
        >
          <thead>
            <tr>
              <th style="cursor: pointer">No</th>
              <th style="cursor: pointer">取引先コード</th>
              <th style="cursor: pointer">相殺コード</th>
              <th style="cursor: pointer">相殺名称</th>
              <th style="cursor: pointer">相殺金額</th>
            </tr>
          </thead>
          <tbody>
              <tr v-for="(item,index) in paymentdetailRghtTable" :key="index">
              <td>{{index+1}}</td>
              <td>{{item.mes_lis_pay_lin_sel_code}}</td>
              <!-- <td>{{item.mes_lis_pay_pay_code}}</td> -->
              <td>{{item.mes_lis_pay_lin_det_det_code}}</td>
              <td>{{item.mes_lis_pay_lin_det_det_meaning}}</td>
              <td class="text-right">{{item.mes_lis_pay_lin_det_amo_payable_amount_sum | priceFormat}}</td>
            </tr>
             <tr v-if="paymentdetailRghtTable && paymentdetailRghtTable.length==0">
            <td class="text-center" colspan="5">データがありません</td>
            </tr>
            <tr>
              <td colspan="4">相殺合計金額</td>
              <td class="text-right">{{totalAmountValOffset | priceFormat}}</td>
            </tr>

          </tbody>
        </table>
      </div>

      </div>
    </div>
    <b-modal size="xl" :hide-backdrop="true" :no-enforce-focus="true" title="未払伝票確認" cancel-title="閉じる" v-model="unpaid_slip_modal" :hide-footer="true" :draggable="true">
      <div class="panel-body">
      <div class="row">
        <div class="col-6">
          <!--<p style="margin:0">出荷データと受領データで差異が発生している伝票のみ表示されています。</p>-->
          <p style="margin:0">未払となった請求伝票のみ表示されています。</p>
          <p style="margin:0">黄色の項目は差異が発生している項目です。</p>
        </div>
        <div class="col-6">
        <h6>ダウンロードを押すと、比較データがダウンロードされます</h6>
           <button class="btn btn-outline-primary" style="float:right;margin-bottom:15px;" type="button" :disabled="is_disabled(unpaid_lists.length>0?true:false)" @click="unpaid_data_download(1)">
        <b-icon icon="download" animation="fade" font-scale="1.2"></b-icon>
        {{ myLang.download }}
      </button>
        </div>
      </div>
      <table
            class="table table-striped table-bordered order_item_details_table"
            style="overflow-x: scroll"
          >
          <thead>

            <tr>
              <th>伝票番号</th>
              <th>直接納品先</th>
              <th>計上日</th>
              <th>請求金額</th>
          </tr>
          </thead>
          <tbody>
          <!-- <tr> -->
          <tr v-for="(value,index) in unpaid_lists" :key="index">
            <td>{{value.mes_lis_inv_lin_lin_trade_number_reference}}</td>
            <td>{{value.mes_lis_inv_lin_tra_code}} {{value.mes_lis_inv_lin_tra_name}}</td>
            <td>{{value.mes_lis_inv_lin_det_transfer_of_ownership_date}}</td>
            <td class="text-right">{{value.mes_lis_inv_lin_det_amo_req_plus_minus}} {{ value.mes_lis_inv_lin_det_amo_requested_amount | priceFormat }}</td>
          </tr>
        <tr v-if="unpaid_lists && unpaid_lists.length==0">
            <td class="text-center" colspan="4">データがありません</td>
        </tr>
          </tbody>

        </table>
        <div class="col-12 text-center">
        <button class="btn btn-primary" style="text-align:center" @click="closeSlipModal">閉じる</button>
      </div>
      </div>
    </b-modal>
  </div>

</template>
<script>
export default {
  data() {
    return {
      payment_detail_header: {},
      byr_buyer_id: null,
      paymentdetailTopTable:{},
      pay_code_lists:[],
      pdtableleft:[],
      paymentdetailRghtTable:[],
      unpaid_slip_modal:false,
      unpaid_lists:[],
      form: new Form({
        select_field_per_page_num: 10,
        page: 1,
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        mes_lis_pay_pay_code: null,
        receive_date_from: null,
        receive_date_to: null,
        mes_lis_buy_name: null,
        mes_lis_pay_per_end_date_from: null,
        mes_lis_pay_per_end_date_to: null,
        check_datetime: null,
        data_payment_id:null,
        pay_code:null,
        end_date:null,
        out_date:null,
        page_title:'payment_details_list',
        downloadType:1
      }),
    };
  },
  beforeCreate: function() {
            if (!this.$session.exists()) {
                this.$router.push('/home');
            }
        },
  methods: {
    //get Table data
    getAllPaymentDetails() {
      axios.post(this.BASE_URL + "api/get_payment_detail_list", this.form)
        .then(({ data }) => {
          this.payment_detail_header = data.payment_item_header;
          this.pay_code_lists = data.pay_code_list;
        //   if (this.pay_code_lists.length<=0) {
        //       this.$router.push({name: 'payment_list'})
        //   }
          this.pdtableleft = data.pdtableleft;
          this.paymentdetailRghtTable = data.paymentdetailRghtTable;

        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loader.hide();
        // });
    },
    download(downloadType = 1) {
      this.form.downloadType=downloadType;
      axios
        .post(this.BASE_URL + "api/payment_download",this.form)
        .then(({ data }) => {
          this.downloadFromUrl(data);
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loader.hide();
        // });
    },
    openSlipModal(){
        axios.post(this.BASE_URL + "api/unpaid_payment_list", this.form)
        .then(({ data }) => {
            this.unpaid_lists=data.unpaid_list;
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loader.hide();
        // });
       this.unpaid_slip_modal = true;
    },
    closeSlipModal(){
       this.unpaid_slip_modal = false;
    },
    unpaid_data_download(download_flag=1){
        axios.post(this.BASE_URL + "api/payment_unpaid_data_download", this.form)
        .then(({ data }) => {
            this.downloadFromUrl(data);
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loader.hide();
        // });
    },
    paymentPdfDownload(){
        let loaderrrsss = Vue.$loading.show();
        axios.post(this.BASE_URL + "api/payment_pdf_download", this.form)
            .then(({ data }) => {
                this.downloadFromUrl(data);
                loaderrrsss.hide();
            })
            // .catch(() => {
            //     this.sweet_advance_alert();
            //     loaderrrsss.hide();
            // });
    },
    goToItemDetailsPage(page_name,payment_detail_header){
        this.$store.commit('paymentItemDetailsModule/reset')
        // this.$store.commit('reset')
        var query_array = { data_payment_id: payment_detail_header.data_payment_id,
                pay_code: payment_detail_header.mes_lis_pay_pay_code,
                sel_code: payment_detail_header.mes_lis_pay_lin_sel_code,
                end_date: payment_detail_header.mes_lis_pay_per_end_date.valueOf(),
                out_date:payment_detail_header.mes_lis_pay_lin_det_pay_out_date.valueOf(),
            };
        this.$router.push({name:page_name,query:query_array})
    },

  },

  created() {
    this.byr_buyer_id = this.$session.get("byr_buyer_id");
    this.form.byr_buyer_id = this.byr_buyer_id;
    this.form.data_payment_id = this.$route.query.data_payment_id
    this.form.pay_code = this.$route.query.pay_code
    this.form.end_date = this.$route.query.end_date
    this.form.out_date = this.$route.query.out_date
    this.getAllPaymentDetails();
    Fire.$emit("byr_has_selected", this.byr_buyer_id);
    Fire.$emit("permission_check_for_buyer", this.byr_buyer_id);
    Fire.$emit("loadPageTitle", "支払合計");
    this.$session.set("payment_detail_query_param",this.$route.query);
  },
  computed: {

    totalAmountVal: function() {
      return this.pdtableleft.reduce(function (sumselling,val) {return  sumselling = (val.sumation_type=='1'?sumselling+parseFloat(val.amount):sumselling-parseFloat(val.amount))},0);

    },

    totalAmountValOffset: function() {
      return this.paymentdetailRghtTable.reduce(function (sumselling,val) {return  sumselling += parseInt(val.mes_lis_pay_lin_det_amo_payable_amount_sum)},0);

    },
  },
  mounted() {},
};
</script>
