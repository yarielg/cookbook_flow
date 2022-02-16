<template>
   <div class="container">
      <loading-dialog :loading="loading"></loading-dialog>
      <div class="row">
         <div class="col-6">
            <v-icon @click="goBack()" class="pr-1">mdi-arrow-left</v-icon>Back
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

         <div class="col-md-8 main-panel pl-4">
            <form
               method="post"
               action=""
               @submit.prevent="addRecipe(status,'no')">

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
                  <label for="">Enter the recipe instructions</label>
                  <div id="editor_instructions" ref="editor"></div>
               </div>

               <br>

               <div class="media_component">
                  <div @drop.prevent="onDrop($event)"
                       @dragover.prevent="dragover = true"
                       @dragenter.prevent="dragover = true"
                       @dragleave.prevent="dragover = false"
                       class="drop_media_zone"
                       @click="launchFilePicker()">
                     <img class="media_placeholder" :src="image_placeholder" alt="">
                     <p>Drag a photo or Click to upload</p>
                  </div>

                  <input type="file"
                         ref="file"
                         @change="onFileChange(
                         $event.target.name, $event.target.files)"
                         style="display:none">
               </div>

               <br><br>

               <div class="form-group photo-gallery mt-5">
                  <div class="photo-wrapper" v-for="photo in photos">
                     <img @click="updatePhoto(photo)" class="img-badge" :alt="photo.caption"  :src="photo.url" alt="">
                     <span :data-photo-id="photo.id" class="delete_photo_btn" @click="deletePhoto(photo.id)">X</span>
                     <span class="photo-featured badge badge-secondary" v-if="photo.primary">Featured</span>
                  </div>
               </div>

               <br>
               <div class="form-group story">
                  <label for="">Add your story! (Optional)</label>
                  <div id="editor_story" ref="editorStory"></div>
               </div>

            </form>
            <media-dialog @updateImage="editPhoto" :photo_update="photo_update" @addImage="fileChanged" :current_image="current_image" @closeDialog="closeMediaDialog()" :dialogMedia="dialogMedia" :multiple="true" ></media-dialog>
         </div>
      </div>
   </div>
</template>

<script>
   const axios = require('axios');

   //this is not funny...

    export default {
        props:['edit_mode'],
        data () {
            return {
               loading:false,
               new_cookbook: '',
               dialogIngredient: false,
               dialogMedia: false,
               current_image: null,
               image_placeholder: '',
               editor: null,
               editorStory: null,
               categories:[],
               status: 'Draft',
               category: -1,
               title: '',
               photo_update: false,
               ingredients:[
               ],
               instructions:'',
               story: '',
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
          this.story = '';
          this.editor = '';
          this.editorStory = '';
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
          var options = {
             modules: {
                toolbar: [
                   ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                   ['blockquote'],

                            // custom button values
                   // text direction

                   [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown

                   [{ 'font': [] }],

                ]
             },
             placeholder: '',
             theme: 'snow'
          };

          var storyOptions = {
             modules: {
                toolbar: [
                   ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                   ['blockquote'],

                   // custom button values
                   // text direction

                   [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown

                   [{ 'font': [] }],                                      // remove formatting button
                ]
             },
             placeholder: '',
             theme: 'snow'
          };

          this.editor = new Quill('#editor_instructions', options);
          this.editorStory = new Quill('#editor_story', storyOptions);

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
           launchFilePicker(){
              this.$refs.file.click();
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
                 formData.append('instructions',this.editor.root.innerHTML.trim());
                 formData.append('story',this.editorStory.root.innerHTML.trim());
                 formData.append('ingredients', JSON.stringify(this.ingredients));
                 formData.append('author_id', parameters.owner.ID);
                 formData.append('photos', JSON.stringify(this.photos));
                 formData.append('status', status);
                 formData.append('cookbooks_ids', this.cookbooks_ids);
                 formData.append('edit', this.edit_mode);
                 formData.append('new_cookbook', this.new_cookbook);
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
                         this.photos.push({
                            id: response.data.image.id,
                            url: URL.createObjectURL(this.current_image),
                            caption: image.caption,
                            primary: image.primary
                         });

                         if(image.primary)
                            this.definePrimary(response.data.image.id);

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
              var photoIndex = this.photos.findIndex((obj => obj.id === this.current_image.id));
              photo.id = this.current_image.id;
              photo.url = this.current_image.url;
              this.photos[photoIndex] = photo;

              if(photo.primary)
               this.definePrimary(this.current_image.id)

              this.photo_update = false;
              this.dialogMedia = false;
              this.current_image = false;

           },
           definePrimary(id){
              this.photos.forEach(function(photo){
                 photo.primary = false;
                 if(photo.id === id){
                    photo.primary = true;
                 }
              });
           },
           updatePhoto(photo){
             this.current_image = photo;
             this.photo_update = true;
             this.dialogMedia = true;
           },
           onDrop(e) {
              //this.dragover = false;
              // If there are already uploaded files remove them
             // if (this.uploadedFiles.length > 0) this.uploadedFiles = [];
              if (e.dataTransfer.files.length > 1) {
                 toastr.warning("Only one file can be uploaded at a time..", 'Error');
                 return''
              }

              this.current_image = e.dataTransfer.files[0];
              this.dialogMedia = true;

           },
           onFileChange(fieldName, file) {
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
                 }
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
                            this.title = response.data.recipe.post_title;
                            this.status = response.data.recipe.post_status;
                            this.ingredients = response.data.recipe.ingredients;
                            this.photos = response.data.recipe.photos;
                            this.editor.root.innerHTML = response.data.recipe.post_content;
                            this.editorStory.root.innerHTML = response.data.recipe.story;

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

   .story_image img{
      width: 90px !important;
   }

   #editor_story .ql-editor{
      height: 200px !important;
   }

   .btn-left{
      margin-right: 5px !important;
   }

   .media_component{
      width: 200px;
      height: 200px;
      margin: 0 auto;
   }

   .drop_media_zone{
      text-align: center;
      cursor: pointer;
   }

   .top-bar-assign{
      z-index: 5555;
   }
</style>
