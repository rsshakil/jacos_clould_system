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
              <td width="10%" class="cl_custom_color">受信日時</td>
              <td width="15%">{{ order_item_lists.receive_datetime }}</td>
              <td width="10%" class="cl_custom_color">取引先</td>
              <td width="15%">
                {{ order_item_lists.mes_lis_shi_par_sel_code }}
                {{ order_item_lists.mes_lis_shi_par_sel_name }}
              </td>
              <td width="10%" class="cl_custom_color">便</td>
              <td width="15%">
                {{
                  getbyrjsonValueBykeyName(
                    "mes_lis_ord_log_del_delivery_service_code",
                    order_item_lists.mes_lis_shi_log_del_delivery_service_code,
                    "orders"
                  )
                }}
              </td>
            </tr>
            <tr>
              <td width="10%" class="cl_custom_color">納品日</td>
              <td width="15%">
                {{ order_item_lists.mes_lis_shi_tra_dat_delivery_date_to_receiver }}
              </td>
              <td width="10%" class="cl_custom_color">部門</td>
              <td width="15%">
                {{ order_item_lists.mes_lis_shi_tra_goo_major_category }}
              </td>
              <td width="10%" class="cl_custom_color">温度区分</td>
              <td width="15%">
                {{ order_item_lists.mes_lis_shi_tra_ins_temperature_code }}
                {{
                  getbyrjsonValueBykeyName(
                    "mes_lis_ord_tra_ins_temperature_code",
                    order_item_lists.mes_lis_shi_tra_ins_temperature_code,
                    "orders"
                  )
                }}
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
              <td width="10%" class="cl_custom_color">発注日</td>
              <td width="15%">
                {{
                  order_item_shipment_data_headTable.mes_lis_shi_tra_dat_order_date
                }}
              </td>
              <td width="10%" class="cl_custom_color">直接納品先</td>
              <td width="15%">
                {{
                  order_item_shipment_data_headTable.mes_lis_shi_par_shi_code
                }}
                {{
                  order_item_shipment_data_headTable.mes_lis_shi_par_shi_name
                }}
              </td>
              <td width="10%" class="cl_custom_color">最終納品先</td>
              <td width="15%">
                {{
                  order_item_shipment_data_headTable.mes_lis_shi_par_rec_code
                }}
                {{
                  order_item_shipment_data_headTable.mes_lis_shi_par_rec_name
                }}
              </td>
            </tr>
            <tr>
              <td width="10%" class="cl_custom_color">伝票番号</td>
              <td width="15%">
                {{
                  order_item_shipment_data_headTable.mes_lis_shi_tra_trade_number
                }}
              </td>
              <td width="10%" class="cl_custom_color">伝票区分</td>
              <td width="15%">
                {{
                  order_item_shipment_data_headTable.mes_lis_shi_tra_ins_trade_type_code
                }}
                {{
                  getbyrjsonValueBykeyName(
                    "mes_lis_ord_tra_ins_trade_type_code",
                    order_item_shipment_data_headTable.mes_lis_shi_tra_ins_trade_type_code,
                    "orders"
                  )
                }}
              </td>
              <td width="10%" class="cl_custom_color">定／特</td>
              <td width="15%">
                {{
                  order_item_shipment_data_headTable.mes_lis_shi_tra_ins_goods_classification_code
                }}
                {{
                  getbyrjsonValueBykeyName(
                    "mes_lis_ord_tra_ins_goods_classification_code",
                    order_item_shipment_data_headTable.mes_lis_shi_tra_ins_goods_classification_code,
                    "orders"
                  )
                }}
              </td>
            </tr>
            <tr>
              <td width="10%" class="cl_custom_color">不定貴区分</td>
              <td width="15%">
                {{
                  order_item_shipment_data_headTable.mes_lis_shi_tra_fre_variable_measure_item_code
                }}
                {{
                  getbyrjsonValueBykeyName(
                    "mes_lis_ord_tra_fre_variable_measure_item_code",
                    order_item_shipment_data_headTable.mes_lis_shi_tra_fre_variable_measure_item_code,
                    "orders"
                  )
                }}
              </td>
              <td width="10%" class="cl_custom_color">税区分・税率</td>
              <td width="15%">
                {{
                  order_item_shipment_data_headTable.mes_lis_shi_tra_tax_tax_type_code
                }}
                {{
                  getbyrjsonValueBykeyName(
                    "mes_lis_ord_tra_tax_tax_type_code",
                    order_item_shipment_data_headTable.mes_lis_shi_tra_tax_tax_type_code,
                    "orders"
                  )
                }}
                {{
                  order_item_shipment_data_headTable.mes_lis_shi_tra_tax_tax_rate
                }}
                %
              </td>

              <td width="10%" class="cl_custom_color">備考</td>
              <td width="15%">
                {{
                  order_item_shipment_data_headTable.mes_lis_shi_tra_not_text
                }}
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
              <td width="10%" class="cl_custom_color">出荷状況</td>
              <td width="15%">
                {{ order_item_shipment_data_headTable.status }}
              </td>
              <td width="10%" class="cl_custom_color_extra">確定状況</td>
              <td width="15%">
                <span v-if="order_item_shipment_data_headTable.decision_datetime!=null">{{ order_item_shipment_data_headTable.decision_datetime }}</span>
                <span v-else>未確定</span>
              </td>
              <td width="10%" class="cl_custom_color_extra">送信状況</td>
              <td width="15%">
              <span v-if="order_item_shipment_data_headTable.send_datetime!=null">{{ order_item_shipment_data_headTable.send_datetime }}</span>
                <span v-else>未送信</span>
              </td>
            </tr>
          </table>
        </div>
      </div>

      <div class="col-12">
        <table
          class="table orderDetailTable table-bordered"
          style="width: 100%"
        >
          <tr>
            <td class="cl_custom_color_extra">訂正納品日</td>
            <td colspan="3">
              <input
                class="form-control"
                :disabled="
                  is_disabled(
                    order_item_shipment_data_headTable.decision_datetime == null
                      ? true
                      : false
                  )
                "
                type="date"
                v-model="
                  order_item_shipment_data_headTable.mes_lis_shi_tra_dat_revised_delivery_date
                "
              />
            </td>
          </tr>
        </table>
      </div>
      <div class="col-12">
        <span class="pagi" style="width: 100%">
          <ul class="list-inline">
            <li
              v-for="(item, index) in order_detail_list_paginates"
              :key="index"
              v-if="data_order_voucher_id == item.data_shipment_voucher_id"
            >
              <span v-if="index >= 1"
                ><button
                  class="btn btn-primary"
                  @click="
                    move_next_prev(
                      order_detail_list_paginates[index - 1]
                        .data_shipment_voucher_id
                    )
                  "
                >
                  ＜前伝票
                </button></span
              >
              <span v-if="index < order_detail_list_paginates.length - 1"
                ><button
                  class="btn btn-primary"
                  @click="
                    move_next_prev(
                      order_detail_list_paginates[index + 1]
                        .data_shipment_voucher_id
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
      <div class="col-12">
        <div class="">
          <table
            class="table table-striped table-bordered table-responsive order_item_details_table data_table"
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
                <th>欠品理由</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(
                  order_item_detail_list, index
                ) in order_item_detail_lists"
                :key="index"
              >
                <td>{{ index + 1 }}</td>
                <td style="text-align: left">
                  商品コード：{{
                    order_item_detail_list.mes_lis_shi_lin_ite_order_item_code
                  }}<br />
                  JANコード：
                  {{ order_item_detail_list.mes_lis_shi_lin_ite_gtin.slice(1)
                  }}<br />
                  商品名：{{ order_item_detail_list.mes_lis_shi_lin_ite_name
                  }}<br />
                  規格：{{ order_item_detail_list.mes_lis_shi_lin_ite_ite_spec
                  }}<br />
                  産地：{{
                    order_item_detail_list.mes_lis_shi_lin_fre_field_name
                  }}<br />
                </td>
                <td>
                  {{ order_item_detail_list.mes_lis_shi_lin_qua_unit_multiple }}
                </td>
                <td>
                  <input
                    type="number"
                    :disabled="
                      is_disabled(
                        order_item_shipment_data_headTable.decision_datetime ==
                          null
                          ? true
                          : false
                      )
                    "
                    class="form-control"
                    :min="0"
                    :max="order_item_detail_list.mes_lis_shi_lin_qua_ord_num_of_order_units"
                    @keyup="ball_case_cal(order_item_detail_list, 'ケース')"
                    v-model="
                      order_item_detail_list.mes_lis_shi_lin_qua_shi_num_of_order_units
                    "
                  />
                  {{
                    order_item_detail_list.mes_lis_shi_lin_qua_ord_num_of_order_units
                  }}
                </td>
                <td>
                  <!--{{order_item_detail_list.mes_lis_shi_lin_qua_unit_of_measure}}  {{getbyrjsonValueBykeyName('mes_lis_ord_lin_qua_unit_of_measure',order_item_detail_list.mes_lis_shi_lin_qua_unit_of_measure,'orders')}}-->
                </td>
                <td>
                  <input
                    type="number"
                    :disabled="
                      is_disabled(
                        order_item_shipment_data_headTable.decision_datetime ==
                          null
                          ? true
                          : false
                      )
                    "
                    class="form-control"
                    :min="0"
                    :max="order_item_detail_list.mes_lis_shi_lin_qua_ord_quantity"
                    @keyup="ball_case_cal(order_item_detail_list, 'バラ')"
                    v-model="
                      order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity
                    "
                  />
                  {{ order_item_detail_list.mes_lis_shi_lin_qua_ord_quantity }}
                </td>

                <td class="text-right">
                  {{
                    (order_item_detail_list.mes_lis_shi_lin_fre_item_weight *
                      order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity)
                      | priceFormat
                  }}
                </td>
                <td class="text-right">
                  <input
                    type="number"
                    :disabled="
                      is_disabled(
                        order_item_shipment_data_headTable.decision_datetime ==
                          null
                          ? true
                          : false
                      )
                    "
                    class="form-control text-right"
                    :min="0"
                    :max="order_item_detail_list.mes_lis_ord_lin_amo_item_net_price_unit_price"
                    v-model="
                      order_item_detail_list.mes_lis_shi_lin_amo_item_net_price_unit_price
                    "
                  />
                  {{
                    order_item_detail_list.mes_lis_ord_lin_amo_item_net_price_unit_price
                  }}
                </td>
                <td class="text-right">
                  <span
                    v-if="
                      order_item_detail_list.mes_lis_shi_lin_amo_item_net_price_unit_price *
                        order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity >
                      0
                    "
                  >
                    {{
                      (order_item_detail_list.mes_lis_shi_lin_amo_item_net_price_unit_price *
                        order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity)
                        | priceFormat
                    }}</span
                  >
                  <span v-else>0</span>
                </td>
                <td class="text-right">
                  <input
                    type="number"
                    :disabled="
                      is_disabled(
                        order_item_shipment_data_headTable.decision_datetime ==
                          null
                          ? true
                          : false
                      )
                    "
                    class="form-control text-right"
                    :min="0"
                    :max="order_item_detail_list.mes_lis_ord_lin_amo_item_selling_price_unit_price"
                    v-model="
                      order_item_detail_list.mes_lis_shi_lin_amo_item_selling_price_unit_price
                    "
                  />
                  {{
                    order_item_detail_list.mes_lis_ord_lin_amo_item_selling_price_unit_price
                  }}
                </td>
                <td class="text-right">
                  <span
                    v-if="
                      order_item_detail_list.mes_lis_shi_lin_amo_item_selling_price_unit_price *
                        order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity >
                      0
                    "
                    >{{
                      (order_item_detail_list.mes_lis_shi_lin_amo_item_selling_price_unit_price *
                        order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity)
                        | priceFormat
                    }}</span
                  >
                  <span v-else>0</span>
                </td>
                <td>
                  <span
                    v-if="
                      order_item_detail_list.mes_lis_shi_lin_qua_shi_num_of_order_units !=
                        order_item_detail_list.mes_lis_shi_lin_qua_ord_num_of_order_units &&
                      order_item_detail_list.mes_lis_shi_lin_qua_sto_reason_code ==
                        '00'
                    "
                    style="color: red; font-size: 12px"
                    >欠品のため欠品理由が必要です。</span
                  >
                  <span
                    v-if="
                      order_item_detail_list.mes_lis_shi_lin_qua_shi_num_of_order_units ==
                        order_item_detail_list.mes_lis_shi_lin_qua_ord_num_of_order_units &&
                      order_item_detail_list.mes_lis_shi_lin_qua_sto_reason_code !=
                        '00'
                    "
                    style="color: red; font-size: 12px"
                    >完納のため欠品理由は不正です。</span
                  >
                  <select
                    :disabled="
                      is_disabled(
                        order_item_shipment_data_headTable.decision_datetime ==
                          null
                          ? true
                          : false
                      )
                    "
                    v-model="
                      order_item_detail_list.mes_lis_shi_lin_qua_sto_reason_code
                    "
                    class="form-control"
                    :class="[
                      order_item_detail_list.mes_lis_shi_lin_qua_shi_num_of_order_units !=
                        order_item_detail_list.mes_lis_shi_lin_qua_ord_num_of_order_units &&
                      order_item_detail_list.mes_lis_shi_lin_qua_sto_reason_code ==
                        '00'
                        ? 'error_found'
                        : '',
                      order_item_detail_list.mes_lis_shi_lin_qua_shi_num_of_order_units ==
                        order_item_detail_list.mes_lis_shi_lin_qua_ord_num_of_order_units &&
                      order_item_detail_list.mes_lis_shi_lin_qua_sto_reason_code !=
                        '00'
                        ? 'error_found'
                        : '',
                    ]"
                  >
                    <option
                      v-for="(
                        item, i
                      ) in mes_lis_shi_lin_qua_sto_reason_codeList"
                      :value="i"
                      :key="i"
                    >
                      {{ item }}
                    </option>
                  </select>
                  {{
                    order_item_detail_list.mes_lis_shi_lin_qua_sto_reason_code
                  }}
                  {{
                    getbyrjsonValueBykeyName(
                      "mes_lis_shi_lin_qua_sto_reason_code",
                      order_item_detail_list.mes_lis_shi_lin_qua_sto_reason_code,
                      "shipments"
                    )
                  }}
                  <!--<input type="hidden" v-model="totalCostPrice += order_item_detail_list.mes_lis_shi_lin_amo_item_net_price_unit_price * order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity">
                <input type="hidden" v-model="totalSellingPrice += order_item_detail_list.mes_lis_shi_lin_amo_item_selling_price_unit_price * order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity">-->
                </td>
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
                <th
                  style="background: #538ed3; color: #fff; text-align: center"
                >
                  原価金額<br />合計
                </th>
                <th style="text-align: right">
                  {{ totalCostPriceVal | priceFormat }}
                </th>
                <th
                  style="background: #538ed3; color: #fff; text-align: center"
                >
                  売価金額<br />合計
                </th>
                <th style="text-align: right">
                  {{ totalSellingPriceVal | priceFormat }}
                </th>
                <th></th>
              </tr>
            </tfoot>
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
      <!-- </div>
        </div>
      </div>-->
    </b-modal>
  </div>
</template>
<style>
input[type=number]{
    min-width: 100px;
}
</style>
<script>
export default {
  // props: ["param_data"],
  breadcrumb() {
    return {
      label: this.breadcumbtitle,
      parent: this.parent,
    };
  },
  data() {
    return {
      breadcumbtitle: "受注伝票明細",
      parent: {
        name: "slr_order_list_details",
        query: {},
      },
      today: new Date().toISOString().slice(0, 10),
      sortKey: "",
      reverse: true,
      order_by: "asc",
      order_detail_lists: {},
      order_detail_list_paginates: {},
      order_item_detail_lists: [],
      order_item_lists: {},
      order_item_shipment_data_headTable: {},
      order_date: "",
      order_detail_list: [],
      show_hide_col_list: [],
      expected_delivery_date: "",
      data_order_voucher_id: "",
      mes_lis_shi_tot_tot_net_price_total: 0,
      mes_lis_shi_tot_tot_selling_price_total: 0,
      totalCostPrice: 0,
      totalSellingPrice: 0,
      status: "",
      // byr_order_id: "",
      edit_order_modal: false,
      selected: [],
      isCheckAll: false,
      form: new Form({}),
      paginate_q: {},
      param_data: [],
      queryData: "",
      byr_buyer_id: null,
    };
  },
  beforeCreate: function () {
    if (!this.$session.exists()) {
      this.$router.push("/home");
    }
  },
  methods: {
    get_prev_next_list() {
      this.paginate_q.page = 1;
      this.paginate_q.per_page = 1;
      this.paginate_q.order_info = this.$session.get("order_param_data");
      axios
        .post(this.BASE_URL + "api/order_detail_paginations", this.paginate_q)
        .then(({ data }) => {
          this.order_detail_list_paginates = data.order_list_detail;
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // this.loader.hide();
        // });
    },
    ball_case_cal(order_item_detail_list, field_type) {
      if (field_type == "ケース") {
        // order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity=order_item_detail_list.mes_lis_shi_lin_qua_shi_num_of_order_units*order_item_detail_list.mes_lis_shi_lin_qua_ord_quantity;
        if (
          order_item_detail_list.mes_lis_shi_lin_qua_shi_num_of_order_units >
          order_item_detail_list.mes_lis_shi_lin_qua_ord_num_of_order_units
        ) {
          order_item_detail_list.mes_lis_shi_lin_qua_shi_num_of_order_units =
            order_item_detail_list.mes_lis_shi_lin_qua_ord_num_of_order_units;
        }
        order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity =
          order_item_detail_list.mes_lis_shi_lin_qua_shi_num_of_order_units *
          order_item_detail_list.mes_lis_shi_lin_qua_unit_multiple;
      } else {
        // var calval=order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity/order_item_detail_list.mes_lis_shi_lin_qua_ord_quantity;
        var calval =
          order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity /
          order_item_detail_list.mes_lis_shi_lin_qua_unit_multiple;
        if (calval > 0 && calval % 1 === 0) {
          if (
            calval >
            order_item_detail_list.mes_lis_shi_lin_qua_ord_num_of_order_units
          ) {
            calval =
              order_item_detail_list.mes_lis_shi_lin_qua_ord_num_of_order_units;
            order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity =
              calval * order_item_detail_list.mes_lis_shi_lin_qua_unit_multiple;
          }
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
        if (order_item_detail_listData.mes_lis_shi_lin_qua_shi_quantity == 0) {
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
    checkValidate() {
      var _this = this;
      var isValidate = 1;
      this.order_item_detail_lists.forEach(function (value, index) {
        if (
          value.mes_lis_shi_lin_qua_shi_num_of_order_units !=
            value.mes_lis_shi_lin_qua_ord_num_of_order_units &&
          value.mes_lis_shi_lin_qua_sto_reason_code == "00"
        ) {
          _this.alert_icon = "error";
          _this.alert_title = "";
          _this.alert_text = "入力データが不正です。入力値を確認してください。";
          _this.sweet_normal_alert();
          isValidate = 0;
          return isValidate;
        }
        if (
          value.mes_lis_shi_lin_qua_shi_num_of_order_units ==
            value.mes_lis_shi_lin_qua_ord_num_of_order_units &&
          value.mes_lis_shi_lin_qua_sto_reason_code != "00"
        ) {
          _this.alert_icon = "error";
          _this.alert_title = "";
          _this.alert_text = "入力データが不正です。入力値を確認してください。";
          _this.sweet_normal_alert();
          isValidate = 0;
          return isValidate;
        }
      });
      return isValidate;
    },


    checkAll() {
      this.isCheckAll = !this.isCheckAll;
      this.selected = [];
      var temp_seleted = [];
      if (this.isCheckAll) {
        this.order_detail_lists.forEach(function (order_detail_list) {
          temp_seleted.push(order_detail_list.byr_order_detail_id);
        });
        this.selected = temp_seleted;
      }
    },
    updateCheckall() {
      if (this.selected.length == this.order_detail_lists.length) {
        this.isCheckAll = true;
      } else {
        this.isCheckAll = false;
      }
    },

    update_checked_item_list() {
      var post_data = {
        selected_item: this.selected,
        user_id: Globals.user_info_id,
      };
      axios
        .post(this.BASE_URL + "api/update_byr_order_detail_status", post_data)
        .then(({ data }) => {
          Fire.$emit("LoadByrorderDetail");
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // this.loader.hide();
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
        this.order_detail_lists.sort((a, b) => a[sortKey] - b[sortKey]);
      } else {
        this.order_by = "asc";
        this.order_detail_lists.sort((a, b) => b[sortKey] - a[sortKey]);
      }
    },
    sortByja_valu(sortKey) {
      if (this.order_by == "asc") {
        this.order_by = "desc";
        this.order_detail_lists.sort((a, b) =>
          a[sortKey].localeCompare(b[sortKey], "ja", {
            ignorePunctuation: true,
          })
        );
      } else {
        this.order_by = "asc";
        this.order_detail_lists.sort((a, b) =>
          b[sortKey].localeCompare(a[sortKey], "ja", {
            ignorePunctuation: true,
          })
        );
      }
    },
    update_shipment_detail(order_detail) {
      axios({
        method: "POST",
        url: this.BASE_URL + "api/update_shipment_detail",
        data: order_detail,
      })
        .then(({ data }) => {
          Fire.$emit("LoadByrorderDetail");
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // this.loader.hide();
        // });
    },
    //get Table data
    move_next_prev(id) {
      this.data_order_voucher_id = id;
      this.get_all_byr_order_item_detail();
    },
    get_all_byr_order_item_detail() {
      axios
        .get(
          this.BASE_URL + "api/order_item_details/" + this.data_order_voucher_id
        )
        .then(({ data }) => {
          this.order_item_detail_lists = data.order_item_list_detail;
          this.mes_lis_shi_tot_tot_net_price_total =
            data.order_item_list_detail[0].mes_lis_shi_tot_tot_net_price_total;
          this.mes_lis_shi_tot_tot_selling_price_total =
            data.order_item_list_detail[0].mes_lis_shi_tot_tot_selling_price_total;
          this.order_item_lists = data.orderItem;
          this.order_item_shipment_data_headTable =
            data.order_item_list_detail[0];
          // console.log(this.order_item_shipment_data_headTable);
          // console.log(this.order_item_lists);
          this.loader.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     this.loader.hide();
        // });
    },

    edit_order_detail(order_detail_list) {
      this.edit_order_modal = true;
    },
  },

  created() {
    this.getbuyerJsonSettingvalue();
    // this.param_data = this.$route.query;

    this.loader = Vue.$loading.show();
    this.data_order_voucher_id = this.$route.query.voucher_id;

    this.get_all_byr_order_item_detail();
    Fire.$on("LoadByrorderItemDetail", () => {
      this.get_all_byr_order_item_detail();
    });
    this.parent.query = this.$session.get("order_param_data");
    Fire.$emit("loadPageTitle", "受注伝票明細");
    this.get_prev_next_list();
  },
  computed: {
    total_selling_price: function () {
      // this.order_item_detail_lists.forEach(function (order_item_detail_list) {
      //     this.mes_lis_shi_tot_tot_selling_price_total +=order_item_detail_list.mes_lis_shi_lin_amo_item_selling_price
      //   });
      // return this.order_item_detail_lists.reduce(function(a, c){return a + Number((c.mes_lis_shi_lin_amo_item_selling_price) || 0)}, 0)
      // return this.mes_lis_shi_tot_tot_selling_price_total;
      return 0;
    },
    totalCostPriceVal: function () {
      return this.order_item_detail_lists.reduce(function (
        sum,
        order_item_detail_list
      ) {
        return (
          sum +
          order_item_detail_list.mes_lis_shi_lin_amo_item_net_price_unit_price *
            order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity
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
          order_item_detail_list.mes_lis_shi_lin_amo_item_selling_price_unit_price *
            order_item_detail_list.mes_lis_shi_lin_qua_shi_quantity
        );
      },
      0);
    },
    total_net_price: function () {
      // this.order_item_detail_lists.forEach(function (order_item_detail_list) {
      //     this.mes_lis_shi_tot_tot_net_price_total +=order_item_detail_list.mes_lis_shi_lin_amo_item_net_price
      //   });
      //   return this.mes_lis_shi_tot_tot_net_price_total;
      // return this.order_item_detail_lists.reduce(function(a, c){return a + Number((c.mes_lis_shi_lin_amo_item_net_price) || 0)}, 0)
      return 0;
    },
    total_selling_prices: function () {
      var totoal = 0;
      // this.order_item_detail_lists.forEach(function (order_item_detail_list) {
      //     totoal +=order_item_detail_list.mes_lis_shi_lin_amo_item_selling_price
      //   });
      return totoal;
      // return this.order_item_detail_lists.reduce(function(a, c){return a + Number((c.mes_lis_shi_lin_amo_item_selling_price) || 0)}, 0)
      // return this.mes_lis_shi_tot_tot_selling_price_total;
    },
    total_net_prices: function () {
      var totoal = 0;
      // this.order_item_detail_lists.forEach(function (order_item_detail_list) {
      //     totoal +=order_item_detail_list.mes_lis_shi_lin_amo_item_net_price
      //   });
      return totoal;
      //   return this.mes_lis_shi_tot_tot_net_price_total;
      // return this.order_item_detail_lists.reduce(function(a, c){return a + Number((c.mes_lis_shi_lin_amo_item_net_price) || 0)}, 0)
    },
  },
  mounted() {},
};
</script>
