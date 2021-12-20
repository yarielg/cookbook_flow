<template>
   <div class="container">
      <div class="row">
         <div class="col-md-4">
            <v-icon @click="goBack()">
               mdi-arrow-left
            </v-icon> Back

         </div>
         <div class="col-md-8">
            <div class="top-bar-assign" v-show="checkForm()">
               <div class="left bar">
                  <v-menu  :close-on-content-click="false" :nudge-width="200" offset-y transition="scale-transition">
                     <template v-slot:activator="{ on, attrs }">
                        <v-btn depressed v-bind="attrs" v-on="on">
                           Save recipe As
                           <v-icon right dark> mdi-chevron-down </v-icon>
                        </v-btn>
                     </template>

                     <v-card>
                        <v-list>
                           <input type="radio" name="status"  v-model="status" value="Draft" id="option_draft"> <label for="option_draft"> Draft</label><br>
                           <input type="radio" name="status" v-model="status" value="Private" id="option_private"> <label for="option_private"> Personal Recipe</label><br>
                           <input type="radio" name="status" v-model="status" value="Publish" id="option_publish"> <label for="option_publish"> Public recipe</label>
                        </v-list>
                     </v-card>
                  </v-menu>

                  <v-menu  :close-on-content-click="false" :nudge-width="350" offset-y transition="scale-transition">
                     <template v-slot:activator="{ on, attrs }">
                        <v-btn depressed v-bind="attrs" v-on="on">
                           Assign To
                           <v-icon right dark> mdi-chevron-down </v-icon>
                        </v-btn>
                     </template>

                     <v-card>
                        <v-list>
                           <div class="cookbook_option" v-for="(cookbook,index) in cookbooks" :key="cookbook.ID">
                              <input type="checkbox" v-model="cookbooks_ids" :id="cookbook.post_name" :value="cookbook.ID">
                              <label :for="cookbook.post_name"> {{ cookbook.post_title }}</label>
                              <br>
                           </div>
                        </v-list>
                     </v-card>
                  </v-menu>
               </div>
               <div class="right bar">
                  <span class="link_action" @click="goViewRecipe()" >Cancel</span>
                  <button :disabled="!checkForm()" @click="addRecipe(status)" class="btn-normal">{{ edit_mode > 1 ? 'Save' : 'Add' }}</button>
               </div>
            </div>
            <button class="float-right btn-normal" v-show="!checkForm()" @click="addRecipe('Draft')">Save as Draft</button>
         </div>
      </div>
      <div class="row">
         <div class="col-md-4 left-panel">
            <h4 class="text-center">Create a Recipe</h4>
            <hr>
            <ul class="ingredients_list">
               <li><span :class="title !== '' ? 'icon_32' : ''"></span> <span >Give it a title</span></li>
               <li><span :class="category > 0 ? 'icon_32' : ''"></span> <span >Select a category</span></li>
               <li><span :class="ingredients.length !== 0 ? 'icon_32' : ''"></span> <span >Add ingredients</span></li>
               <li><span :class="!isQuillEmpty()  ? 'icon_32' : ''"></span> <span >Add Instructions</span></li>
               <li><span :class="photos.length !== 0 ? 'icon_32' : ''"></span> <span >Add Photo(s)</span></li>
            </ul>
         </div>

         <div class="col-md-8 main-panel">
            <form
               method="post"
               action=""
               @submit.prevent="addRecipe(status)">

               <div class="form-group">
                  <label for="recipe_title">Recipe Title</label>
                  <input v-model="title" type="text" class="form-control" id="recipe_title">
               </div>

               <div class="form-group">
                  <label for="recipe_category">Recipe Category</label>
                  <select v-model="category" name="recipe_category" class="form-control" id="recipe_category">
                     <option value="-1" selected>Select Category</option>
                     <option v-for="category in categories" :key="category.term_id" :value="category.term_id">{{ category.name }}</option>
                  </select>
               </div>

               <div class="form-group">
                  <h5>RECIPE INGREDIENTS</h5>
                  <ul>
                     <li v-for="ingredient in ingredients" :key="ingredient.key" v-if="ingredient.name && ingredient.unit && ingredient.quantity"> {{ ingredient.quantity }} {{ ingredient.unit }} {{ ingredient.name }}</li>
                  </ul>
               </div>
               <div @click="dialogIngredient = true" class="ingredients_action">
                  + Click to start adding recipe ingredients
               </div>

               <ingredient-dialog @addIngredient="addIngredientHandler"
                                  @removeIngredient="removeIngredientHandler"
                                  @closeDialog="closeIngredientDialogHandler()"
                                  :dialogIngredient="dialogIngredient"
                                  :ingredients="ingredients">
               </ingredient-dialog>

               <br><br>

               <div class="form-group">
                  <label for="">Recipe Instructions</label>
                  <div id="editor_instructions" ref="editor"></div>
               </div>

               <br>

               <div class="form-group">
                  <label for="">Add Photos</label>
                  <v-file-input v-model="current_image" @change="fileChanged"  label="Add an image" />
               </div>

               <div class="form-group photo-gallery">
                  <div class="photo-wrapper" v-for="photo in photos">
                     <img class="img-badge"  :src="photo.url" alt="">
                     <span :data-photo-id="photo.id" class="delete_photo_btn" @click="deletePhoto(photo.id)">X</span>
                  </div>
               </div>


            </form>
         </div>
      </div>
   </div>
</template>

<script>
   const axios = require('axios');

    export default {
        props:['edit_mode'],
        data () {
            return {
               dialogIngredient: false,
               current_image: null,
               editor: null,
               categories:[],
               status: 'Draft',
               category: -1,
               title: '',
               ingredients:[
               ],
               instructions:'',
               photos:[],
               cookbooks_ids: [],
               cookbooks:[],
               cookbooks_selected: [],

            }
        },
        created(){
            this.getCategories();
            this.getCookbooks();
            if(parseFloat(this.edit_mode) > 0){
               this.getRecipe();
            }
        },
        setDefaults(){
          this.title = "";
          this.ingredients = [];
          this.instructions = '';
          this.editor = '';
          this.photos = [];
          this.category = -1;
          this.current_image =  null;
          this.status = 'Draft';
          this.cookbooks = [];
          this.cookbooks_ids = [];

        },
       computed:{
          recipe_id(){
             return this.edit_mode;
          }
       },
       mounted(){
          var options = {
             modules: {
                toolbar: [
                   ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                   ['blockquote', 'code-block'],

                   [{ 'header': 1 }, { 'header': 2 }],               // custom button values
                   [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                   [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
                   [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
                   [{ 'direction': 'rtl' }],                         // text direction

                   [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
                   [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

                   [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
                   [{ 'font': [] }],
                   [{ 'align': [] }],

                   ['clean']                                         // remove formatting button
                ]
             },
             placeholder: 'Compose an epic...',
             theme: 'snow'
          };
          this.editor = new Quill('#editor_instructions', options);
       },
        methods:{
           checkField(field,type){
              var flag = false;
              console.log(field)
              switch (type) {
                  case 'text':
                     flag = field !== '';
                     break
              }
           },
           goBack(){
              this.$emit('goBack');
           },
           goViewRecipe(){
              this.$emit('goViewRecipe');
           },
           goViewRecipeWithId(id){
              this.$emit('goViewRecipeWithId',id);
           },
           closeIngredientDialogHandler(){
              this.dialogIngredient = false;
           },
           checkForm(){
              if(parseInt(this.category) !== -1 && this.title !== '' && !this.isQuillEmpty() && this.ingredients.length > 0 && this.photos.length > 0 ){
                 return true;
              }
              return false;
           },
           removeIngredientHandler(key){
              this.ingredients = this.ingredients.filter(function( ingredient ) {
                 return ingredient.key !== key;
              });
           },

           addIngredientHandler(e){
              e.preventDefault();
              this.ingredients.push(
                      {
                         key: this.ingredients.length + 1,
                         name:'',
                         quantity:1,
                         unit: 'oz'
                      }
              );
           },
           addRecipe(status){
              if(this.title !== '' ){
                 const formData = new FormData();
                 formData.append('action', 'add_recipe');
                 formData.append('category', this.category);
                 formData.append('title', this.title);
                 formData.append('instructions',this.editor.root.innerHTML.trim());
                 formData.append('ingredients', JSON.stringify(this.ingredients));
                 formData.append('author_id', parameters.owner.ID);
                 formData.append('photos', JSON.stringify(this.photos));
                 formData.append('status', status);
                 formData.append('cookbooks_ids', this.cookbooks_ids);
                 formData.append('edit', this.edit_mode);

                 axios.post(parameters.ajax_url, formData)
                      .then( response => {
                         if(response.data.success){
                            if(this.edit_mode > 0){
                               toastr.success('The recipe has been updated', 'Recipe Updated!');
                            }else{
                               toastr.success('The recipe has been created', 'Recipe Created!');
                            }

                            this.goViewRecipeWithId(response.data.id)


                         }else{
                            toastr.error('The recipe was not inserted', 'Error');
                         }
                   })
              }else{
                 toastr.warning('You must define a Recipe title', 'Error');
              }
           },
           fileChanged(e){
              if(this.current_image !== null && this.current_image !== ''){
                 const formData = new FormData();
                 formData.append('action', 'add_photo');
                 formData.append('image', this.current_image);

                 axios.post(parameters.ajax_url, formData)
                   .then( response => {
                      if(response.data.success){
                         this.photos.push({
                            id: response.data.photo_id,
                            url: URL.createObjectURL(e)
                         });
                         this.current_image = null;
                      }else{
                         toastr.warning('The photo was not inserted', 'Error');
                      }
                 });
              }
           },
           isQuillEmpty() {
              if(this.editor){
                 if ((this.editor.getContents()['ops'] || []).length !== 1) { return false }
                 return this.editor.getText().trim().length === 0
              }else{
                 return false;
              }

           },
           deletePhoto(photo_id){
              this.photos = this.photos.filter(function( photo ) {
                 return photo.id !== photo_id;
              });
           },
           getCategories(){
              const formData = new FormData();
              formData.append('action', 'get_recipe_categories');
              axios.post(parameters.ajax_url, formData)
                .then( response => {
                   if(response.data.success){
                      this.categories =  response.data.categories;
                   }else{
                      toastr.warning('We could not get the recipe categories', 'Error');
                   }
                });
           },
           getCookbooks(){
              const formData = new FormData();
              formData.append('action', 'get_user_cookbooks');
              formData.append('author_id', parameters.owner.ID)
              axios.post(parameters.ajax_url, formData)
                      .then( response => {
                         if(response.data.success){
                            this.cookbooks =  response.data.cookbooks;
                         }else{
                            toastr.warning('We could not get the cookbooks', 'Error');
                         }
                      });
           },
           getRecipe(){
              const formData = new FormData();
              formData.append('action', 'get_recipe');
              formData.append('id', this.edit_mode);
              formData.append('author_id', parameters.owner.ID)
              axios.post(parameters.ajax_url, formData)
                      .then( response => {
                         if(response.data.success){
                            this.category =  response.data.recipe.category;
                            this.title = response.data.recipe.post_title;
                            this.status = response.data.recipe.post_status;
                            this.ingredients = response.data.recipe.ingredients;
                            this.photos = response.data.recipe.photos;
                            this.editor.root.innerHTML = response.data.recipe.post_content;
                            this.status = response.data.recipe.post_status;
                            this.cookbooks_ids = response.data.recipe.cookbooks_ids;

                         }else{
                            toastr.warning('We could not get the recipe categories', 'Error');
                         }

                      });
           }
        }

    }
</script>

<style>
   img.img-badge {
      width: 100px;
   }

   .photo-wrapper {
      /* width: 70px; */
      display: inline-block;
      background: beige;
      padding: -52px;
      position: relative;
      margin-right: 10px;
   }

   span.delete_photo_btn {
      position: absolute;
      background: red;
      padding: 2px 8px;
      top: -12px;
      right: -12px;
      border-radius: 50%;
      color: white;
   }

   .photo-gallery{
      display: flex;
   }

   .ingredients_list{
      list-style: none !important;
   }

   .ingredients_list li{
      display: flex !important;
      clear: both;
   }

   .ingredients_list li span:first-child{
      min-width: 24px;
      display: inline-block;
   }

   .ingredients_list li.checked{
      text-decoration: line-through;
   }

   .v-input--switch{
      float: right;
   }

   .v-label{
      margin-bottom: 0;
   }

    .v-list{
       padding: 13px;
    }
</style>
