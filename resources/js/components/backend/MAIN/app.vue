
<template>
<div class="container-fluid" id="main_content_section">
    <div class="row">
        <div class="col-md-12 p-0">

            <navbar :app="this"></navbar>
        </div>
    </div>
    <div class="row">

        <div class="col-md-2 px-0">
            <sidebar :app="this"></sidebar>
        </div>
        <div class="col-md-10">
            <div class="stats-small stats-small--1 card card-small" style="margin-top:20px">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-12 custombredcumbs">
                            <!--<Breadcrumbs/>-->
                            <!--<app-breadcrumbs></app-breadcrumbs>-->
                            <app-breadcrumbs container="nav">
                                <h6 slot-scope="{to, label, utils}">
                                    <router-link
                                    :to="to"
                                    class="your-custom-class"
                                    exact
                                    :itemprop="utils && utils.itemprop"
                                    >{{label}}</router-link>
                                    <i class="fas fa-angle-right"></i>
                                </h6>

                                <span
                                    slot="current"
                                    slot-scope="{label}"
                                    class="custom-current-class"
                                >{{label}}</span>
                                </app-breadcrumbs>
                                <div class="clear-both" style="clear:fix"></div>
                            <page_header_default></page_header_default>
                        </div>
                    </div>
                    <!-- <router-view/> -->
                    <router-view :key="$route.fullPath"/>
                </div>
            </div>
        </div>
        <!-- <spinner v-if="loading"></spinner>
        <div v-else-if="initiated"></div> -->
       


    </div>
    <!--<div class="col-md-12" style="margin-top:70px; padding:0px;">-->
             <projectfooter ></projectfooter> 
        <!--</div>-->
</div>
 
        
</template>

<script>
const navbar = () =>
    import ( /* webpackChunkName: "navbar" */ '../DFLT/navbar.vue')
const sidebar = () =>
    import ( /* webpackChunkName: "sidebar" */ '../DFLT/side_bar.vue')
 const projectfooter = () =>
     import ( /* webpackChunkName: "projectfooter" */ '../DFLT/footer')
const page_header_default = () =>
    import ( /* webpackChunkName: "page_header_default" */ '../DFLT/page_header_default.vue')
export default {
name:'app',
components:{
    navbar,
    sidebar,
    projectfooter,
    page_header_default,
},
data(){
    return{
        user:null,
        // loading:false,
        initiated:false,
        // BASE_URL:Globals.base_url,
        req: axios.create({
            BASE_URL:this.BASE_URL
        })
    }
},
methods:{
    changePasswordModal(user_id,user_name){
        // this.init_user();
        // this.fieldsEmpty();
        // this.password_modal_title='Change the password for '+user_name;
        this.passwordModalShow=true;
        this.user_update_id=user_id;
    },
},
created(){
    // this.loading=false;
}
}
</script>
