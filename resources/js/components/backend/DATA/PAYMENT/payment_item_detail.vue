<template>
  <div class="row">
   <div class="col-12">
    <div class="col-12" style="background: #d8e3f0; padding: 10px;margin-bottom:20px;">
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
        <tr>
          <td class="cl_custom_color_active">取引先コード</td>
          <td colspan="100%">{{payment_detail_header.mes_lis_pay_lin_sel_code}}</td>
        </tr>
      </table>
    </div>
    </div>
    <div class="col-12">
    <div class="col-12" style="background: #d8e3f0; padding: 10px">
      <table class="table orderDetailTable cmnWidthTable table-bordered" style="width: 100%">
          <tr>
            <td class="cl_custom_color">計上日</td>
            <td><div class="input-group">

      <input type="date" class="form-control" v-model="form.from_date">
      <div class="input-group-prepend">
        <span class="input-group-text">~</span>
      </div>
      <input type="date" class="form-control" v-model="form.to_date">
    </div></td>
            <td class="cl_custom_color">部門</td>
            <td colspan="3">
                <!--<multiselect v-model="form.category_code" :options="byr_buyer_category_lists" label="category_name" track-by="category_code" :searchable="true" :close-on-select="true" :clear-on-select="true" :select-label="''" :deselect-label="''" :selected-label="'選択中'" :preserve-search="true" placeholder="部門"><span slot="noOptions">候補がありません</span> <span slot="noResult">候補がありません</span></multiselect>-->
                 <multiselect v-model="form.category_code" :options="byr_buyer_category_lists" label="category_name" track-by="category_code" :searchable="true" :close-on-select="true" :clear-on-select="true" :select-label="''" :deselect-label="''" :selected-label="'選択中'" :preserve-search="true"  placeholder="部門">
               <span slot="noOptions">候補がありません</span> <span slot="noResult">候補がありません</span>
            </multiselect>
            </td>

          </tr>
          <tr>
            <td class="cl_custom_color">納品先コード</td>
            <td> <input type="text" class="form-control" v-model="form.mes_lis_pay_lin_tra_code" style="float:left;width:60%;margin-right:10px;">
             <button @click="showAllCustomerCode" class="btn btn-primary" style="float:left;width:35%;">
              {{ myLang.refer }}
            </button>
            </td>
            <td class="cl_custom_color">伝票番号</td>
            <td><input type="text" class="form-control" v-model="form.mes_lis_pay_lin_lin_trade_number_reference"></td>
            <td class="cl_custom_color">支払内容</td>
            <td>
            <select class="form-control" v-model="form.mes_lis_inv_lin_det_pay_code">
            <option value="*">全て</option>
            <option v-for="(opt, i) in mes_lis_inv_lin_det_pay_code_list"
                  :key="i"
                  :value="i"
                >
                  {{ opt }}
                </option>
            </select>
            </td>
          </tr>
          <tr>
            <td class="cl_custom_color">伝票区分</td>
            <td>
                <select class="form-control" v-model="form.mes_lis_pay_lin_det_trade_type_code">
                    <option value="*">全て</option>
                    <option v-for="(dsc, i) in mes_lis_pay_lin_det_trade_type_codeList" :key="i" :value="i">
                        {{ dsc}}
                    </option>
                </select>
            </td>
            <td class="cl_custom_color">請求区分</td>
            <td><select class="form-control" v-model="form.mes_lis_pay_lin_det_balance_carried_code">
            <option value="*">全て</option>
            <option v-for="(opt, i) in mes_lis_inv_lin_det_balance_carried_code_list"
                  :key="i"
                  :value="i"
                >
                  {{ opt }}
                </option>
            </select></td>
            <td class="cl_custom_color">照合結果</td>
            <td>
            <select class="form-control" v-model="form.mes_lis_pay_lin_det_verification_result_code">
            <option value="*">全て</option>
            <option v-for="(opt, i) in mes_lis_pay_lin_det_verification_result_code_list"
                  :key="i"
                  :value="i"
                >
                  {{ opt }}
                </option>
            </select>
            </td>
          </tr>
        </table>
    </div>
    </div>


      <div class="col-12" style="text-align: center">
        <button
          @click="searchByFormData()"
          class="btn btn-primary active srchBtn"
          type="button"
        >
          {{ myLang.search }}
        </button>
      </div>

    <div class="col-12">
      <div class="row">
      <div class="col-12">
      <h4 class="page_custom_title">検索結果：一覧</h4>
      <p>
              <span class="tableRowsInfo">{{ paymentdetailTopTable.from }}〜{{ paymentdetailTopTable.to }} 件表示中／全：{{ paymentdetailTopTable.total }}件</span>
              <span class="pagi"
                >
              <advanced-laravel-vue-paginate :data="paymentdetailTopTable"
              :onEachSide="2"
              previousText="<"
              nextText=">"
              alignment="center"
                @paginateTo="getAllPaymentDetails"/>
              </span>
              <span class="selectPagi">
                <select class="form-control selectPage" @change="getAllPaymentDetails"
                  v-model="form.select_field_per_page_num" >
                  <!--<option value="0">表示行数</option>
                  <option v-for="n in order_detail_lists.last_page" :key="n"
                :value="n">{{n}}</option>-->
                <option value="10">10行</option>
                <option value="20">20行</option>
                <option value="50">50行</option>
                <option value="100">100行</option>
                </select>
              </span>
            </p>
        <table
          class="table table-striped order_item_details_table table-bordered data_table"
        >
          <thead>
            <tr>
              <th>No</th>
              <th class="pointer_class" @click="sorting('mes_lis_pay_lin_det_transfer_of_ownership_date')">計上日 <span class="float-right" :class="iconSet('mes_lis_pay_lin_det_transfer_of_ownership_date')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_pay_lin_det_goo_major_category')">部門コード <span class="float-right" :class="iconSet('mes_lis_pay_lin_det_goo_major_category')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_pay_lin_tra_code')">納品先コード <span class="float-right" :class="iconSet('mes_lis_pay_lin_tra_code')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_pay_lin_lin_trade_number_reference')">伝票番号 <span class="float-right" :class="iconSet('mes_lis_pay_lin_lin_trade_number_reference')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_pay_lin_det_pay_code')">支払内容 <span class="float-right" :class="iconSet('mes_lis_pay_lin_det_pay_code')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_pay_lin_det_trade_type_code')">伝票区分 <span class="float-right" :class="iconSet('mes_lis_pay_lin_det_trade_type_code')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_pay_lin_det_balance_carried_code')">請求区分 <span class="float-right" :class="iconSet('mes_lis_pay_lin_det_balance_carried_code')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_pay_lin_det_amo_requested_amount')">請求金額 <span class="float-right" :class="iconSet('mes_lis_pay_lin_det_amo_requested_amount')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_pay_lin_det_amo_payable_amount')">支払金額 <span class="float-right" :class="iconSet('mes_lis_pay_lin_det_amo_payable_amount')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_pay_lin_det_verification_result_code')">照合結果 <span class="float-right" :class="iconSet('mes_lis_pay_lin_det_verification_result_code')"></span></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(value,index) in paymentdetailTopTable.data" :key="index">
              <td>{{ paymentdetailTopTable.current_page * form.select_field_per_page_num - form.select_field_per_page_num + index + 1 }}</td>
              <td>{{value.mes_lis_pay_lin_det_transfer_of_ownership_date}}</td>
              <td>{{value.mes_lis_pay_lin_det_goo_major_category}}</td>
              <td>{{value.mes_lis_pay_lin_tra_code}}</td>
              <td>{{value.mes_lis_pay_lin_lin_trade_number_reference}}</td>
              <td>{{value.mes_lis_pay_lin_det_pay_code}}
              {{
                getbyrjsonValueBykeyName(
                  "mes_lis_pay_lin_det_pay_code",
                  value.mes_lis_pay_lin_det_pay_code,
                  "payments"
                )
              }}
              </td>
              <td>{{value.mes_lis_pay_lin_det_trade_type_code}}
              {{
                getbyrjsonValueBykeyName(
                  "mes_lis_pay_lin_det_trade_type_code",
                  value.mes_lis_pay_lin_det_trade_type_code,
                  "payments"
                )
              }}
              </td>
              <td>{{value.mes_lis_pay_lin_det_balance_carried_code}}</td>
              <td class="text-right"><span v-if="value.mes_lis_pay_lin_det_amo_pay_plus_minus=='-'">{{value.mes_lis_pay_lin_det_amo_pay_plus_minus}}</span>{{value.mes_lis_pay_lin_det_amo_requested_amount | priceFormat}}</td>
              <td class="text-right"><span v-if="value.mes_lis_pay_lin_det_amo_pay_plus_minus=='-'">{{value.mes_lis_pay_lin_det_amo_pay_plus_minus}}</span>{{value.mes_lis_pay_lin_det_amo_payable_amount | priceFormat}}</td>
              <td>{{value.mes_lis_pay_lin_det_verification_result_code}}
              {{
                getbyrjsonValueBykeyName(
                  "mes_lis_pay_lin_det_verification_result_code",
                  value.mes_lis_pay_lin_det_verification_result_code,
                  "payments"
                )
              }}
              </td>
            </tr>
            <tr v-if="paymentdetailTopTable.data && paymentdetailTopTable.data.length==0">
                <td colspan="11">データがありません</td>
            </tr>
          </tbody>
        </table>
      </div>


      </div>
    </div>
    <b-modal
      size="lg"
      :hide-backdrop="true"
      title="納品先コード一覧"
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
              <th>納品先コード</th>
              <th>納品先名</th>

            </tr>
          </thead>
          <tbody>
          <tr v-for="(value,index) in order_customer_code_lists" @click="onRowClicked(value)" :key="index">
          <td>{{index+1}}</td>
          <td>{{value.mes_lis_pay_lin_tra_code}}</td>
          <td>{{value.mes_lis_pay_lin_tra_name}}</td>
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
breadcrumb(){
    return {
    label: "支払伝票一覧",
    parent: this.parentQ
  }
},
  data() {
    return {
      parentQ:{
        name: 'payment_detail',
        query: {},
      },
      payment_detail_header: {},
      byr_buyer_id: null,
            order_customer_code_lists: {},
      showAllCustomerCodeListModal:false,

      mes_lis_pay_lin_det_verification_result_code_list:{},
      mes_lis_inv_lin_det_pay_code_list:{},
      mes_lis_inv_lin_det_balance_carried_code_list:{},
      paymentdetailTopTable:{},
      form: new Form({
        select_field_per_page_num: 10,
        page: 1,
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        mes_lis_pay_lin_tra_code: null,
        from_date: null,
        to_date: null,
        category_code: {category_code:'*',category_name:'全て'},
        mes_lis_pay_lin_sel_code:null,
        mes_lis_inv_lin_det_pay_code:'*',
        mes_lis_pay_lin_det_verification_result_code:'*',
        mes_lis_pay_lin_det_trade_type_code:'*',
        mes_lis_pay_lin_det_balance_carried_code:'*',
        mes_lis_pay_lin_lin_trade_number_reference: null,
        check_datetime: null,
        pay_code:null,
        sel_code:null,
        end_date:null,
        out_date:null,
        submit_type: "page_load",
        payment_id:'',
        sort_by:'data_payment_pay_detail_id ',
        sort_type:"ASC",
        pay_code_list:[1001, 1002, 1004]
      }),
    };
  },
  beforeCreate: function() {
            if (!this.$session.exists()) {
                this.$router.push('/home');
            }
        },
  methods: {
     onRowClicked (item) {
        this.form.mes_lis_pay_lin_tra_code = item.mes_lis_pay_lin_tra_code;
       this.showAllCustomerCodeListModal = false;
    },
    sorting(sorted_field){
          this.form.sort_by=sorted_field;
          this.form.sort_type=this.form.sort_type=="ASC"?"DESC":"ASC";
          this.getAllPaymentDetails();

      },
    //get Table data
    showAllCustomerCode(){
     let loaderss = Vue.$loading.show();
      this.showAllCustomerCodeListModal = true;
      axios.post(this.BASE_URL + "api/get_payment_trade_code_list", this.form)
        .then(({ data }) => {
          this.order_customer_code_lists = data.order_customer_code_lists;
          loaderss.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loaderss.hide();
        // });
    },
    //get Table data
    getAllPaymentDetails(page=1) {
      this.form.page = page;
      this.select_field_page_num = page;
      let loaders = Vue.$loading.show();
      axios.post(this.BASE_URL + "api/get_payment_item_detail_list", this.form)
        .then(({ data }) => {

          this.payment_detail_header = data.payment_item_header;
          this.paymentdetailTopTable = data.paymentdetailTopTable;
          this.buyer_settings = JSON.parse(data.buyer_settings);

          this.mes_lis_pay_lin_det_verification_result_code_list = this.buyer_settings.payments.mes_lis_pay_lin_det_verification_result_code;
          this.mes_lis_inv_lin_det_pay_code_list = this.buyer_settings.invoices.mes_lis_inv_lin_det_pay_code;
          this.mes_lis_inv_lin_det_balance_carried_code_list = this.buyer_settings.invoices.mes_lis_inv_lin_det_balance_carried_code;

          loaders.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loaders.hide();
        // });
    },

searchByFormData() {
   let loader = Vue.$loading.show();
      Fire.$emit("LoadPaymentItemDetail",this.select_field_page_num);
      loader.hide();
    },
  },

  created() {
      this.form = this.$store.getters['paymentItemDetailsModule/getFormData'];
    this.getbuyerJsonSettingvalue();
    this.byr_buyer_id = this.$session.get("byr_buyer_id");
    this.form.byr_buyer_id = this.$session.get("byr_buyer_id");
    this.form.payment_id = this.$route.query.data_payment_id;
    this.form.pay_code = this.$route.query.pay_code
    this.form.sel_code = this.$route.query.sel_code
    this.form.end_date = this.$route.query.end_date
    this.form.out_date = this.$route.query.out_date
    // this.getbuyerJsonSettingvalue();
    this.getAllPaymentDetails(this.form.page);
    Fire.$on("LoadPaymentItemDetail", (page=this.form.page) => {
      this.getAllPaymentDetails(page);
    });
    Fire.$emit("byr_has_selected", this.byr_buyer_id);
    Fire.$emit("permission_check_for_buyer", this.byr_buyer_id);
    Fire.$emit("loadPageTitle", "支払伝票一覧");
    this.parentQ.query = this.$session.get('payment_detail_query_param');
  },
  computed: {

    totalAmountVal: function() {
      return this.pdtableleft.reduce(function (sumselling,val) {return  sumselling = (val.sumation_type=='1'?sumselling+val.amount:sumselling-val.amount)},0);

    },

    totalAmountValOffset: function() {
      return this.paymentdetailRghtTable.reduce(function (sumselling,val) {return  sumselling += val.mes_lis_pay_lin_det_amo_requested_amount_sum},0);

    },
  },
  mounted() {},
};
</script>
