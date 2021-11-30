import Vue from 'vue'
import App from './App.vue';
import toastr from 'toastr';
import vuetify from './plugin/vuetify.js'
import IngredientDialog from "./IngredientDialog.vue";
import AddRecipe from "./AddRecipe.vue";
import AddCookbook from "./AddCookbook.vue";

Vue.component('ingredient-dialog', IngredientDialog)
Vue.component('add-recipe', AddRecipe)
Vue.component('add-cookbook', AddCookbook)

new Vue({
    el: '#vwp-plugin',
    vuetify,
    render: h => h(App)
});

