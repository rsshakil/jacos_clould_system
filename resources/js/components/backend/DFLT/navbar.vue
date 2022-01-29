<template>
  <!-- <div> -->
  <!-- Hi -->
  <!-- {{app.user?app.user.name:'Account'}} -->
  <main class="main-content p-0">
    <div class="main-navbar sticky-top bg_custom_color" :style="{'background-color':customBg}">
      <!-- Main Navbar -->
      <nav class="navbar align-items-stretch navbar-light flex-md-nowrap p-0">
        <div class="row" style="margin-right: 0; margin-left: 0; width: 100%">
          <div class="col-2">
            <div class="site_logo">
              <router-link to="/home">
                <img
                  id="main-logo"
                  class="d-inline-block align-top mr-1"
                  style="max-width: 100%; height: 60px"
                  :src="BASE_URL + '/public/backend/images/logo/jacos_logo.png'"
                  alt="Jacos Dashboard"
                />
              </router-link>
            </div>
          </div>
          <div class="col-4">
            <!--<div class="custom_bredcrubs">
              <Breadcrumbs></Breadcrumbs>
          </div>-->
            <h4 class="selected_byr_by_sly" v-if="userType!='byr'">
              得意先名：<span class="selected_byr_customer">{{ selected_customer_list }}</span>
            </h4>
          </div>
          <!-- @focusout="toggle = false" -->

          <div class="col-1 p-0">
            <!-- <button
              v-if="company_name != ''"
               @mouseover="hover = true"

              class="btn btn-default byr_list_show"
            >
              得意先選択
            </button> -->

            <div
              class="top_byr_slr_list"
              v-if="company_name != '' && hover"
              @mouseleave="hover = false"
            >
            <!-- style="display: none" -->
            <!-- v-if="toggle" -->
              <table
                class="table b-table custom_slr_byr_top_table table-bordered"
              >
                <thead>
                  <tr>
                    <th>得意先名</th>
                    <th>受注件数</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="buyer in slr_order_list"
                    :key="buyer.byr_buyer_id"
                  >
                    <td class="btn-outline-primary custom_navbr_button" style="text-align: center;">
                      <!-- <router-link :to="{
                    name: 'selected_buyer',
                    params: {
                      byr_buyer_id: buyer.byr_buyer_id,
                    },
                  }"
                  class="btn btn-outline-primary">
                  {{ buyer.buyer_name }} &nbsp &nbsp
                 {{ buyer.total_order }}件
                    </router-link> -->
                      <button class="btn"  @click="buyer_route_change(buyer.byr_buyer_id);hover = false">{{ buyer.buyer_name }}</button
                      >
                      <!-- <router-link :to="{name: 'selected_buyer',params: {byr_buyer_id: buyer.byr_buyer_id,},}" class="btn" style="border:none; display:block; height:100%">
                        {{buyer.buyer_name}}
                      </router-link> -->
                    </td>
                    <td>{{ buyer.total_order }}件</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-5">
            <ul class="navbar-nav top_custom_ul flex-row">
              <li class="nav-item" v-if="userCompanyInfo.company_name != ''">
                <a class="uer_company nav-link top_menu_custom_a">
                  <b-icon icon="grid3x3-gap-fill"></b-icon>
                  {{ userCompanyInfo.company_name }}</a
                >
              </li>

              <li class="nav-item dropdown">
                <a
                  class="nav-link top_menu_custom_a loggedUserName  text-nowrap px-3"
                  data-toggle="dropdown"
                  href="#"
                  role="button"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  <!-- @if(Auth::user()->image) -->
                  <!--dropdown-toggle off dropdown
              <img
                class="user-avatar rounded-circle mr-2"
                style="max-height: 46px !important"
                v-if="user_data.user"
                :src="imageSrc()"
                alt
              />-->
                  <!-- <img class="rounded-circle" v-if="user_image" :src="user_image" alt="No image" width="110" /> -->
                  <!-- @endif -->
                  <span class="d-none d-md-inline-block">
                    <b-avatar variant=""></b-avatar>
                     {{ user_data.global_user_name ? user_data.global_user_name : "" }}
                  </span>

                </a>
                <div
                  class="dropdown-menu dropdown-menu-small d-none"
                  style="margin-left: -60px"
                >

                  <router-link
                    :to="{
                      name: 'users',
                      params: {
                        id: global_user_id,
                        auth_id: global_user_id,
                      },
                    }"
                    class="dropdown-item"
                  >
                    <i class="material-icons">&#xE7FD;</i> Profile
                  </router-link>
                  <!-- @endcan
              @can('personal_password_change')-->
                  <router-link
                    :to="{
                      name: 'password_reset',
                      params: {
                        id: global_user_id,
                        auth_id: global_user_id,
                      },
                    }"
                    class="dropdown-item pc"
                  >
                    <i class="fas fa-edit"></i>
                    {{ myLang.change_password }}
                  </router-link>
                  <div class="dropdown-divider"></div>
                </div>
              </li>
              <li class="nav-item">
                <button class="btn btn-defalut logout_btn text-danger" @click="logout()">
                  Logout
                </button>
              </li>
              <li class="nav-item dropdown" v-if="APP_ENV!='production'">
                <a class="nav-link top_menu_custom_a dropdown-toggle text-nowrap px-3" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="flag-icon flag-icon-jp" v-if="local == 'ja'"></span>
                  <span class="flag-icon flag-icon-us" v-if="local == 'en'"></span>
                  {{ local == "en" ? "English" : "日本語" }}
                </a>
                <div class="dropdown-menu dropdown-menu-small" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" :href="BASE_URL + 'language/en'">
                    <span class="flag-icon flag-icon-us"></span>
                    English
                  </a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" :href="BASE_URL + 'language/ja'">
                    <span class="flag-icon flag-icon-jp"></span>
                    日本語
                  </a>
                </div>
              </li>
            </ul>
          </div>
        </div>

        <nav class="nav">
          <a
            href="#"
            class="nav-link nav-link-icon toggle-sidebar d-md-inline d-lg-none text-center border-left"
            data-toggle="collapse"
            data-target=".header-navbar"
            aria-expanded="false"
            aria-controls="header-navbar"
          >
            <i class="material-icons">&#xE5D2;</i>
          </a>
        </nav>
      </nav>
    </div>
    <b-modal
      id="table_col_setting"
      :hide-backdrop="true"
      ref="table_colShowHide"
      title="表示項目設定"
      cancel-title="キャンセル"
      ok-title="変更"
      @ok.prevent="update_col_setting()"
      :no-enforce-focus="true">
      <!-- <div v-html="table_col_setting_list"></div> -->
      <table class="table table-bordered table_col_ssettings">
        <thead>
          <th>表示項目名</th>
          <th>表示ON/OFF</th>
        </thead>
        <tbody>
          <tr v-for="table_col in table_col_arry" :key="table_col.header_field">
            <td>{{ table_col.header_text }}</td>
            <td>
              <label class="switch">
                <input
                  @change="handleChange(table_col)"
                  :id="table_col.header_field"
                  type="checkbox"
                  v-model="table_col.header_status"
                />
                <span class="slider round"></span>
              </label>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- <b-form-checkbox-group id="checkbox-group" v-model="selected_columns">
        <ul style="list-style:none;">
          <li v-for="(col_list, index) in col_lists" :key="index">
            <b-form-checkbox :value="col_list.header_field" switch>
              <p class="btn btn-info" style="padding:5px;margin:3px;">{{ col_list.header_text }}</p>
            </b-form-checkbox>
          </li>
        </ul>
      </b-form-checkbox-group>-->
    </b-modal>


  </main>
</template>

<script>
export default {
  name: "navbar",
  props: ["app"],
  data() {
    return {
      local: Globals.local,
      user_data: null,
      userType:Globals.global_user_type,
      company_name: null,
      slr_order_list:null,
      customBg:'#538ED3',
      hover: false,
      userCompanyInfo:{},
      selected_customer_list: "未選択",
      buyer_info_for_saller:[],
      fields: [
        {
          key: "header_1",
          label: "得意先名",
          sortable: false,
        },
        {
          key: "header_2",
          label: "受注件数",
          sortable: false,
        },
      ],
      byrslrlst: [
        { isActive: true, header_1: "イオン", header_2: "500件" },
        { isActive: true, header_1: "イオン", header_2: "500件" },
        { isActive: true, header_1: "イオン", header_2: "500件" },
        { isActive: true, header_1: "イオン", header_2: "500件" },
        { isActive: true, header_1: "イオン", header_2: "500件" },
      ],
      selectfieldCounter:0,

      // BASE_URL:BASE_URL,
    };
  },
  methods: {

    imageSrc() {
      return (
        this.BASE_URL +
        "/storage/app/public/backend/images/users/" +
        this.user_data.user.image
      );
    },
    get_logged_user_company(){
      axios
        .post(this.myLang.base_url + "api/get_logged_user_company_by_user_id", {
          user_id: this.myLang.user_info_id,
        })
        .then(({ data }) => {
          this.userCompanyInfo = data.userCompanyInfo;
        });
    },
    get_user_company_info() {
       axios
        .post(this.myLang.base_url + "api/get_byr_order_data_by_slr", {
          user_id: this.myLang.user_info_id,
        })
        .then(({ data }) => {
            this.slr_order_list = data.slr_order_info;
        });
    },
    get_slected_byr_info(byr_buyer_id) {
      axios
        .get(
          this.BASE_URL +
            "api/get_selected_byr_info/" +byr_buyer_id
        )
        .then(({ data }) => {
          
          if(data.byr_info!=null){
             this.$session.set('byr_buyer_company',data.byr_info.company_name)
             this.company_name = data.byr_info.company_name;
             this.selected_customer_list = data.byr_info.company_name;

          }

        });
    },


  },
  created() {
    this.get_user_company_info();
    if(Globals.global_user_type=='slr'){
      this.customBg='#538ED3';
    }else if(Globals.global_user_type=='others'){
      this.customBg='#f73f3f';
    }else{
       this.customBg='#53c1d3';
    }
    this.get_logged_user_company();
    Fire.$on("getLoggedUserInfo", () => {
      this.get_logged_user_company();
    });
    this.user_data = this.app._data;
    Fire.$on("byr_has_selected", (byr_buyer_id) => {
     
      this.get_slected_byr_info(byr_buyer_id);
      if(this.$session.has('byr_buyer_company')){
        this.selected_customer_list = this.$session.get('byr_buyer_company');
      }else{
        this.selected_customer_list = '未選択';
      }
    });
    if(this.$session.has('byr_buyer_company')){
        this.selected_customer_list = this.$session.get('byr_buyer_company');
      }else{
        this.selected_customer_list = '未選択';
      }

  },
};
</script>
<style>
/* .byr_list_show:focus .top_byr_slr_list{
  display: block;
} */
</style>
