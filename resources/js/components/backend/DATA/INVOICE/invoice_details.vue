<template>
  <div class="row">
  <div class="col-12">
    <div class="col-12" style="background: #d8e3f0;padding: 10px;margin-bottom:20px;">
        <table
          class="table orderDetailTable cmnWidthTable table-bordered"
          style="width: 100%"
        >
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
        <table
          class="table orderDetailTable cmnWidthTable table-bordered"
          style="width: 100%"
        >
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
            <multiselect v-model="form.category_code" :options="byr_buyer_category_lists" label="category_name" track-by="category_code" :searchable="true" :close-on-select="true" :clear-on-select="true" :select-label="''" :deselect-label="''" :selected-label="'選択中'" :preserve-search="true"  placeholder="部門"><span slot="noOptions">候補がありません</span> <span slot="noResult">候補がありません</span></multiselect>
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
        <button class="btn btn-primary active srchBtn" type="button" @click="invoice_details()">
          {{ myLang.search }}
        </button>
      </div>
      <div class="col-12">
        <br />
        <h4 class="page_custom_title">{{ myLang.search_result }}</h4>
      </div>

    <div class="col-12">
        <div class="row">
          <div class="col-4">
      <p>
        <span class="tableRowsInfo">{{ invoice_detail_lists.from }}〜{{ invoice_detail_lists.to }} 件表示中／全：{{ invoice_detail_lists.total }}件</span>
        <span class="pagi">
          <advanced-laravel-vue-paginate
            :data="invoice_detail_lists"
            :onEachSide="2"
            previousText="<"
            nextText=">"
            alignment="center"
            @paginateTo="invoice_details"
          />
        </span>
        <span class="selectPagi">
          <select
            class="form-control selectPage"
            @change="invoice_details()"
            v-model="form.select_field_per_page_num">
            <option value="10">10行</option>
            <option value="20">20行</option>
            <option value="50">50行</option>
            <option value="100">100行</option>
          </select>
        </span>
      </p>
          </div>
      <div class="col-3 p-3" style="background-color:#d8e3f0; border-radius:1rem;">
                <p class="mb-0">検索結果のダウンロードはこちら</p>
                <div class="btn-group">
                  <button
                    type="button"
                    class="btn btn-primary active dropdown-toggle"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                    :disabled="is_disabled(invoice_detail_length>=1?true:false)"
                  >
                    <b-icon
                      icon="download"
                      animation="fade"
                      font-scale="1.2"
                    ></b-icon>
                    ダウンロード
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item" @click="invoice_download(1)" type="button"> CSV </button>
                    <!-- <button class="dropdown-item" @click="order_download(2)" type="button"> JCA </button> -->
                  </div>

          </div>
      </div>
       <div class="col-3">
                <p class="mb-0">出荷・受領データの比較はこちら</p>
                 <button @click="invoiceCompareData" class="btn btn-primary " style="float:left;margin-right:10px;">出荷受領比較</button>

              </div>
              <div class="col-2">
               <p class="mb-0">請求伝票追加はこちら</p>
                <b-button class="active text-left pull-left invoice_details_create_button" @click="addInvoiceDetail" variant="primary">新規伝票追加</b-button>
              </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-12">
            <table class="table table-striped table-bordered order_item_details_table invoice_details_table" style="overflow-x: scroll">
          <thead>
              <tr class="first_heading_th">
                <th></th>
                <th>
                  <input
                    @click="checkAll"
                    v-model="isCheckAll"
                    type="checkbox"
                  /> 全選択
                </th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-left" colspan="3"></th>
              </tr>
            <tr>
              <th>No</th>
              <th>確定</th>
              <th class="pointer_class" @click="sorting('mes_lis_inv_lin_det_transfer_of_ownership_date')">計上日 <span class="float-right" :class="iconSet('mes_lis_inv_lin_det_transfer_of_ownership_date')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_inv_lin_det_goo_major_category')">部門コード <span class="float-right" :class="iconSet('mes_lis_inv_lin_det_goo_major_category')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_inv_lin_tra_code')">納品先 <span class="float-right" :class="iconSet('mes_lis_inv_lin_tra_code')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_inv_lin_lin_trade_number_reference')">伝票番号 <span class="float-right" :class="iconSet('mes_lis_inv_lin_lin_trade_number_reference')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_inv_lin_det_pay_code')">請求内容 <span class="float-right" :class="iconSet('mes_lis_inv_lin_det_pay_code')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_inv_lin_det_balance_carried_code')">請求区分 <span class="float-right" :class="iconSet('mes_lis_inv_lin_det_balance_carried_code')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_inv_lin_det_amo_requested_amount')">請求金額 <span class="float-right" :class="iconSet('mes_lis_inv_lin_det_amo_requested_amount')"></span></th>
              <th class="pointer_class" @click="sorting('send_datetime')">送信日時 <span class="float-right" :class="iconSet('send_datetime')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_pay_lin_det_pay_out_date')">支払日 <span class="float-right" :class="iconSet('mes_lis_pay_lin_det_pay_out_date')"></span></th>
            <th colspan="2">詳細</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(value, index) in invoice_detail_lists.data" :key="index">
              <td>
                  <!-- {{ index + 1 }} -->
                  {{ invoice_detail_lists.current_page * form.select_field_per_page_num - form.select_field_per_page_num + index +1 }}
             </td>
              <!-- <td><input type="checkbox" class="form-control" /></td> -->
              <!-- <td>{{ value.mes_lis_inv_per_end_date }}</td> -->
              <td>
                  <span v-if="value.decision_datetime != null">
                    <b-button pill variant="info" @click="decissionDateUpdate(value.data_invoice_pay_detail_id)" :disabled="is_disabled(!value.send_datetime)">済</b-button>
                  </span>
                  <span v-else>
                    <input
                      type="checkbox"
                      v-bind:value="value.data_invoice_pay_detail_id"
                      v-model="selected"
                      @change="updateCheckall()"
                    />
                  </span>
                </td>
              <td>
                {{ value.mes_lis_inv_lin_det_transfer_of_ownership_date }}
              </td>
              <td>
                {{ value.mes_lis_inv_lin_det_goo_major_category }}
              </td>
              <td>
                {{ value.mes_lis_inv_lin_tra_code }} {{value.mes_lis_inv_lin_tra_name_sbcs}}
                <!-- {{ value.mes_lis_inv_lin_tra_name }} -->
              </td>
              <td>{{ value.mes_lis_inv_lin_lin_trade_number_reference }}</td>
              <td>{{ value.mes_lis_inv_lin_det_pay_code }}
              {{
                getbyrjsonValueBykeyName(
                  "mes_lis_inv_lin_det_pay_code",
                  value.mes_lis_inv_lin_det_pay_code,
                  "invoices" )
              }}
              </td>
              <td>
                  {{
                getbyrjsonValueBykeyName(
                  "mes_lis_inv_lin_det_balance_carried_code",
                  value.mes_lis_inv_lin_det_balance_carried_code,
                  "invoices")
              }}
              </td>
              <td class="text-right">{{ value.mes_lis_inv_lin_det_amo_requested_amount | priceFormat }}</td>
              <td>{{ value.send_datetime }}</td>
              <td>{{ value.mes_lis_pay_lin_det_pay_out_date }}</td>
              <td>
                  <button @click="editInvoiceDetail(value)" class="btn btn-primary" :disabled="is_disabled(!value.decision_datetime?true:false)">変更</button>
              </td>
              <td>
                <button @click="deleteInvoiceDetail(value)" class="btn btn-danger" :disabled="is_disabled(!value.decision_datetime?true:false)">削除</button>
              </td>
            </tr>
            <tr v-if="invoice_detail_lists.data && invoice_detail_lists.data.length==0">
                <td class="text-center" colspan="100%">データがありません</td>
            </tr>
          </tbody>
        </table>
        </div>
    </div>
    </div>

    <!-- <div class="col-12">

    </div> -->
    <div class="col-12">
        <div class="row">
          <div class="col-6">
            <!-- <div class="pcontent">
              <p>
                ファイルを選択し「アップロード」ボタンをクリックすると、確定済みデータとしてアップロードされます。
              </p>
            </div>
            <div class="pcontentBtom">
              <label for="updateordershipmentcsv" class="btn btn-primary active">
                <b-icon
                  icon="upload"
                  animation="fade"
                  font-scale="1.2"
                ></b-icon>
                アップロード
              </label>
              <input
                type="file"
                @change="invoiceUpdate"
                id="updateordershipmentcsv"
                class="btn btn-primary active"
                style="display: none"
              />
            </div> -->
          </div>
          <div class="col-6 text-right">
            <button class="btn btn-lg btn-primary active" @click="updateDatetimeDecessionfield">
              選択行を請求確定
            </button>
            <button class="btn btn-lg btn-danger active" @click="sendInvoiceData">
              請求データ送信
            </button>
          </div>
        </div>
      </div>
      <b-modal size="lg" :hide-backdrop="true" :no-enforce-focus="true" title="請求伝票追加" ok-title="追加" cancel-title="キャンセル"
      @ok.prevent="update_invoice_detail()" v-model="addInvoiceDetailModal">
      <div class="panel-body add_item_body">
        <p v-if="errors.length">
        <b>次の間違いを正しくしてください:</b>
        <ul>
          <li style="color:red;" v-for="error in errors">{{ error }}</li>
        </ul>
      </p>
        <form>
          <p class="text-center">請求伝票を追加できます</p>
          <input type="hidden" v-model="invoiceDetail.data_invoice_id">
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label">計上日</label>
              <div class="col-sm-10">
                <input type="date" class="form-control"  v-model="invoiceDetail.mes_lis_inv_lin_det_transfer_of_ownership_date" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label">部門コード</label>
              <div class="col-sm-10">
                <input type="number" class="form-control"  v-model="invoiceDetail.mes_lis_inv_lin_det_goo_major_category">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label">納品先コード</label>
              <div class="col-sm-10">
                <input type="number" class="form-control"  v-model="invoiceDetail.mes_lis_inv_lin_tra_code" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label">伝票番号</label>
              <div class="col-sm-10">
                <input type="number" class="form-control"  v-model="invoiceDetail.mes_lis_inv_lin_lin_trade_number_reference" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label">請求内容</label>
              <div class="col-sm-10">
                <select class="form-control" v-model="invoiceDetail.mes_lis_inv_lin_det_pay_code" required>

              <option
                v-for="(temp, i) in mes_lis_inv_lin_det_pay_code_list"
                :key="i" v-if="temp!=''" :value="i">
                {{ temp }}
              </option>
            </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label">請求区分</label>
              <div class="col-sm-10">
                <select class="form-control"  v-model="invoiceDetail.mes_lis_inv_lin_det_balance_carried_code" required>

              <option
                v-for="(temp, i) in mes_lis_inv_lin_det_balance_carried_codeList"
                :key="i" v-if="temp!='' " :value="i">
                {{ temp }}
              </option>
            </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label">請求金額</label>
              <div class="col-sm-10">
                <input type="number" class="form-control"  v-model="invoiceDetail.requested_amount" required>
              </div>
            </div>

        </form>
      </div>
      <!-- </div>
        </div>
      </div>-->
    </b-modal>

<b-modal size="lg" :hide-backdrop="true" :no-enforce-focus="true" title="請求伝票変更" ok-title="変更" cancel-title="キャンセル"
      @ok.prevent="update_invoice_detail()" v-model="editInvoiceDetailModal">
      <div class="panel-body add_item_body">
       <p v-if="errors.length">
        <b>次の間違いを正しくしてください:</b>
        <ul>
          <li style="color:red;" v-for="error in errors">{{ error }}</li>
        </ul>
      </p>
        <form>
          <p class="text-center">請求伝票を変更できます</p>
          <input type="hidden" v-model="invoiceDetail.data_invoice_id">
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label">計上日</label>
              <div class="col-sm-10">
                <input type="date" class="form-control"  v-model="invoiceDetail.mes_lis_inv_lin_det_transfer_of_ownership_date">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label">部門コード</label>
              <div class="col-sm-10">
                <input type="number" class="form-control"  v-model="invoiceDetail.mes_lis_inv_lin_det_goo_major_category">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label">納品先コード</label>
              <div class="col-sm-10">
                <input type="number" class="form-control"  v-model="invoiceDetail.mes_lis_inv_lin_tra_code">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label">伝票番号</label>
              <div class="col-sm-10">
                <input type="number" class="form-control" v-model="invoiceDetail.mes_lis_inv_lin_lin_trade_number_reference">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label">請求内容</label>
              <div class="col-sm-10">
                <select class="form-control" v-model="invoiceDetail.mes_lis_inv_lin_det_pay_code">

              <option
                v-for="(temp, i) in mes_lis_inv_lin_det_pay_code_list"
                :key="i" v-if="temp!=''" :value="i">
                {{ temp }}
              </option>
            </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label">請求区分</label>
              <div class="col-sm-10">
                <select class="form-control"  v-model="invoiceDetail.mes_lis_inv_lin_det_balance_carried_code">

              <option
                v-for="(temp, i) in mes_lis_inv_lin_det_balance_carried_codeList"
                :key="i" v-if="temp!='' " :value="i">
                {{ temp }}
              </option>
            </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputPassword" class="col-sm-2 col-form-label">請求金額</label>
              <div class="col-sm-10">
                <input type="number" class="form-control"  v-model="invoiceDetail.requested_amount">
              </div>
            </div>

        </form>
      </div>
      <!-- </div>
        </div>
      </div>-->
    </b-modal>

<b-modal size="xl" :hide-backdrop="true" :no-enforce-focus="true" title="出荷・受領比較" cancel-title="閉じる" v-model="invoiceCompareModal" :hide-footer="true" :draggable="true">
      <div class="panel-body">
      <div class="row">
        <div class="col-6">
          <p style="margin:0">出荷データと受領データで差異が発生している伝票のみ表示されています。</p>
          <p style="margin:0">[確認]ボタンを押すと、伝票明細が確認できます。</p>
          <p style="margin:0">黄色の項目は差異が発生している項目です。</p>
        </div>
        <div class="col-6">
        <h6>ダウンロードを押すと、比較データがダウンロードされます</h6>
           <button class="btn btn-outline-primary" style="float:right;margin-bottom:15px;" type="button" :disabled="is_disabled(compareDataList.length>0?true:false)" @click="compare_data_download(1)">
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
              <th>取引先コード</th>
              <th>伝票番号</th>
              <th>直接納品先</th>
              <!--<th>出荷計上日</th>
              <th>受領計上日</th>-->
              <th>出荷原価金額合計</th>
              <th>受領原価金額合計</th>
              <th>明細比較</th>

              <th>請求金額</th>
              <th>受領反映</th>
              <th>作業状況</th>
          </tr>
          </thead>
          <tbody>
            <tr v-for="(value,index) in compareDataList" :key="index">
                <td>{{value.mes_lis_shi_par_sel_code}}</td>
                <td>{{value.mes_lis_shi_tra_trade_number}}</td>
                <td>{{value.mes_lis_shi_par_shi_code}} {{value.mes_lis_shi_par_shi_name}}</td>
                <!--<td :class="sameCheck(value.shipment_delivery_date,value.mes_lis_acc_tra_dat_transfer_of_ownership_date)">{{ value.shipment_delivery_date }}</td>
                <td :class="sameCheck(value.shipment_delivery_date,value.mes_lis_acc_tra_dat_transfer_of_ownership_date)">{{value.mes_lis_acc_tra_dat_transfer_of_ownership_date}}</td>-->
                <td class="text-right" :class="sameCheck(value.mes_lis_shi_tot_tot_net_price_total,value.mes_lis_acc_tot_tot_net_price_total)">{{ zeroShow(value.mes_lis_shi_tot_tot_net_price_total) | priceFormat}}</td>
                <td class="text-right" :class="sameCheck(value.mes_lis_shi_tot_tot_net_price_total,value.mes_lis_acc_tot_tot_net_price_total)">{{zeroShow(value.mes_lis_acc_tot_tot_net_price_total) | priceFormat}}</td>
                <td><button @click="comparedItemList(value)" class="btn btn-primary">確認</button></td>
                <td class="text-right">{{zeroShow(value.mes_lis_inv_lin_det_amo_requested_amount) | priceFormat}}</td>
                <td><button @click="update_requested_invoice_amount(value)" class="btn btn-info" :disabled="is_disabled(!value.decision_datetime?true:false)">反映</button></td>
                <td>
                  <span v-if="value.check_datetime==null"><input type="checkbox" @click="update_check_date_time(value,1)" class="form-control"></span>
                  <span v-else><button @click="update_check_date_time(value,2)" class="btn btn-info">済</button></span>
                </td>
            </tr>
            <tr v-if="compareDataList && compareDataList.length==0">
                <td class="text-center" colspan="100%">データがありません</td>
            </tr>
          </tbody>

        </table>
        <div class="col-12 text-center">
        <button class="btn btn-primary" style="text-align:center" @click="closeInvoiceCompare">閉じる</button>
      </div>
      </div>
    </b-modal>

<b-modal
      size="xl"
      :hide-backdrop="true"
      title="出荷・受領比較（明細）"
      cancel-title="閉じる"
      v-model="invoiceitemDatalistModal"
      :hide-footer="true"
      :draggable="true"
      :no-enforce-focus="true"
    >
      <div class="panel-body">
      <div class="row">
        <div class="col-12">
          <p style="margin:0">差異が発生している伝票の明細が表示されています。</p>
          <p style="margin:0">黄色の項目は差異が発生している項目です。</p>
        </div>

      </div>
      <table
            class="table table-striped table-bordered order_item_details_table"
            style="overflow-x: scroll"
          >
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
          <tr v-for="(value,index) in compare_item_list" :key="index">
            <td>{{ value.mes_lis_shi_lin_lin_line_number }}</td>
            <td>{{ value.mes_lis_shi_lin_ite_order_item_code }}</td>
            <td>{{ value.mes_lis_shi_lin_ite_name }}</td>
            <td class="text-right" :class="sameCheck(value.mes_lis_shi_lin_qua_shi_quantity,value.mes_lis_acc_lin_qua_rec_quantity)">{{ value.mes_lis_shi_lin_qua_shi_quantity }}</td>
            <td class="text-right" :class="sameCheck(value.mes_lis_shi_lin_qua_shi_quantity,value.mes_lis_acc_lin_qua_rec_quantity)">{{ value.mes_lis_acc_lin_qua_rec_quantity }}</td>
            <td class="text-right" :class="sameCheck(value.mes_lis_shi_lin_amo_item_net_price,value.mes_lis_acc_lin_amo_item_net_price)">{{ zeroShow(value.mes_lis_shi_lin_amo_item_net_price) | priceFormat }}</td>
            <td class="text-right" :class="sameCheck(value.mes_lis_shi_lin_amo_item_net_price,value.mes_lis_acc_lin_amo_item_net_price)">{{ zeroShow(value.mes_lis_acc_lin_amo_item_net_price) | priceFormat }}</td>
          </tr>
          </tbody>

        </table>
        <div class="col-12 text-center">
          <button class="btn btn-primary" style="text-align:center" @click="closeComparedItemList">閉じる</button>
        </div>
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
        </tr>
        </thead>
<tbody>
        <tr v-for="(valueItm,index) in order_search_modal2List" :key="index" @click="setRowscodeIntoForm2(valueItm.mes_lis_inv_lin_tra_code)">
        <td>{{index+1}}</td>
          <td>{{valueItm.mes_lis_inv_lin_tra_code}}</td>
          <td><span v-if="valueItm.mes_lis_inv_lin_tra_name!=''">{{valueItm.mes_lis_inv_lin_tra_name}}</span><span v-else>{{valueItm.mes_lis_inv_lin_tra_name_sbcs}}</span></td>

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
       errors: [],
      invoice_detail_lists: {},
      invoice_detail_length: 0,
      byr_voucher_lists: {},
      editInvoiceDetailModal:false,
      addInvoiceDetailModal:false,
      invoiceCompareModal:false,
      invoiceitemDatalistModal:false,
      order_search_modal2:false,
      order_search_modal2List:[],
      invoice_lists_length:0,
      file: "",
      data_invoice_id: "",
      isCheckAll: false,
      selected: [],
      null_selected: [],
      not_null_selected: [],
      compareDataList: [],
      compare_item_list: [],
      date_null:false,
      null_selected_message:false,
      decision_datetime_status: ["未確定あり", "確定済"],
      send_datetime_status: ["未送信あり", "送信済"],
      payment_datetime_status: ["支払い済み", "未払い"],
      invoiceDetail:{
        data_invoice_pay_detail_id:'',
        data_invoice_id:'',
        mes_lis_inv_lin_det_transfer_of_ownership_date:'',
        mes_lis_inv_lin_det_goo_major_category:'',
        mes_lis_inv_lin_tra_code:'',
        mes_lis_inv_lin_lin_trade_number_reference:'',
        mes_lis_inv_lin_det_pay_code:'',
        mes_lis_inv_lin_det_balance_carried_code:'',
        requested_amount:'',
        mes_lis_inv_lin_tra_gln:'',
        mes_lis_inv_lin_sel_gln:'',
        mes_lis_inv_lin_sel_code:''
      },
      byr_buyer_id:null,
      adm_user_id: Globals.user_info_id,
      form: new Form({
        data_invoice_id: null,
        select_field_per_page_num: 10,
        page: 1,
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        data_count: false,
        send_data:false,
        param_data:[],
        from_date:'',
        to_date:'',
        mes_lis_inv_lin_tra_code:'',
        mes_lis_inv_lin_lin_trade_number_reference:'',
        decision_datetime_status:'*',
        category_code:{category_code:'*',category_name:'全て'},
        send_datetime_status:'*',
        payment_datetime_status:'*',
        sort_by:'data_invoice_pay_detail_id ',
        sort_type:"ASC",
        page_title:'invoice_details_list',
        shipment_ids:[]
      }),
    };
  },
  beforeCreate: function () {
    if (!this.$session.exists()) {
      this.$router.push("/home");
    }
  },
  methods: {
    setRowscodeIntoForm2(valCode){
        this.form.mes_lis_inv_lin_tra_code = valCode;
        this.order_search_modal2 = false;
      },
      deliverySearchForm2() {
        let loaders11111 = Vue.$loading.show();
      this.order_search_modal2 = true;
       this.$route.query.adm_user_id = Globals.user_info_id;
       this.$route.query.byr_buyer_id = this.byr_buyer_id;
      axios.post(this.BASE_URL + "api/get_voucher_detail_popup2_invoice", this.$route.query)
        .then(({ data }) => {
            this.order_search_modal2List = data.popUpList;
            loaders11111.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loaders11111.hide();
        // });
    },
    checkForm: function (e) {
      this.errors = [];
        if(!this.invoiceDetail.mes_lis_inv_lin_det_transfer_of_ownership_date){this.errors.push("計上日 フィールドは必須項目です")}
        //if(!this.invoiceDetail.mes_lis_inv_lin_det_goo_major_category){this.errors.push("部門コード フィールドは必須項目です")}
        if(!this.invoiceDetail.mes_lis_inv_lin_tra_code){this.errors.push("納品先コード フィールドは必須項目です")}
        if(!this.invoiceDetail.mes_lis_inv_lin_lin_trade_number_reference){this.errors.push("伝票番号 フィールドは必須項目です")}
        if(this.invoiceDetail.mes_lis_inv_lin_det_pay_code==''){this.errors.push("請求内容 フィールドは必須項目です")}
        if(this.invoiceDetail.mes_lis_inv_lin_det_balance_carried_code==''){this.errors.push("請求区分 フィールドは必須項目です")}
        if(!this.invoiceDetail.requested_amount){this.errors.push("請求金額 フィールドは必須項目です")}

      if (!this.errors.length) {
        return true;
      }
      return false;
    },
    invoiceCompareData(){
        // console.log(this.form);
      this.invoiceCompareModal = true;
      axios.post(this.BASE_URL + "api/invoice_compare_data", this.form)
        .then(({ data }) => {
            this.compareDataList = data.voucherList;
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loaders11111.hide();
        // });
    },
    compare_data_download(){
        axios.post(this.BASE_URL + "api/invoice_compare_data_download", this.form)
        .then(({ data }) => {
            this.downloadFromUrl(data);
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loaders11111.hide();
        // });
    },
    update_requested_invoice_amount(value){
      var _this = this;
      _this.alert_icon = "warning";
      _this.alert_title = "";
      _this.alert_text = "対象伝票の請求金額を受領原価金額合計に変更しますがよろしいでしょうか。";
      _this.yes_btn = "はい";
      _this.cancel_btn = "キャンセル";
        _this.confirm_sweet().then((result) => {
          if(result.isConfirmed){
              let loaderrr = Vue.$loading.show();
            axios.post(this.BASE_URL + "api/update_invoice_pay_details_amount",{item:value})
              .then(({ data }) => {
                _this.alert_icon = "success";
                _this.alert_title = "";
                _this.alert_text ="対象伝票の請求金額を変更しました。";
                _this.sweet_normal_alert();
                loaderrr.hide();
                 _this.invoiceCompareData();
                 _this.invoice_details();
              })
            //   .catch(() => {
            //     this.sweet_advance_alert();
            //     loaderrr.hide();
            // });
        }
        });

    },
update_check_date_time(value,action_type){
  let obj = {
    item:value,
    action_type:action_type
  }
axios.post(this.BASE_URL + "api/update_invoice_check_datetime", obj)
        .then(({ data }) => {
           this.invoiceCompareData();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loaderrr.hide();
        // });
},
    comparedItemList(value){
      this.invoiceitemDatalistModal = true;
      axios.post(this.BASE_URL + "api/invoice_compare_item", value)
        .then(({ data }) => {
            this.compare_item_list = data.compareItemList;
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loaderrr.hide();
        // });
    },
    closeComparedItemList(){
      this.invoiceitemDatalistModal = false;
    },
    closeInvoiceCompare(){
       this.invoiceCompareModal = false;
    },
    editInvoiceDetail(valuess){
      this.editInvoiceDetailModal = true;
      this.invoiceDetail = valuess;
      if(valuess.mes_lis_inv_lin_det_amo_req_plus_minus=='+'){
      this.invoiceDetail.requested_amount=valuess.mes_lis_inv_lin_det_amo_requested_amount;

      }else{
      this.invoiceDetail.requested_amount='-'+valuess.mes_lis_inv_lin_det_amo_requested_amount;
      }
    this.invoiceDetail.mes_lis_inv_lin_tra_gln=this.getbyrjsonValueBykeyName("invoice_pay_info","mes_lis_inv_lin_tra_gln","invoices");
    this.invoiceDetail.mes_lis_inv_lin_sel_gln=this.getbyrjsonValueBykeyName("invoice_pay_info","mes_lis_inv_lin_sel_gln","invoices");
    this.invoiceDetail.mes_lis_inv_lin_sel_code=this.param_data.pay_code;
     // console.log(valuess);
      // this.invoiceDetail.fill(value)
    },
    addInvoiceDetail(){
      this.addInvoiceDetailModal = true;
        this.invoiceDetail = {
          data_invoice_pay_detail_id:'',
        data_invoice_id:this.$route.query.data_invoice_id,
        mes_lis_inv_lin_det_transfer_of_ownership_date:'',
        mes_lis_inv_lin_det_goo_major_category:'',
        mes_lis_inv_lin_tra_code:'',
        mes_lis_inv_lin_lin_trade_number_reference:'',
        mes_lis_inv_lin_det_pay_code:'',
        mes_lis_inv_lin_det_balance_carried_code:'',
        requested_amount:'',
        mes_lis_inv_lin_tra_gln:this.getbyrjsonValueBykeyName("invoice_pay_info","mes_lis_inv_lin_tra_gln","invoices"),
        mes_lis_inv_lin_sel_gln:this.getbyrjsonValueBykeyName("invoice_pay_info","mes_lis_inv_lin_sel_gln","invoices"),
        mes_lis_inv_lin_sel_code:this.param_data.pay_code
        };
    },
    update_invoice_detail(){
      var _this = this;
      if(this.checkForm()){
      axios.post(this.BASE_URL + "api/update_invoice_detail", this.invoiceDetail)
        .then(({ data }) => {
            this.editInvoiceDetailModal = false;
            this.addInvoiceDetailModal = false;
           Fire.$emit("LoadByrinvoiceDetails",this.form.page);
           _this.alert_icon = "success";
                _this.alert_title = "";
                _this.alert_text = "請求伝票を追加しました";
                _this.sweet_normal_alert();
            })
            // .catch(() => {
            //     this.sweet_advance_alert();
            //     // loaderrr.hide();
            // });
        }
    },
    deleteInvoiceDetail(value){
      var _this = this;
      this.alert_icon = "warning";
      this.alert_title = "";
      this.alert_text = "対象の請求を削除しますがよろしいでしょうか?";
      this.yes_btn = "はい";
      this.cancel_btn = "キャンセル";

        this.confirm_sweet().then((result) => {
          if (result.value) {
            axios
              .post(this.BASE_URL + "api/delete_invoice_detail",value)
              .then(({ data }) => {
                  if (data.status==1) {
                     _this.alert_icon = "success";
                    _this.alert_title = "削除されました!";
                  }else{
                     _this.alert_icon = "error";
                    _this.alert_title = "削除されません!";
                  }
                _this.alert_text =data.message;
                _this.sweet_normal_alert();
                Fire.$emit("LoadByrinvoiceDetails",_this.form.page);
              }).catch(() => {
                  _this.alert_icon = "error";
                 _this.alert_title = "削除されません!";
                this.sweet_advance_alert();
                // loaderrr.hide();
                });
          }
        });
    },
    //get Table data
    invoice_details(page = 1) {
      let loader = Vue.$loading.show();
        this.form.page=page;
      axios.post(this.BASE_URL + "api/get_invoice_details_list", this.form)
        .then(({ data }) => {
            this.invoice_detail_lists = data.invoice_details_list;
            this.invoice_detail_length = this.invoice_detail_lists.data.length;
            this.invoice_lists_length = this.invoice_detail_lists.data.length;
            this.form.shipment_ids=data.shipment_ids;
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
          this.invoice_details();

      },
    checkAll() {
      this.isCheckAll = !this.isCheckAll;
      this.selected = [];
      this.null_selected = [];
      this.not_null_selected = [];
      if (this.isCheckAll) {
        for (var key in this.invoice_detail_lists.data) {
          if (this.invoice_detail_lists.data[key].decision_datetime) {
            this.not_null_selected.push(
              this.invoice_detail_lists.data[key].data_invoice_pay_detail_id
            );
          } else {
            this.null_selected.push(
              this.invoice_detail_lists.data[key].data_invoice_pay_detail_id
            );
          }
        }
      }

      if (this.null_selected.length <= this.form.select_field_per_page_num && this.null_selected.length != 0) {
        this.date_null = false;
        this.selected = this.null_selected;
        this.null_selected_message = true;
      } else if (this.not_null_selected.length <= this.form.select_field_per_page_num && this.not_null_selected.length != 0) {
        this.date_null = true;
        this.selected = this.not_null_selected;
        this.null_selected_message = false;
      }
      //   console.log(this.selected);
    },
    updateCheckall() {
      // console.log(this.selected)
      if (this.selected.length == this.invoice_detail_lists.data.length) {
        this.isCheckAll = true;
      } else {
        this.isCheckAll = false;
      }
      this.null_selected = this.selected;
      this.null_selected_message = true;
      this.date_null = false;
    },
    decissionDateUpdate(data_invoice_pay_detail_id) {
      if (this.isCheckAll) {
        this.alert_text =
          "対象となる請求確定を取消しますがよろしいでしょうか。";
        this.selected = this.null_selected.concat(this.not_null_selected);
      } else {
        this.selected.push(data_invoice_pay_detail_id);
        this.alert_text = "請求確定を取消しますがよろしいでしょうか。";
      }
      this.date_null = true;
      this.null_selected_message = false;
      this.updateDecissionDateTime();
    },
    updateDecissionDateTime() {
      var _this = this;
      this.alert_icon = "warning";
      this.alert_title = "";
      this.yes_btn = "はい";
      this.cancel_btn = "キャンセル";
      this.selectedNum = this.selected.length;
      if (this.selectedNum > 0) {
        this.confirm_sweet().then((result) => {
          if (result.value) {
            // console.log(this.selected);
            //   return 0;
            axios.post(
                this.BASE_URL + "api/update_invoice_decession_datetime",
                { update_id: this.selected, date_null: this.date_null }
              )
              .then(({ data }) => {
                _this.alert_icon = "success";
                _this.alert_title = "";
                _this.alert_text =
                  _this.selectedNum + "件の請求を確定しました。";
                if (!this.null_selected_message) {
                  _this.alert_text = "請求確定を取消しました。";
                }
                _this.sweet_normal_alert();
                this.invoice_details()
                // Fire.$emit("LoadByrorderDetail",_this.form.page);
                this.selected = [];
                // this.date_null = false;
                this.isCheckAll = false;
                this.null_selected_message = false;
              })
              // .catch(() => {
              //       this.sweet_advance_alert();
              //       // loader.hide();
              //   });
          } else {
            this.selected = [];
            this.isCheckAll = false;
            this.null_selected_message = false;
          }
        });
      } else {
        this.null_selected_message = false;
        this.alert_text = "対象となる請求がありません。再度確認して実行してください。";
        this.sweet_normal_alert();
      }
    },
    updateDatetimeDecessionfield() {
      if (this.null_selected.length > 0) {
        this.alert_text =
          this.selected.length + "件の請求を確定しますがよろしいでしょうか。";
        this.updateDecissionDateTime();
      } else {
        this.alert_icon = "warning";
        this.alert_title = "";
        this.alert_text = "対象となる請求がありません。再度確認して実行してください。";
        this.sweet_normal_alert();
      }
    },
    sendInvoiceData() {
      var _this = this;
      this.alert_icon = "warning";
      this.alert_title = "";
      this.yes_btn = "はい";
      this.cancel_btn = "キャンセル";
      this.form.data_count=true;
      axios.post(this.BASE_URL + "api/send_invoice_data", this.form).then(({ data }) => {
          let csv_data_count = data.csv_data_count;
          if (csv_data_count > 0) {
            _this.alert_text = csv_data_count + "件の請求を送信しますがよろしいでしょうか。";
            this.confirm_sweet().then((result) => {
              if (result.value) {
                  this.form.data_count=false;
                  this.form.send_data=true;
                axios.post(this.BASE_URL + "api/send_invoice_data", this.form)
                  .then(({ data }) => {
                    _this.alert_icon = "success";
                    _this.alert_title = "";
                    _this.alert_text =
                      data.csv_data_count + "件の確定請求を送信しました。";
                    _this.sweet_normal_alert();
                    this.form.send_data=false;
                    Fire.$emit("LoadByrinvoiceDetails",_this.form.page);
                  });
              }
            });
          } else {
            _this.alert_text = "対象となる請求がありません。再度確認して実行してください。";
            this.form.data_count=false;
            this.form.send_data=false;
            _this.sweet_normal_alert();
          }
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loader.hide();
        // });
    },
    invoice_download(downloadType = 1) {
      //downloadcsvshipment_confirm
      this.form.downloadType=downloadType
      axios.post(this.BASE_URL + "api/download_invoice",this.form)
        .then(({ data }) => {
           this.downloadFromUrl(data);
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loader.hide();
        // });
    },
    sameCheck(value1,value2){
        if(value1!=value2){
            return 'same_yellow';
        }
    }
  },

  created() {
        this.form = this.$store.getters['invoiceDetailsModule/getFormData'];
        this.byr_buyer_id=this.$session.get("byr_buyer_id")
        Fire.$emit("byr_has_selected", this.byr_buyer_id);
        Fire.$emit("permission_check_for_buyer", this.byr_buyer_id);
        this.param_data = this.$route.query;
        this.form.param_data = this.param_data;
        this.form.data_invoice_id = this.param_data.data_invoice_id;
        this.invoiceDetail.data_invoice_id = this.param_data.data_invoice_id;
        this.getbuyerJsonSettingvalue();

        this.form.byr_buyer_id = this.byr_buyer_id;
        this.invoice_details(this.form.page);
        Fire.$on("LoadByrinvoiceDetails", (page=this.form.page) => {
        this.invoice_details(page);
        });
        Fire.$emit('loadPageTitle','請求伝票一覧')
  },
  computed: {

  },
  mounted() {
  },
};
</script>
<style>
.same_yellow{
    background: yellow;
}
</style>
