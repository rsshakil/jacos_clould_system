<template>
  <div>
    <div class="row">
      <div class="col-12" v-role="['Super Admin']">
        <ul class="buyer_button_list buyer_button_list1">
          <li variant="outline-primary" v-for="(byr_list,i) in byr_info" :key="i" @click="buyer_slr_order_show(byr_list.byr_buyer_id)">
          <b-button variant="outline-primary" >
            {{ byr_list.company_name }}
          </b-button>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<style>
.buyer_button_list1{
margin-top:18px !important
}
</style>
<script>
export default {
  data() {
    return {
      byr_slr_list: "",
      byr_info: null,
    };
  },
  methods: {
    loadData() {
      axios
        .post(this.BASE_URL + "api/get_byr_list_order", {
          user_id: this.myLang.user_info_id,
        })
        .then(({ data }) => {
          this.byr_info = data.byr_info;
        });
    },
    buyer_slr_order_show(byr_buyer_id){
        // console.log(byr_buyer_id);
        this.$session.set("slr_byr_buyer_id",byr_buyer_id)
        Fire.$emit('byr_menu_show');
        if (this.$route.name != 'slr_order_list') {
                this.$router.push({name:"slr_order_list"});
        }
    }
  },
  created() {
    //   Fire.$emit('byr_menu_show');
  },
  mounted() {
    this.loadData();
  },
};
</script>
