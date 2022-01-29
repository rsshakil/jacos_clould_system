<template>
<div>
    <ul class="nav jcs_left_side_bar_menu flex-column">

<li class="nav-item">
          <a
            class="nav-link collapsed"
            href="#admmenu1"
            data-toggle="collapse"
            data-target="#admmenu1"
            ><b-icon icon="gear-fill" font-scale="1.2"></b-icon>
            ADM</a
          >
          <div class="collapse" id="admmenu1" aria-expanded="false">
            <ul class="flex-column pl-2 nav">
              <li class="nav-item" v-can="['manage_user_menu']">
                <router-link to="/users" class="nav-link">
                  <b-icon icon="person-circle" font-scale="1.2"></b-icon>
                  {{ myLang.manage_users }}
                </router-link>
              </li>
              <li class="nav-item" v-can="['role_menu']">
                <router-link to="/role" class="nav-link">
                  <b-icon icon="person-check-fill" font-scale="1.2"></b-icon>
                  {{ myLang.role_management }}
                </router-link>
              </li>
              <li class="nav-item" v-can="['permission_menu']">
                <router-link to="/permission" class="nav-link">
                  <b-icon icon="person-bounding-box" font-scale="1.2"></b-icon>
                  {{ myLang.permission_management }}
                </router-link>
              </li>
              <li class="nav-item" v-can="['assign_role_to_user_menu']">
                <router-link to="/assign_role_to_user" class="nav-link">
                  <b-icon icon="folder-symlink-fill" font-scale="1.2"></b-icon>
                  {{ myLang.assign_role_to_user }}
                </router-link>
              </li>
              <li class="nav-item" v-can="['assign_permission_to_user_menu']">
                <router-link to="/assign_permission_to_user" class="nav-link">
                  <b-icon icon="unlock-fill" font-scale="1.2"></b-icon>
                  {{ myLang.assign_permission_to_user }}
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