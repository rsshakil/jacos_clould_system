<template>
<div>
    <ul class="nav jcs_left_side_bar_menu flex-column">
              <li class="nav-item" v-can="['order_list']">
                   <!-- class="btn btn-link btn-link-custom" -->
                <span @click="formReset('orderModule','order_list')">
                <router-link to="/order_list" class="nav-link">
                  <b-icon icon="receipt" font-scale="1.2"></b-icon>
                 受注・出荷業務
                </router-link>
                </span>
              </li>
               <li class="nav-item" v-can="['receive_list']">
                   <span @click="formReset('receiveListModule','receive_list')">
                    <router-link to="/receive_list" class="nav-link">
                    <b-icon icon="card-checklist" font-scale="1.2"></b-icon>
                    受領確認
                    </router-link>
                    </span>
              </li>
              <li class="nav-item" v-can="['return_list']">
                  <span @click="formReset('returnListModule','return_list')">
                <router-link to="/return_list" class="nav-link">
                  <b-icon icon="card-checklist" font-scale="1.2"></b-icon>
                 返品確認
                </router-link>
                  </span>
              </li>
              <li class="nav-item" v-can="['voucher_setting']">
                <router-link to="/voucher_setting" class="nav-link">
                  <b-icon icon="tools" font-scale="1.2"></b-icon>
                  {{ myLang.jacos_voucher_setting }}
                </router-link>
              </li>
              <li class="nav-item" v-can="['invoice_list']">
                  <span @click="formReset('invoiceListModule','invoice_list')">
                <router-link to="/invoice_list" class="nav-link">
                  <b-icon icon="card-checklist" font-scale="1.2"></b-icon>
                  請求業務
                </router-link>
                  </span>
              </li>
              <li class="nav-item" v-can="['payment_list']">
                  <span @click="formReset('paymentListModule','payment_list')">
                <router-link to="/payment_list" class="nav-link">
                  <b-icon icon="card-checklist" font-scale="1.2"></b-icon>
                支払確認
                </router-link>
                  </span>
              </li>
              <li class="nav-item" v-can="['stock_item_list']">
                <span @click="formReset('stockItemModule','stock_item_list')">
                    <router-link to="/stock_item_list" class="nav-link">
                       <b-icon icon="card-checklist" font-scale="1.2"></b-icon>
                       集計情報
                    </router-link>
                </span>
              </li>


              <li class="nav-item" v-can="['item_master']">
                <router-link to="/item_master" class="nav-link">
                  <b-icon icon="receipt" font-scale="1.2"></b-icon>
                  {{ myLang.product_management }}
                </router-link>
              </li>
              <li class="nav-item" v-can="['invoice_list']">
                <router-link to="/management_setting" class="nav-link">
                  <b-icon icon="receipt" font-scale="1.2"></b-icon>
                  管理
                </router-link>
              </li>
              <!--add menu here-->
    </ul>
</div>
</template>
<script>
export default {
  data() {
    return {

    };

  },
  methods: {
      formReset(module_name,page_name){
        //   console.log(module_name);
        //   this.$store.commit(module_name+'/reset')
          this.$store.commit('reset')
      }
  },
  created(){

  }
};
</script>
