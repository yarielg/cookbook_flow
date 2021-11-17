import Vue from 'vue'
import App from './App.vue';
import vuetify from './plugin/vuetify.js'

new Vue({
    el: '#vwp-plugin',
    vuetify,
    render: h => h(App)
});

