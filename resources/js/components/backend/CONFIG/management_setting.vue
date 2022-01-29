<template>
  <div class="row">


    <div class="col-12">
      <div class="row">

      <div class="col-12">

          <table
          class="table table-striped order_item_details_table table-bordered data_table"
        >
          <thead>
            <tr>
              <th style="cursor: pointer">取引先コード</th>
              <th style="cursor: pointer">受注FAX番号</th>
              <th>On/Off</th>
              <th style="cursor: pointer">支払FAX番号</th>
              <th>On/Off</th>
              <th>締日登録</th>
            </tr>
          </thead>
          <tbody>
           <tr v-for="(item,index) in cmnConnectOptionList" :key="index">
            <td>{{item.rows.partner_code}}</td>
            <td><input type="text" class="form-control" v-model="item.jsonRows.order.fax.number"/></td>
            <td><label class="switch">
                <input
                  type="checkbox"
                  v-model="item.jsonRows.order.fax.exec"
                />
                <span class="slider round"></span>
              </label></td>
            <td>
            <input type="text" class="form-control" v-model="item.jsonRows.payment.fax.number"/></td>
            <td><label class="switch">
                <input
                  type="checkbox"
                  v-model="item.jsonRows.payment.fax.exec"
                />
                <span class="slider round"></span>
              </label></td>
              <td><button class="btn btn-primary" @click="updateInvoiceSettingByModal(item)">締日登録</button></td>
           </tr>
          </tbody>
        </table>
        <button class="btn btn-primary" @click="update_optional_json_value">更新</button>
      </div>

 <div class="col-12 text-center page_c_title_bar text-sm-left mb-0" style="padding-top:20px;padding-bottom:15px;">
      <h4 class="page_custom_title">マニュアル</h4>

    </div>
    <div class="col-12 text-left">
      <span style="background: #f1f1f1;padding: 8px;color: #000 !important;">各種マニュアル一括</span>
      <button class="btn btn-outline-primary" type="button" @click="userManualDownload()">
        <b-icon icon="download" animation="fade" font-scale="1.2"></b-icon>
        {{ myLang.download }}
      </button>
    </div>


      </div>
    </div>
    <b-modal
    :hide-backdrop="true"
      v-model="invoice_update_setting"
      title="締日登録"
      cancel-title="キャンセル"
      ok-title="決定"
      @ok.prevent="update_invoice_json_setting()"
      :no-enforce-focus="true"
    >
     <h4>請求業務の締日を登録できます</h4>
     <label for="invoicejson_0" class="">締日</label>

     <div class="selectFildlistdata" style="position:relative;">
     <div class="customselectFields" v-for="(input,index) in selectfieldList" :key="input.id">
     <select class="form-control custominvoicejsnslect" v-model="input.value">
        <option v-for="(n,i) in 30" :value="n" :key="i">{{n}}日</option>
        <option value="last">月末</option>
     </select>
     <b-icon @click="removeSelectField(input)" v-if="index!=0" class="customMinusIcon" icon="trash" aria-hidden="true"></b-icon>
      <b-icon @click="addSelectField" v-if="index==0" class="customPlusIcon" icon="plus-square-fill" aria-hidden="true"></b-icon>
    </div>
    </div>
    </b-modal>
  </div>
</template>
<script>
export default {
  data() {
    return {
      cmnConnectOptionList: {},
      order_columns:[],
      byr_buyer_id: null,
      selectfieldCounter:0,
      cmn_connect_id_invoice_update:'',
      tempM:'',
      invoice_update_setting:false,
      selectfieldList: [{
          id: 'invoicejson_0',
          value: ''
      }],
      updatedFaxList:[
        {
          cmn_connect_id:'',
          type:'',
          number:'',
        }
      ],
      form: new Form({
        adm_user_id: Globals.user_info_id,
        byr_buyer_id: null,
        payment_id:'',
      }),
    };
  },
  methods: {
    userManualDownload(){
        let loader = Vue.$loading.show();
        axios.post(this.BASE_URL + "api/download_user_manual").then(({data})=>{
            if (data.new_file_name || data.url) {
                this.downloadFromUrl(data)
            }
            loader.hide();
        })
      //download code here
    },
    update_optional_json_value(){

       this.alert_icon = "warning";
      this.alert_title = "";
      this.alert_text = "FAX番号の登録を更新しますがよろしいでしょうか。";
      this.yes_btn = "はい";
      this.cancel_btn = "キャンセル";
      this.confirm_sweet().then((result) => {
        if(result.value){
      var formDatas = {
        allJson : this.cmnConnectOptionList,
      };
      axios.post(this.BASE_URL + "api/update_cmn_connects_optionalAllJson", formDatas)
                .then(({data}) => {
                    Swal.fire({
                        icon: "success",
                        title: "",
                        text: "FAX番号の登録を更新しました。",
                    });
                    this.getAllCmnConnectOptionalJsons();
                });
                }
      })
    },
    //get Table data
    getAllCmnConnectOptionalJsons() {
      let loader = Vue.$loading.show();
      axios.post(this.BASE_URL + "api/get_partner_fax_list",this.form)
        .then(({ data }) => {
            this.cmnConnectOptionList = data.result;
            loader.hide();
        });
    },
   updateInvoiceSettingByModal(item){
    //    console.log(cmnConnectOptionList)
    //    console.log(item)
     this.invoice_update_setting=true;
     this.cmn_connect_id_invoice_update =  item.rows.cmn_connect_id;
      if(item.jsonRows.invoice.closing_date.length>0){
        this.selectfieldList = [];
        // return 0
      }
    (item.jsonRows.invoice.closing_date).forEach((value, index) => {
    this.selectfieldList.push({
                id: `invoicejson_${++this.selectfieldCounter}`,
                value: value,
            });
        });
        // console.log(this.selectfieldList)
     //this.selectfieldList=
   },
     addSelectField() {
            this.selectfieldList.push({
                id: `invoicejson_${++this.selectfieldCounter}`,
                value: '',
            });
        },
        removeSelectField(event) {
            this.selectfieldList.splice(this.selectfieldList.indexOf(event), 1);
        },

        update_invoice_json_setting() {

            this.alert_icon = "warning";
      this.alert_title = "";
      this.alert_text = "締日の登録を更新しますがよろしいでしょうか。";
      this.yes_btn = "はい";
      this.cancel_btn = "キャンセル";
      this.confirm_sweet().then((result) => {
        if(result.value){
            this.invoice_update_setting=false;
            var post_data = {
                selected_item: this.selectfieldList,
                user_id: Globals.user_info_id,
                cmn_connect_id:this.cmn_connect_id_invoice_update,
            };
            axios
                .post(this.BASE_URL + "api/update_cmn_connects_optional", post_data)
                .then(({data}) => {
                    Swal.fire({
                        icon: "success",
                        title: "",
                        text: "締日の登録を更新しました。",
                    });
                    this.getAllCmnConnectOptionalJsons();
                });
        }
      })

        },

  },

  created() {
    this.byr_buyer_id = this.$session.get("byr_buyer_id");
    this.form.byr_buyer_id = this.byr_buyer_id;
    this.form.payment_id = this.$route.params.payment_id
    this.getAllCmnConnectOptionalJsons();
    //this.getInvoiceJson();
    Fire.$emit("byr_has_selected", this.byr_buyer_id);
    Fire.$emit("permission_check_for_buyer", this.byr_buyer_id);
    Fire.$emit("loadPageTitle", "自社情報登録");

  },
  mounted() {},
};
</script>

