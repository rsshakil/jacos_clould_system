<template>
<div>
    <ul class="nav jcs_left_side_bar_menu flex-column">

<li class="nav-item">
          <a
            class="nav-link collapsed"
            href="#jacosmenu1"
            data-toggle="collapse"
            data-target="#jacosmenu1"
            ><b-icon icon="gear-fill" font-scale="1.2"></b-icon>
            JACOS</a
          >
          <div class="collapse" id="jacosmenu1" aria-expanded="false">
            <ul class="flex-column pl-2 nav">
            <li v-can="['byr_view','manage_user_menu']" class="nav-item">
                <router-link to="/data_test" class="nav-link">
                  <b-icon icon="house-fill" font-scale="1.2"></b-icon>
                  データテスト
                </router-link>
              </li>
            <li v-can="['byr_view','manage_user_menu']" class="nav-item">
                <router-link to="/scenario_history" class="nav-link">
                  <b-icon icon="house-fill" font-scale="1.2"></b-icon>
                  {{ myLang.scenario_history }}
                </router-link>
              </li>
             <li v-can="['byr_view','manage_user_menu']" class="nav-item">
                <router-link to="/blog" class="nav-link">
                  <b-icon icon="house-fill" font-scale="1.2"></b-icon>
                  {{ myLang.new_notice }}
                </router-link>
              </li>
              <li class="nav-item" v-can="['byr_management']">
                <router-link to="/byr_management" class="nav-link">
                  <b-icon icon="card-checklist" font-scale="1.2"></b-icon>
                  {{ myLang.buyer_management }}
                </router-link>
              </li>
              <li class="nav-item" v-can="['slr_management']">
                <router-link to="/slr_management" class="nav-link">
                  <b-icon icon="card-checklist" font-scale="1.2"></b-icon>
                  {{ myLang.wholesaler_management }}
                </router-link>
              </li>
              <li class="nav-item" v-can="['scenario_management']">
                <router-link to="/job_management" class="nav-link">
                  <b-icon icon="card-checklist" font-scale="1.2"></b-icon>
                  {{ myLang.job_management }}
                </router-link>
              </li>
            <li class="nav-item" v-can="['scenario_management']">
                <router-link to="/scenario_management" class="nav-link">
                  <b-icon icon="card-checklist" font-scale="1.2"></b-icon>
                  {{ myLang.scenario_management }}
                </router-link>
            </li>
            <li class="nav-item" v-can="['document_management']">
                <router-link to="/document_management" class="nav-link">
                  <b-icon icon="card-checklist" font-scale="1.2"></b-icon>
                  Document Management
                </router-link>
            </li>
               <li class="nav-item" v-can="['byr_view']">
          <a
            class="nav-link collapsed"
            href="#pdf_platform_setting"
            data-toggle="collapse"
            data-target="#pdf_platform_setting"
            ><b-icon icon="grid-fill" font-scale="1.2"></b-icon>
            {{ myLang.pdf_platform }}</a>
          <div class="collapse" id="pdf_platform_setting" aria-expanded="false">
            <ul class="flex-column pl-2 nav">
              <li class="nav-item" v-can="['pdf_platform_setting']">
                <router-link to="/pdf_platform_setting" class="nav-link">
                  <b-icon icon="receipt" font-scale="1.2"></b-icon>
                  {{ myLang.pdf_platform_setting }}
                </router-link>
              </li>
              <li class="nav-item" v-can="['pdf_platform_view']">
                <router-link to="/pdf_platform_view" class="nav-link">
                  <b-icon icon="receipt" font-scale="1.2"></b-icon>
                  {{ myLang.pdf_platform_show }}
                </router-link>
              </li>
            </ul>
          </div>
        </li>


              </ul>
              </div>
              </li>
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

  },
  created(){

  }
};
</script>
