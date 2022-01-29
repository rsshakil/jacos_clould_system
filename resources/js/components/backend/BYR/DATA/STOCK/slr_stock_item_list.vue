<template>
  <div class="row" v-can="['byr_view', 'slr_view']">
    <div class="col-12" style="background: #d8e3f0; padding: 10px">
        <table class="table orderDetailTable cmnWidthTable table-bordered" style="width: 100%">
        <tr>
          <td class="cl_custom_color">
           納品日
          </td>
          <td>
            <div class="input-group">
                <input type="date" class="form-control" v-model="form.date_to_receiver_from">
                <div class="input-group-prepend">
                    <span class="input-group-text">~</span>
                </div>
                <input type="date" class="form-control" v-model="form.date_to_receiver_to">
            </div>
            <p style="margin-top:10px;">
                <button class="btn btn-primary" @click="dateUpdate(7)">
                    1週間
                </button>
                <button class="btn btn-primary"  @click="dateUpdate(30)">
                    1ヶ月
                </button>
                <button class="btn btn-primary"  @click="resetDate()">
                    すべて
                </button>
            </p>

          </td>
          <td class="cl_custom_color">
            商品コード
          </td>
          <td>
            <input type="text" v-model="form.item_code" class="form-control" style="float: left; max-width: 170px; margin-right: 5px" placeholder="商品コード"/>
            <button class="btn btn-primary" style="float:left;" @click="showAllItemCode">
              {{ myLang.refer }}
            </button>
          </td>
        </tr>
        <tr v-role="['Byr']">
           <td class="cl_custom_color">
            取引先コード
          </td>
          <td>
            <input type="text" v-model="form.partner_code" class="form-control" style="float: left; max-width: 170px; margin-right: 5px" placeholder="取引先コード"/>
            <button class="btn btn-primary" style="float:left;" @click="showAllPartnerCode">
              {{ myLang.refer }}
            </button>
          </td>
        </tr>

      </table>
    </div>
    <div class="col-12" style="text-align: center">
      <button class="btn btn-primary active srchBtn" type="button" @click="getAllNoStockItem()">
        {{ myLang.search }}
      </button>
    </div>
    <div class="col-12 text-center page_c_title_bar text-sm-left mb-0">
      <h4 class="page_custom_title">検索結果：一覧</h4>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-5">
                <p>
              <span class="tableRowsInfo">{{ stock_items.from }}〜{{ stock_items.to }} 件表示中／全：{{ stock_items.total }}件</span>
              <span class="pagi">
              <advanced-laravel-vue-paginate :data="stock_items"
              :onEachSide="2"
              previousText="<"
              nextText=">"
              alignment="center"
                @paginateTo="getAllNoStockItem"/>
              </span>
              <span class="selectPagi">
                <select class="form-control selectPage"
                   v-model="form.per_page" @change="getAllNoStockItem">
                <option value="10">10行</option>
                <option value="20">20行</option>
                <option value="50">50行</option>
                <option value="100">100行</option>
                </select>
              </span>
            </p>
            </div>
            <!-- <div class="col-5 p-3" style="background-color:#d8e3f0; border-radius:1rem;margin-bottom:15px;">
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
                    :disabled="is_disabled(stock_items_length>=1?true:false)"
                  >
                    <b-icon
                      icon="download"
                      animation="fade"
                      font-scale="1.2"
                    ></b-icon>
                    ダウンロード
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <button class="dropdown-item" @click="orderDownload(1)" type="button">
                      CSV
                    </button>
                    <button class="dropdown-item" @click="orderDownload(2)" type="button">
                      JCA
                    </button>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <p class="mb-0">帳票（PDF）の印刷はこちら</p>
                <b-form inline>
                  <label class="sr-only" for="inline-form-input-name">各種帳票の印刷はこちら</label>
                  <select class="mb-2 mr-sm-2 mb-sm-0 form-control" v-model="form.shipment_download_type" :disabled="is_disabled(stock_items_length>=1?true:false)">
                    <option value="order_pdf">発注明細書</option>
                    <option value="picking_pdf">ピッキング表</option>
                  </select>
                  <b-button class="active" variant="primary" @click="pdfDownload()" :disabled="is_disabled(stock_items_length>=1?true:false)">印刷</b-button>
                </b-form>
              </div>
            </div>
          </div> -->

</div>
</div>
<div class="col-12">
      <div class="">
        <table class="table table-striped order_item_details_table table-bordered">
          <thead>
            <tr>
              <th style="cursor: pointer">No</th>
              <th class="pointer_class" @click="sorting('mes_lis_shi_lin_ite_name')">商品名 <span class="float-right" :class="iconSet('mes_lis_shi_lin_ite_name')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_shi_lin_ite_order_item_code')">商品コード <span class="float-right" :class="iconSet('mes_lis_shi_lin_ite_order_item_code')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_shi_tra_trade_number')">伝票数 <span class="float-right" :class="iconSet('mes_lis_shi_tra_trade_number')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_shi_lin_qua_ord_quantity')">受注数 <span class="float-right" :class="iconSet('mes_lis_shi_lin_qua_ord_quantity')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_shi_lin_amo_item_net_price_unit_price')">原単価 <span class="float-right" :class="iconSet('mes_lis_shi_lin_amo_item_net_price_unit_price')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_shi_lin_amo_item_net_price')">原価合計 <span class="float-right" :class="iconSet('mes_lis_shi_lin_amo_item_net_price')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_shi_lin_qua_sto_quantity')">欠品数 <span class="float-right" :class="iconSet('mes_lis_shi_lin_qua_sto_quantity')"></span></th>
              <th class="pointer_class" @click="sorting('percentage')">欠品率 <span class="float-right" :class="iconSet('percentage')"></span></th>
              <th class="pointer_class" @click="sorting('partner_code')">取引先コード <span class="float-right" :class="iconSet('partner_code')"></span></th>
              <th class="pointer_class" @click="sorting('seller_name')">取引先名 <span class="float-right" :class="iconSet('seller_name')"></span></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(stock_item, index) in stock_items.data" :key="index">
                <td>{{ stock_items.current_page * form.per_page - form.per_page + index + 1 }}</td>
                <td>{{ stock_item.mes_lis_shi_lin_ite_name }}</td>
                <td>{{ stock_item.mes_lis_shi_lin_ite_order_item_code }}</td>
                <td>{{ stock_item.mes_lis_shi_tra_trade_number }}</td>
                <td>{{ stock_item.mes_lis_shi_lin_qua_ord_quantity }}</td>
                <td class="text-right">{{ number_format(stock_item.mes_lis_shi_lin_amo_item_net_price_unit_price) }}</td>
                <td class="text-right">{{ number_format(stock_item.mes_lis_shi_lin_amo_item_net_price) }}</td>
                <td>{{ stock_item.mes_lis_shi_lin_qua_sto_quantity }}</td>
                <td class="text-right">{{ stock_item.percentage }}%</td>
                <td>{{ stock_item.partner_code }}</td>
                <td>{{ stock_item.seller_name }}</td>
            </tr>

            <tr v-if="(stock_items.data && stock_items_length==0)">
            <td colspan="100%" style="text-align:center">データがありません</td>
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
      v-model="itemCodeModal"
      :hide-footer="true"
      :no-enforce-focus="true"
    >
      <div class="panel-body add_item_body">

          <div class="row">
  <table class="table table-striped order_item_details_table order_list_partner_code_modal table-bordered data_table">
          <thead>
            <tr>
              <th style="cursor: pointer">No</th>
              <th>商品名</th>
              <th>商品コード</th>
            </tr>
          </thead>
          <tbody>
          <tr v-for="(value,index) in item_code_lists" @click="onRowClicked(value)" :key="index">
          <td>{{index+1}}</td>
          <td>{{value.mes_lis_shi_lin_ite_name}}</td>
          <td>{{value.mes_lis_shi_lin_ite_order_item_code}}</td>
          </tr>
          <tr v-if="(item_code_lists && item_code_lists.length==0)">
            <td colspan="100%" style="text-align:center">データがありません</td>
            </tr>
          </tbody>
          </table>
          </div>
      </div>
    </b-modal>
    <b-modal
      size="lg"
      :hide-backdrop="true"
      title="取引先コード"
      cancel-title="閉じる"
      v-model="partnerCodeModal"
      :hide-footer="true"
      :no-enforce-focus="true"
    >
      <div class="panel-body add_item_body">

          <div class="row">
  <table class="table table-striped order_item_details_table order_list_partner_code_modal table-bordered data_table">
          <thead>
            <tr>
              <th style="cursor: pointer">No</th>
              <th>商品名</th>
              <th>商品コード</th>
            </tr>
          </thead>
          <tbody>
          <tr v-for="(value,index) in partner_code_lists" @click="onPartnerRowClicked(value)" :key="index">
          <td>{{index+1}}</td>
          <td>{{value.mes_lis_shi_par_sel_name}}</td>
          <td>{{value.mes_lis_shi_par_sel_code}}</td>
          </tr>
          <tr v-if="(partner_code_lists && partner_code_lists.length==0)">
            <td colspan="100%" style="text-align:center">データがありません</td>
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
        stock_items:{},
        stock_items_length:0,
        itemCodeModal:false,
        partnerCodeModal:false,
        item_code_lists: [],
        partner_code_lists: [],
        form:{
            adm_user_id: Globals.user_info_id,
            byr_buyer_id:null,
            data_order_id: null,
            date_to_receiver_from:null,
            date_to_receiver_to: new Date().toJSON().slice(0,10),
            item_code:null,
            partner_code:null,
            per_page:10,
            page:1,
            sort_by: 'mes_lis_shi_lin_ite_order_item_code ',
            sort_type: "ASC",
            page_title: 'stock_item_list',
        }
    };
  },
  methods: {
      getAllNoStockItem(page){
        this.loader = Vue.$loading.show();
        this.form.page=page;
        axios.post(this.BASE_URL + "api/slr_get_stock_item_list", this.form)
            .then(({ data }) => {
                this.stock_items=data.stock_items;
                this.stock_items_length=data.stock_items.data.length;
                this.loader.hide();
            })
            // .catch(() => {
            //     this.sweet_advance_alert();
            //     this.loader.hide();
            // });
      },
      dateUpdate(sub_day){
        //   var myCurrentDate=new Date();
        this.form.date_to_receiver_to = new Date().toJSON().slice(0,10);
        var myPastDate=new Date(this.form.date_to_receiver_to);
        myPastDate.setDate(myPastDate.getDate() - sub_day);
        this.form.date_to_receiver_from=myPastDate.toJSON().slice(0,10);
        // d.setMonth(d.getMonth() - 1); //one month ago
        // console.log(myPastDate)
      },
      resetDate(){
          this.form.date_to_receiver_to =null;
          this.form.date_to_receiver_from=null;
      },
      sorting(sorted_field){
        this.form.sort_by=sorted_field;
        this.form.sort_type=this.form.sort_type=="ASC"?"DESC":"ASC";
        this.getAllNoStockItem(this.form.page);

      },
      showAllItemCode(){
        let loaders = Vue.$loading.show();
        this.itemCodeModal = true;
        axios.post(this.BASE_URL + "api/slr_get_stock_item_code_list", this.form)
            .then(({ data }) => {
            this.item_code_lists = data.stockItemCodeList;
            loaders.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loaders.hide();
        // });
      },
      showAllPartnerCode(){
        let loaders = Vue.$loading.show();
        this.partnerCodeModal = true;
        axios.post(this.BASE_URL + "api/slr_get_partner_code_list", this.form)
            .then(({ data }) => {
            this.partner_code_lists = data.partner_code_lists;
            loaders.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loaders.hide();
        // });
      },
      onRowClicked(value){
          this.form.item_code=value.mes_lis_shi_lin_ite_order_item_code
          this.itemCodeModal = false;
      },
      onPartnerRowClicked(value){
          this.form.partner_code=value.mes_lis_shi_par_sel_code
          this.partnerCodeModal = false;
      },

  },

  mounted() {
      this.form = this.$store.getters['slrStockItemModule/getFormData'];
      this.form.byr_buyer_id=this.$session.get("slr_byr_buyer_id")
      this.getAllNoStockItem(this.form.page)
  },
};
</script>
