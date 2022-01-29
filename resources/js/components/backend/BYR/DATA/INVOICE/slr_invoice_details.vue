<template>
<div class="row">
    <div class="col-12">
        <div class="col-12" style="background: #d8e3f0; padding: 10px; margin-bottom: 20px">
            <table class="table orderDetailTable cmnWidthTable table-bordered" style="width: 100%">
                <tr>
                    <td class="cl_custom_color">締日</td>
                    <td>{{ param_data.end_date }}</td>
                    <td class="cl_custom_color">請求取引先</td>
                    <td>
                        {{ param_data.pay_code }}
                        {{ param_data.pay_name }}
                    </td>
                </tr>
                <tr>
                    <td class="cl_custom_color">発注者</td>
                    <td>
                        {{ param_data.buy_code }}
                        {{ param_data.buy_name }}
                    </td>
                    <!--<td class="cl_custom_color">請求状況</td>
            <td></td>-->
                    <td class="cl_custom_color">請求金額</td>
                    <td class="text-right">
                        {{ param_data.requested_amount | priceFormat }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="col-12">
        <div class="col-12" style="background: #d8e3f0; padding: 10px">
            <table class="table orderDetailTable cmnWidthTable table-bordered" style="width: 100%">
                <tr>
                    <td class="cl_custom_color">計上日</td>
                    <td>
                        <div class="input-group">

                            <input type="date" class="form-control" v-model="form.from_date">
                            <div class="input-group-prepend">
                                <span class="input-group-text">~</span>
                            </div>
                            <input type="date" class="form-control" v-model="form.to_date">
                        </div>
                    </td>

                    <td class="cl_custom_color">部門</td>
                    <td>
                        <multiselect v-model="form.category_code" :options="byr_buyer_category_lists" label="category_name" track-by="category_code" :searchable="true" :close-on-select="true" :clear-on-select="true" :select-label="''" :deselect-label="''" :selected-label="'選択中'" :preserve-search="true" placeholder="部門"><span slot="noOptions">候補がありません</span> <span slot="noResult">候補がありません</span></multiselect>
                    </td>

                    <td class="cl_custom_color">納品先</td>
                    <td>
                        <input type="text" class="form-control topHeaderInputFieldBtn" v-model="form.mes_lis_inv_lin_tra_code" />
                        <button class="btn btn-primary active" @click="deliverySearchForm2" style="float:left;">
                            参照
                        </button>
                    </td>
                </tr>
                <tr>
                    <td class="cl_custom_color">伝票番号</td>
                    <td>
                        <input type="text" v-model="form.mes_lis_inv_lin_lin_trade_number_reference" class="form-control" />
                    </td>
                </tr>
                <tr>
                    <td class="cl_custom_color">確定状況</td>
                    <td>
                        <select class="form-control" v-model="form.decision_datetime_status">
                            <option value="*">全て</option>
                            <option :value="item" v-for="(item,i) in decision_datetime_status" :key="i">
                                {{ item }}
                            </option>
                        </select>
                    </td>
                    <td class="cl_custom_color">送信状況</td>
                    <td>
                        <select class="form-control" v-model="form.send_datetime_status">
                            <option value="*">全て</option>
                            <option :value="item" v-for="(item,i) in send_datetime_status" :key="i">
                                {{ item }}
                            </option>
                        </select>
                    </td>
                    <td class="cl_custom_color">支払状況</td>
                    <td>
                        <select class="form-control" v-model="form.payment_datetime_status">
                            <option value="*">全て</option>
                            <option :value="item" v-for="(item,i) in payment_datetime_status" :key="i">
                                {{ item }}
                            </option>
                        </select>
                    </td>
                </tr>

            </table>
        </div>
    </div>
    <div class="col-12" style="text-align: center">
        <button class="btn btn-primary active srchBtn" type="button" @click="invoice_details">
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
                    <span class="tableRowsInfo">{{ invoice_detail_lists.from }}〜{{
                invoice_detail_lists.to
              }}
                        件表示中／全：{{ invoice_detail_lists.total }}件</span>
                    <span class="pagi">
                        <advanced-laravel-vue-paginate :data="invoice_detail_lists" :onEachSide="2" previousText="<" nextText=">" alignment="center" @paginateTo="invoice_details" />
                    </span>
                    <span class="selectPagi">
                        <select class="form-control selectPage" @change="invoice_details" v-model="form.per_page">
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
            </div>
            <div class="col-7">
                <div class="row">
                    <div class="col-4" style="padding-left: 0 !important">
                        <p class="mb-0">検索結果のダウンロードはこちら</p>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary active dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <b-icon icon="download" animation="fade" font-scale="1.2"></b-icon>
                                ダウンロード
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button class="dropdown-item" @click="invoice_download(1)" type="button" :disabled="is_disabled(invoice_detail_length >= 1 ? true : false)">
                                    CSV
                                </button>
                                <!-- <button class="dropdown-item" @click="order_download(2)" type="button"> JCA </button> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-3"></div>
                    <div class="col-5"></div>
                </div>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-12">
                <table class="table table-striped table-bordered order_item_details_table" style="overflow-x: scroll">
                    <thead>
                        <tr class="first_heading_th">
                            <th class="text-right" colspan="100%">
                                <button @click="invoiceCompareData" class="btn btn-primary" style="float: right; margin-right: 10px">
                                    出荷受領比較
                                </button>
                            </th>
                        </tr>
                        <tr>
                            <th>No</th>
                            <th>確定</th>
                            <th class="pointer_class" @click="
                    sorting('mes_lis_inv_lin_det_transfer_of_ownership_date')
                  ">
                                計上日
                                <span class="float-right" :class="
                      iconSet('mes_lis_inv_lin_det_transfer_of_ownership_date')
                    "></span>
                            </th>
                            <th class="pointer_class" @click="sorting('mes_lis_inv_lin_det_goo_major_category')">
                                部門コード
                                <span class="float-right" :class="iconSet('mes_lis_inv_lin_det_goo_major_category')"></span>
                            </th>
                            <th class="pointer_class" @click="sorting('mes_lis_inv_lin_tra_code')">
                                納品先
                                <span class="float-right" :class="iconSet('mes_lis_inv_lin_tra_code')"></span>
                            </th>
                            <th class="pointer_class" @click="sorting('mes_lis_inv_lin_lin_trade_number_reference')">
                                伝票番号
                                <span class="float-right" :class="
                      iconSet('mes_lis_inv_lin_lin_trade_number_reference')
                    "></span>
                            </th>
                            <th class="pointer_class" @click="sorting('mes_lis_inv_lin_det_pay_code')">
                                請求内容
                                <span class="float-right" :class="iconSet('mes_lis_inv_lin_det_pay_code')"></span>
                            </th>
                            <th class="pointer_class" @click="sorting('mes_lis_inv_lin_det_balance_carried_code')">
                                請求区分
                                <span class="float-right" :class="iconSet('mes_lis_inv_lin_det_balance_carried_code')"></span>
                            </th>
                            <th class="pointer_class" @click="sorting('mes_lis_inv_lin_det_amo_requested_amount')">
                                請求金額
                                <span class="float-right" :class="iconSet('mes_lis_inv_lin_det_amo_requested_amount')"></span>
                            </th>
                            <th class="pointer_class" @click="sorting('send_datetime')">
                                送信日時
                                <span class="float-right" :class="iconSet('send_datetime')"></span>
                            </th>
                            <th class="pointer_class" @click="sorting('deleted_at')">削除日時 <span class="float-right" :class="iconSet('deleted_at')"></span></th>
                            <th v-role="['Super Admin']">削除実行</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(value, index) in invoice_detail_lists.data" :key="index">
                            <td>
                                <!-- {{ index + 1 }} -->
                                {{
                    invoice_detail_lists.current_page * form.per_page -
                    form.per_page +
                    index +
                    1
                  }}
                            </td>
                            <td>
                                <span v-if="value.decision_datetime != null">
                                    <b-button pill variant="info" :disabled="is_disabled(!value.send_datetime)">済</b-button>
                                </span>
                                <span v-else>
                                    <input type="checkbox" v-bind:value="value.data_invoice_pay_detail_id" />
                                </span>
                            </td>
                            <td>
                                {{ value.mes_lis_inv_lin_det_transfer_of_ownership_date }}
                            </td>
                            <td>
                                {{ value.mes_lis_inv_lin_det_goo_major_category }}
                            </td>
                            <td>
                                {{ value.mes_lis_inv_lin_tra_code }}
                                {{ value.mes_lis_inv_lin_tra_name_sbcs }}
                                <!-- {{ value.mes_lis_inv_lin_tra_name }} -->
                            </td>
                            <td>{{ value.mes_lis_inv_lin_lin_trade_number_reference }}</td>
                            <td>
                                {{ value.mes_lis_inv_lin_det_pay_code }}
                                {{
                    getbyrjsonValueBykeyName(
                      "mes_lis_inv_lin_det_pay_code",
                      value.mes_lis_inv_lin_det_pay_code,
                      "invoices"
                    )
                  }}
                            </td>
                            <td>
                                {{
                    getbyrjsonValueBykeyName(
                      "mes_lis_inv_lin_det_balance_carried_code",
                      value.mes_lis_inv_lin_det_balance_carried_code,
                      "invoices"
                    )
                  }}
                            </td>
                            <td class="text-right">
                                {{
                    value.mes_lis_inv_lin_det_amo_requested_amount | priceFormat
                  }}
                            </td>
                            <td>{{ value.send_datetime }}</td>
                            <td>{{ value.deleted_at | momentDateTimeFormat }}</td>
                            <span v-role="['Super Admin']">
                                <td v-if="value.deleted_at==null">
                                    <b-button pill variant="danger" @click="deleteOrRetrive(value,'d')">削除</b-button>
                                </td>
                                <td v-else>
                                    <b-button pill variant="info" @click="deleteOrRetrive(value,'r')">削除取消</b-button>
                                </td>
                            </span>
                        </tr>
                        <tr v-if="invoice_detail_lists.data && invoice_detail_lists.data.length == 0">
                            <td class="text-center" colspan="100%">データがありません</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <b-modal size="xl" :hide-backdrop="true" title="出荷・受領比較" cancel-title="閉じる" v-model="invoiceCompareModal" :hide-footer="true" :draggable="true">
        <div class="panel-body">
            <div class="row">
                <div class="col-6">
                    <p style="margin: 0">
                        出荷データと受領データで差異が発生している伝票のみ表示されています。
                    </p>
                    <p style="margin: 0">
                        [確認]ボタンを押すと、伝票明細が確認できます。
                    </p>
                    <p style="margin: 0">黄色の項目は差異が発生している項目です。</p>
                </div>
                <div class="col-6">
                    <h6>ダウンロードを押すと、比較データがダウンロードされます</h6>
                    <button class="btn btn-outline-primary" style="float: right; margin-bottom: 15px" type="button" :disabled="is_disabled(compareDataList.length > 0 ? true : false)" @click="compare_data_download(1)">
                        <b-icon icon="download" animation="fade" font-scale="1.2"></b-icon>
                        {{ myLang.download }}
                    </button>
                </div>
            </div>
            <table class="table table-striped table-bordered order_item_details_table" style="overflow-x: scroll">
                <thead>
                    <tr>
                        <th>取引先コード</th>
                        <th>伝票番号</th>
                        <th>直接納品先</th>
                        <th>出荷計上日</th>
                        <th>受領計上日</th>
                        <th>出荷原価金額合計</th>
                        <th>受領原価金額合計</th>
                        <th>明細比較</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(value, index) in compareDataList" :key="index">
                        <td>{{ value.mes_lis_shi_par_sel_code }}</td>
                        <td>{{ value.mes_lis_shi_tra_trade_number }}</td>
                        <td>
                            {{ value.mes_lis_shi_par_shi_code }}
                            {{ value.mes_lis_shi_par_shi_name }}
                        </td>
                        <td :class="
                  sameCheck(
                    value.shipment_delivery_date,
                    value.mes_lis_acc_tra_dat_transfer_of_ownership_date
                  )
                ">
                            {{ value.shipment_delivery_date }}
                        </td>
                        <td :class="
                  sameCheck(
                    value.shipment_delivery_date,
                    value.mes_lis_acc_tra_dat_transfer_of_ownership_date
                  )
                ">
                            {{ value.mes_lis_acc_tra_dat_transfer_of_ownership_date }}
                        </td>
                        <td class="text-right" :class="
                  sameCheck(
                    value.mes_lis_shi_tot_tot_net_price_total,
                    value.mes_lis_acc_tot_tot_net_price_total
                  )
                ">
                            {{
                  zeroShow(value.mes_lis_shi_tot_tot_net_price_total)
                    | priceFormat
                }}
                        </td>
                        <td class="text-right" :class="
                  sameCheck(
                    value.mes_lis_shi_tot_tot_net_price_total,
                    value.mes_lis_acc_tot_tot_net_price_total
                  )
                ">
                            {{
                  zeroShow(value.mes_lis_acc_tot_tot_net_price_total)
                    | priceFormat
                }}
                        </td>
                        <td>
                            <button @click="comparedItemList(value)" class="btn btn-primary">
                                確認
                            </button>
                        </td>
                    </tr>
                    <tr v-if="compareDataList && compareDataList.length == 0">
                        <td class="text-center" colspan="100%">データがありません</td>
                    </tr>
                </tbody>
            </table>
            <div class="col-12 text-center">
                <button class="btn btn-primary" style="text-align: center" @click="closeInvoiceCompare">
                    閉じる
                </button>
            </div>
        </div>
    </b-modal>

    <b-modal size="xl" :hide-backdrop="true" title="出荷・受領比較（明細）" cancel-title="閉じる" v-model="invoiceitemDatalistModal" :hide-footer="true" :draggable="true" :no-enforce-focus="true">
        <div class="panel-body">
            <div class="row">
                <div class="col-12">
                    <p style="margin: 0">
                        差異が発生している伝票の明細が表示されています。
                    </p>
                    <p style="margin: 0">黄色の項目は差異が発生している項目です。</p>
                </div>
            </div>
            <table class="table table-striped table-bordered order_item_details_table" style="overflow-x: scroll">
                <thead>
                    <tr>
                        <th>行番号</th>
                        <th>商品コード</th>
                        <th>商品名</th>
                        <th class="text-center">出荷数量（バラ）</th>
                        <th class="text-center">受領数量（バラ）</th>
                        <th class="text-center">出荷原価金額</th>
                        <th class="text-center">受領原価金額</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(value, index) in compare_item_list" :key="index">
                        <td>{{ value.mes_lis_shi_lin_lin_line_number }}</td>
                        <td>{{ value.mes_lis_shi_lin_ite_order_item_code }}</td>
                        <td>{{ value.mes_lis_shi_lin_ite_name }}</td>
                        <td class="text-right" :class="
                  sameCheck(
                    value.mes_lis_shi_lin_qua_shi_quantity,
                    value.mes_lis_acc_lin_qua_rec_quantity
                  )
                ">
                            {{ value.mes_lis_shi_lin_qua_shi_quantity }}
                        </td>
                        <td class="text-right" :class="
                  sameCheck(
                    value.mes_lis_shi_lin_qua_shi_quantity,
                    value.mes_lis_acc_lin_qua_rec_quantity
                  )
                ">
                            {{ value.mes_lis_acc_lin_qua_rec_quantity }}
                        </td>
                        <td class="text-right" :class="
                  sameCheck(
                    value.mes_lis_shi_lin_amo_item_net_price,
                    value.mes_lis_acc_lin_amo_item_net_price
                  )
                ">
                            {{
                  zeroShow(value.mes_lis_shi_lin_amo_item_net_price)
                    | priceFormat
                }}
                        </td>
                        <td class="text-right" :class="
                  sameCheck(
                    value.mes_lis_shi_lin_amo_item_net_price,
                    value.mes_lis_acc_lin_amo_item_net_price
                  )
                ">
                            {{
                  zeroShow(value.mes_lis_acc_lin_amo_item_net_price)
                    | priceFormat
                }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="col-12 text-center">
                <button class="btn btn-primary" style="text-align: center" @click="closeComparedItemList">
                    閉じる
                </button>
            </div>
        </div>
    </b-modal>
    <b-modal size="lg" :hide-backdrop="true" title="納品先検索" ok-title="検　索" cancel-title="閉じる" @ok.prevent="update_order_voucher_detail()" v-model="order_search_modal2" :no-enforce-focus="true">
        <div class="panel-body">
            <table class="
            table
            orderTopDetailTable
            table-striped
            popupListTable
            table-bordered
          " style="width: 100%">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>納品先コード</th>
                        <th>納品先名</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(valueItm, index) in order_search_modal2List" :key="index" @click="setRowscodeIntoForm2(valueItm.mes_lis_inv_lin_tra_code)">
                        <td>{{ index + 1 }}</td>
                        <td>{{ valueItm.mes_lis_inv_lin_tra_code }}</td>
                        <td>
                            <span v-if="valueItm.mes_lis_inv_lin_tra_name != ''">{{
                  valueItm.mes_lis_inv_lin_tra_name
                }}</span><span v-else>{{
                  valueItm.mes_lis_inv_lin_tra_name_sbcs
                }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </b-modal>
</div>
</template>

<script>
export default {
    data() {
        return {
            param_data: [],
            invoice_detail_lists: {},
            invoice_detail_length: 0,
            invoiceCompareModal: false,
            invoiceitemDatalistModal: false,
            order_search_modal2: false,
            order_search_modal2List: [],
            compareDataList: [],
            compare_item_list: [],
            decision_datetime_status: ["未確定あり", "確定済"],
            send_datetime_status: ["未送信あり", "送信済"],
            payment_datetime_status: ["支払い済み", "未払い"],
            form: new Form({
                data_invoice_id: null,
                per_page: 10,
                page: 1,
                adm_user_id: Globals.user_info_id,
                byr_buyer_id: null,
                param_data: [],
                from_date: "",
                to_date: "",
                mes_lis_inv_lin_tra_code: "",
                mes_lis_inv_lin_lin_trade_number_reference: "",
                decision_datetime_status: "*",
                category_code: {
                    category_code: "*",
                    category_name: "全て"
                },
                send_datetime_status: "*",
                payment_datetime_status: '*',
                sort_by: "data_invoice_pay_detail_id ",
                sort_type: "ASC",
                page_title: "invoice_details_list",
                shipment_ids: [],
            }),
        };
    },
    methods: {
        setRowscodeIntoForm2(valCode) {
            this.form.mes_lis_inv_lin_tra_code = valCode;
            this.order_search_modal2 = false;
        },
        deliverySearchForm2() {
            this.order_search_modal2 = true;
            this.$route.query.adm_user_id = Globals.user_info_id;
            this.$route.query.byr_buyer_id = this.form.byr_buyer_id;
            axios
                .post(
                    this.BASE_URL + "api/slr_get_voucher_detail_popup2_invoice",
                    this.$route.query
                )
                .then(({
                    data
                }) => {
                    this.order_search_modal2List = data.popUpList;
                })
                // .catch(() => {
                //     this.sweet_advance_alert();
                //     // loader.hide();
                // });
        },
        invoiceCompareData() {
            // console.log(this.form);
            this.invoiceCompareModal = true;
            axios
                .post(this.BASE_URL + "api/slr_invoice_compare_data", this.form)
                .then(({
                    data
                }) => {
                    this.compareDataList = data.voucherList;
                })
                // .catch(() => {
                //     this.sweet_advance_alert();
                //     // loader.hide();
                // });
        },
        compare_data_download() {
            axios
                .post(this.BASE_URL + "api/slr_invoice_compare_data_download", this.form)
                .then(({
                    data
                }) => {
                    this.downloadFromUrl(data);
                })
                // .catch(() => {
                //     this.sweet_advance_alert();
                //     // loader.hide();
                // });
        },
        comparedItemList(value) {
            this.invoiceitemDatalistModal = true;
            axios
                .post(this.BASE_URL + "api/slr_invoice_compare_item", value)
                .then(({
                    data
                }) => {
                    this.compare_item_list = data.compareItemList;
                })
                // .catch(() => {
                //     this.sweet_advance_alert();
                //     // loader.hide();
                // });
        },
        closeComparedItemList() {
            this.invoiceitemDatalistModal = false;
        },
        closeInvoiceCompare() {
            this.invoiceCompareModal = false;
        },

        //get Table data
        invoice_details(page = 1) {
            let loader = Vue.$loading.show();
            this.form.page = page;
            axios
                .post(this.BASE_URL + "api/slr_get_invoice_details_list", this.form)
                .then(({
                    data
                }) => {
                    this.invoice_detail_lists = data.invoice_details_list;
                    this.invoice_detail_length = this.invoice_detail_lists.data.length;
                    this.form.shipment_ids = data.shipment_ids;
                    loader.hide();
                })
                // .catch(() => {
                //     this.sweet_advance_alert();
                //     loader.hide();
                // });
        },
        sorting(sorted_field) {
            this.form.sort_by = sorted_field;
            this.form.sort_type = this.form.sort_type == "ASC" ? "DESC" : "ASC";
            this.invoice_details();
        },
        invoice_download(downloadType = 1) {
            //downloadcsvshipment_confirm
            this.form.downloadType = downloadType;
            axios
                .post(this.BASE_URL + "api/slr_download_invoice", this.form)
                .then(({
                    data
                }) => {
                    this.downloadFromUrl(data);
                })
                // .catch(() => {
                //     this.sweet_advance_alert();
                //     // loader.hide();
                // });
        },
        deleteOrRetrive(value, type) {
            // console.log(value)
            this.alert_icon = 'warning';
            this.alert_title = (type === 'd' ? '対象データを削除しますか？' : '対象データの削除を取り消しますか？');
            this.alert_text = (type === 'd' ? '削除取消より取り消しができます' : '');
            this.yes_btn = (type === 'd' ? '削除します' : '削除を取り消します')
            this.confirm_sweet().then((result) => {
                if (result.value) {
                    axios.post(this.BASE_URL + "api/invoice_details_delete_or_retrive", value).then(({
                        data
                    }) => {
                        // console.log(data)
                        if (data.status == 1) {
                            this.alert_icon = 'success';
                            this.alert_title = (type === 'd' ? '削除しました' : '削除取り消しました');
                            this.alert_text = '';
                            this.sweet_normal_alert()
                            this.invoice_details(this.form.page)
                        }
                    })
                    // .catch(() => {
                    //     this.sweet_advance_alert();
                    //     // loader.hide();
                    // });
                }
            })
        },
        sameCheck(value1, value2) {
            if (value1 != value2) {
                return "same_yellow";
            }
        },
    },

    created() {
        this.form = this.$store.getters['slrInvoiceDetailsModule/getFormData'];
        this.form.byr_buyer_id = this.$session.get("slr_byr_buyer_id");
        this.param_data = this.$route.query;
        this.form.param_data = this.param_data;
        this.form.data_invoice_id = this.param_data.data_invoice_id;
        this.getbuyerJsonSettingvalue();
        this.invoice_details();
        Fire.$on("LoadByrinvoiceDetails", (page = 1) => {
            this.invoice_details(page);
        });
        Fire.$emit("loadPageTitle", "請求伝票一覧");
    },
    computed: {},
    mounted() {},
};
</script>

<style>
.same_yellow {
    background: yellow;
}
</style>
