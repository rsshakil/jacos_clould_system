<template>
  <div class="row">
    <div class="col-12">
      <!-- <h4 class="top_title text-center" style="margin-top:10px;">{{myLang.receive_list_head}}</h4>-->
      <div class="col-12" style="background: #d8e3f0; padding: 10px">
        <table
          class="table orderDetailTable cmnWidthTable table-bordered"
          style="width: 100%"
        >
          <tr>
            <td class="cl_custom_color">受信日</td>
            <td>
             <div class="input-group">
                    <input type="date" class="form-control" v-model="form.receive_date_from">
                    <div class="input-group-prepend">
                        <span class="input-group-text">~</span>
                    </div>
                    <input type="date" class="form-control" v-model="form.receive_date_to">
                </div>

            </td>

            <td class="cl_custom_color">
              {{ myLang.customer_code }}
            </td>
            <td>
              <input type="text" v-model="form.sel_code" class="form-control" style="float: left; width: 110px; margin-right: 15px" />
              <button @click="showAllCustomerCode" class="btn btn-primary" style="float:left;">
              {{ myLang.refer }}
            </button>

            </td>
           <td class="cl_custom_color">便</td>
            <td>
              <select class="form-control" v-model="form.delivery_service_code">
                <option value="*">全て</option>
                <option v-for="(dsc, i) in json_delivery_service_codeList" :key="i" :value="i">
                {{ dsc}}
              </option>
              </select>
            </td>
          </tr>
          <tr>
            <td class="cl_custom_color">納品日</td>
            <td>
             <div class="input-group">
                    <input type="date" class="form-control" v-model="form.ownership_date_from">
                    <div class="input-group-prepend">
                        <span class="input-group-text">~</span>
                    </div>
                    <input type="date" class="form-control" v-model="form.ownership_date_to">
                </div>

            </td>
            <td class="cl_custom_color">部門</td>
            <td>
                <multiselect v-model="form.major_category" :options="byr_buyer_category_lists" label="category_name" track-by="category_code" :searchable="true" :close-on-select="true" :clear-on-select="true" :select-label="''" :deselect-label="''" :selected-label="'選択中'" :preserve-search="true" placeholder="部門"><span slot="noOptions">候補がありません</span> <span slot="noResult">候補がありません</span></multiselect>
            </td>
            <td class="cl_custom_color">温度区分</td>
            <td>
              <select class="form-control" v-model="form.temperature_code">
                <option value="*">全て</option>
                <option
                v-for="(temp, i) in json_temperature_codeList"
                :key="i" v-if="temp!='' " :value="temp">
                {{ temp }}
              </option>
              </select>
            </td>
          </tr>
          <tr>
            <td class="cl_custom_color">参照状況</td>
            <td>
              <select class="form-control" v-model="form.check_datetime">
              <option value="*">全て</option>
              <option value="1">未参照</option>
              <option value="2">参照済</option>
            </select>
            </td>
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
    <div class="col-12" style="text-align: center">
      <button class="btn btn-primary active srchBtn" type="button" @click="getAllReceivedItem()">
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
              <span class="tableRowsInfo"
                >{{ received_item_list.from }}〜{{
                  received_item_list.to
                }}
                件表示中／全：{{ received_item_list.total }}件</span
              >
              <span class="pagi">
                <advanced-laravel-vue-paginate
                  :data="received_item_list"
                  :onEachSide="2"
                  previousText="<"
                  nextText=">"
                  alignment="center"
                  @paginateTo="getAllReceivedItem"
                />
              </span>
              <span class="selectPagi">
                <select
                  @change="getAllReceivedItem()"
                  v-model="form.select_field_per_page_num"
                  class="form-control selectPage"
                >
                  <option value="10">10行</option>
                  <option value="20">20行</option>
                  <option value="50">50行</option>
                  <option value="100">100行</option>
                </select>
              </span>
            </p>
          </div>
          <div class="col-3 p-3" style="background-color:#d8e3f0; border-radius:1rem;margin-bottom:15px;">
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
                    :disabled="is_disabled(received_item_length>=1?true:false)"
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
                    <!--<button class="dropdown-item" @click="receive_download(2)" type="button" :disabled="is_disabled(received_item_length>=1?true:false)">
                      JCA
                    </button>-->
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    <div class="col-12">
      <div class="">
        <table class="table table-striped table-bordered order_item_details_table data_table">
          <thead>
            <tr>
              <th class="pointer_class">No</th>
              <th class="pointer_class" @click="sorting('receive_datetime')">受信日時 <span class="float-right" :class="iconSet('receive_datetime')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_acc_par_sel_code')">取引先 <span class="float-right" :class="iconSet('mes_lis_acc_par_sel_code')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_acc_tra_dat_transfer_of_ownership_date')">納品日 <span class="float-right" :class="iconSet('mes_lis_acc_tra_dat_transfer_of_ownership_date')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_acc_tra_goo_major_category')">部門 コード <span class="float-right" :class="iconSet('mes_lis_acc_tra_goo_major_category')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_acc_log_del_delivery_service_code')">便 <span class="float-right" :class="iconSet('mes_lis_acc_log_del_delivery_service_code')"></span></th>
              <th class="pointer_class" @click="sorting('mes_lis_acc_tra_ins_temperature_code')">温度区分 <span class="float-right" :class="iconSet('mes_lis_acc_tra_ins_temperature_code')"></span></th>
              <th class="pointer_class" >伝票枚数</th>
              <th class="pointer_class" @click="sorting('check_datetime')">参照状況 <span class="float-right" :class="iconSet('check_datetime')"></span></th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(received_item, index) in received_item_list.data"
              :key="index"
            >
              <td>{{ index + 1 }}</td>
              <td>
                  <button @click="goToDetailsPage('receive_detail',received_item)" class="btn btn-link btn-link-custom">
                      {{ received_item.receive_datetime }}
                  </button>
              <!-- <router-link
                    :to="{
                      name: 'receive_detail',
                      query: {
                        data_receive_id: received_item.data_receive_id,
                          sel_name:received_item.mes_lis_acc_par_sel_name,
                          sel_code:received_item.mes_lis_acc_par_sel_code,
                          major_category:received_item.mes_lis_acc_tra_goo_major_category,
                          delivery_service_code:received_item.mes_lis_acc_log_del_delivery_service_code,
                          ownership_date:received_item.mes_lis_acc_tra_dat_transfer_of_ownership_date
                      },
                    }"
                    class=""
                    >
              {{ received_item.receive_datetime }}
              </router-link> -->
              </td>
              <td>{{ received_item.mes_lis_acc_par_sel_code }} {{ received_item.mes_lis_acc_par_sel_name }}</td>
              <td>{{ received_item.mes_lis_acc_tra_dat_transfer_of_ownership_date }}</td>
              <td>{{ received_item.mes_lis_acc_tra_goo_major_category }}</td>
              <td>
              {{
                getbyrjsonValueBykeyName(
                  "mes_lis_ord_log_del_delivery_service_code",
                  received_item.mes_lis_acc_log_del_delivery_service_code,
                  "orders",
                  buyer_settings
                )
              }}
              </td>
              <td>
                  {{
                    received_item.mes_lis_acc_tra_ins_temperature_code
                  }}
                  {{
                    getbyrjsonValueBykeyName(
                      "mes_lis_ord_tra_ins_temperature_code",
                      received_item.mes_lis_acc_tra_ins_temperature_code,
                      "orders",buyer_settings
                    )
                  }}
              </td>

              <td>{{ received_item.cnt }}</td>
              <td>{{ received_item.check_datetime }}</td>
            </tr>
            <tr v-if="received_item_list.data && received_item_list.data.length==0">
                <td colspan="10">データがありません</td>
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
          <td>{{value.mes_lis_acc_par_sel_code}}</td>
          <td>{{value.mes_lis_acc_par_sel_name}}</td>
          <td>{{value.mes_lis_acc_par_pay_code}}</td>
          <td>{{value.mes_lis_acc_par_pay_name}}</td>

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
      received_item_list: {},
      received_item_length: 0,
      byr_buyer_lists: {},
      order_customer_code_lists: {},
      showAllCustomerCodeListModal:false,
      byr_buyer_id:null,
      form: {
        select_field_per_page_num:10,
        page:1,
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        receive_date_from: null,
        receive_date_to: null,
        sel_code: null,
        ownership_date_from: null,
        ownership_date_to: null,
        trade_number: null,
        // major_category: "*",
        delivery_service_code: "*",
        temperature_code: "*",
        sta_doc_type: "*",
        check_datetime: '*',
        major_category:{category_code:'*',category_name:'全て'},
        sort_by:'receive_datetime ',
        sort_type:"DESC",
        page_title:'receive_list',
        downloadType:1

      },
    };
  },
  beforeCreate: function() {
            if (!this.$session.exists()) {
                this.$router.push('/home');
            }
        },
  methods: {
    onRowClicked (item) {
        this.form.sel_code = item.mes_lis_acc_par_sel_code;
       this.showAllCustomerCodeListModal = false;
    },
    //get Table data
    showAllCustomerCode(){
     let loaders = Vue.$loading.show();
      this.showAllCustomerCodeListModal = true;
      axios.post(this.BASE_URL + "api/get_receive_customer_code_list", this.form)
        .then(({ data }) => {
          this.order_customer_code_lists = data.order_customer_code_lists;
         loaders.hide();
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     loaders.hide();
        // });
    },
    //get Table data
    getAllReceivedItem(page = 1) {
        // console.log(this.form)
        this.form.page=page;
         let loader = Vue.$loading.show();
        axios.post(this.BASE_URL +"api/data_receive_list",this.form)
            .then(({data}) => {
                this.received_item_list = data.received_item_list;
                this.received_item_length = this.received_item_list.data.length;
                this.byr_buyer_lists = data.byr_buyer_list;
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
          this.getAllReceivedItem();

      },
      receive_download(downloadType = 1) {
      //downloadcsvshipment_confirm
      this.form.downloadType= downloadType,
      axios
        .post(this.BASE_URL + "api/receive_download", this.form)
        .then(({ data }) => {
          this.downloadFromUrl(data);
          this.getAllReceivedItem(this.form.page)
        })
        // .catch(() => {
        //     this.sweet_advance_alert();
        //     // loader.hide();
        // });
    },
    goToDetailsPage(page_name,received_item){
        this.$store.commit('receiveDetailListModule/reset')
        // this.$store.commit('reset')
        var query_array = { data_receive_id: received_item.data_receive_id,
                sel_name: received_item.mes_lis_acc_par_sel_name,
                sel_code: received_item.mes_lis_acc_par_sel_code,
                major_category: received_item.mes_lis_acc_tra_goo_major_category,
                delivery_service_code:received_item.mes_lis_acc_log_del_delivery_service_code,
                ownership_date: received_item.mes_lis_acc_tra_dat_transfer_of_ownership_date.valueOf(),
            };
        this.$router.push({name:page_name,query:query_array})
    },
  },

  created() {
        this.byr_buyer_id=this.$session.get("byr_buyer_id");
        this.form.byr_buyer_id=this.byr_buyer_id;
        this.$store.commit('receiveListModule/formValuesStoreBYRID',this.byr_buyer_id);
        this.form = this.$store.getters['receiveListModule/getFormData'];
        this.getbuyerJsonSettingvalue();
        this.getAllReceivedItem(this.form.page);
        Fire.$emit("byr_has_selected",this.byr_buyer_id);
        Fire.$emit("permission_check_for_buyer", this.byr_buyer_id);
        Fire.$emit("loadPageTitle", "受領データ一覧");
  },
  mounted() {
  },
};
</script>
<style>
.btn-group > .btn.active{
    z-index: 0
}
</style>
