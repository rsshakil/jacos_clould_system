/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");
import VueRouter from "vue-router";
// import vueselect from 'vue-select2';
//Routes
import { routes } from "./routes";
import store from "./store/store";
// ================
// import BackMixin from 'vue-router-back-mixin'
// Route sync
// import { sync } from 'vuex-router-sync'
// import store from './store' // vuex store instance
// const unsync = sync(store, routes) // done. Returns an unsync callback fn
// unsync() // Unsyncs store from router
// ===============
//Import Sweetalert2
import Swal from "sweetalert2";
//Import v-from
import { Form, HasError, AlertError } from "vform";
//Import vue multi select
import Multiselect from "vue-multiselect";
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
// import FlagIcon from 'vue-flag-icon'
// Fabric js
import { fabric } from 'fabric'
// import VueKonva from 'vue-konva'
import { Cropper } from 'vue-advanced-cropper'

import Loading from 'vue-loading-overlay';
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css';
// Vue session
import VueSession from 'vue-session'
Vue.use(VueSession)
    //vue font awsam

import { library } from '@fortawesome/fontawesome-svg-core'
import { faUserSecret } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faUserSecret)

Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.config.productionTip = false

window.Fire = new Vue();
Vue.use(VueRouter);
const originalPush = VueRouter.prototype.push

VueRouter.prototype.push = function push(location) {
    return originalPush.call(this, location).catch(err => err)
}

//import VueBreadcrumbs from 'vue-2-breadcrumbs';
// Vue.use(VueBreadcrumbs, {
//     template: '        <ul v-if="$breadcrumbs.length" aria-label="breadcrumb" class="custm_brd_crumbs">\n' +
//         '             <li v-for="(crumb, key) in $breadcrumbs" v-if="crumb.meta.breadcrumb" :key="key"><span v-if="key == 0 && getBreadcrumb(crumb.meta.breadcrumb_title)" > {{getBreadcrumb(crumb.meta.breadcrumb_title)}} > </span><router-link class="breadcrumb-item_custom" :to="{ path: getPath(crumb) }"> {{ getBreadcrumb(crumb.meta.breadcrumb) }} </router-link> <span v-if="key+1 != Object.keys($breadcrumbs).length - 1"> > </span> </li>' +
//         '        </ul>'
// });
// Vue.use(VueBreadcrumbs, {
//     template: '        <div v-if="$breadcrumbs.length" aria-label="breadcrumb" class="custm_brd_crumbs">\n' +
//         '             <router-link v-for="(crumb, key) in $breadcrumbs" v-if="crumb.meta.breadcrumb" :key="key" class="breadcrumb-item_custom btn btn-primary btn-arrow-right" :to="{ path: getPath(crumb) }">{{ getBreadcrumb(crumb.meta.breadcrumb) }} > {{ getBreadcrumb(crumb.meta.breadcrumb_title) }}</router-link>' +
//         '        </div>'
// });
// Install BootstrapVue
import Vue2Crumbs from 'vue-2-crumbs'

Vue.use(Vue2Crumbs)
Vue.use(BootstrapVue)
    //     // Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)
    // Vue.use(FlagIcon);
    // Register fabric js globally
Vue.use(fabric)
Vue.use(Cropper)
    // Vue.use(VueKonva)
    // register globally
Vue.component('multiselect', Multiselect)
    // Vue.use(vueselect);


//Pagination laravel-vue-pagination
Vue.component('pagination', require('laravel-vue-pagination'));
// spinner register
Vue.component('spinner', require('vue-simple-spinner'));
Vue.use(Loading, {
    // props
    color: 'red'
}, {
    // slots
})

window.Swal = Swal;
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: toast => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    }
});
window.Toast = Toast;

window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
// define a mixin object for global function
// const myMixin = () =>
//     import ( /* webpackChunkName: "myMixin" */ './myMixin.js')
import myMixin from "./myMixin";
Vue.mixin(myMixin);

// Permission check directive
Vue.directive("can", function(el, binding, vnode) {
    var given_permission = binding.value;
    if (typeof given_permission == "string") {
        given_permission = [given_permission];
    }
    if (given_permission) {
        var true_array = [];
        var false_array = [];
        for (let i = 0; i < given_permission.length; i++) {
            if (Permissions.indexOf(given_permission[i]) !== -1) {
                true_array.push(true);
            } else {
                false_array.push(false);
            }
        }
        if (true_array.length) {
            return (vnode.elm.hidden = false);
        } else {
            return (vnode.elm.hidden = true);
        }
    }
});
// Role check directive
Vue.directive("role", function(el, binding, vnode) {
    var given_role = binding.value;
    if (typeof given_role == "string") {
        given_role = [given_role];
    }
    if (given_role) {
        var true_array = [];
        var false_array = [];
        for (let i = 0; i < given_role.length; i++) {
            if (Roles.indexOf(given_role[i]) !== -1) {
                true_array.push(true);
            } else {
                false_array.push(false);
            }
        }
        if (true_array.length) {
            return (vnode.elm.hidden = false);
        } else {
            return (vnode.elm.hidden = true);
        }
    }
});

import { VTooltip, VPopover, VClosePopover } from 'v-tooltip'

Vue.directive('tooltip', VTooltip)
Vue.directive('close-popover', VClosePopover)
Vue.component('v-popover', VPopover)
import CKEditor from '@ckeditor/ckeditor5-vue';
Vue.use(CKEditor);

import 'advanced-laravel-vue-paginate/dist/advanced-laravel-vue-paginate.css'
Vue.use(require('advanced-laravel-vue-paginate'));

Vue.use(require('vue-moment'));



var router = new VueRouter({
    routes: routes,
    linkActiveClass: "active", // active class for non-exact links.
    linkExactActiveClass: "active", // active class for *exact* links.
    mode: "history",
    base: "/jcs",
    // base: `${process.env.APP_ENV}` == 'local' ? "/jcs" : '/',
});
// console.log(app_url);
router.onReady(() => {
    if (Globals.user_info_id == '' && router.currentRoute.path != "/home") {
        router.push("home")
    }
});

// Warnind before route exit
// Vue.mixin({
//     beforeRouteLeave(to, from, next) {
//         const answer = window.confirm('Do you really want to leave? you have unsaved changes!')
//         if (answer) {
//             next()
//         } else {
//             next(false)
//         }
//     }
// })

// Set axios default parameters
// axios.defaults.params = {
//     adm_user_id: Globals.user_info_id ? Globals.user_info_id : '',
//     global_user_type: Globals.global_user_type ? Globals.global_user_type : ''
// };
// axios.defaults.data = {
//     adm_user_id: Globals.user_info_id ? Globals.user_info_id : '',
//     global_user_type: Globals.global_user_type ? Globals.global_user_type : ''
// };
// Set axios default response action
axios.interceptors.response.use(function(response) {
    if (response.data.status == 419 || response.status == 419) {
        window.location.reload();
    }
    return response;
}, function(error) {
    window.location.replace(`${process.env.APP_URL}login`);
    return Promise.reject(error);
});
router.beforeEach(async(to, from, next) => {
    // console.log(to);
    axios.get('api/is-auth').then(({ data }) => {
        if (!data) {
            window.location.reload();
        }
    }).catch(error => console.log("error"));

    if (to.name !== 'home') {
        if (Permissions.indexOf(to.name) === -1) {
            next({ name: 'home' });
        } else {
            next();
        }
    } else {
        next();
    }
    // axios.post('',){

    // }
});
// router.beforeEach(async(to, from, next) => {
//     console.log("Loaded");
// });

import App from "./components/backend/MAIN/app.vue";
import { nextTick } from "q";
import Axios from "axios";
new Vue({
    router: router,
    store,
    render: h => h(App)
}).$mount("#app");