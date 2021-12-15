<template>
   <div class="container">
      <div class="row">
         <div class="col-10">
            <v-icon @click="goBack()">
               mdi-arrow-left
            </v-icon> Back
         </div>
         <div class="col-2">
            <v-switch
              :label="`Public`"
              v-model="status"
            ></v-switch>
         </div>
      </div>
      <div class="row">
         <div class="col-md-4">
            <h4>Create a Recipe</h4>
            <hr>
            <ul class="ingredients_list">
               <li><span :class="title !== '' ? 'icon_32' : ''"></span> <span >Give it a title</span></li>
               <li><span :class="category > 0 ? 'icon_32' : ''"></span> <span >Select a category</span></li>
               <li><span :class="ingredients.length !== 0 ? 'icon_32' : ''"></span> <span >Add ingredients</span></li>
               <li><span :class="!isQuillEmpty()  ? 'icon_32' : ''"></span> <span >Add Instructions</span></li>
               <li><span :class="photos.length !== 0 ? 'icon_32' : ''"></span> <span >Add Photo(s)</span></li>
            </ul>
         </div>
         <div class="col-md-8">
            <form
               method="post"
               action=""
               @submit.prevent="addRecipe()">

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

               <button :disabled="!checkForm()"  type="submit" class="btn-normal">{{ edit_mode > 1 ? 'Save Recipe' : 'Add Recipe' }}</button>

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
               status: false,
               category: -1,
               title: '',
               ingredients:[
               ],
               instructions:'',
               photos:[]

            }
        },
        created(){
            this.getCategories();
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
        },
       computed:{

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
           goBack(){
              this.$emit('goBack');
           },
           closeIngredientDialogHandler(){
              this.dialogIngredient = false;
              console.log(this.category);
           },
           checkForm(){
              if(this.category !== -1 && this.name !== '' && !this.isQuillEmpty() && this.ingredients.length > 0 && this.photos.length > 0 ){
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
           addRecipe(){
              if(this.checkForm() ){
                 const formData = new FormData();
                 formData.append('action', 'add_recipe');
                 formData.append('category', this.category);
                 formData.append('title', this.title);
                // formData.append('instructions', JSON.stringify(this.editor.root.innerHTML.trim()));
                 formData.append('instructions',this.editor.root.innerHTML.trim());
                 formData.append('ingredients', JSON.stringify(this.ingredients));
                 formData.append('author_id', parameters.current_user.data.ID);
                 formData.append('photos', JSON.stringify(this.photos));
                 formData.append('status', this.status ? 'publish' : 'draft');
                 formData.append('edit', this.edit_mode);

                 axios.post(parameters.ajax_url, formData)
                      .then( response => {
                         if(response.data.success){
                            if(this.edit_mode > 0){
                               toastr.success('The recipe has been updated', 'Recipe Updated!');
                            }else{
                               toastr.success('The recipe has been created', 'Recipe Created!');
                               this.$emit('goViewRecipe');
                            }

                         }else{
                            toastr.error('The recipe was not inserted', 'Error');
                         }
                   })
              }else{
                 toastr.warning('you have some errors, please correct them.', 'Error');
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
           getRecipe(){
              const formData = new FormData();
              formData.append('action', 'get_recipe');
              formData.append('id', this.edit_mode);
              axios.post(parameters.ajax_url, formData)
                      .then( response => {
                         console.log(response.data.recipe)
                         if(response.data.success){
                            this.category =  response.data.recipe.category;
                            this.title = response.data.recipe.post_title;
                            this.status = response.data.recipe.post_status;
                            this.ingredients = response.data.recipe.ingredients;
                            this.photos = response.data.recipe.photos;
                            this.editor.root.innerHTML = response.data.recipe.post_content;
                            this.status = response.data.recipe.post_status === "publish" ? true : false;

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
</style>
