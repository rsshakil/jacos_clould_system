<template>
  <div>
    <div class="row">
      <div class="col-12">
      <div class="col-12" style="background: #d8e3f0; padding: 10px;margin-bottom: 20px;">
        <table
          class="table orderDetailTable table-bordered"
          style="width: 100%"
        >
          <tr>
            <td class="cl_custom_color" style="width:10%">受信日</td>
            <td style="width:15%"><span v-if="order_info && Object.keys(order_info).length"> {{ order_info.receive_datetime }}</span></td>
            <td class="cl_custom_color" style="width:10%">取引先</td>
            <td style="width:15%">
            <span v-if="order_info && Object.keys(order_info).length">
              {{ order_info.mes_lis_acc_par_sel_code}}
              {{ order_info.mes_lis_acc_par_sel_name }}
              </span>
            </td>
            <td class="cl_custom_color" style="width:10%">便</td>
            <td style="width:15%">
            <span v-if="order_info && Object.keys(order_info).length">

              {{
                getbyrjsonValueBykeyName(
                  "mes_lis_ord_log_del_delivery_service_code",
                  order_info.mes_lis_acc_log_del_delivery_service_code,
                  "orders",
                  buyer_settings
                )
              }}
              </span>
            </td>
          </tr>
          <tr>
            <td class="cl_custom_color" style="width:10%">計上日</td>
            <td style="width:15%"><span v-if="order_info && Object.keys(order_info).length">{{ order_info.mes_lis_acc_tra_dat_transfer_of_ownership_date }}</span></td>
            <td class="cl_custom_color" style="width:10%">部門</td>
            <td style="width:15%"><span v-if="order_info && Object.keys(order_info).length">{{ order_info.mes_lis_acc_tra_goo_major_category }}</span></td>

            <td class="cl_custom_color" style="width:10%">温度区分</td>
            <td style="width:15%">
            <span v-if="order_info && Object.keys(order_info).length">
              {{ order_info.mes_lis_acc_tra_ins_temperature_code }}
              {{
                getbyrjsonValueBykeyName(
                  "mes_lis_ord_tra_ins_temperature_code",
                  order_info.mes_lis_acc_tra_ins_temperature_code,
                  "orders",
                  buyer_settings
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
            <td class="cl_custom_color" style="width:10%">直接納品先コード</td>
            <td style="width:15%">
              <input type="text" v-model="form.searchCode1" class="form-control topHeaderInputFieldBtn" />
              <button
                @click="deliverySearchForm1"
                class="btn btn-primary active"
              >
                参照
              </button>
            </td>
            <td class="cl_custom_color" style="width:10%">最終納品先コード</td>
            <td style="width:15%">
              <input type="text" v-model="form.searchCode2" class="form-control topHeaderInputFieldBtn" />
              <button
                @click="deliverySearchForm2"
                class="btn btn-primary active"
              >
                参照
              </button>
            </td>
            <td class="cl_custom_color" style="width:10%">伝票番号</td>
            <td style="width:15%">
              <input
                type="text"
                v-model="form.trade_number"
                class="form-control"
              />
            </td>
          </tr>
          <tr>
          <td style="width:10%" class="cl_custom_color">商品コード</td>
            <td style="width:15%">
              <input type="text" v-model="form.searchCode3" class="form-control topHeaderInputFieldBtn" />
              <button
                @click="deliverySearchForm3"
                class="btn btn-primary active"
              >
                参照
              </button>
            </td>
            <td class="cl_custom_color" style="width:10%">定／特</td>
            <td style="width:15%">
              <select
                class="form-control"
                v-model="form.goods_classification_code"
              >
                <option value="*">全て</option>

                <option
                  v-for="(opt, i) in mes_lis_ord_tra_ins_goods_classification_codeList"
                  :key="i"
                  :value="i"
                >
                  {{ opt }}
                </option>
              </select>
            </td>
            <td class="cl_custom_color" style="width:10%">伝票区分</td>
            <td style="width:15%">
              <select
                class="form-control"
                v-model="form.voucher_class"
              >
                <option value="*">全て</option>

                <option v-for="(opt, i) in mes_lis_ord_tra_ins_trade_type_codeList" :key="i" :value="i">
                  {{ opt }}
                </option>
              </select>
            </td>

          </tr>
          <tr>
          <td class="cl_custom_color" style="width:10%">出荷比較</td>
            <td style="width:15%">
              <select
                class="form-control"
                v-model="form.confirm_status">
                <option value="*">全て</option>
                <option :value="item" v-for="(item,i) in confirmationOptionList" :key="i">
                  {{ item }}
                </option>
              </select>
            </td>
            <td class="cl_custom_color" style="width:10%">受領訂正</td>
            <td colspan="3" style="width:15%">
              <select
                class="form-control"
                v-model="form.decesion_status">
                <option value="*">全て</option>
                <option :value="item" v-for="(item,i) in receiveOptionList" :key="i">
                  {{ item }}
                </option>
              </select>
            </td>
          </tr>
        </table>
      </div>
      </div>

      <div class="col-12" style="text-align: center">
        <button
          @click="get_all_receive_detail()"
          class="btn btn-primary active srchBtn"
          type="button"
        >
          {{ myLang.search }}
        </button>
      </div>
      <div class="col-12">
        <br />
        <h4 class="page_custom_title">検索結果：一覧</h4>
      </div>

      <div class="col-12">
        <div class="row">
          <div class="col-5">
            <p>
              <span class="tableRowsInfo"
                >{{ receive_detail_lists.from }}〜{{
                  receive_detail_lists.to
                }}
                件表示中／全：{{ receive_detail_lists.total }}件</span
              >
              <span class="pagi">
                <advanced-laravel-vue-paginate
                  :data="receive_detail_lists"
                  :onEachSide="2"
                  previousText="<"
                  nextText=">"
                  alignment="center"
                  @paginateTo="get_all_receive_detail"
                />
              </span>
              <span class="selectPagi">
                <select
                  @change="selectNumPerPage"
                  v-model="form.select_field_per_page_num"
                  class="form-control selectPage"
                >
                  <!--<option value="0">表示行数</option>
                  <option v-for="n in receive_detail_lists.last_page" :key="n"
                :value="n">{{n}}</option>-->
                  <option value="10">10行</option>
                  <option value="20">20行</option>
                  <option value="50">50行</option>
                  <option value="100">100行</option>
                </select>
              </span>
            </p>
          </div>
          <div class="col-3 p-3" style="background-color:#d8e3f0; border-radius:1rem;">
            <div class="row">
              <div class="col-12">
                <p class="mb-0">検索結果のダウンロードはこちら</p>
                <div class="btn-group">
                  <button
                    type="button"
                    class="btn btn-primary active dropdown-toggle"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                    :disabled="is_disabled(receive_details_length>=1?true:false)"
                  >
                    <b-icon
                      icon="download"
                      animation="fade"
                      font-scale="1.2"
                    ></b-icon>
                    ダウンロード
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item" @click="receive_download(1)" type="button">
                      CSV
                    </button>
                    <!--<button class="dropdown-item" @click="receive_download(2)" type="button" :disabled="is_disabled(receive_details_length>=1?true:false)">
                      JCA
                    </button>-->
                  </div>
                </div>
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
            style="overflow-x: scroll"
          >
            <thead>

              <tr>
                <th>No</th>
                <th class="pointer_class" @click="sorting('mes_lis_acc_par_shi_code')">直接納品先 <span class="float-right" :class="iconSet('mes_lis_acc_par_shi_code')"></span></th>
                <th class="pointer_class" @click="sorting('mes_lis_acc_par_rec_code')">最終納品先 <span class="float-right" :class="iconSet('mes_lis_acc_par_rec_code')"></span></th>
                <th class="pointer_class" @click="sorting('mes_lis_acc_tra_trade_number')">伝票番号 <span class="float-right" :class="iconSet('mes_lis_acc_tra_trade_number')"></span></th>
                <th class="pointer_class" @click="sorting('mes_lis_acc_tra_ins_goods_classification_code')">定／特 <span class="float-right" :class="iconSet('mes_lis_acc_tra_ins_goods_classification_code')"></span></th>
                <th class="pointer_class" @click="sorting('mes_lis_acc_tra_ins_trade_type_code')">伝票区分 <span class="float-right" :class="iconSet('mes_lis_acc_tra_ins_trade_type_code')"></span></th>
                <th class="pointer_class" @click="sorting('mes_lis_acc_tot_tot_net_price_total')">原価金額合計 <span class="float-right" :class="iconSet('mes_lis_acc_tot_tot_net_price_total')"></span></th>
                <th class="pointer_class" >出荷比較</th>
                <th class="pointer_class" >受領訂正日時</th>
                <!-- <th class="pointer_class" @click="sorting('mes_lis_acc_tot_tot_net_price_total')">受領内容 <span class="float-right" :class="iconSet('mes_lis_acc_tot_tot_net_price_total')"></span></th> -->
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(order_detail_list, index) in receive_detail_lists.data"
                :key="index"
              >
                <td>
                  {{
                    receive_detail_lists.current_page *
                      form.select_field_per_page_num -
                    form.select_field_per_page_num +
                    index +
                    1
                  }}
                </td>

                <td>
                {{ order_detail_list.mes_lis_acc_par_shi_code }}
                {{ order_detail_list.mes_lis_acc_par_shi_name }}

                </td>
                <td>
                  {{ order_detail_list.mes_lis_acc_par_rec_code}}
                  {{ order_detail_list.mes_lis_acc_par_rec_name }}
                </td>
                <td>
                  <router-link
                    :to="{
                      name: 'receive_item_detail',
                      params: {
                        data_receive_voucher_id:
                          order_detail_list.data_receive_voucher_id,
                      },
                    }"
                    class=""
                    >{{
                      order_detail_list.mes_lis_acc_tra_trade_number
                    }}</router-link
                  >
                </td>
                <td>{{order_detail_list.mes_lis_acc_tra_ins_goods_classification_code}}
                 {{
                getbyrjsonValueBykeyName(
                  "mes_lis_ord_tra_ins_goods_classification_code",
                  order_detail_list.mes_lis_acc_tra_ins_goods_classification_code,
                  "orders",
                  buyer_settings
                )
              }}
                </td>
                <td>
                  {{order_detail_list.mes_lis_acc_tra_ins_trade_type_code}}
                  {{
                getbyrjsonValueBykeyName(
                  "mes_lis_acc_tra_ins_trade_type_code",
                  order_detail_list.mes_lis_acc_tra_ins_trade_type_code,
                  "receives",
                  buyer_settings
                )
              }}
                </td>
                <td class="text-right">
                  {{ order_detail_list.mes_lis_acc_tot_tot_net_price_total | priceFormat}}
                </td>
                <td>
                <span v-if="order_detail_list.mes_lis_acc_tot_tot_net_price_total==order_detail_list.mes_lis_shi_tot_tot_net_price_total"></span>
                <span v-else> 差分あり</span>
               <!-- 差分なし-->
                </td>

                <td>
                  {{order_detail_list.update_datetime}}
                </td>

              </tr>
              <tr v-if="receive_detail_lists.data && receive_detail_lists.data.length==0">
                <td class="text-center" colspan="9">データがありません</td>
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
      v-model="order_search_modal1"
      :no-enforce-focus="true"
    >
      <div class="panel-body">
        <table
          class="table orderTopDetailTable table-striped popupListTable table-bordered"
          style="width: 100%"
        >
        <thead>
                <tr>
                <th>NO</th>
                <th>納品先コード</th>
                <th>納品先名</th>
                <th>納品経路</th>
                </tr>
        </thead>
        <tbody>
                <tr v-for="(valueItm,index) in order_search_modal1List" :key="index" @click="setRowscodeIntoForm1(valueItm.mes_lis_acc_par_shi_code)">
                <td>{{index+1}}</td>
                <td>{{valueItm.mes_lis_acc_par_shi_code}}</td>
                <td>{{valueItm.mes_lis_acc_par_shi_name}}</td>
                <td>{{valueItm.mes_lis_acc_log_del_route_code}}
                {{
                        getbyrjsonValueBykeyName(
                        "mes_lis_ord_log_del_route_code",
                        valueItm.mes_lis_acc_log_del_route_code,
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
      v-model="order_search_modal2"
      :no-enforce-focus="true"
    >
      <div class="panel-body">
        <table
          class="table orderTopDetailTable table-striped popupListTable table-bordered"
          style="width: 100%"
        >
            <thead>
                <tr>
                    <th>NO</th>
                    <th>納品先コード</th>
                    <th>納品先名</th>
                    <th>納品経路</th>
                </tr>
            </thead>
            <tbody>
                    <tr v-for="(valueItm,index) in order_search_modal2List" :key="index" @click="setRowscodeIntoForm2(valueItm.mes_lis_acc_par_rec_code)">
                    <td>{{index+1}}</td>
                    <td>{{valueItm.mes_lis_acc_par_rec_code}}</td>
                    <td>{{valueItm.mes_lis_acc_par_rec_name}}</td>
                    <td>{{valueItm.mes_lis_acc_log_del_route_code}}
                    {{
                            getbyrjsonValueBykeyName(
                            "mes_lis_ord_log_del_route_code",
                            valueItm.mes_lis_acc_log_del_route_code,
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
      v-model="order_search_modal3"
      :no-enforce-focus="true"
    >
      <div class="panel-body">
        <table
          class="table orderTopDetailTable table-striped popupListTable table-bordered"
          style="width: 100%"
        >
            <thead>
                <tr>
                    <th>NO</th>
                    <th>商品コード</th>
                    <th>商品名</th>
                    <th>規格</th>
                </tr>
            </thead>
            <tbody>
                    <tr v-for="(valueItm,index) in order_search_modal3List" :key="index" @click="setRowscodeIntoForm3(valueItm.mes_lis_acc_lin_ite_order_item_code)">
                    <td>{{index+1}}</td>
                    <td>{{valueItm.mes_lis_acc_lin_ite_order_item_code}}</td>
                    <td>{{valueItm.mes_lis_acc_lin_ite_name}}</td>
                    <td>{{valueItm.mes_lis_acc_lin_ite_ite_spec}}

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
      rows: 100,
      currentPage: 1,
      today: new Date().toISOString().slice(0, 10),
      sortKey: "",
      reverse: true,
      order_by: "asc",
      receive_detail_lists: {},
      receive_details_length: 0,
      order_info: {},
      order_date: "",
      order_detail_list: [],
      show_hide_col_list: [],
      expected_delivery_date: "",
      status: "",
       buyer_settings:[],
        byr_buyer_lists: {},
      // byr_order_id: "",
      edit_order_modal: false,
      order_search_modal1: false,
      order_search_modal2: false,
      order_search_modal3: false,
      order_search_modal1List: [],
        order_search_modal2List: [],
        order_search_modal3List: [],
      selected: [],
      selectedNum: 0,
      isCheckAll: false,
      fixedSpecialOptionList: [
        { "01": "定番" },
        { "02": "準特価" },
        { "03": "特売" },
      ],
      situationOptionList: ["未確定あり", "確定済"],
      printingStatusOptionList: ["未印刷あり", "印刷済"],
      deliveryDestnationOptionList: ["店舗", "物流センター"],
      receiveOptionList: ["訂正あり", "訂正なし"],
      confirmationOptionList: ["差分あり", "差分なし"],
      mes_lis_ord_tra_ins_goods_classification_codeList: [],
      mes_lis_ord_tra_ins_trade_type_codeList: [],
      date_null: false,
      null_selected: [],
      not_null_selected: [],
      null_selected_message: false,
      form: new Form({
        page: 1,
        select_field_page_num: 1,
        select_field_per_page_num:10,
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,

        data_receive_id:null,
        sel_name:null,
        sel_code:null,
        major_category:null,
        delivery_service_code:null,
        ownership_date:null,
        // Search
        searchCode1:'',
        searchCode2:'',
        searchCode3:'',
        decesion_status:"*",
        confirm_status:"*",
        voucher_class:"*",
        goods_classification_code:"*",
        trade_number:null,
        mes_lis_acc_par_shi_code:'',
        mes_lis_acc_par_rec_code:'',
        order_info:{},
        sort_by:'data_receive_voucher_id ',
        sort_type:"ASC",
        page_title:'receive_details_list',
        downloadType:1
      }),
      param_data: [],
      // buyer_settings:null,
    };
  },
  methods: {

    setRowscodeIntoForm1(valCode){
        this.form.searchCode1 = valCode;
        this.order_search_modal1=false;
      },
      setRowscodeIntoForm2(valCode){
        this.form.searchCode2 = valCode;
        this.order_search_modal2 = false;
      },
      setRowscodeIntoForm3(valCode){
        this.form.searchCode3 = valCode;
        this.order_search_modal3 = false;
      },
    deliverySearchForm1() {
      let loaders333111 = Vue.$loading.show();
      this.order_search_modal1 = true;
      this.$route.query.adm_user_id = Globals.user_info_id;
      this.$route.query.byr_buyer_id = this.byr_buyer_id;
      axios.post(this.BASE_URL + "api/get_voucher_detail_popup1_receive", this.$route.query)
        .then(({ data }) => {
            this.order_search_modal1List = data.popUpList;
            loaders333111.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loaders333111.hide();
        // });
    },
    deliverySearchForm2() {
      let loaders3333222 = Vue.$loading.show();
      this.order_search_modal2 = true;
       this.$route.query.adm_user_id = Globals.user_info_id;
       this.$route.query.byr_buyer_id = this.byr_buyer_id;
      axios.post(this.BASE_URL + "api/get_voucher_detail_popup2_receive", this.$route.query)
        .then(({ data }) => {
            this.order_search_modal2List = data.popUpList;
            loaders3333222.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loaders3333222.hide();
        // });
    },
    deliverySearchForm3() {
      let loaders3333 = Vue.$loading.show();
      this.order_search_modal3 = true;
       this.$route.query.adm_user_id = Globals.user_info_id;
       this.$route.query.byr_buyer_id = this.byr_buyer_id;
      axios.post(this.BASE_URL + "api/get_voucher_detail_popup3_receive", this.$route.query)
        .then(({ data }) => {
            this.order_search_modal3List = data.popUpList;
            loaders3333.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loaders3333.hide();
        // });
    },

    selectNumPerPage() {

      if (this.form.select_field_per_page_num != 10) {
        Fire.$emit("LoadByrorderDetail",this.form.select_field_page_num);
      }
    },
    receive_download(downloadType = 1) {
      //downloadcsvshipment_confirm
      this.form.downloadType=downloadType;
      axios
        .post(this.BASE_URL + "api/receive_download", this.form)
        .then(({ data }) => {
          this.downloadFromUrl(data);
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loaders3333.hide();
        // });
    },

    get_all_receive_detail(page = 1) {
      let loader = Vue.$loading.show();
        this.form.page=page;
        this.form.select_field_page_num = page;
      axios
        .post(this.BASE_URL + "api/data_receive_detail_list", this.form)
        .then(({ data }) => {
          this.receive_detail_lists = data.received_detail_list;
          this.receive_details_length = this.receive_detail_lists.data.length;
            // if (this.receive_details_length<=0) {
            //   this.$router.push({name: 'receive_list'})
            // }
          this.byr_buyer_lists = data.byr_buyer_list;
          this.buyer_settings = JSON.parse(data.buyer_settings);
          this.mes_lis_ord_tra_ins_goods_classification_codeList = this.buyer_settings.orders.mes_lis_ord_tra_ins_goods_classification_code;
          this.mes_lis_ord_tra_ins_trade_type_codeList = this.buyer_settings.orders.mes_lis_ord_tra_ins_trade_type_code;
          this.order_info = data.order_info;
          this.form.order_info = this.order_info;
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
          this.get_all_receive_detail();

      },
  },

  created() {
    this.form.byr_buyer_id=this.$session.get("byr_buyer_id");
    this.$store.commit('receiveDetailListModule/formValuesStoreBYRID',this.$session.get("byr_buyer_id"));
    this.form = this.$store.getters['receiveDetailListModule/getFormData'];
    Fire.$emit("byr_has_selected", this.form.byr_buyer_id);
    Fire.$emit("permission_check_for_buyer", this.form.byr_buyer_id);
    this.getbuyerJsonSettingvalue();
    this.form.data_receive_id = this.$route.query.data_receive_id;
    this.form.sel_name = this.$route.query.sel_name;
    this.form.sel_code = this.$route.query.sel_code;
    this.form.major_category = this.$route.query.major_category;
    this.form.delivery_service_code = this.$route.query.delivery_service_code;
    this.form.ownership_date = this.$route.query.ownership_date;
    //this.loader = Vue.$loading.show();
    this.get_all_receive_detail(this.form.page);
    Fire.$on("LoadByrorderDetail", (page=this.form.page) => {
      this.get_all_receive_detail(page);
    });
    this.$session.set("receive_list_detail_query_param",this.$route.query);
    Fire.$emit("loadPageTitle", "受領伝票一覧");
  },
  mounted() {
  },
};
</script>
