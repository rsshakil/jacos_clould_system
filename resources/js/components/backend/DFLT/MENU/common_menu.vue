<template>
<div>
    <ul class="nav jcs_left_side_bar_menu flex-column">

<li class="nav-item">
          <a
            class="nav-link collapsed"
            href="#cmnmenu1"
            data-toggle="collapse"
            data-target="#cmnmenu1"
            ><b-icon icon="gear-fill" font-scale="1.2"></b-icon>
            CMN</a
          >
          <div class="collapse" id="cmnmenu1" aria-expanded="false">
            <ul class="flex-column pl-2 nav">
              <li class="nav-item" v-can="['manage_user_menu']">
                <router-link :to="{ name: 'partner_list_manage'}" class="nav-link">
                  <b-icon icon="person-circle" font-scale="1.2"></b-icon>
                  {{ myLang.partner_management }}
                  </router-link>
              </li>
              <li class="nav-item" v-can="['manage_user_menu']">
                <router-link :to="{ name: 'user_list_manage'}" class="nav-link">
                  <b-icon icon="person-circle" font-scale="1.2"></b-icon>
                  {{ myLang.user_management }}
                  </router-link>
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
