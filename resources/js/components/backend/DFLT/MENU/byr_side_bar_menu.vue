<template>
<div>
    <ul class="nav jcs_left_side_bar_menu flex-column">

        <li class="nav-item" v-can="['byr_view','slr_view']">
          <a
            class="nav-link collapsed"
            href="#byrmenu"
            data-toggle="collapse"
            data-target="#byrmenu"
            ><b-icon icon="grid-fill" font-scale="1.2"></b-icon>
            {{ myLang.buyer_management }}</a
          >
          <div class="collapse" id="byrmenu" aria-expanded="false">
            <ul class="flex-column pl-2 nav">

              <li class="nav-item"  v-for="permission in permissions_by_user" :key="permission.permission_id">
                <router-link :to="'/'+permission.permission_name" class="nav-link">
                  <b-icon icon="receipt" font-scale="1.2"></b-icon>
                  {{permission.permission_name}}
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
  name: "sidebar",
  props: ["permissions_by_user"],
  data() {
    return {
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
        // permission_by_user:[],
    };

  },
  methods: {

  },
  created(){
    // console.log(this.permissions_by_user);
  }
};
</script>
