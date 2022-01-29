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
              <option
                v-for="(dcnt, i) in decission_cnt"
                :key="i"
                :value="Object.keys(dcnt)[0]"
              >
                {{ Object.values(dcnt)[0] }}
              </option>
            </select>
          </td>
          <td class="cl_custom_color">送信状況</td>
          <td>
            <select class="form-control" v-model="form.send_cnt">
             <option
                v-for="(dcnt, i) in send_cnt"
                :key="i"
                :value="Object.keys(dcnt)[0]"
              >
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
      <button class="btn btn-primary active srchBtn" type="button" @click="get_all_invoice_list()">
          {{ myLang.search }}
        </button>
    </div>
    <div class="col-12">
        <br />
        <h4 class="page_custom_title">{{ myLang.search_result }}</h4>
      </div>
    <div class="col-12">
    <div class="row">
    <div class="col-5">
            <p>
              <span class="tableRowsInfo">{{ invoice_lists.from }}〜{{ invoice_lists.to }} 件表示中／全：{{ invoice_lists.total }}件</span>
              <span class="pagi"
                >
              <advanced-laravel-vue-paginate :data="invoice_lists"
              :onEachSide="2"
              previousText="<"
              nextText=">"
              alignment="center"
                @paginateTo="get_all_invoice_list"/>
              </span>
              <span class="selectPagi">
                <select class="form-control selectPage" @change="get_all_invoice_list()"
                  v-model="form.select_field_per_page_num" >
                <option value="10">10行</option>
                <option value="20">20行</option>
                <option value="50">50行</option>
                <option value="100">100行</option>
                </select>
              </span>
            </p>
            </div>
<div class="col-3 p-3" style="background-color:#d8e3f0; border-radius:1rem;margin-bottom:15px;">
                <p class="mb-0">検索結果のダウンロードはこちら</p>
                <div class="btn-group">
                  <button
                    type="button"
                    class="btn btn-primary active dropdown-toggle"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                    :disabled="is_disabled(invoice_lists_length>=1?true:false)"
                  >
                    <b-icon
                      icon="download"
                      animation="fade"
                      font-scale="1.2"
                    ></b-icon>
                    ダウンロード
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item" @click="invoice_download(1)" type="button">
                      CSV
                    </button>
                  </div>

              </div>
              </div>
<div class="col-2" style="padding-right:0;">
 <p class="mb-0">手動で締め処理実行はこちら<br>※通常は登録した締日にて自動実行されます</p>
<button @click="viewInvoicePopup" class="btn btn-primary " style="float:left;">新規請求</button>

</div>

<div class="col-2">
 <p class="mb-0">手動で締日作成はこちら<br>※通常は締日からの登録は不要です</p>
            <button class="btn btn-primary" @click="runInvoiceSchedular" style="float:left;">締め処理実行</button>
</div>

</div>
</div>
<div class="col-12">
    <div class="">
        <table
            class="table table-striped table-bordered order_item_details_table invoice_list_table" style="overflow-x: scroll">
          <thead>
            <tr>
              <th>No</th>
              <th class="pointer_class" @click="sorting('mes_lis_inv_per_end_date')">締日 <span class="float-right" :class="iconSet('mes_lis_inv_per_end_date')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_inv_pay_code')">取引先コード <span class="float-right" :class="iconSet('mes_lis_inv_pay_code')"></span></th>
              <th class="pointer_class">伝票　総数</th>
              <th class="pointer_class">未確定　伝票数</th>
              <th class="pointer_class">未送信　伝票数</th>
              <th class="pointer_class" @click="sorting('mes_lis_inv_lin_det_amo_requested_amount')">請求金額 <span class="float-right" :class="iconSet('mes_lis_inv_lin_det_amo_requested_amount')"></span></th>
            </tr>
          </thead>
          <tbody>
              <!-- {{ i=invoice_lists.from }} -->
            <tr v-for="(value, index) in invoice_lists.data" :key="index">
              <td>{{ invoice_lists.current_page * form.select_field_per_page_num - form.select_field_per_page_num + index + 1 }}</td>
              <td>
                   <button @click="goToDetailsPage('invoice_details',value)" class="btn btn-link btn-link-custom">
                      {{ value.mes_lis_inv_per_end_date }}
                  </button>
                  <!-- <router-link
                  :to="{
                    name: 'invoice_details',
                    query: {
                      data_invoice_id: value.data_invoice_id,
                      end_date: value.mes_lis_inv_per_end_date.valueOf(),
                      pay_code: value.mes_lis_inv_pay_code,
                      pay_name: value.mes_lis_inv_pay_name,
                      buy_code: value.mes_lis_buy_code,
                      buy_name: value.mes_lis_buy_name,
                      requested_amount: value.total_amount,
                    },
                  }" class="">{{ value.mes_lis_inv_per_end_date }}</router-link> -->
                <!-- <router-link :to="{ name: 'invoice_detail', params: { data_invoice_id: value.data_invoice_id }, }" class="btn btn-info">{{ value.mes_lis_inv_per_end_date }}</router-link> -->
              </td>
              <td>{{ value.mes_lis_inv_pay_code }}</td>
              <td>{{ value.cnt }}</td>
              <td>{{ value.decision_cnt }}</td>
              <td>{{ value.send_cnt }}</td>
              <td class="text-right">{{value.total_amount | priceFormat }}</td>

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
      title="新規請求作成"
      ok-title="登録"
      cancel-title="閉じる"
      @ok.prevent="insertInvoice()"
      v-model="invoiceCreateModal"
      :no-enforce-focus="true"
    >
      <div class="panel-body">
      <p v-if="errors.length">
        <b>次の間違いを正しくしてください:</b>
        <ul>
          <li style="color:red;" v-for="(error,i) in errors" :key="i">{{ error }}</li>
        </ul>
      </p>
        <table
          class="table orderDetailTable table-bordered"
          style="width: 100%"
        >
          <tr>
            <td class="cl_custom_color">取引先コード</td>
            <td>
                <select class="form-control" v-model="invoiceData.mes_lis_inv_pay_code">
                    <option v-for="(partner_code,i) in partner_codes" :value="partner_code.partner_code" :key="i">{{ partner_code.partner_code }}</option>
                </select>
            </td>
            <td class="cl_custom_color">締日</td>
            <td>
                <div class="input-group">

                    <input type="date" v-model="invoiceData.mes_lis_inv_per_begin_date" class="form-control">
                    <div class="input-group-prepend">
                        <span class="input-group-text">~</span>
                    </div>
                    <input type="date" v-model="invoiceData.mes_lis_inv_per_end_date" class="form-control">
                </div>
            </td>
          </tr>
        </table>
      </div>
    </b-modal>

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
  <table class="table table-striped order_item_details_table table-bordered data_table invoice_list_partner_code_modal">
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
          <td>{{value.mes_lis_buy_name}}</td>

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
      errors:[],
      byr_buyer_lists: {},
      invoiceCreateModal:false,
      order_customer_code_lists: {},
      showAllCustomerCodeListModal:false,
      send_cnt: [{ "*": "全て" }, { "!0": "未送信あり" }, { 0: "送信済" }],
      decission_cnt: [{ "*": "全て" }, { "!0": "未確定あり" }, { 0: "確定済" }],
      partner_codes:[],
      file: "",
      selected_byr: "0",
      invoiceData:{
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        // mes_lis_inv_pay_id:'',
        mes_lis_inv_pay_code:'',
        mes_lis_inv_per_begin_date:'',
        mes_lis_inv_per_end_date:'',
        sta_sen_identifier:'',
        sta_rec_identifier:'',
        mes_lis_pay_code:'',
        mes_lis_pay_gln:'',
        mes_lis_buy_code:'',
        mes_lis_buy_gln:'',
        mes_lis_inv_pay_gln:'',
      },
      send_datetime_status: ["未請求", "請求済","再請求あり"],
      form: new Form({
        select_field_per_page_num:10,
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
  beforeCreate: function() {
            if (!this.$session.exists()) {
                this.$router.push('/home');
            }
        },
  methods: {
    onRowClicked (item) {
        this.form.mes_lis_inv_pay_code = item.mes_lis_inv_pay_code;
       this.showAllCustomerCodeListModal = false;
    },
    //get Table data
    showAllCustomerCode(){
    let loadersCusCode = Vue.$loading.show();
      this.showAllCustomerCodeListModal = true;
      axios.post(this.BASE_URL + "api/get_invoice_customer_code_list", this.form)
        .then(({ data }) => {
          this.order_customer_code_lists = data.order_customer_code_lists;
          loadersCusCode.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loadersCusCode.hide();
        // });
    },
    viewInvoicePopup(){
      this.invoiceCreateModal = true;
      this.invoiceData.mes_lis_inv_pay_code=this.partner_codes[0].partner_code;
      this.invoiceData.sta_rec_identifier = this.getbyrjsonValueBykeyName("invoice_pay_info","sta_rec_identifier","invoices");
      this.invoiceData.mes_lis_pay_code = this.getbyrjsonValueBykeyName("invoice_pay_info","mes_lis_pay_code","invoices");
    this.invoiceData.mes_lis_pay_gln = this.getbyrjsonValueBykeyName("invoice_pay_info","mes_lis_pay_gln","invoices");
    this.invoiceData.mes_lis_buy_code = this.getbyrjsonValueBykeyName("invoice_pay_info","mes_lis_buy_code","invoices");
    this.invoiceData.mes_lis_buy_gln = this.getbyrjsonValueBykeyName("invoice_pay_info","mes_lis_buy_gln","invoices");
    this.invoiceData.mes_lis_inv_pay_gln = this.getbyrjsonValueBykeyName("invoice_pay_info","mes_lis_inv_pay_gln","invoices");
    },

    //get Table data
    get_all_invoice_list(page = 1) {
        this.form.page=page;
         let loader = Vue.$loading.show();
      axios.post(this.BASE_URL + "api/get_all_invoice_list",this.form)
        .then(({ data }) => {
          this.invoice_lists = data.invoice_list;
          this.invoice_lists_length=this.invoice_lists.data.length;

          this.byr_buyer_lists = data.byr_buyer_list;
          this.partner_codes = data.partner_codes;
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
          this.get_all_invoice_list(this.form.page);

      },
      invoice_download(downloadType = 1) {
      //downloadcsvshipment_confirm
      this.form.downloadType=downloadType
      axios.post(this.BASE_URL + "api/download_invoice", this.form)
        .then(({ data }) => {
           this.downloadFromUrl(data);
           this.get_all_invoice_list(this.form.page);
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loader.hide();
        // });
    },
      runInvoiceSchedular() {
            var _this = this;
            this.alert_icon = "warning";
            this.alert_title = "";
            this.alert_text = "締め処理を実行しますが、よろしいでしょうか？";
            this.yes_btn = "はい";
            this.cancel_btn = "キャンセル";
            this.confirm_sweet().then((result) => {
                if (result.value) {
                    axios.post(this.BASE_URL + "api/exec_invoice_schedular", this.form)
                    .then(({ data }) => {
                        // console.log(data);
                        if (data.status==1) {
                            Fire.$emit("LoadByrinvoice");
                            _this.alert_title = "完了";
                            if (data.data.total_success_data==0) {
                                _this.alert_text ='請求対象データがありません';
                            }else{
                                _this.alert_text =data.data.total_success_data+' 伝票を締め処理しました';
                            }
                        }else{
                            _this.alert_text =data.message;
                            _this.alert_title = "Error";
                        }
                        _this.alert_icon = data.data.class;
                        _this.sweet_normal_alert();
                    })
                    // .catch(() => {
                    //     this.sweet_advance_alert();
                    //     // loader.hide();
                    // });
                }
            })
    },
    checkForm: function (e) {
      this.errors = [];
        if(this.invoiceData.sta_sen_identifier==''){this.errors.push("取引先コード フィールドは必須項目です")}
        if(this.invoiceData.mes_lis_inv_pay_code==''){this.errors.push("取引先コード フィールドは必須項目です")}
        if(this.invoiceData.mes_lis_inv_per_begin_date==''){this.errors.push("開始日 フィールドは必須項目です")}
        if(!this.invoiceData.mes_lis_inv_per_end_date){this.errors.push("終了日 フィールドは必須項目です")}

      if (!this.errors.length) {
        return true;
      }
      return false;
    },
    insertInvoice() {
      var _this = this;
      this.invoiceData.sta_sen_identifier=this.invoiceData.mes_lis_inv_pay_code+'00';
      if(this.checkForm()){
      axios
        .post(this.BASE_URL + "api/invoiceInsert",this.invoiceData)
        .then(({data}) => {
         Fire.$emit("LoadByrinvoice");
         _this.alert_icon = "success";
        _this.alert_title = "";
        _this.alert_text =
          "請求データを追加しました。";
        _this.sweet_normal_alert();
        _this.invoiceCreateModal = false;
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loader.hide();
        // });
      }
    },
    goToDetailsPage(page_name,invoice_list){
        this.$store.commit('invoiceDetailsModule/reset')
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

    change(e) {
      const selectedCode = e.target.value;
      const option = this.options.find((option) => {
        return selectedCode === option.byr_buyer_id;
      });
      //   this.$emit("input", option);
    },
  },

  created() {
      this.form = this.$store.getters['invoiceListModule/getFormData'];
    Fire.$emit("byr_has_selected", this.$session.get("byr_buyer_id"));
    Fire.$emit("permission_check_for_buyer", this.$session.get("byr_buyer_id"));
    this.invoiceData.byr_buyer_id = this.$session.get("byr_buyer_id");
    this.form.byr_buyer_id = this.$session.get("byr_buyer_id");
    this.getbuyerJsonSettingvalue();
    this.get_all_invoice_list(this.form.page);
    Fire.$on("LoadByrinvoice", (page=this.form.page) => {
      this.get_all_invoice_list(page);
    });
    Fire.$emit("loadPageTitle", "請求データ一覧");

  },
  mounted() {
  },
   computed: {
  },
};
</script>
