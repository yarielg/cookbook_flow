<template>
    <div class="container">
        <div class="row">
            <div class="col-10">
                <v-icon @click="goBack()">
                    mdi-arrow-left
                </v-icon> Back
            </div>
            <div class="col-2">

            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <h4>Create a Cookbook</h4>
                <hr>
                <!--
                <ul class="ingredients_list">
                    <li :class="title !== '' ? 'checked' : ''"> Give it a title</li>
                    <li :class="category > 0 ? 'checked' : ''"> Select a category</li>
                    <li :class="ingredients.length !== 0 ? 'checked' : ''"> Add ingredients</li>
                    <li :class="!isQuillEmpty()  ? 'checked' : ''"> Add Instructions</li>
                    <li :class="photos.length !== 0 ? 'checked' : ''"> Add Photo(s)</li>
                </ul>
                -->
            </div>
            <div class="col-md-8">
                <form
                        method="post"
                        action=""
                        @submit.prevent="addCookbook()">

                    <div class="form-group">
                        <label for="cookbook_title">Title</label>
                        <input v-model="title" type="text" class="form-control" id="cookbook_title">
                    </div>

                    <div class="form-group">
                        <label for="dedications_title">Dedications</label>
                        <textarea v-model="dedications" class="form-control" id="dedications_title" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="acknowledgments_title">Acknowledgments</label>
                        <textarea v-model="acknowledgments" class="form-control" id="acknowledgments_title" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="introduction_title">Introduction</label>
                        <textarea v-model="introduction" class="form-control" id="introduction_title" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="">Front Cover</label>
                        <v-file-input v-model="front_image" @change="fileChanged(1)"  label="Add an image" />
                    </div>

                    <div class="form-group">
                        <label for="">Back Cover</label>
                        <v-file-input v-model="back_image" @change="fileChanged(2)"  label="Add an image" />
                    </div>

                    <!--<div class="form-group photo-gallery">
                        <div class="photo-wrapper" v-for="photo in photos">
                            <img class="img-badge"  :src="photo.url" alt="">
                            <span :data-photo-id="photo.id" class="delete_photo_btn" @click="deletePhoto(photo.id)">X</span>
                        </div>
                    </div>-->

                    <br>

                    <div class="form-group">
                        <label for="recipe_category">Add Recipes</label>
                        <!--<select  v-model="recipe" name="recipe_search" class="form-control" id="recipe_search">
                            <option value="-1" selected>Select Recipe</option>
                            <option  v-for="recipe in recipes" :key="recipe.ID" :value="recipe"><img class="recipe_img" :src="recipe.photo_url" alt=""> {{ recipe.post_title }}</option>
                        </select>-->
                        <v-combobox
                                @change="selectRecipe()"
                                v-model="selected_recipes"
                                :items="recipes"
                                item-text="post_title"
                                item-value="ID"
                                :search-input.sync="search"
                                hide-selected
                                multiple
                                persistent-hint
                                chips
                        >

                        </v-combobox>
                    </div>



                    <!--<div class="form-group">
                        <label for="">Add Photos</label>
                        <v-file-input v-model="current_image" @change="fileChanged"  label="Add an image" />
                    </div>-->

                    <!--<div class="form-group photo-gallery">
                        <div class="photo-wrapper" v-for="photo in photos">
                            <img class="img-badge"  :src="photo.url" alt="">
                            <span :data-photo-id="photo.id" class="delete_photo_btn" @click="deletePhoto(photo.id)">X</span>
                        </div>
                    </div>-->

                    <button :disabled="!checkForm()"  type="submit" class="btn-normal">{{ edit_mode > 1 ? 'Save Cookbook' : 'Add Cookbook' }}</button>

                </form>
            </div>
        </div>
    </div>
</template>

<script>
    const axios = require('axios');

    export default {
        props:['edit_mode','recipes'],
        data () {
            return {
                title:'',
                dedications:'',
                acknowledgments:'',
                introduction:'',
                front_image: null,
                back_image: null,
                front: -1,
                back: -1,
                recipe: null,
                search:null,
                selected_recipes:[]
            }
        },
        created(){

        },
        setDefaults(){

        },
        computed:{
            myRecipes: function(){
                return this.recipes
            }
        },
        mounted(){

        },
        methods:{
            getTheRecipesIDs(){
                let ids = [];
                for (let i = 0; i < this.selected_recipes.length; i++){
                    ids.push(this.selected_recipes[i].ID);
                }
                return ids;
            },
            selectRecipe(){
            },
            goBack(){
                this.$emit('goBack');
            },
            checkForm(){
                if(this.title !== '' && this.dedications !== '' && this.acknowledgments !== '' && this.introduction !== '' && this.selected_recipes.length > 0 ){
                    return true;
                }
                return false;
            },
            addCookbook(){
                if(this.checkForm() ){
                    const formData = new FormData();
                    formData.append('action', 'add_cookbook');
                    formData.append('title', this.title);
                    formData.append('dedications', this.dedications);
                    formData.append('acknowledgments', this.acknowledgments);
                    formData.append('introduction', this.introduction);
                    formData.append('back', this.back);
                    formData.append('front', this.front);
                    formData.append('recipes', this.getTheRecipesIDs());

                    formData.append('author_id', parameters.owner.ID);

                    formData.append('edit', this.edit_mode);

                    axios.post(parameters.ajax_url, formData)
                        .then( response => {
                            if(response.data.success){
                                if(this.edit_mode > 0){
                                    toastr.success('The cookbook has been updated', 'Cookbook Updated!');
                                }else{
                                    toastr.success('The cookbook has been created', 'Cookbook Created!');
                                    this.goBack();
                                }

                            }else{
                                toastr.error('The cookbook was not inserted', 'Error');
                            }
                        })
                }else{
                    toastr.warning('You have some errors, please correct them.', 'Error');
                }
            },
            fileChanged(type,e){

                if(this.current_image !== null && this.current_image !== ''){
                    const formData = new FormData();
                    formData.append('action', 'add_photo');
                    formData.append('image', type == 1 ? this.front_image : this.back_image);

                    axios.post(parameters.ajax_url, formData)
                        .then( response => {
                            if(response.data.success){
                                if(type == 1){
                                    this.front = response.data.photo_id
                                }else{
                                    this.back = response.data.photo_id
                                }

                            }else{
                                toastr.warning('The photo was not inserted', 'Error');
                            }
                        });
                }
            },
            /*deletePhoto(photo_id){
                this.photos = this.photos.filter(function( photo ) {
                    return photo.id !== photo_id;
                });
            },*/
            getCookbook(){
                const formData = new FormData();
                formData.append('action', 'get_cookbook');
                formData.append('id', this.edit_mode);
                axios.post(parameters.ajax_url, formData)
                    .then( response => {
                        console.log(response.data.recipe)
                        /*if(response.data.success){
                            this.category =  response.data.recipe.category;
                            this.title = response.data.recipe.post_title;
                            this.status = response.data.recipe.post_status;
                            this.ingredients = response.data.recipe.ingredients;
                            this.photos = response.data.recipe.photos;
                            this.editor.root.innerHTML = response.data.recipe.post_content;
                            this.status = response.data.recipe.post_status === "publish" ? true : false;

                        }else{
                            toastr.warning('We could not get the recipe categories', 'Error');
                        }*/

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
        list-style: decimal;
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

    #input-13{
        border: none !important;
    }
</style>
