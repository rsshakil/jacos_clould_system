<template>
  <div>
    <byr_order_list></byr_order_list>
    <byr_slr_list_with_order></byr_slr_list_with_order>
    <blog_view></blog_view>
  </div>
</template>

<script>
// const page_header_default = () =>
//   import(/* webpackChunkName: "page_header_default" */ "./page_header_default");
const byr_order_list = () =>
  import(/* webpackChunkName: "byr_slr_list_with_order" */ "../ADM/BYR_ORDER/byr_order_list.vue");
const byr_slr_list_with_order = () =>
  import(/* webpackChunkName: "byr_slr_list_with_order" */ "../SLR/byr_slr_list_with_order.vue");
const blog_view = () =>
  import(/* webpackChunkName: "blog_view" */ "../CMN/blog_view.vue");
// import page_header_default from './page_header_default'
// import byr_slr_list_with_order from './SLR/byr_slr_list_with_order'
// import blog_view from './blog_view'
export default {
  components: {
    byr_order_list: byr_order_list,
    byr_slr_list_with_order: byr_slr_list_with_order,
    blog_view: blog_view,
  },
  data() {
    return {
      home_title: "",
    };
  },
  methods: {},
  created() {
    Fire.$emit("buyer_session_destroy");
    Fire.$emit("loadPageTitle", "得意先一覧");
    Fire.$emit('byr_menu_show');
  },
  mounted() {

  }
};
</script>
