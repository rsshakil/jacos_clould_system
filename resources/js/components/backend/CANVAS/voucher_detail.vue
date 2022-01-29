<template>
    <div class="row" v-can="['byr_view']">
                <div class="col-12">
                    <h4 class="top_title text-center" style="margin-top:10px;">伝票一覧・新規請求</h4>
                </div>
                <div class="col-12 text-center">
        <div class="row">
        <div class="col"></div>
        <div class="col-4">
        <table class="table table-bordered top_info_table">
          <thead>
            <th>発注日</th>
            <th>納品日</th>
            <th>請求日</th>
          </thead>
          <tbody>
            <tr>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
        </div>
          <div class="col"></div>

        </div>
      </div>
                <div class="col-12 text-center">

      <label>
        <!--<input type="file" id="file" ref="file" v-on:change="onChangeFileUpload()"/>-->
      </label>
                </div>
                <div class="col-12">
                    <div class="">
                        <table class="table table-striped table-bordered data_table">
                            <thead>
                                <tr>
                                    <th colspan="100%" style="border: none;">
                                        <div class="input-group mb-1" style="margin-left: 10px;max-width: 250px; float: left;">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-primary" type="button">小売選択</button>
                                            </div>
                                            <select class="form-control" v-model="selected_byr">
                                            <option :value="0">全小売</option>
                                              <option v-for="(option, index) in byr_buyer_lists"
                    :key="index" :value="option.super_code"
                    :selected="selectedOption(option)">
                    {{ option.super_code }}
            </option>
                                            </select>
                                        </div>
                                         <div class="input-group mb-1" style="margin-left: 10px;max-width: 250px; float: left;">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-primary" type="button">店舗</button>
                                            </div>
                                            <select class="form-control" v-model="selected_byr_shop">
                                            <option :value="0">全店舗</option>
                                              <option v-for="(option, index) in byr_shop_lists"
                    :key="index" :value="option.byr_shop_id"
                    :selected="selectedOption_shop(option)">
                    {{ option.shop_name }}
            </option>
                                            </select>
                                        </div>

                                         <div class="input-group mb-1" style="margin-left: 10px;max-width: 250px; float: left;">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-primary" type="button">伝票番号</button>
                                            </div>
                                            <select class="form-control" v-model="selected_byr_voucher">
                                            <option :value="0">全伝票</option>
                                              <option v-for="(option, index) in byr_voucher_lists"
                    :key="index" :value="option.voucher_number"
                    :selected="selectedOption_voucher(option)">
                    {{ option.voucher_number }}
            </option>
                                            </select>
                                        </div>
                                        <!--<div class="active-pink-3 active-pink-4 mb-1" style="margin-left: 10px;max-width: 100%; float: left;">
                                            <input class="form-control" type="text" placeholder="Search" aria-label="Search">
                                        </div>-->
                                        <button style="float:right" class="btn btn-primary">新規請求</button>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer">No <span id="id_icon"></span></th>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="name" style="cursor: pointer">商品名<span id="orderdate_icon"></span></th>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="email" style="cursor: pointer">JANコード <span id="delivery_icon"></span></th>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="email" style="cursor: pointer">発注数量<span id="ordertype_icon"></span></th>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="email" style="cursor: pointer">納品数量<span id="ordertype_icon"></span></th>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="email" style="cursor: pointer">欠品理由<span id="ordertype_icon"></span></th>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="email" style="cursor: pointer">単価<span id="ordertype_icon"></span></th>
                                    <th class="sorting" data-sorting_type="asc" data-column_name="email" style="cursor: pointer">合計金額<span id="ordertype_icon"></span></th>
                                </tr>

                            </thead>
                            <tbody>

                                <tr v-for="(value,index) in invoice_detail_lists" :key="value.byr_invoice_detail_id">
                                    <td>{{index+1}}</td>
                                    <td v-if="value.item_name !== null && value.item_name !== ''">{{value.item_name}}</td>
                                    <td v-else>{{value.item_name_kana}}</td>
                                    <td>{{value.jan}}</td>
                                    <td>{{value.order_quantity}}</td>
                                    <td v-if="value.confirm_quantity !== null && value.confirm_quantity !== 0 && value.confirm_quantity !== ''"><b-icon v-if="value.order_quantity != value.confirm_quantity" v-tooltip.html="'Quantity details<br>'+value.order_quantity+'<br>'+value.confirm_quantity+''" icon="exclamation-circle-fill" variant="warning" scale="0.5"></b-icon>{{value.confirm_quantity}}</td>
                                   <td v-else>{{value.order_quantity}}</td>
                                    <td>{{value.lack_reason}}</td>
                                    <td v-if="value.revised_cost_unit_price !== null && value.revised_cost_unit_price !== 0 && value.revised_cost_unit_price !== ''">{{value.revised_cost_unit_price}}</td>
                                   <td v-else>{{value.cost_unit_price}}</td>
                                   <td v-if="value.revised_cost_price !== null && value.revised_cost_price !== 0 && value.revised_cost_price !== ''">{{value.revised_cost_price}}</td>
                                   <td v-else>{{value.cost_price}}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
</template>
<script>
export default {
  data() {
    return {
        'invoice_detail_lists':{},
        'byr_buyer_lists':{},
        'selected_byr':'0',
        'byr_shop_lists':{},
        'selected_byr_shop':'0',
        'byr_voucher_lists':{},
        'selected_byr_voucher':'0',
        'file':'',
        'byr_invoice_id':'',
        'voucher_number':'',
        'start_date':'',
        'end_date':'',
        'total_price':'',
    };
  },
  methods: {
    //get Table data
    get_all_invoice_detail(){
        axios.get(this.BASE_URL +"api/get_all_invoice_by_voucher_number/"+this.voucher_number).then(({data}) => {
            this.invoice_detail_lists = data.invoice_list;
            this.byr_buyer_lists = data.byr_buyer_list;
            this.byr_shop_lists = data.shop_list;
            this.byr_voucher_lists = data.voucher_list;
        });
    },
    check_byr_order_api(){
       let formData = new FormData();
        formData.append("up_file", this.file);
            axios({
        method: 'POST',
        url: this.BASE_URL + "api/job_exec/1",
        data: formData,
        headers: {'Content-Type': 'multipart/form-data' }
        }).then(({data}) => {
            // console.log(response);
        Fire.$emit('LoadByrorder');
        })
        .catch(function (response) {

        });
    },
    onChangeFileUpload(){
        this.file = this.$refs.file.files[0];
        this.check_byr_order_api();
      },
      selectedOption(option) {
      if (this.value) {
        return option.byr_buyer_id === this.value.byr_buyer_id;
      }
      return false;
    },
    selectedOption_shop(option) {
      if (this.value) {
        return option.byr_shop_id === this.value.byr_shop_id;
      }
      return false;
    },
      selectedOption_voucher(option) {
      if (this.value) {
        return option.voucher_number === this.value.voucher_number;
      }
      return false;
    },
    change(e) {
      const selectedCode = e.target.value;
      const option = this.options.find((option) => {
        return selectedCode === option.byr_buyer_id;
      });
    //   this.$emit("input", option);
    }
  },

  created() {
    this.voucher_number = this.$route.params.voucher_number;
      this.get_all_invoice_detail();
      Fire.$on("LoadByrinvoice_detail", () => {
      this.get_all_invoice_detail();
    });

  },
  mounted() {

  }
};
</script>
