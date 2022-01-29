<template>
  <div>
    <div class="row">
      <div class="col-12">
        <div
          class="col-12"
          style="background: #d8e3f0; padding: 10px; margin-bottom: 20px"
        >
          <table
            class="table orderDetailTable table-bordered"
            style="width: 100%"
          >
            <tr>
              <td class="cl_custom_color" style="width: 10%">受信日時</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_lists && Object.keys(order_item_lists).length
                  "
                  >{{ order_item_lists.receive_datetime }}</span
                >
              </td>
              <td class="cl_custom_color" style="width: 10%">取引先</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_lists && Object.keys(order_item_lists).length
                  "
                  >{{ order_item_lists.mes_lis_acc_par_sel_code }}
                  {{ order_item_lists.mes_lis_acc_par_sel_name }}</span
                >
              </td>
              <td class="cl_custom_color" style="width: 10%">便</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_lists && Object.keys(order_item_lists).length
                  "
                  >{{
                    getbyrjsonValueBykeyName(
                      "mes_lis_ord_log_del_delivery_service_code",
                      order_item_lists.mes_lis_acc_log_del_delivery_service_code,
                      "orders",
                      buyer_settings
                    )
                  }}</span
                >
              </td>
            </tr>
            <tr>
              <td class="cl_custom_color" style="width: 10%">納品日</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_lists && Object.keys(order_item_lists).length
                  "
                  >{{
                    order_item_lists.mes_lis_acc_tra_dat_transfer_of_ownership_date
                  }}</span
                >
              </td>
              <td class="cl_custom_color" style="width: 10%">部門</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_lists && Object.keys(order_item_lists).length
                  "
                  >{{
                    order_item_lists.mes_lis_acc_tra_goo_major_category
                  }}</span
                >
              </td>
              <td class="cl_custom_color" style="width: 10%">温度区分</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_lists && Object.keys(order_item_lists).length
                  "
                  >{{ order_item_lists.mes_lis_acc_tra_ins_temperature_code }}
                  {{
                    getbyrjsonValueBykeyName(
                      "mes_lis_ord_tra_ins_temperature_code",
                      order_item_lists.mes_lis_acc_tra_ins_temperature_code,
                      "orders",
                      buyer_settings
                    )
                  }}</span
                >
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div class="col-12">
        <div
          class="col-12"
          style="background: #d8e3f0; padding: 10px; margin-bottom: 20px"
        >
          <table
            class="table orderDetailTable table-bordered"
            style="width: 100%"
          >
            <tr>
            <td class="cl_custom_color" style="width: 10%">発注日</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_shipment_data_headTable &&
                    Object.keys(order_item_shipment_data_headTable).length
                  "
                  >{{
                    order_item_shipment_data_headTable.mes_lis_acc_tra_dat_order_date
                  }}</span
                >
              </td>
              <td class="cl_custom_color" style="width: 10%">直接納品先</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_shipment_data_headTable &&
                    Object.keys(order_item_shipment_data_headTable).length
                  "
                  >{{
                    order_item_shipment_data_headTable.mes_lis_acc_par_shi_code
                  }}
                  {{
                    order_item_shipment_data_headTable.mes_lis_acc_par_shi_name
                  }}</span
                >
              </td>
              <td class="cl_custom_color" style="width: 10%">最終納品先</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_shipment_data_headTable &&
                    Object.keys(order_item_shipment_data_headTable).length
                  "
                  >{{
                    order_item_shipment_data_headTable.mes_lis_acc_par_rec_code
                  }}
                  {{
                    order_item_shipment_data_headTable.mes_lis_acc_par_rec_name
                  }}</span
                >
              </td>

            </tr>
            <tr>
            <td class="cl_custom_color" style="width: 10%">伝票番号</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_shipment_data_headTable &&
                    Object.keys(order_item_shipment_data_headTable).length
                  "
                  >{{
                    order_item_shipment_data_headTable.mes_lis_acc_tra_trade_number
                  }}</span
                >
              </td>

              <td class="cl_custom_color" style="width: 10%">伝票区分</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_shipment_data_headTable &&
                    Object.keys(order_item_shipment_data_headTable).length
                  "
                  >{{
                    order_item_shipment_data_headTable.mes_lis_acc_tra_ins_trade_type_code
                  }}
                  {{
                    getbyrjsonValueBykeyName(
                      "mes_lis_ord_tra_ins_trade_type_code",
                      order_item_shipment_data_headTable.mes_lis_acc_tra_ins_trade_type_code,
                      "orders",
                      buyer_settings
                    )
                  }}</span
                >
              </td>
              <td class="cl_custom_color" style="width: 10%">定／特</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_shipment_data_headTable &&
                    Object.keys(order_item_shipment_data_headTable).length
                  "
                  >{{
                    order_item_shipment_data_headTable.mes_lis_acc_tra_ins_goods_classification_code
                  }}
                  {{
                    getbyrjsonValueBykeyName(
                      "mes_lis_ord_tra_ins_goods_classification_code",
                      order_item_shipment_data_headTable.mes_lis_acc_tra_ins_goods_classification_code,
                      "orders",
                      buyer_settings
                    )
                  }}</span
                >
              </td>

            <!--  <td class="cl_custom_color" style="width: 10%">発注者</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_shipment_data_headTable &&
                    Object.keys(order_item_shipment_data_headTable).length
                  "
                  >{{
                    order_item_shipment_data_headTable.mes_lis_buy_name
                  }}</span
                >
              </td>-->
            </tr>
            <tr>


<td class="cl_custom_color" style="width: 10%">不定貴区分</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_shipment_data_headTable &&
                    Object.keys(order_item_shipment_data_headTable).length
                  "
                  >{{
                    order_item_shipment_data_headTable.mes_lis_acc_tra_fre_原価金額able_measure_item_code
                  }}
                  {{
                    getbyrjsonValueBykeyName(
                      "mes_lis_ord_tra_fre_variable_measure_item_code",
                      order_item_shipment_data_headTable.mes_lis_acc_tra_fre_variable_measure_item_code,
                      "orders",
                      buyer_settings
                    )
                  }}</span
                >
              </td>

               <td class="cl_custom_color" style="width: 10%">税区分・税率</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_shipment_data_headTable &&
                    Object.keys(order_item_shipment_data_headTable).length
                  "
                  >{{
                    order_item_shipment_data_headTable.mes_lis_acc_tra_tax_tax_type_code
                  }}
                  {{
                    getbyrjsonValueBykeyName(
                      "mes_lis_ord_tra_tax_tax_type_code",
                      order_item_shipment_data_headTable.mes_lis_acc_tra_tax_tax_type_code,
                      "orders",
                      buyer_settings
                    )
                  }}
                  {{
                    order_item_shipment_data_headTable.mes_lis_acc_tra_tax_tax_rate
                  }}
                  %</span
                >
              </td>
              <!--<td class="cl_custom_color" style="width: 10%">受領内容</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_shipment_data_headTable &&
                    Object.keys(order_item_shipment_data_headTable).length
                  "
                  >{{
                    (order_item_shipment_data_headTable.status = "訂正なし")
                  }}</span
                >
              </td>-->
              <td class="cl_custom_color" style="width: 10%">備考</td>
              <td colspan="5" style="width: 15%">
                <span
                  v-if="
                    order_item_shipment_data_headTable &&
                    Object.keys(order_item_shipment_data_headTable).length
                  "
                  >{{
                    order_item_shipment_data_headTable.mes_lis_acc_tra_not_text
                  }}</span
                >
              </td>
            </tr>

          </table>
        </div>
      </div>
 <div class="col-12">
        <div
          class="col-12"
          style="background: #d8e3f0; padding: 10px; margin-bottom: 20px"
        >
          <table
            class="table orderDetailTable table-bordered"
            style="width: 100%"
          >
<tr>

              <td class="cl_custom_color" style="width: 10%">実納品日</td>
              <td colspan="3" style="width: 15%">
                <span>{{
                  order_item_shipment_data_headTable.mes_lis_acc_tra_dat_revised_delivery_date_to_receiver
                }}</span>
              </td>
              <td class="cl_custom_color_extra" style="width: 10%">受領訂正日時</td>
              <td style="width: 15%">
                <span
                  v-if="
                    order_item_shipment_data_headTable &&
                    Object.keys(order_item_shipment_data_headTable).length
                  "
                  >{{
                    order_item_shipment_data_headTable.update_datetime
                  }}</span
                >
              </td>
            </tr>
          </table>
        </div>
      </div>

      <div class="col-12" style="text-align: center"></div>
      <div class="col-12">
        <span class="pagi" style="width: 100%">
          <ul class="list-inline">
            <li
              v-for="(item, index) in order_detail_item_list_paginates"
              :key="index"
              v-if="
                form.data_receive_voucher_id == item.data_receive_voucher_id
              "
            >
              <span v-if="index >= 1"
                ><button
                  class="btn btn-primary"
                  @click="
                    move_next_prev(
                      order_detail_item_list_paginates[index - 1]
                        .data_receive_voucher_id
                    )
                  "
                >
                  ＜前伝票
                </button></span
              >
              <span v-if="index < order_detail_item_list_paginates.length - 1"
                ><button
                  class="btn btn-primary"
                  @click="
                    move_next_prev(
                      order_detail_item_list_paginates[index + 1]
                        .data_receive_voucher_id
                    )
                  "
                >
                  次伝票＞
                </button></span
              >
            </li>
          </ul>
        </span>
      </div>
      <div class="col-12"></div>
      <div class="col-12">
        <table
          class="table table-striped table-bordered order_item_details_table"
          style="overflow-x: scroll"
        >
          <thead>
            <tr>
              <th>No</th>
              <th>商品</th>
              <th>入数</th>
              <th>ケース数</th>
              <th>単位</th>
              <th>バラ数</th>
              <th>重量</th>
              <th>原単価</th>
              <th>原価金額</th>
              <th>売単価</th>
              <th>売価金額</th>
              <!--  <th>訂正理由</th>-->
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(order_item_detail_list, index) in order_item_detail_lists"
              :key="index"
            >
              <td>{{ index + 1 }}</td>
              <td style="text-align: left">
                商品コード：{{
                  order_item_detail_list.mes_lis_acc_lin_ite_order_item_code
                }}<br />
                JANコード：
                {{ order_item_detail_list.mes_lis_acc_lin_ite_gtin.slice(1)
                }}<br />
                商品名：{{ order_item_detail_list.mes_lis_acc_lin_ite_name
                }}<br />
                規格：{{ order_item_detail_list.mes_lis_acc_lin_ite_ite_spec
                }}<br />
                産地：{{ order_item_detail_list.mes_lis_acc_lin_fre_field_name
                }}<br />
              </td>
              <td>
                {{
                  order_item_detail_list.mes_lis_acc_lin_qua_unit_multiple
                }}
              </td>
              <td>
                {{
                  order_item_detail_list.mes_lis_acc_lin_qua_rec_num_of_order_units
                }}
                <!--
                <input type="text" class="form-control" @keyup="ball_case_cal(order_item_detail_list,'ケース')" v-model="order_item_detail_list.mes_lis_acc_lin_qua_shi_num_of_order_units">
                {{order_item_detail_list.mes_lis_acc_lin_qua_ord_num_of_order_units}}
                -->
              </td>
              <td>
                <!-- {{order_item_detail_list.mes_lis_acc_lin_qua_unit_of_measure}}  {{getbyrjsonValueBykeyName('mes_lis_ord_lin_qua_unit_of_measure',order_item_detail_list.mes_lis_acc_lin_qua_unit_of_measure,'orders',buyer_settings)}}-->
                <!--{{
                  order_item_detail_list.mes_lis_acc_lin_qua_unit_of_measure
                }}-->
              </td>
              <td>
                <!--<input type="text" class="form-control" @keyup="ball_case_cal(order_item_detail_list,'バラ')" v-model="order_item_detail_list.mes_lis_acc_lin_qua_shi_quantity">
                {{order_item_detail_list.mes_lis_acc_lin_qua_ord_quantity}}-->
                {{ order_item_detail_list.mes_lis_acc_lin_qua_rec_quantity }}
              </td>

              <td>
                <!--{{order_item_detail_list.mes_lis_acc_lin_fre_item_weight * order_item_detail_list.mes_lis_acc_lin_qua_shi_quantity}}-->
                <span v-if="order_item_detail_list.mes_lis_acc_lin_fre_received_weight!='0.000'">{{ order_item_detail_list.mes_lis_acc_lin_fre_received_weight }}</span>
              </td>
              <td class="text-right">
                <!-- <input type="text" class="form-control" v-model="order_item_detail_list.mes_lis_acc_lin_amo_item_net_price_unit_price">
                {{order_item_detail_list.mes_lis_ord_lin_amo_item_net_price_unit_price}}-->
                {{
                  order_item_detail_list.mes_lis_acc_lin_amo_item_net_price_unit_price
                    | priceFormatFloat
                }}
              </td>
              <td class="text-right">
                <!--{{ order_item_detail_list.mes_lis_acc_lin_amo_item_net_price_unit_price * order_item_detail_list.mes_lis_acc_lin_qua_shi_quantity}}-->
                {{
                  order_item_detail_list.mes_lis_acc_lin_amo_item_net_price
                    | priceFormatNullZero
                }}
              </td>
              <td class="text-right">
                <!--<input type="text" class="form-control" v-model="order_item_detail_list.mes_lis_acc_lin_amo_item_selling_price_unit_price">
                {{order_item_detail_list.mes_lis_ord_lin_amo_item_selling_price_unit_price}}-->
                {{
                  order_item_detail_list.mes_lis_acc_lin_amo_item_selling_price_unit_price
                    | priceFormat
                }}
              </td>
              <td class="text-right">
                <!--{{order_item_detail_list.mes_lis_acc_lin_amo_item_selling_price_unit_price * order_item_detail_list.mes_lis_acc_lin_qua_shi_quantity}}-->
                {{
                  order_item_detail_list.mes_lis_acc_lin_amo_item_selling_price
                    | priceFormatNullZero
                }}
              </td>
              <!--  <td>-->
              <!--{{order_item_detail_list.mes_lis_acc_lin_qua_rec_reason_code}} {{getbyrjsonValueBykeyName('mes_lis_acc_lin_qua_rec_reason_code',order_item_detail_list.mes_lis_acc_lin_qua_rec_reason_code,'receives',buyer_settings)}}
                <select v-model="order_item_detail_list.mes_lis_acc_lin_qua_rec_reason_code" class="form-control ">
                <option v-for="item in mes_lis_acc_lin_qua_rec_reason_codeList" :value="Object.keys(item)[0]">{{Object.values(item)[0]}}</option>
                </select>-->
              <!--<input type="hidden" v-model="totalCostPrice += order_item_detail_list.mes_lis_shi_lin_amo_item_net_price_unit_price * order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity">
                <input type="hidden" v-model="totalSellingPrice += order_item_detail_list.mes_lis_shi_lin_amo_item_selling_price_unit_price * order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity">-->
              <!-- {{order_item_detail_list.mes_lis_acc_lin_qua_rec_reason_code}}
                </td>-->
            </tr>
            <tr
              v-if="
                order_item_detail_lists && order_item_detail_lists.length == 0
              "
            >
              <td class="text-center" colspan="12">データがありません</td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th style="background: #538ed3; color: #fff; text-align: center">
                原価金額<br />合計
              </th>
              <th style="text-align: right">
                <!--{{ totalCostPriceVal | priceFormat }}
                {{order_item_detail_lists[0].mes_lis_acc_tot_tot_net_price_total | priceFormat}}-->
                {{total_mes_lis_acc_tot_tot_net_price_total | priceFormat}}
              </th>
              <th style="background: #538ed3; color: #fff; text-align: center">
                売価金額<br />合計
              </th>
              <th style="text-align: right">
                <!--{{ totalSellingPriceVal | priceFormat }}
                {{order_item_detail_lists[0].mes_lis_acc_tot_tot_selling_price_total | priceFormat}}-->
                {{total_mes_lis_acc_tot_tot_selling_price_total | priceFormat}}
              </th>
            </tr>
          </tfoot>
        </table>
        <!--<button style="float:right" class="btn btn-lg btn-primary pull-right text-right active">
              更新
            </button>-->
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
      <!-- </div>
        </div>
      </div>-->
    </b-modal>
  </div>
</template>
<script>
export default {
  breadcrumb() {
    return {
      label: "受領伝票明細",
      parent: this.parentQ,
    };
  },
  data() {
    return {
      parentQ: {
        name: "receive_detail",
        query: {},
      },
      sortKey: "",
      reverse: true,
      order_by: "asc",
      order_detail_lists: {},
      order_detail_item_list_paginates: {},
      order_item_lists: {},
      order_item_detail_lists: [],
      order_item_shipment_data_headTable: {},
      mes_lis_acc_lin_qua_rec_reason_codeList: [],
      order_date: "",
      order_detail_list: [],
      buyer_settings: [],
      byr_buyer_lists: {},
      show_hide_col_list: [],
      expected_delivery_date: "",
      data_order_voucher_id: "",
      totalCostPrice: 0,
      totalSellingPrice: 0,
      total_mes_lis_acc_tot_tot_net_price_total:0,
total_mes_lis_acc_tot_tot_selling_price_total:0,
      status: "",
      // byr_order_id: "",
      edit_order_modal: false,
      selected: [],
      isCheckAll: false,
      form: new Form({
        data_receive_voucher_id: null,
        byr_buyer_id: null,
        adm_user_id: Globals.user_info_id,
      }),
      param_data: [],
      queryData: "",
      byr_buyer_id: null,
      data_receive_voucher_id: "",
    };
  },
  beforeCreate: function () {
    if (!this.$session.exists()) {
      this.$router.push("/home");
    }
  },
  methods: {
    ball_case_cal(order_item_detail_list, field_type) {
      if (field_type == "ケース") {
        order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity =
          order_item_detail_list.mes_lis_shi_lin_qua_shi_num_of_order_units *
          order_item_detail_list.mes_lis_shi_lin_qua_ord_quantity;
      } else {
        var calval =
          order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity /
          order_item_detail_list.mes_lis_shi_lin_qua_ord_quantity;
        if (calval > 0 && calval % 1 === 0) {
          order_item_detail_list.mes_lis_shi_lin_qua_shi_num_of_order_units = calval;
        }
      }
      this.checkUpdateDeliveryStatus();
    },
    checkUpdateDeliveryStatus() {
      var caseBallQtycheck = [];
      var allIsZero = [];
      var allIsNotZero = [];
      var allIsZeroNotZero = [];
      var totalRows = this.order_item_detail_lists.length;
      this.order_item_detail_lists.forEach(function (
        order_item_detail_listData
      ) {
        if (order_item_detail_listData.mes_lis_acc_lin_qua_shi_quantity == 0) {
          allIsZero.push(0);
        } else {
          allIsNotZero.push(1);
        }
      });
      this.order_item_shipment_data_headTable.status = "一部未納";
      if (totalRows == allIsZero.length) {
        this.order_item_shipment_data_headTable.status = "未納";
      }
      if (totalRows == allIsNotZero.length) {
        this.order_item_shipment_data_headTable.status = "完納";
      }
    },
    move_next_prev(id) {
      this.form.data_receive_voucher_id = id;
      this.data_receive_voucher_id = id;
      this.get_all_receive_item_detail();
    },
    //get Table data
    get_all_receive_item_detail() {
      axios
        .post(this.BASE_URL + "api/data_receive_item_detail_list", this.form)
        .then(({ data }) => {
          this.order_item_detail_lists = data.received_item_detail_list;
          this.order_item_shipment_data_headTable =
            data.received_item_detail_list[0];
          this.byr_buyer_lists = data.byr_buyer_list;
          this.buyer_settings = JSON.parse(data.buyer_settings);
          this.order_item_lists = data.received_item_detail_list[0];
          this.mes_lis_acc_lin_qua_rec_reason_codeList = this.buyer_settings.receives.mes_lis_acc_lin_qua_rec_reason_code;
          this.total_mes_lis_acc_tot_tot_net_price_total=this.order_item_detail_lists[0].mes_lis_acc_tot_tot_net_price_total;
          this.total_mes_lis_acc_tot_tot_selling_price_total=this.order_item_detail_lists[0].mes_lis_acc_tot_tot_selling_price_total;
          this.loader.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loader.hide();
        // });
    },

    //get Table data
    get_all_receive_item_detail_pagination() {
      this.parentQ.query.adm_user_id = Globals.user_info_id;
      this.parentQ.query.byr_buyer_id = this.byr_buyer_id;
      axios
        .post(
          this.BASE_URL + "api/data_receive_detail_list_pagination",
          this.parentQ.query
        )
        .then(({ data }) => {
          this.order_detail_item_list_paginates =
            data.received_detail_list_single_pagination;
        }).catch(() => {
            this.sweet_advance_alert();
            // loader.hide();
        });
    },
  },

  created() {
    this.byr_buyer_id = this.$session.get("byr_buyer_id");
    this.form.byr_buyer_id = this.$session.get("byr_buyer_id");
    Fire.$emit("byr_has_selected", this.$session.get("byr_buyer_id"));
    Fire.$emit("permission_check_for_buyer", this.$session.get("byr_buyer_id"));
    this.parentQ.query = this.$session.get("receive_list_detail_query_param");
    this.getbuyerJsonSettingvalue();
    this.loader = Vue.$loading.show();
    this.data_receive_voucher_id = this.$route.params.data_receive_voucher_id;
    this.form.data_receive_voucher_id = this.$route.params.data_receive_voucher_id;
    this.get_all_receive_item_detail();

    Fire.$on("LoadByrorderItemDetail", () => {
      this.get_all_receive_item_detail();
    });
    this.get_all_receive_item_detail_pagination();
    Fire.$emit("loadPageTitle", "受領伝票明細");
  },
  computed: {
    totalCostPriceVal: function () {
      return this.order_item_detail_lists.reduce(function (
        sum,
        order_item_detail_list
      ) {
        return (
          sum +
          order_item_detail_list.mes_lis_acc_lin_amo_item_net_price_unit_price *
            order_item_detail_list.mes_lis_acc_lin_qua_shi_quantity
        );
      },
      0);

      // return this.order_item_detail_lists.reduce(function (sum,order_item_detail_list) {
      //  return  sum+order_item_detail_list.mes_lis_shi_lin_amo_item_net_price_unit_price * order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity;
      // },0);
    },
    totalSellingPriceVal: function () {
      return this.order_item_detail_lists.reduce(function (
        sumselling,
        order_item_detail_list
      ) {
        return (
          sumselling +
          order_item_detail_list.mes_lis_acc_lin_amo_item_selling_price_unit_price *
            order_item_detail_list.mes_lis_acc_lin_qua_shi_quantity
        );
      },
      0);
    },
  },
  mounted() {},
};
</script>
