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
import ViewCookbook from "./ViewCookbook.vue";
import Collaborators from "./Collaborators.vue";
import CollaboratorDialog from "./CollaboratorDialog.vue";
import PublishView from "./PublishView.vue";
import Loading from "./Loading.vue";
import MediaDialog from "./MediaDialog.vue";
import TemplateDialog from "./TemplateDialog.vue";
import PostCardDialog from "./PostCardDialog.vue";

Vue.component('vueper-slide', VueperSlide);
Vue.component('vueper-slides', VueperSlides);
Vue.component('ingredient-dialog', IngredientDialog);
Vue.component('add-recipe', AddRecipe);
Vue.component('view-recipe', ViewRecipe);
Vue.component('view-cookbook', ViewCookbook);
Vue.component('add-cookbook', AddCookbook);
Vue.component('collaborators', Collaborators);
Vue.component('collaborator-dialog', CollaboratorDialog);
Vue.component('publish-view', PublishView);
Vue.component('loading-dialog', Loading);
Vue.component('media-dialog', MediaDialog);
Vue.component('template-dialog', TemplateDialog);
Vue.component('postcard-dialog', PostCardDialog);

new Vue({
    el: '#vwp-plugin',
    vuetify,
    render: h => h(App)
});
