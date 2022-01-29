<template>
  <!-- Main Sidebar -->
  <!-- main-sidebar -->
  <aside class="main-sidebar custom_sidebar" style="position: relative; margin-top: 20px;width:94%;margin-left:15px;height:92%;display:block;overflow-y:scroll;">
    <div class="nav-wrapper">

      <ul class="nav jcs_left_side_bar_menu flex-column">
        <li class="nav-item">
          <router-link to="/home" class="nav-link">
            <b-icon icon="house-fill" font-scale="1.2"></b-icon> Home
          </router-link>
        </li>
      </ul>

      <adm_menu v-role="['Super Admin']"></adm_menu>
      <common_menu v-role="['Super Admin']"></common_menu>
      <jacos_menu v-role="['Super Admin']"></jacos_menu>
      <slr_menu v-if="permission_menu" :permissions_by_user="permissions_by_user" v-can="['slr_view']"></slr_menu>
      <byr_menu v-can="['byr_view']"></byr_menu>
      <user_menu v-role="['Super Admin']"></user_menu>
      <others_menu v-role="['Super Admin']"></others_menu>
      <byr_side_bar_menu v-role="['Super Admin']" v-if="permission_menu" :permissions_by_user="permissions_by_user"></byr_side_bar_menu>
    </div>
  </aside>
</template>

<script>
const adm_menu = () =>
    import ( /* webpackChunkName: "adm_menu" */ './MENU/adm_menu')
const common_menu = () =>
    import ( /* webpackChunkName: "common_menu" */ './MENU/common_menu')
const jacos_menu = () =>
    import ( /* webpackChunkName: "jacos_menu" */ './MENU/jacos_menu')
const byr_side_bar_menu = () =>
    import ( /* webpackChunkName: "byr_side_bar_menu" */ './MENU/byr_side_bar_menu')
const byr_menu = () =>
    import ( /* webpackChunkName: "byr_menu" */ './MENU/byr_menu')
const slr_menu = () =>
    import ( /* webpackChunkName: "slr_menu" */ './MENU/slr_menu')
const others_menu = () =>
    import ( /* webpackChunkName: "others_menu" */ './MENU/others_menu')
const user_menu = () =>
    import ( /* webpackChunkName: "user_menu" */ './MENU/user_menu')
// import adm_menu from './MENU/adm_menu'
// import common_menu from './MENU/common_menu'
// import jacos_menu from './MENU/jacos_menu'
// import byr_side_bar_menu from './MENU/byr_side_bar_menu'
// import byr_menu from './MENU/byr_menu'
// import slr_menu from './MENU/slr_menu'
// import others_menu from './MENU/others_menu'
// import user_menu from './MENU/user_menu'
export default {
  name: "sidebar",
  props: ["app"],
  components:{
    byr_side_bar_menu,
    adm_menu,
    common_menu,
    jacos_menu,
    byr_menu,
    slr_menu,
    others_menu,
    user_menu,
},
  data() {
    return {
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
        permissions_by_user:[],
        permission_menu:false,
    };

  },
  methods: {
    logout: function () {
      axios
        .post("logout")
        .then((response) => {
            this.init(response.status);
          if (response.status === 302 || 401) {

          } else {
            // throw error and go to catch block
          }
        })
        .catch((error) => {});
    },
    allPermissionCheck(byr_id=null){
        if (this.$route.name!=='home') {
           axios.post(this.BASE_URL+'api/get_permissions_for_buyer',{byr_id:byr_id}).then(({data})=>{
            this.permissions_by_user=data.permission_array;
            if (this.permissions_by_user.length>0) {
            this.permission_menu= true
            }


        })
        }

    }

  },
  created(){
    // this.allPermissionCheck();
    this.byr_buyer_id=this.$session.get("byr_buyer_id")
    if (this.$session.get("byr_buyer_id")) {
        this.allPermissionCheck(this.byr_buyer_id);
    }
    Fire.$on("permission_check_for_buyer", (byr_id) => {
        this.allPermissionCheck(byr_id);
    });
    Fire.$on("buyer_session_destroy", () => {
        this.$session.destroy()
        this.permission_menu= false;
        Fire.$emit('byr_has_selected');
    });
  }
};
</script>
