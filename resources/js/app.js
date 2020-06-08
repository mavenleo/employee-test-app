require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue'
import VueIziToast from 'vue-izitoast';
import "izitoast/dist/css/iziToast.css";
Vue.use(VueIziToast, {position: "topCenter", progressBar: false},);


const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    el: '#app',
});
