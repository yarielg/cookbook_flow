<template>
   <div class="container">
      <div class="row">
         <div class="col-12">
            <v-icon @click="goBack()">
               mdi-arrow-left
            </v-icon> Back
         </div>
      </div>
      <div class="row">
         <div class="col-md-4">
            <h4>Create a Recipe</h4>
            <hr>
            <ul>
               <li>1. Select a category</li>
               <li>2. Give it a title</li>
               <li>3. Add ingredients</li>
               <li>4. Add Instructions</li>
               <li>5. Add Photo(s)</li>
               <li>6. Recipe review</li>
            </ul>
         </div>
         <div class="col-md-8">
            <form
               method="post"
               action=""
               @submit.prevent="addRecipe()">

               <div class="form-group">
                  <label for="recipe_title">Title</label>
                  <input v-model="title" type="text" class="form-control" id="recipe_title">
               </div>

               <div class="form-group">
                  <label for="recipe_category">Category</label>
                  <select v-model="category" name="recipe_category" class="form-control" id="recipe_category">
                     <option value="-1" selected>Select Category</option>
                     <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                  </select>
               </div>

               <div class="form-group">
                  <h5>RECIPE INGREDIENTS</h5>
                  <p v-for="ingredient in ingredients" :key="ingredient.key" v-if="ingredient.name && ingredient.unit && ingredient.quantity">{{ ingredient.key }}. {{ ingredient.quantity }} {{ ingredient.unit }} {{ ingredient.name }}</p>
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

               <div class="form-group">
                  <label for="recipe_instructions">Instructions</label>
                  <textarea name="recipe_instructions" v-model="instructions"  class="form-control" id="recipe_instructions"></textarea>
               </div>

               <div class="form-group">
                  <v-file-input v-model="current_image" required @change="fileChanged"  label="Add an image" />
               </div>

               <div class="form-group photo-gallery">
                  <div class="photo-wrapper" v-for="photo in photos">
                     <img class="img-badge"  :src="photo.url" alt="">
                     <span :data-photo-id="photo.id" class="delete_photo_btn" @click="deletePhoto(photo.id)">X</span>
                  </div>
               </div>



               <button type="submit" class="btn btn-primary">Add Recipe</button>

            </form>
         </div>
      </div>
   </div>
</template>


<script>
   const axios = require('axios');

    export default {
        props:[],
        data () {
            return {
               dialogIngredient: false,
               current_image: null,
               categories:[
                  {
                     id: 1,
                     name: 'Cakes and Bakery'
                  },{
                     id: 2,
                     name: 'Other Stuff'
                  }
               ],
               category: -1,
               title: '',
               ingredients:[
               ],
               instructions:'',
               photos:[]

            }
        },
        created(){
        },
        methods:{
           goBack(){
              this.$emit('goBack');
           },
           closeIngredientDialogHandler(){
              this.dialogIngredient = false;
           },
           checkForm(){
               //asdasd
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
                         unit: ''
                      }
              );
           },
           addRecipe(){
              if(this.category !== -1 && this.name !== '' && this.instructions !== '' && this.ingredients.length > 0 ){
                 const formData = new FormData();
                 formData.append('action', 'add_recipe');
                 formData.append('category', this.category);
                 formData.append('title', this.title);
                 formData.append('instructions', this.instructions);
                 formData.append('ingredients', JSON.stringify(this.ingredients));
                 formData.append('author_id', parameters.current_user.data.ID);
                 formData.append('photos', JSON.stringify(this.photos));

                 axios.post(parameters.ajax_url, formData)
                      .then( response => {
                         if(response.data.success){
                            toastr.success('The recipe has been created', 'Recipe Created!');
                         }else{
                            toastr.error('The recipe was not inserted', 'Error');
                         }
                   })
              }else{
                 toastr.warning('you have some field errors, please correct them.', 'Error');
              }
           },
           removePhoto(photo_id){

           },
           fileChanged(e){

              if(this.current_image !== null && this.current_image !== ''){
                 const formData = new FormData();
                 formData.append('action', 'add_recipe_photo');
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
           deletePhoto(photo_id){
              console.log(photo_id)
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
      top: 12px;
      right: 12px;
      border-radius: 50%;
      color: white;
   }

   .photo-gallery{
      display: flex;
   }
</style>
