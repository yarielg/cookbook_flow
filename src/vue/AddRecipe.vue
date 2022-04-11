<template>
   <div class="container">
      <loading-dialog :loading="loading"></loading-dialog>
      <div class="row align-items-center">
         <div class="col-6">
            <div class="back-arrow">
               <v-icon @click="goBack()" class="pr-1">mdi-arrow-left</v-icon>Back 
            </div>
         </div>
         <div class="col-6">
            <button class="float-right btn-normal" v-show="!checkForm()" @click="addRecipe('Draft','no')">Save as Draft</button>
         </div>
         <div class="col-12">
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
                        <hr>
                        <v-card-actions>
                           <input v-model="new_cookbook" type="text" id="add_new_cookbook" name="add_new_cookbook" class="mr-3" placeholder="Add to new cookbook">
                           <button color="btn-normal" @click="addRecipe(status,'new')">Create</button>
                        </v-card-actions>
                     </v-card>
                  </v-menu>
               </div>
               <div class="right bar">
                  <span class="link_action" @click="goViewRecipe()" >Cancel</span>
                  <button :disabled="!checkForm()" @click="addRecipe(status,'no')" class="btn-normal">{{ edit_mode > 1 ? 'Save' : 'Add' }}</button>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-3 left-panel recipe_items">
            <h4 class="pl-7 pt-3 pb-1 mb-0">Create a Recipe</h4>
            <hr>
            <ol class="ingredients_list pt-5 ml-2">
               <li :class="title !== '' ? 'completed' : ''"><span :class="title !== '' ? 'icon_32' : ''"></span> <span >Give it a title</span></li>
               <li :class="category > 0 ? 'completed' : ''"><span :class="category > 0 ? 'icon_32' : ''"></span> <span >Select a category</span></li>
               <!--<li :class="ingredients.length !== 0 ? 'completed' : ''"><span :class="ingredients.length !== 0 ? 'icon_32' : ''"></span> <span >Add ingredients</span></li>-->
               <li :class="ingredients !== ''  ? 'completed' : ''"><span :class="ingredients !== ''  ? 'icon_32' : ''"></span> <span >Add Ingredients</span></li>
               <li :class="instructions !== ''  ? 'completed' : ''"><span :class="instructions !== ''  ? 'icon_32' : ''"></span> <span >Add Instructions</span></li>
               <li :class="food_photo.length !== 0 ? 'completed' : ''"><span :class="food_photo.length !== 0 ? 'icon_32' : ''"></span> <span >Add Food Photo</span></li>
            </ol>
         </div>

         <div class="col-md-9 main-panel">
            <form
               method="post"
               action=""
               @submit.prevent="addRecipe(status,'no')">

               <div class="form-group">
                  <label for="recipe_title">Recipe Title</label>
                  <input maxlength="60" @keydown="onKeyDown($event,title,60)" v-model="title" type="text" class="form-control" id="recipe_title" placeholder="Give your recipe a title">
               </div>

               <div class="form-group">
                  <label for="recipe_category">Recipe Category</label>
                  <select v-model="category" name="recipe_category" class="form-control" id="recipe_category">
                     <option value="-1" selected>Select a Category</option>
                     <option v-for="category in categories" :key="category.term_id" :value="category.term_id">{{ category.name }}</option>
                  </select>
               </div>

               <div class="form-group">
                  <label for="recipe_category">Recipe Country</label>
                  <select v-model="country" name="recipe_country" class="form-control" id="recipe_country">
                     <option value="-1" selected>Select a Country</option>
                     <option v-for="(name, key) in countries" :key="key" :value="key">{{ name }}</option>
                  </select>
               </div>

               <div class="form-group"> 
                  <label for="ingredients">INGREDIENTS</label>
                  <textarea maxlength="2300" @keydown="onKeyDown($event,ingredients,2300)" v-model="ingredients" class="form-control" id="ingredients" rows="5"></textarea>
               </div>


               <div class="form-group">
                  <label for="instructions">RECIPE INSTRUCTIONS</label>
                  <textarea maxlength="2300" @keydown="onKeyDown($event,instructions,2300)" v-model="instructions" class="form-control" id="instructions" rows="5"></textarea>
               </div>

               <br>

               <label>ADD ONE FOOD PHOTO</label>
               <div class="media_component">
                  <div @drop.prevent="onDrop($event,'food')"
                       @dragover.prevent="dragover = true"
                       @dragenter.prevent="dragover = true"
                       @dragleave.prevent="dragover = false"
                       class="drop_media_zone"
                       @click="launchFilePicker('food')">
                     <img class="media_placeholder" :src="image_placeholder" alt="">
                     <p class="media-placeholder-title">Drag a photo here or <strong>upload.</strong></p>
                  </div>

                  <input type="file"
                         ref="file"
                         @change="onFileChange(
                         $event.target.name, $event.target.files, false,'food')"
                         style="display:none">
               </div>


               <div class="form-group photo-gallery mt-5">
                  <div class="photo-wrapper" v-for="photo in food_photo">
                     <img  class="img-badge" :alt="photo.caption"  :src="photo.url" alt="">
                     <span :data-photo-id="photo.id" class="delete_photo_btn" @click="deletePhoto(photo.id,'food')">X</span>
                    <!-- <span class="photo-featured badge badge-secondary" v-if="photo.primary">Featured</span>-->
                  </div>
               </div>

               <br>

            </form>
         </div>
      </div>
      <br>

      <div class="row mt-4">
         <div class="col-md-3 left-panel recipe_items">
            <h4 class="pl-7 pt-3 pb-1 mb-0">Share Your Story</h4>
            <hr>
            <ol class="ingredients_list pt-5 ml-2">
               <li :class="headline_story !== '' ? 'completed' : ''"><span :class="headline_story !== '' ? 'icon_32' : ''"></span> <span >Give Your Story a Headline</span></li>
               <li :class="story !== '' ? 'completed' : ''"><span :class="story !== '' ? 'icon_32' : ''"></span> <span >Share Your Story</span></li>
               <li :class="story_photo.length !== 0 ? 'completed' : ''"><span :class="story_photo.length !== 0 ? 'icon_32' : ''"></span> <span >Add a Photo</span></li>
            </ol>
         </div>

         <div class="col-md-9 main-panel">
            <h4>SHARE YOUR STORY (OPTIONAL)</h4>
            <div class="form-group story">
               <label for="headline_story">HEADLINE (Optional)</label>
               <input maxlength="60" @keydown="onKeyDown($event,title,60)" v-model="headline_story" type="text" class="form-control" id="headline_story">
            </div>

            <div class="form-group story">
               <label for="story">ADD YOUR STORY! (Optional)</label>
               <textarea maxlength="2300" @keydown="onKeyDown($event,story,2300)" v-model="story" class="form-control" id="story" rows="5"></textarea>
            </div>

            <label>ADD A PHOTO (OPTIONAL)</label>
            <div class="media_component">
               <div @drop.prevent="onDrop($event,'story')"
                    @dragover.prevent="dragover = true"
                    @dragenter.prevent="dragover = true"
                    @dragleave.prevent="dragover = false"
                    class="drop_media_zone"
                    @click="launchFilePicker('story')">
                  <img class="media_placeholder" :src="image_placeholder" alt="">
                  <p class="media-placeholder-title">Drag a photo here or <strong>upload.</strong></p>
               </div>

               <input type="file"
                      ref="file_story"
                      @change="onFileChange(
                         $event.target.name, $event.target.files, true,'story')"
                      style="display:none">
            </div>

            <div class="form-group photo-gallery mt-5">
               <div class="photo-wrapper" v-for="photo in story_photo">
                  <img class="img-badge" :alt="photo.caption"  :src="photo.url" alt="">
                  <span :data-photo-id="photo.id" class="delete_photo_btn" @click="deletePhoto(photo.id,'story')">X</span>
                  <!--<span class="photo-featured badge badge-secondary" v-if="photo.primary">Featured</span>-->
               </div>
            </div>
         </div>
      </div>
      <media-dialog @updateImage="editPhoto" :caption="caption" :photo_update="photo_update" @addImage="fileChanged" :current_image="current_image" @closeDialog="closeMediaDialog()" :dialogMedia="dialogMedia" :multiple="true" ></media-dialog>
   </div>
</template>

<script>
   const axios = require('axios');

    export default {
        props:['edit_mode'],
        data () {
            return {
               loading:false,
               new_cookbook: '',
               dialogMedia: false,
               current_image: null,
               image_placeholder: '',
               editor: null,
               editorStory: null,
               editorIngredients: null,
               categories:[],
               countries:[],
               status: 'Publish',
               category: -1,
               country: -1,
               title: '',
               photo_update: false,
               instructions:'',
               story: '',
               ingredients: '',
               food_photo:[],
               cookbooks_ids: [],
               cookbooks:[],
               cookbooks_selected: [],
               caption: false,
               headline_story: "",
               story_photo: [],
               image_type: 'food'

            }
        },
        created(){
            this.getCategories();
            this.getCookbooks();
            this.getCountries();
            if(parseFloat(this.edit_mode) > 0){
               this.getRecipe();
            }
        },
        setDefaults(){
          this.title = "";
          this.ingredients = '';
          this.instructions = '';
          this.story = '';
          this.headline_story = '';
          this.editor = '';
          this.editorStory = '';
          this.editorIngredients = '';
          this.food_photo = [];
          this.story_photo = [];
          this.category = -1;
          this.current_image =  null;
          this.status = 'Draft';
          this.cookbooks = [];
          this.cookbooks_ids = [];

        },
       computed:{
          recipe_id(){
             return this.edit_mode;
          },
          editMode(){
             if(this.edit_mode > 0){
                return true;
             }else{
                return false
             }
          }
       },
       updated() {
           this.image_placeholder = parameters.plugin_path + '/assets/images/image.png'
       },
       mounted(){

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
           onKeyDown(evt,element,max){
              if (element.length >= max) {
                 if (evt.keyCode >= 48 && evt.keyCode <= 90) {
                    evt.preventDefault()
                    return
                 }
              }
           },
           launchFilePicker(type){
              if(type === 'food'){
                 this.$refs.file.click();
              }else{
                 this.$refs.file_story.click();
              }

           },
           goViewRecipe(){
              if(this.edit_mode > 0){
                 this.$emit('goViewRecipe');
              }else{
                 this.goBack();
              }
           },
           goCookbookEditWithId(id){
              this.$emit('goCookbookEditWithId',id);
           },
           closeMediaDialog(){
              this.current_image = null;
              this.photo_update = false;
              this.dialogMedia = false;
           },
           goViewRecipeWithId(id){
              this.$emit('goViewRecipeWithId',id);
           },
           closeIngredientDialogHandler(){
              this.dialogIngredient = false;
           },
           checkForm(){
              if(parseInt(this.category) !== -1 && this.title !== '' && this.instructions !== '' && this.ingredients !=='' && this.country !== '-1'){
                 return true;
              }
              return false;
           },
           /*removeIngredientHandler(key){
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
           },*/
           addRecipe(status, type){
              if(type === 'new' && this.new_cookbook === ''){
                 toastr.error('You must define a valid cookbook name', 'Error');
                 return;
              }
              if(this.title !== '' ){
                 const formData = new FormData();
                 formData.append('action', 'add_recipe');
                 formData.append('category', this.category);
                 formData.append('title', this.title);
                 formData.append('instructions',this.instructions);
                 formData.append('story',this.story);
                 formData.append('headline_story',this.headline_story);
                 //formData.append('ingredients', JSON.stringify(this.ingredients));
                 formData.append('ingredients', this.ingredients);
                 formData.append('author_id', parameters.owner.ID);
                 formData.append('photos', JSON.stringify(this.food_photo));
                 formData.append('story_photos', JSON.stringify(this.story_photo));
                 formData.append('status', status);
                 formData.append('cookbooks_ids', this.cookbooks_ids);
                 formData.append('edit', this.edit_mode);
                 formData.append('new_cookbook', this.new_cookbook);
                 formData.append('country', this.country);
                 formData.append('type', type);

                 this.loading = true;

                 axios.post(parameters.ajax_url, formData)
                      .then( response => {
                         if(response.data.success){
                            if(this.edit_mode > 0){
                               toastr.success('The recipe has been updated', 'Recipe Updated!');
                            }else{
                               toastr.success('The recipe has been created', 'Recipe Created!');
                            }

                            /**
                             * Where redirect depending on the user selection
                             */
                            if(type === 'new'){
                               this.goCookbookEditWithId(response.data.cookbook_id);
                               return;
                            }

                            this.goViewRecipeWithId(response.data.id)

                         }else{
                            toastr.error('The recipe was not inserted', 'Error');
                         }
                   })
              }else{
                 toastr.warning('You must define a Recipe title', 'Error');
              }
              this.loading = false;
           },
           fileChanged(image){
              if(this.current_image !== null && this.current_image !== ''){
                 const formData = new FormData();
                 formData.append('action', 'add_photo');
                 formData.append('image', this.current_image);
                 /*formData.append('caption', this.caption);
                 formData.append('primary', this.primary);*/

                 this.loading = true;

                 axios.post(parameters.ajax_url, formData)
                   .then( response => {
                      if(response.data.success){
                         if(this.image_type === 'food'){
                            this.food_photo = [{
                               id: response.data.image.id,
                               url: URL.createObjectURL(this.current_image),
                               caption: image.caption,
                              /* primary: image.primary*/
                            }];
                         }else{
                            this.story_photo = [{
                               id: response.data.image.id,
                               url: URL.createObjectURL(this.current_image),
                               caption: image.caption,
                              /* primary: image.primary*/
                            }];
                         }

                         /*if(image.primary)
                            this.definePrimary(response.data.image.id);*/

                         this.current_image = null;
                      }else{
                         toastr.warning('The photo was not inserted', 'Error');
                      }
                      this.closeMediaDialog()
                      this.loading = false;


                 });
              }
           },
           editPhoto(photo){
              /*var photoIndex = this.food_photo.findIndex((obj => obj.id === this.current_image.id));
              photo.id = this.current_image.id;
              photo.url = this.current_image.url;
              this.food_photo[photoIndex] = photo;

              if(photo.primary)
               this.definePrimary(this.current_image.id)

              this.photo_update = false;
              this.dialogMedia = false;
              this.current_image = false;*/

           },
           definePrimary(id){
              this.food_photo.forEach(function(photo){
                 photo.primary = false;
                 /*if(photo.id === id){
                    photo.primary = true;
                 }*/
              });
           },
           updatePhoto(photo){
             this.current_image = photo;
             this.photo_update = true;
             this.dialogMedia = true;
           },
           onDrop(e,type) {
              //this.dragover = false;
              // If there are already uploaded files remove them
             // if (this.uploadedFiles.length > 0) this.uploadedFiles = [];
              if (e.dataTransfer.files.length > 1) {
                 toastr.warning("Only one file can be uploaded at a time..", 'Error');
                 return;
              }

              this.current_image = e.dataTransfer.files[0];
              this.dialogMedia = true;
              this.image_type = type;

           },
           onFileChange(fieldName, file, caption,type) {
              const { maxSize } = this
              let imageFile = file[0];

              if (file.length>0) {
                 let size = imageFile.size / maxSize / maxSize
                 if (!imageFile.type.match('image.*')) {
                    toastr.warning('Please choose an image file', 'Error');
                 } else if (size>1) {
                    toastr.warning('Your file is too big! Please select an image under 1MB', 'Error');
                 } else {

                    this.current_image = imageFile;
                    this.dialogMedia = true;
                    this.caption = caption;
                    this.image_type = type;
                 }
              }
           },
           isQuillEmpty(editor) {
              if(editor){
                 if ((editor.getContents()['ops'] || []).length !== 1) { return false }
                 return editor.getText().trim().length === 0
              }else{
                 return false;
              }

           },
           deletePhoto(photo_id, type){
              console.log('Deleting Image')
              if(type === 'food'){
                 this.food_photo = this.food_photo.filter(function( photo ) {
                    return photo.id !== photo_id;
                 });
              }else{
                 this.story_photo = this.story_photo.filter(function( photo ) {
                    return photo.id !== photo_id;
                 });
              }

           },
           getCountries(){
              const formData = new FormData();
              formData.append('action', 'get_countries');
              this.loading = true;
              axios.post(parameters.ajax_url, formData)
                      .then( response => {
                         if(response.data.success){
                            this.countries =  response.data.countries;
                         }else{
                            toastr.warning('We could not get the recipe countries', 'Error');
                         }
                         this.loading = false;
                      });
           },
           getCategories(){
              const formData = new FormData();
              formData.append('action', 'get_recipe_categories');
              this.loading = true;
              axios.post(parameters.ajax_url, formData)
                .then( response => {
                   if(response.data.success){
                      this.categories =  response.data.categories;
                   }else{
                      toastr.warning('We could not get the recipe categories', 'Error');
                   }
                   this.loading = false;
                });
           },
           getCookbooks(){
              const formData = new FormData();
              formData.append('action', 'get_user_cookbooks');
              formData.append('author_id', parameters.owner.ID);
              this.loading = true;
              axios.post(parameters.ajax_url, formData)
                      .then( response => {
                         if(response.data.success){
                            this.cookbooks =  response.data.cookbooks;
                         }else{
                            toastr.warning('We could not get the cookbooks', 'Error');
                         }
                         this.loading = false;
                      });
           },
           getRecipe(){
              const formData = new FormData();
              formData.append('action', 'get_recipe');
              formData.append('id', this.edit_mode);
              formData.append('author_id', parameters.owner.ID);
              this.loading = true;
              axios.post(parameters.ajax_url, formData)
                      .then( response => {
                         if(response.data.success){
                            this.category =  response.data.recipe.category;
                            this.country =  response.data.recipe.country;
                            this.title = response.data.recipe.post_title;
                            this.status = response.data.recipe.post_status;
                            this.food_photo = response.data.recipe.photos;
                            this.story_photo = response.data.recipe.story_photos;
                            this.ingredients = response.data.recipe.ingredients;
                            this.instructions = response.data.recipe.instructions;
                            this.story = response.data.recipe.story;
                            this.headline_story = response.data.recipe.headline_story;
                            this.status = response.data.recipe.post_status;
                            this.cookbooks_ids = response.data.recipe.cookbooks_ids;

                         }else{
                            toastr.warning('We could not get the recipe categories', 'Error');
                         }
                         this.loading = false;
                      });
           }
        }

    }
</script>

<style scoped>
   img.img-badge {
      width: 100px;
   }

   .photo-featured{
      position: absolute;
      bottom: 0;
      left: 5px;
   }

   .photo-wrapper {
      /* width: 70px; */
      display: inline-block;
      padding: -52px;
      position: relative;
      margin-right: 23px;
   }

   span.delete_photo_btn {
      position: absolute;
      background: red;
      padding: 5px 10px;
      top: -12px;
      right: -12px;
      border-radius: 50%;
      color: white;
      font-size: 10px;
      cursor: pointer;
   }

   .ingredients_list{
      list-style: none !important;
   }

   .ingredients_list li{    
      list-style: auto;
      clear: both;
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

   .story_image img{
      width: 90px !important;
   }

   #editor_story .ql-editor{
      height: 200px !important;
   }

   .btn-left{
      margin-right: 5px !important;
   }


   .top-bar-assign{
      z-index: 5555;
      position: fixed;
      top: 36px;
      width: 75%;

   }

   textarea.form-control{
      resize: none;
   }

</style>
