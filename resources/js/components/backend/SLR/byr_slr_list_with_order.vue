<template>
  <div>
    <div class="row">
      <div class="col-12" v-role="['Slr', 'Byr']">
        <ul class="buyer_button_list">
          <li
            v-for="order_item in slr_order_list"
            :key="order_item.byr_buyer_id">
          <b-button variant="outline-primary" @click="buyer_route_change(order_item.byr_buyer_id)">
            {{ order_item.buyer_name }}&nbsp;&nbsp;{{ order_item.total_order }}件
          </b-button>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      byr_slr_list: "",
      slr_order_list: null,
    };
  },
  methods: {
    loadData() {
      axios
        .post(this.BASE_URL + "api/get_byr_order_data_by_slr", {
          user_id: this.myLang.user_info_id,
        })
        .then(({ data }) => {
          this.slr_order_list = data.slr_order_info;
        });
    },
  },
  created() {
      Fire.$emit('byr_menu_show');
  },
  mounted() {
    this.loadData();
  },
};
</script>
