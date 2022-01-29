<template>
  <div class="row">
  <div class="col-12">
    <div class="col-12" style="background: #d8e3f0; padding: 10px">
      <!--<h4 class="top_title text-center" style="margin-top:10px;">{{myLang.payment_data}}</h4>-->
      <table class="table orderDetailTable payemntWidthTable table-bordered" style="width: 100%">
        <tr>
        <td class="cl_custom_color">受信日</td>
          <td>
          <div class="input-group">
                    <input type="date" class="form-control" v-model="form.receive_date_from">
                    <div class="input-group-prepend">
                        <span class="input-group-text">~</span>
                    </div>
                    <input type="date" class="form-control" v-model="form.receive_date_to">
                </div>

          </td>
          <td class="cl_custom_color">請求取引先コード</td>
          <td colspan="3">
            <input
              type="text"
              class="form-control topHeaderInputFieldBtn"
              v-model="form.mes_lis_pay_pay_code"
            />
            <button @click="showAllCustomerCode" class="btn btn-primary active">参照</button>
          </td>
        </tr>
        <tr>
        <td class="cl_custom_color">締日</td>
          <td>
          <div class="input-group">
                    <input type="date" class="form-control" v-model="form.mes_lis_pay_per_end_date_from">
                    <div class="input-group-prepend">
                        <span class="input-group-text">~</span>
                    </div>
                    <input type="date" class="form-control" v-model="form.mes_lis_pay_per_end_date_to">
                </div>
           </td>
          <td class="cl_custom_color">支払日</td>
          <td>
          <div class="input-group">
                    <input type="date" class="form-control" v-model="form.mes_lis_pay_lin_det_pay_out_date_from">
                    <div class="input-group-prepend">
                        <span class="input-group-text">~</span>
                    </div>
                    <input type="date" class="form-control" v-model="form.mes_lis_pay_lin_det_pay_out_date_to">
                </div>
           </td>
          <td class="cl_custom_color">参照状況</td>
          <td>
              <select class="form-control" v-model="form.check_datetime">
              <option value="*">全て</option>
              <option value="1">未参照</option>
              <option value="2">参照済</option>
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
    <div class="col-12" style="text-align: center">
      <button class="btn btn-primary active srchBtn" type="button" @click="getAllPayments">
        {{ myLang.search }}
      </button>
    </div>

    <div class="col-12 text-center page_c_title_bar text-sm-left mb-0">
      <h4 class="page_custom_title">検索結果：一覧</h4>

    </div>
    <div class="col-12 text-center">
      <button class="btn btn-outline-primary" type="button" @click="download(1)" :disabled="is_disabled(payment_lists_length>=1?true:false)">
        <b-icon icon="download" animation="fade" font-scale="1.2"></b-icon>
        {{ myLang.download }}
      </button>
    </div>
    <div class="col-12">
        <div class="row">
          <div class="col-5">
            <p>
              <span class="tableRowsInfo"
                >{{ payment_lists.from }}〜{{
                  payment_lists.to
                }}
                件表示中／全：{{ payment_lists.total }}件</span
              >
              <span class="pagi">
                <advanced-laravel-vue-paginate
                  :data="payment_lists"
                  :onEachSide="2"
                  previousText="<"
                  nextText=">"
                  alignment="center"
                  @paginateTo="getAllPayments"
                />
              </span>
              <span class="selectPagi">
                <select
                  @change="getAllPayments"
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
        <table
          class="table table-striped order_item_details_table table-bordered data_table"
        >
          <thead>
            <tr>
              <th>No</th>
              <th class="pointer_class" @click="sorting('receive_datetime')">受信日時 <span class="float-right" :class="iconSet('receive_datetime')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_pay_pay_code')">請求取引先コード <span class="float-right" :class="iconSet('mes_lis_pay_pay_code')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_buy_name')">発注者 <span class="float-right" :class="iconSet('mes_lis_buy_name')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_pay_per_end_date')">締日 <span class="float-right" :class="iconSet('mes_lis_pay_per_end_date')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_pay_lin_det_pay_out_date')">支払日 <span class="float-right" :class="iconSet('mes_lis_pay_lin_det_pay_out_date')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_pay_lin_det_amo_payable_amount')">支払金額 <span class="float-right" :class="iconSet('mes_lis_pay_lin_det_amo_payable_amount')"></span></th>
              <th class="pointer_class" @click="sorting('check_datetime')">参照状況 <span class="float-right" :class="iconSet('check_datetime')"></span></th>
              <th class="pointer_class" @click="sorting('deleted_at')">削除日時 <span class="float-right" :class="iconSet('deleted_at')"></span></th>
              <th v-role="['Super Admin']">削除実行</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(payment, index) in payment_lists.data" :key="index">
              <td>{{ index + 1 }}</td>
              <td>
                  <button @click="goToDetailsPage('slr_payment_detail',payment)" class="btn btn-link btn-link-custom">
                      {{ payment.receive_datetime }}
                  </button>
                    </td>
              <td>{{ payment.mes_lis_pay_pay_code }}</td>
              <td>{{ payment.mes_lis_buy_name }}</td>
              <td>{{ payment.mes_lis_pay_per_end_date }}</td>
              <td>{{ payment.mes_lis_pay_lin_det_pay_out_date }}</td>
              <td class="text-right">{{ payment.total_amount | priceFormat}}</td>
              <td>{{ payment.check_datetime }}</td>
              <td>{{ payment.deleted_at | momentDateTimeFormat }}</td>
              <span v-role="['Super Admin']">
                <td v-if="payment.deleted_at==null">
                    <b-button pill variant="danger" @click="deleteOrRetrive(payment,'d')">削除</b-button>
                </td>
                <td v-else><b-button pill variant="info" @click="deleteOrRetrive(payment,'r')">削除取消</b-button></td>
              </span>
            </tr>
            <tr v-if="payment_lists.data && payment_lists.data.length==0">
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
      :no-enforce-focus="true"
    >
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

            </tr>
          </thead>
          <tbody>
          <tr v-for="(value,index) in order_customer_code_lists" @click="onRowClicked(value)" :key="index">
          <td>{{index+1}}</td>
          <td>{{value.mes_lis_pay_pay_code}}</td>
          <td>{{value.mes_lis_pay_pay_name}}</td>
          <td>{{value.mes_lis_buy_code}}</td>
          <td>{{value.mes_lis_buy_name}}</td>
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
      payment_lists: {},
      order_customer_code_lists: {},
      showAllCustomerCodeListModal:false,

      payment_lists_length: 0,
      byr_buyer_lists: {},
    //   byr_buyer_id: null,
      form: new Form({
        per_page: 10,
        page: 1,
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        mes_lis_pay_pay_code: null,
        receive_date_from: null,
        receive_date_to: null,
        mes_lis_buy_name: null,
        mes_lis_pay_per_end_date_from: null,
        mes_lis_pay_per_end_date_to: null,
        mes_lis_pay_lin_det_pay_out_date_from: null,
        mes_lis_pay_lin_det_pay_out_date_to: null,
        check_datetime: '*',
        trade_number: null,
        page_title: "slr_payment_list",
        sort_by:'receive_datetime ',
        sort_type:"DESC",
        downloadType:1,
      }),
    };
  },
  // beforeCreate: function() {
  //           if (!this.$session.exists()) {
  //               this.$router.push('/home');
  //           }
  //       },
  methods: {
    onRowClicked (item) {
        this.form.mes_lis_pay_pay_code = item.mes_lis_pay_pay_code;
       this.showAllCustomerCodeListModal = false;
    },
    //get Table data
    showAllCustomerCode(){
     let loaders = Vue.$loading.show();
      this.showAllCustomerCodeListModal = true;
      axios.post(this.BASE_URL + "api/slr_get_payment_customer_code_list", this.form)
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
    getAllPayments() {
      let loader = Vue.$loading.show();
      axios.post(this.BASE_URL + "api/slr_get_payment_list", this.form)
        .then(({ data }) => {
          this.payment_lists = data.payment_item_list;
          this.payment_lists_length = this.payment_lists.data.length;
          this.byr_buyer_lists = data.byr_buyer_list;
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
          this.getAllPayments();

      },
      download(downloadType = 1) {
      //downloadcsvshipment_confirm
      let loader = Vue.$loading.show();
      this.form.downloadType=downloadType;
      axios
        .post(this.BASE_URL + "api/slr_payment_download", this.form)
        .then(({ data }) => {
          this.downloadFromUrl(data);
          loader.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loader.hide();
        // });
    },
    deleteOrRetrive(order_list,type='d'){
        this.alert_icon='warning';
        this.alert_title=(type==='d'?'対象データを削除しますか？':'対象データの削除を取り消しますか？');
        this.alert_text=(type==='d'?'削除取消より取り消しができます':'');
        this.yes_btn=(type==='d'?'削除します':'削除を取り消します')
        this.confirm_sweet().then((result) => {
            if (result.value) {
                axios.post(this.BASE_URL + "api/payment_delete_or_retrive", order_list).then(({ data }) => {
                    if (data.status==1) {
                        this.alert_icon='success';
                        this.alert_title=(type==='d'?'削除しました':'削除取り消しました');
                        this.alert_text= '';
                        this.sweet_normal_alert()
                        this.getAllPayments(this.form.page)
                    }
                })
                // .catch(() => {
                //     this.sweet_advance_alert();
                //     // loader.hide();
                // });
            }
        })
    },
    goToDetailsPage(page_name,payment_list){
        this.$store.commit('slrPaymentItemDetailsModule/reset')
        // this.$store.commit('reset')
        var query_array = { data_payment_id: payment_list.data_payment_id,
                pay_code: payment_list.mes_lis_pay_pay_code,
                end_date: payment_list.mes_lis_pay_per_end_date.valueOf(),
                out_date:payment_list.mes_lis_pay_lin_det_pay_out_date.valueOf(),
            };
        this.$router.push({name:page_name,query:query_array})
    },
  },

  created() {
      this.form = this.$store.getters['slrPaymentListModule/getFormData'];
    this.form.byr_buyer_id=this.$session.get("slr_byr_buyer_id")
    this.getAllPayments();
    // Fire.$on("getAllPayments", () => {
    // this.getAllPayments();
    // });
    // Fire.$emit("byr_has_selected", this.byr_buyer_id);
    // Fire.$emit("permission_check_for_buyer", this.byr_buyer_id);
    Fire.$emit("loadPageTitle", "支払データ一覧");
  },
  mounted() {},
};
</script>
