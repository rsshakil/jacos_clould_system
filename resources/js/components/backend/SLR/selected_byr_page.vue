<template>
<div>


                <!-- <router-view/> -->
                <!--<page_header_default></page_header_default>-->
               <!-- <byr_slr_list_with_order></byr_slr_list_with_order>-->
                <blog_view_for_select_customer></blog_view_for_select_customer>



</div>
</template>

<script>
const page_header_default = () =>
    import ( /* webpackChunkName: "page_header_default" */ '../DFLT/page_header_default.vue')
const byr_slr_list_with_order = () =>
    import ( /* webpackChunkName: "byr_slr_list_with_order" */ './byr_slr_list_with_order')
const blog_view_for_select_customer = () =>
    import ( /* webpackChunkName: "blog_view_for_select_customer" */ './blog_view_for_select_customer')
//  import page_header_default from '../page_header_default'
//  import byr_slr_list_with_order from './byr_slr_list_with_order'
//  import blog_view_for_select_customer from './blog_view_for_select_customer'
    export default {
        components : {
            'page_header_default': page_header_default,
            'byr_slr_list_with_order': byr_slr_list_with_order,
            'blog_view_for_select_customer': blog_view_for_select_customer,
        },
        data(){
            return {
                home_title:'',
            }
        },
        beforeCreate: function() {
            if (!this.$session.exists()) {
                this.$router.push('/home');
            }
        },
        methods:{
        //    buyer_route_change(byr_buyer_id){
        //      console.log(byr_buyer_id);
        //       this.$router.push({
        //         name: 'selected_buyer',
        //         params: { byr_buyer_id: byr_buyer_id }
        //       })
        //    },
        },
          created() {
            //   router.app.$session.set('buyer_id', this.$route.params.byr_buyer_id);
            // this.$session.start()
            // this.$session.set('byr_buyer_id', this.$route.params.byr_buyer_id)
            //  this.byr_session_check()
          
             Fire.$emit('byr_has_selected',this.$session.get('byr_buyer_id'));
          

             Fire.$emit('permission_check_for_buyer',this.$session.get('byr_buyer_id'));
             Fire.$emit("loadPageTitle", "得意先別HOME");
            },
        mounted() {
        },
        route: {
            canReuse: false,
        },
    }
</script>
