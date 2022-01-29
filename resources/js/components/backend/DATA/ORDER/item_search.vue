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
            <td style="width:10%" class="cl_custom_color">受信日時</td>
            <td style="width:15%">
            <span v-if="order_info && Object.keys(this.order_info).length">
            {{ order_info.receive_datetime }}
            </span>
            </td>
            <td style="width:10%" class="cl_custom_color">取引先</td>
            <td style="width:10%">
            <span v-if="order_info && Object.keys(this.order_info).length">
             {{ order_info.mes_lis_shi_par_sel_code }}
              {{ order_info.mes_lis_shi_par_sel_name }}
              </span>
            </td>
            <td style="width:10%" class="cl_custom_color">便</td>
            <td style="width:15%"><span v-if="order_info && Object.keys(this.order_info).length">{{getbyrjsonValueBykeyName('mes_lis_ord_log_del_delivery_service_code',order_info.mes_lis_shi_log_del_delivery_service_code,'orders')}}</span></td>
          </tr>
          <tr>
            <td style="width:10%" class="cl_custom_color">納品日</td>
            <td style="width:15%">
            <span v-if="order_info && Object.keys(this.order_info).length">
            {{ order_info.mes_lis_shi_tra_dat_delivery_date_to_receiver }}
            </span>
            </td>
            <td style="width:10%" class="cl_custom_color">部門</td>
            <td style="width:15%">
            <span v-if="order_info && Object.keys(this.order_info).length">
            {{ order_info.mes_lis_shi_tra_goo_major_category }}
            </span>
            </td>

            <td style="width:10%" class="cl_custom_color">温度区分</td>
            <td style="width:15%"><span v-if="order_info && Object.keys(this.order_info).length">{{ order_info.mes_lis_shi_tra_ins_temperature_code }} {{getbyrjsonValueBykeyName('mes_lis_ord_tra_ins_temperature_code',order_info.mes_lis_shi_tra_ins_temperature_code,'orders')}}</span></td>
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
            <td class="cl_custom_color">商品コード</td>
            <td>
              <input type="text" class="form-control topHeaderInputFieldBtn"/>
              <button @click="searchBypopupmodal" class="btn btn-primary active">参照</button>
            </td>
            <td class="cl_custom_color">JANコード</td>
            <td>
              <input type="text" class="form-control topHeaderInputFieldBtn" v-model="form.mes_lis_shi_lin_ite_gtin" />
            </td>
            </tr>
        </table>
      </div>
      </div>

      <div class="col-12" style="text-align: center">
        <button class="btn btn-primary active srchBtn" type="button" @click="getItemSearchData()">
          {{ myLang.search }}
        </button>
      </div>
      <div class="col-12">
        <br />
        <h4 class="page_custom_title">{{ myLang.search_result }}</h4>
      </div>


    </div>
    <hr />
    <div class="row">
      <div class="col-12">
      <p>
              <span class="tableRowsInfo"
                >{{ order_item_lists.from }}〜{{
                  order_item_lists.to
                }}
                件表示中／全：{{ order_item_lists.total }}件</span
              >
              <span class="pagi"
                >
              <advanced-laravel-vue-paginate :data="order_item_lists"
              :onEachSide="2"
              previousText="<"
              nextText=">"
              alignment="center"
                @paginateTo="getItemSearchData"/>
              </span>
              <span class="selectPagi">
                <select class="form-control selectPage" @change="getItemSearchData()"
                  v-model="form.per_page">
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
        <div class="">
          <table
            class="table table-striped table-bordered order_item_details_table"
            style="overflow-x: scroll"
          >
            <thead>

              <tr>
                <th>No</th>
                <th class="pointer_class" @click="sorting('mes_lis_shi_lin_ite_order_item_code')">商品コード <span class="float-right" :class="iconSet('mes_lis_shi_lin_ite_order_item_code')"></span></th>
                <th class="pointer_class" @click="sorting('mes_lis_shi_lin_ite_gtin')">JANコード <span class="float-right" :class="iconSet('mes_lis_shi_lin_ite_gtin')"></span></th>
                <th class="pointer_class" @click="sorting('mes_lis_shi_lin_ite_name')">商品名 <span class="float-right" :class="iconSet('mes_lis_shi_lin_ite_name')"></span></th>
                <th class="pointer_class" @click="sorting('mes_lis_shi_lin_ite_ite_spec')">規格 <span class="float-right" :class="iconSet('mes_lis_shi_lin_ite_ite_spec')"></span></th>
                <th class="pointer_class" @click="sorting('mes_lis_shi_lin_fre_field_name')">産地 <span class="float-right" :class="iconSet('mes_lis_shi_lin_fre_field_name')"></span></th>

              </tr>
            </thead>
            <tbody>
              <tr v-for="(order_detail_list, index) in order_item_lists.data" :key="index">
                <td>
                  {{ order_item_lists.current_page * form.per_page - form.per_page + index + 1 }}
                </td>

                <td><router-link
                    :to="{
                      name: 'item_search_detail',
                      query: {
                      data_order_id: order_info.data_order_id,
                      delivery_date: order_info.mes_lis_shi_tra_dat_delivery_date_to_receiver,
                      major_category:order_info.mes_lis_shi_tra_goo_major_category,
                      delivery_service_code:order_info.mes_lis_shi_log_del_delivery_service_code,
                      temperature_code:order_info.mes_lis_shi_tra_ins_temperature_code,
                      item_code:order_detail_list.mes_lis_shi_lin_ite_order_item_code,
                    },
                    }" class="">
                  {{ order_detail_list.mes_lis_shi_lin_ite_order_item_code }}
                  </router-link>
                </td>
                <td>{{ order_detail_list.mes_lis_shi_lin_ite_gtin }}</td>
                <td>{{ order_detail_list.mes_lis_shi_lin_ite_name }}</td>
                <td>{{ order_detail_list.mes_lis_shi_lin_ite_ite_spec }}</td>
                <td>{{ order_detail_list.mes_lis_shi_lin_fre_field_name }}</td>
              </tr>
              <tr v-if="order_item_lists.data && order_item_lists.data.length==0">
                <td class="text-center" colspan="6">データがありません</td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-12">

      </div>
    </div>

    <b-modal
      size="lg"
      :hide-backdrop="true"
      title="商品コード"
      ok-title="検　索"
      cancel-title="閉じる"
      @ok.prevent="searchItemDetail()"
      v-model="order_search_modal3"
      :no-enforce-focus="true"
    >
      <div class="panel-body">
        <table
          class="table orderTopDetailTable table-bordered"
          style="width: 100%"
        >
          <tr>
            <td class="cl_custom_color">商品コード（発注用）</td>
            <td><input type="text" class="form-control" v-model="form.mes_lis_shi_lin_ite_supplier_item_code"/></td>
            <td class="cl_custom_color">JANコード</td>
            <td>
              <input type="text" class="form-control" v-model="form.mes_lis_shi_lin_ite_gtin"/>
            </td>
          </tr>

          <tr>
            <td class="cl_custom_color">商品名</td>
            <td colspan="3"><input type="text" v-model="form.mes_lis_shi_lin_ite_name" class="form-control"/></td>
          </tr>
          <tr>
            <td class="cl_custom_color">規格</td>
            <td colspan="3"><input type="text" v-model="form.mes_lis_shi_lin_ite_ite_spec" class="form-control"/></td>
          </tr>
          <tr>
            <td class="cl_custom_color">取引先コード</td>
            <td><input type="text" class="form-control"/></td>
            <td class="cl_custom_color">納品先コード</td>
            <td>
              <input type="text" class="form-control"/>
            </td>
          </tr>
          <tr>
            <td class="cl_custom_color">部門</td>
            <td>
            <select class="form-control" v-model="form.deliveryDestnation" style="width: 220px">
              <option value="">全て</option>
                <option :value="item" v-for="(item,i) in deliveryDestnationOptionList" :key="i">{{ item }}</option>
              </select>
            </td>
            <td class="cl_custom_color">不定貴区分</td>
            <td>
              <select class="form-control" v-model="form.mes_lis_shi_tra_fre_variable_measure_item_code" style="width: 220px">
              <option value="*">全て</option>
                <option :value="item" v-for="(item,i) in deliveryDestnationOptionList" :key="i">{{ item }}</option>
              </select>
            </td>

          </tr>
        </table>
      </div>
    </b-modal>
  </div>
</template>
<script>
import AdvancedLaravelVuePaginate from 'advanced-laravel-vue-paginate';
import 'advanced-laravel-vue-paginate/dist/advanced-laravel-vue-paginate.css'

export default {
  components: {
        AdvancedLaravelVuePaginate
    },
    breadcrumb(){
    return {
    label: this.breadcumbtitle,
    parent: this.parent
  }


},

  data() {
    return {
        breadcumbtitle:'受注商品別一覧',
        parent: { name: 'order_list_details', query: {}},
        order_info:[],
        item_search_query:[],
        order_search_modal3:false,
        deliveryDestnationOptionList:{},
        order_item_lists:{},
        form: {
            mes_lis_shi_lin_ite_gtin:null,
            mes_lis_shi_lin_ite_order_item_code:null,
            per_page:10,
            data_order_id:null,
            page:1,
            sort_by:'mes_lis_shi_lin_ite_order_item_code ',
            sort_type:"ASC",
            order_info:[],
        },
    };
  },
  beforeCreate: function() {
            if (!this.$session.exists()) {
                this.$router.push('/home');
            }
        },
  methods: {
      sorting(sorted_field){
          this.form.sort_by=sorted_field;
          this.form.sort_type=this.form.sort_type=="ASC"?"DESC":"ASC";
          this.getItemSearchData();

      },
      searchBypopupmodal(){
        this.order_search_modal3= true;
      },
    //get Table data
    getItemSearchData(page = 1) {
      let loader = Vue.$loading.show();
        this.form.page=page;
        axios.post(this.BASE_URL + "api/get_all_shipment_item_by_search", this.form)
        .then(({ data }) => {
            // console.log(data);
            this.order_item_lists = data.order_item_lists;
            // this.order_info = data.order_info;
            loader.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loader.hide();
        // });
    },



  },

  created() {
    Fire.$emit("byr_has_selected", this.$session.get("byr_buyer_id"));
    Fire.$emit("permission_check_for_buyer", this.$session.get("byr_buyer_id"));
   // this.loader = Vue.$loading.show();
    this.$session.set("order_item_search_query",this.$route.query)
    this.order_info=this.$session.get("order_info");
    this.order_info['data_order_id']=this.$route.query.data_order_id;
    this.form = this.$store.getters['itemSearchModule/getFormData'];
    this.form.order_info=this.order_info;


    this.getbuyerJsonSettingvalue();
    this.getItemSearchData(this.form.page);
    Fire.$on("getItemSearchData", (page=this.form.page) => {
      this.getItemSearchData(page);
    });
    // this.item_search_query = this.$route.query;
    this.parent.query = this.$session.get('order_param_data');
    Fire.$emit("loadPageTitle", "受注商品別一覧");

  },
  mounted() {
  },
};
</script>
