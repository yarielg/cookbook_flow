import  Vue from 'vue'

// In your Vue.js component.
import { VueperSlides, VueperSlide } from 'vueperslides'
import 'vueperslides/dist/vueperslides.css'

import App from './App.vue';
import toastr from 'toastr';
import vuetify from './plugin/vuetify.js'
import IngredientDialog from "./IngredientDialog.vue";
import AddRecipe from "./AddRecipe.vue";
import AddCookbook from "./AddCookbook.vue";
import ViewRecipe from "./ViewRecipe.vue";

Vue.component('vueper-slide', VueperSlide)
Vue.component('vueper-slides', VueperSlides)
Vue.component('ingredient-dialog', IngredientDialog)
Vue.component('add-recipe', AddRecipe)
Vue.component('view-recipe', ViewRecipe)
Vue.component('add-cookbook', AddCookbook)


new Vue({
    el: '#vwp-plugin',
    vuetify,
    render: h => h(App)
});

