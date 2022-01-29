<template>
  <div>
    <!-- <default_byr_order_detail :param_data="param_data"></default_byr_order_detail> -->
    <default_order_vouchers></default_order_vouchers>
    <!-- <component v-bind:is="component" /> -->
  </div>
</template>
<script>
// './bms_byr_order_detail.vue'

import default_order_vouchers from './slr_order_list_details.vue'
// import bms_byr_order_detail from '../BMS/bms_byr_order_detail'
export default {
  components: {
    default_order_vouchers,
    //  bms_byr_order_detail
  },
  data() {
    return {
      // component:"default_byr_order_detail",
      // param_data:{data_order_id:null,
      // delivery_date:null,
      // major_category:null,
      // delivery_service_code:null,
      // temperature_code:null,}
    };
  },
  beforeCreate: function() {
            if (!this.$session.exists()) {
                this.$router.push('/home');
            }
        },
  methods: {

  },

  created() {
    //   this.byr_session_check()
    // this.param_data.data_order_id = this.$route.params.data_order_id;
    // this.param_data.delivery_date = this.$route.params.delivery_date;
    // this.param_data.major_category = this.$route.params.major_category;
    // this.param_data.delivery_service_code = this.$route.params.delivery_service_code;
    // this.param_data.temperature_code = this.$route.params.temperature_code;
    // console.log(this.$route.query);
    //  axios.post(this.BASE_URL + "api/get_byr_info_by_data_order_id",{data_order_id:this.data_order_id,delivery_date:this.delivery_date})
    //     .then(({data}) => {
    //       console.log(data);
    //       if(this.data_order_id=='1'){
    //         this.component = default_byr_order_detail;
    //       }else{
    //         this.component = bms_byr_order_detail;
    //       }
    //     })
  },
  mounted() {
  }
};
</script>

