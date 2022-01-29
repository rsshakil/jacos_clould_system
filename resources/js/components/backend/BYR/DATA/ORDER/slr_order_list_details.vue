<template>
  <div>
    <div class="row">
    <div class="col-12">
      <div class="col-12" style="background: #d8e3f0;padding: 10px;margin-bottom:20px;">
        <table
          class="table orderDetailTable table-bordered"
          style="width: 100%"
        >

          <tr>
            <td width="10%" class="cl_custom_color">受信日時</td>
            <td width="15%"><span v-if="order_info">{{ order_info.receive_datetime }}</span></td>
            <td width="10%" class="cl_custom_color">取引先</td>
            <td width="15%">
            <span v-if="order_info">
              {{ order_info.mes_lis_shi_par_sel_code }}
              {{ order_info.mes_lis_shi_par_sel_name }}
              </span>
            </td>
            <td width="10%" class="cl_custom_color">便</td>
            <td width="15%">
            <span v-if="order_info">
              {{
                getbyrjsonValueBykeyName(
                  "mes_lis_ord_log_del_delivery_service_code",
                  order_info.mes_lis_shi_log_del_delivery_service_code,
                  "orders"
                )
              }}
              </span>
            </td>
          </tr>
          <tr>
            <td width="10%" class="cl_custom_color">納品日</td>
            <td width="15%">
            <span v-if="order_info">
            {{ order_info.mes_lis_shi_tra_dat_delivery_date_to_receiver }}
            </span>
            </td>
            <td width="10%" class="cl_custom_color">部門</td>
            <td width="15%">
            <span v-if="order_info">
            {{ order_info.mes_lis_shi_tra_goo_major_category }}
            </span>
            </td>

            <td width="10%" class="cl_custom_color">温度区分</td>
            <td width="15%">
            <span v-if="order_info">
              {{ order_info.mes_lis_shi_tra_ins_temperature_code }}
              {{
                getbyrjsonValueBykeyName(
                  "mes_lis_ord_tra_ins_temperature_code",
                  order_info.mes_lis_shi_tra_ins_temperature_code,
                  "orders"
                )
              }}
              </span>
            </td>
          </tr>
        </table>
      </div>
      </div>
      <div class="col-12">
      <div class="col-12" style="background: #d8e3f0; padding: 10px">
        <table
          class="table orderDetailTable table-bordered"
          style="width: 100%"
        >
          <tr>
            <td style="width:10%" class="cl_custom_color">直接納品先コード</td>
            <td style="width:15%">
              <input type="text" v-model="form.par_shi_code" class="form-control topHeaderInputFieldBtn" />
              <button @click="parShiCodeList" class="btn btn-primary active">
                参照
              </button>
            </td>
            <td style="width:10%" class="cl_custom_color">最終納品先コード</td>
            <td style="width:15%">
              <input type="text" v-model="form.par_rec_code" class="form-control topHeaderInputFieldBtn" />
              <button @click="parRecCodeList" class="btn btn-primary active">
                参照
              </button>
            </td>
            <td style="width:10%" class="cl_custom_color">伝票番号</td>
            <td style="width:15%">
              <input type="text" v-model="form.mes_lis_shi_tra_trade_number" class="form-control" />
            </td>
          </tr>
          <tr>
            <td style="width:10%" class="cl_custom_color">商品コード</td>
            <td style="width:15%">
              <input type="text" v-model="form.order_item_code" class="form-control topHeaderInputFieldBtn" />
              <button @click="parItemCodeList" class="btn btn-primary active">
                参照
              </button>
            </td>
            <td style="width:10%" class="cl_custom_color">定／特</td>
            <td style="width:15%">
              <select class="form-control" v-model="form.fixedSpecial">
                <option value="*">全て</option>
                <option v-for="(opt, i) in fixedSpecialOptionList" :key="i" :value="Object.keys(opt)[0]">
                  {{ Object.values(opt)[0] }}
                </option>
              </select>
            </td>
          </tr>
          <tr>
            <td style="width:10%" class="cl_custom_color">印刷状況</td>
            <td style="width:15%">
              <select class="form-control" v-model="form.printingStatus">
                <option value="*">全て</option>
                <option :value="item" v-for="(item,i) in printingStatusOptionList" :key="i">
                  {{ item }}
                </option>
              </select>
            </td>
            <td style="width:10%" class="cl_custom_color">確定状況</td>
            <td style="width:15%">
              <select class="form-control" v-model="form.situation">
                <option value="*">全て</option>
                <option :value="item" v-for="(item,i) in situationOptionList" :key="i">
                  {{ item }}
                </option>
              </select>
            </td>
            <td style="width:10%" class="cl_custom_color">送信状況</td>
            <td style="width:15%">
              <select class="form-control" v-model="form.send_datetime">
                <option value="*">全て</option>
                <option :value="item" v-for="(item,i) in send_datetime_options" :key="i">
                  {{ item }}
                </option>
              </select>
            </td>
          </tr>
        </table>
      </div>
      </div>

      <div class="col-12" style="text-align: center">
        <button @click="get_all_byr_order_detail" class="btn btn-primary active srchBtn" type="button">
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
              <span class="tableRowsInfo"
                >{{ order_detail_lists.from }}〜{{
                  order_detail_lists.to
                }}
                件表示中／全：{{ order_detail_lists.total }}件</span
              >
              <span class="pagi">
                <advanced-laravel-vue-paginate
                  :data="order_detail_lists"
                  :onEachSide="2"
                  previousText="<"
                  nextText=">"
                  alignment="center"
                  @paginateTo="get_all_byr_order_detail"
                />
              </span>
              <span class="selectPagi">
                <select
                @change="get_all_byr_order_detail"
                  v-model="form.per_page"
                  class="form-control selectPage"
                >
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
                <!--<b-button
                  class="active"
                  variant="primary"
                  v-on:click="order_details_download"
                >
                  <b-icon
                    icon="download"
                    animation="fade"
                    font-scale="1.2"
                  ></b-icon>
                  ダウンロード</b-button>-->

                <div class="btn-group">
                  <button
                    type="button"
                    class="btn btn-primary active dropdown-toggle"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <b-icon
                      icon="download"
                      animation="fade"
                      font-scale="1.2"
                    ></b-icon>
                    ダウンロード
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item" @click="order_details_download(1)" type="button" :disabled="is_disabled(order_detail_list_length>=1?true:false)">
                      CSV
                    </button>
                    <button class="dropdown-item" @click="order_details_download(2)" type="button" :disabled="is_disabled(order_detail_list_length>=1?true:false)">
                      JCA
                    </button>
                  </div>
                </div>
              </div>
              <div class="col-3">
                <p class="mb-0">商品別の更新はこちら</p>
                <button @click="goToitemeSarchPage('slr_item_search',item_search_q)" class="active btn btn-primary">
                      商品別登録
                  </button>
                <!-- <router-link :to="{name: 'slr_item_search',query:item_search_q}" class="active btn btn-primary">
                  商品別登録
                </router-link> -->
              </div>
              <div class="col-5">
                <b-form inline>
                  <label class="sr-only" for="inline-form-input-name">各種帳票の印刷はこちら</label>
                  <select class="mb-2 mr-sm-2 mb-sm-0 form-control" v-model="form.pdf_download_type">
                    <!-- <option>選択してください</option> -->
                    <option value="order_pdf">発注明細書</option>
                  </select>
                  <b-button class="active" variant="primary" @click="pdfDownload()">印刷</b-button>
                </b-form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-12">
        <div class="">
          <table
            class="table table-striped table-bordered order_item_details_table"
            style="overflow-x: scroll">
            <thead>
              <tr>
                <th>No</th>
                <th>確定</th>
                <th class="pointer_class" @click="sorting('mes_lis_shi_par_shi_code')">直接納品先コード <span class="float-right" :class="iconSet('mes_lis_shi_par_shi_code')"></span></th>
                <th class="pointer_class" @click="sorting('mes_lis_shi_par_rec_code')">最終納品先 <span class="float-right" :class="iconSet('mes_lis_shi_par_rec_code')"></span></th>
                <th class="pointer_class" @click="sorting('mes_lis_shi_tra_trade_number')">伝票番号 <span class="float-right" :class="iconSet('mes_lis_shi_tra_trade_number')"></span></th>
                <th class="pointer_class" @click="sorting('mes_lis_shi_tra_ins_goods_classification_code')">定／特 <span class="float-right" :class="iconSet('mes_lis_shi_tra_ins_goods_classification_code')"></span></th>
                <th class="pointer_class" @click="sorting('mes_lis_shi_tot_tot_net_price_total')">原価金額 合計 <span class="float-right" :class="iconSet('mes_lis_shi_tot_tot_net_price_total')"></span></th>
                <th class="pointer_class" @click="sorting('status')">出荷状況 <span class="float-right" :class="iconSet('status')"></span></th>
                <th class="pointer_class" @click="sorting('updated_at')">最終更新日時 <span class="float-right" :class="iconSet('updated_at')"></span></th>
                <th class="pointer_class" @click="sorting('print_datetime')">納品明細書 印刷状況 <span class="float-right" :class="iconSet('print_datetime')"></span></th>
                <th class="pointer_class" @click="sorting('send_datetime')">送信日時 <span class="float-right" :class="iconSet('send_datetime')"></span></th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(order_detail_list, index) in order_detail_lists.data"
                :key="index"
              >
                <td>
                  {{
                    order_detail_lists.current_page *
                      form.per_page -
                    form.per_page +
                    index +
                    1
                  }}
                </td>
                <td>
                  <span v-if="order_detail_list.decision_datetime != null">
                    <b-button pill variant="info" :disabled="is_disabled(!order_detail_list.send_datetime)">
                      済
                    </b-button
                    >
                  </span>
                  <span v-else>
                    <input
                      type="checkbox"
                      v-bind:value="order_detail_list.data_shipment_voucher_id"
                    />
                  </span>
                </td>
                <td>{{ order_detail_list.mes_lis_shi_par_shi_code }}</td>
                <td>
                  {{ order_detail_list.mes_lis_shi_par_rec_code }}
                  {{ order_detail_list.mes_lis_shi_par_rec_name }}
                </td>
                <td>
                  <router-link
                    :to="{
                      name: 'slr_order_item_list_detail',
                      query: {
                        voucher_id:order_detail_list.data_shipment_voucher_id,
                      },
                    }"
                    class=""
                    >{{
                      order_detail_list.mes_lis_shi_tra_trade_number
                    }}</router-link
                  >
                </td>
                <td>
                  {{
                    order_detail_list.mes_lis_shi_tra_ins_goods_classification_code
                  }}
                  {{
                    getbyrjsonValueBykeyName(
                      "mes_lis_ord_tra_ins_goods_classification_code",
                      order_detail_list.mes_lis_shi_tra_ins_goods_classification_code,
                      "orders"
                    )
                  }}
                </td>
                <td class="text-right">
                  {{ order_detail_list.mes_lis_shi_tot_tot_net_price_total | priceFormat}}
                </td>
                <td>{{ order_detail_list.status }}</td>
                <td>{{ formatDate(order_detail_list.updated_at) }}</td>
                <td>{{ order_detail_list.print_datetime }}</td>
                <td>{{ order_detail_list.send_datetime }}</td>
              </tr>
               <tr v-if="order_detail_lists.data && order_detail_lists.data.length==0">
                <td class="text-center" colspan="11">データがありません</td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <b-modal
      size="lg"
      :hide-backdrop="true"
      title="発注データ修正"
      ok-title="修正"
      cancel-title="キャンセル"
      @ok.prevent="save_user()"
      v-model="edit_order_modal"
      :no-enforce-focus="true"
    >
      <div class="panel-body add_item_body">
        <form>
          <input
            type="hidden"
            name="vendor_item_id"
            id="vendor_item_id"
            value
          />
          <div class="row">
            <div class="col-6">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3"
                    >伝票番号</span
                  >
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="basic-url"
                  aria-describedby="basic-addon3"
                />
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">発注日</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="basic-url"
                  aria-describedby="basic-addon3"
                />
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">商品名</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="basic-url"
                  aria-describedby="basic-addon3"
                />
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">原価</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="basic-url"
                  aria-describedby="basic-addon3"
                />
              </div>
            </div>
            <div class="col-6">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">JAN</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="basic-url"
                  aria-describedby="basic-addon3"
                />
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">納品日</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="basic-url"
                  aria-describedby="basic-addon3"
                />
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">規格</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="basic-url"
                  aria-describedby="basic-addon3"
                />
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon3">売価</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  id="basic-url"
                  aria-describedby="basic-addon3"
                />
              </div>
            </div>
          </div>
        </form>
      </div>
    </b-modal>
    <b-modal
      size="lg"
      :hide-backdrop="true"
      title="納品先検索"
      ok-title="検　索"
      cancel-title="閉じる"
      @ok.prevent="update_order_voucher_detail()"
      v-model="shi_code_modal"
      :no-enforce-focus="true"
    >
      <div class="panel-body">
        <table class="table orderTopDetailTable table-striped popupListTable table-bordered" style="width: 100%">
        <thead>
            <tr>
                <th>NO</th>
                <th>納品先コード</th>
                <th>納品先名</th>
                <th>納品経路</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(valueItm,index) in shi_code_list" :key="index" @click="setShiCodeIntoForm(valueItm.mes_lis_shi_par_shi_code)">
                <td>{{index+1}}</td>
                <td>{{valueItm.mes_lis_shi_par_shi_code}}</td>
                <td>{{valueItm.mes_lis_shi_par_shi_name}}</td>
                <td>{{valueItm.mes_lis_shi_log_del_route_code}}
                {{
                        getbyrjsonValueBykeyName(
                        "mes_lis_ord_log_del_route_code",
                        valueItm.mes_lis_shi_log_del_route_code,
                        "orders"
                        )
                    }}
                </td>
            </tr>
        </tbody>
        </table>
      </div>
    </b-modal>
    <b-modal
      size="lg"
      :hide-backdrop="true"
      title="納品先検索"
      ok-title="検　索"
      cancel-title="閉じる"
      @ok.prevent="update_order_voucher_detail()"
      v-model="rec_code_modal"
      :no-enforce-focus="true"
    >
      <div class="panel-body">
        <table class="table orderTopDetailTable table-striped popupListTable table-bordered" style="width: 100%">
        <thead>
            <tr>
                <th>NO</th>
                <th>納品先コード</th>
                <th>納品先名</th>
                <th>納品経路</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(valueItm,index) in rec_code_list" :key="index" @click="setRecCodeIntoForm(valueItm.mes_lis_shi_par_rec_code)">
                <td>{{index+1}}</td>
                <td>{{valueItm.mes_lis_shi_par_rec_code}}</td>
                <td>{{valueItm.mes_lis_shi_par_rec_name}}</td>
                <td>{{valueItm.mes_lis_shi_log_del_route_code}}
                {{
                        getbyrjsonValueBykeyName(
                        "mes_lis_ord_log_del_route_code",
                        valueItm.mes_lis_shi_log_del_route_code,
                        "orders"
                        )
                    }}
                </td>
            </tr>
        </tbody>
        </table>
      </div>
    </b-modal>
    <b-modal
      size="lg"
      :hide-backdrop="true"
      title="商品コード"
      ok-title="検　索"
      cancel-title="閉じる"
      @ok.prevent="update_order_voucher_detail()"
      v-model="item_code_modal"
      :no-enforce-focus="true"
    >
      <div class="panel-body">
        <table class="table orderTopDetailTable table-striped popupListTable table-bordered" style="width: 100%">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>商品コード</th>
                    <th>商品名</th>
                    <th>規格</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(valueItm,index) in item_code_list" :key="index" @click="setItemCodeIntoForm(valueItm.mes_lis_shi_lin_ite_order_item_code)">
                    <td>{{index+1}}</td>
                    <td>{{valueItm.mes_lis_shi_lin_ite_order_item_code}}</td>
                    <td>{{valueItm.mes_lis_shi_lin_ite_name}}</td>
                    <td>{{valueItm.mes_lis_shi_lin_ite_ite_spec}}

                    </td>
                </tr>
            </tbody>
        </table>
      </div>
    </b-modal>
  </div>
</template>
<script>
import AdvancedLaravelVuePaginate from "advanced-laravel-vue-paginate";
import "advanced-laravel-vue-paginate/dist/advanced-laravel-vue-paginate.css";

export default {
  components: {
    AdvancedLaravelVuePaginate,
  },
  // props: ["param_data"],
  data() {
    return {
        order_detail_lists: {},
        order_info: {},
        order_detail_list_length:0,
        edit_order_modal: false,
        shi_code_modal: false,
        rec_code_modal: false,
        item_code_modal: false,
        shi_code_list: [],
        rec_code_list: [],
        item_code_list: [],
        selected: [],
        fixedSpecialOptionList: [
            { "01": "定番" },
            { "02": "準特価" },
            { "03": "特売" },
        ],
        situationOptionList: ["未確定あり", "確定済"],
        printingStatusOptionList: ["未印刷あり", "印刷済"],
        deliveryDestnationOptionList: ["店舗", "物流センター"],
        send_datetime_options: ["未送信あり", "送信済"],
        form: new Form({
            data_order_id:null,
            byr_buyer_id:null,
            adm_user_id:Globals.user_info_id,
            order_info:[],
            downloadType:1,
            printingStatus: "*",
            situation: "*",
            fixedSpecial: "*",
            deliveryDestnation: "",
            deliveryCode: "",
            deliveryDate: "",
            deliveryName: "",
            mes_lis_shi_tra_trade_number: null,
            send_datetime: '*',
            sort_by:'data_shipment_voucher_id',
            sort_type:"ASC",
            page_title:'slr_order_detail_list',
            adm_user_id: Globals.user_info_id,
            byr_buyer_id:null,
            par_shi_code:null,
            par_rec_code:null,
            order_item_code:null,
            page:1,
            per_page:10,
            pdf_download_type:'order_pdf',
            trash_status:'*'
        }),
        param_data: [],
        item_search_q: [],
      // buyer_settings:null,
    };
  },
  methods: {
      //get Table data
    get_all_byr_order_detail(page = 1) {
      let loader = Vue.$loading.show();
        this.form.page=page
        this.form.order_info=this.param_data
        // this.select_field_page_num = page;
        axios.post(this.BASE_URL + "api/slr_order_details", this.form)
            .then(({ data }) => {
            this.order_detail_lists = data.order_list_detail;
            this.order_info = data.order_info;
            this.form.order_info=this.order_info
            this.order_detail_list_length=this.order_detail_lists.data.length
            this.$session.set("order_info",this.order_info)
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
          this.get_all_byr_order_detail();

      },
      setShiCodeIntoForm(valCode){
        this.form.par_shi_code = valCode;
        this.shi_code_modal=false;
      },
      setRecCodeIntoForm(valCode){
        this.form.par_rec_code = valCode;
        this.rec_code_modal = false;
      },
      setItemCodeIntoForm(valCode){
        this.form.order_item_code = valCode;
        this.item_code_modal = false;
      },
    parShiCodeList() {
      this.shi_code_modal = true;
      axios.post(this.BASE_URL + "api/slr_get_voucher_shi_code_list", this.form)
        .then(({ data }) => {
            this.shi_code_list = data.shi_code_list;
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loader.hide();
        // });
    },
    parRecCodeList() {
      this.rec_code_modal = true;
      axios.post(this.BASE_URL + "api/slr_get_voucher_rec_code_list", this.form)
        .then(({ data }) => {
            this.rec_code_list = data.rec_code_list;
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loader.hide();
        // });
    },
    parItemCodeList() {
      this.item_code_modal = true;
      axios.post(this.BASE_URL + "api/slr_get_voucher_item_code_list", this.form)
        .then(({ data }) => {
            this.item_code_list = data.item_code_list;
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loader.hide();
        // });
    },

    exec_confirm_qty(order_detail, event) {
      if (
        parseFloat(order_detail.confirm_quantity) >
        parseFloat(order_detail.order_quantity)
      ) {
        Swal.fire({
          icon: "warning",
          title: "Invalid Confirm Quantity!",
          text: "You can not confrim order more then your order quantity!",
        });
        order_detail.confirm_quantity = order_detail.order_quantity;
      }
      if (event.key == "Enter") {
        event.preventDefault();

      }
    },
    sortBynumeric_valu(sortKey) {
      // this.order_detail_lists.sort((a, b) => a[sortKey] < b[sortKey] ? 1 : -1);
      if (this.order_by == "asc") {
        this.order_by = "desc";
        this.order_detail_lists.data.sort((a, b) => a[sortKey] - b[sortKey]);
      } else {
        this.order_by = "asc";
        this.order_detail_lists.data.sort((a, b) => b[sortKey] - a[sortKey]);
      }
    },
    sortByja_valu(sortKey) {
      if (this.order_by == "asc") {
        this.order_by = "desc";
        this.order_detail_lists.data.sort((a, b) =>
          a[sortKey].localeCompare(b[sortKey], "ja", {
            ignorePunctuation: true,
          })
        );
      } else {
        this.order_by = "asc";
        this.order_detail_lists.data.sort((a, b) =>
          b[sortKey].localeCompare(a[sortKey], "ja", {
            ignorePunctuation: true,
          })
        );
      }
    },
    // order data download
    order_details_download(downloadType = 1) {
      //downloadcsvshipment_confirm
      var _this = this;
     let loaderrrsss = Vue.$loading.show();
    this.form.order_info= this.order_info;
    this.form.downloadType=downloadType;
    //   axios.post(this.BASE_URL + "api/downloadcsvshipment_confirm", this.form)
      axios.post(this.BASE_URL + "api/slr_shipment_download", this.form)
        .then(({ data }) => {
            _this.downloadFromUrl(data);
           loaderrrsss.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loaderrrsss.hide();
        // });
    },
    pdfDownload(){
      //download pdf
      var _this = this;
     let loaderrrsss = Vue.$loading.show();
    this.form.order_info= this.order_info;
    // this.form.downloadType=downloadType;
      axios.post(this.BASE_URL + "api/slr_order_pdf_download", this.form)
        .then(({ data }) => {
            _this.downloadFromUrl(data);
           loaderrrsss.hide();
           this.get_all_byr_order_detail();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loaderrrsss.hide();
        // });
    },
    goToitemeSarchPage(page_name,item_search_q){
        this.$store.commit('slrItemSearchModule/reset')
        this.$router.push({name:page_name,query:item_search_q})
    }
  },

  created() {
      this.form = this.$store.getters['slrOrderDetailsModule/getFormData'];
    this.form.byr_buyer_id=this.$session.get("slr_byr_buyer_id")
    this.data_order_id=this.$route.query.data_order_id
    this.form.data_order_id=this.data_order_id
this.getbuyerJsonSettingvalue();
    this.param_data = this.$route.query;
    this.item_search_q = this.$route.query;
  this.$session.set("order_param_data",this.param_data)
    this.get_all_byr_order_detail();
    Fire.$on("LoadByrorderDetail", (page=1) => {
      this.get_all_byr_order_detail(page);
    });
    Fire.$emit("loadPageTitle", "受注伝票一覧");
  },
  mounted() {
  },
};
</script>
