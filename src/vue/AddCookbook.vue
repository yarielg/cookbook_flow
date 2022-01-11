<template>
    <div class="container">
        <loading-dialog :loading="loading"></loading-dialog>
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
                        <textarea v-model="dedication" class="form-control" id="dedications_title" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="acknowledgments_title">Acknowledgements</label>
                        <textarea v-model="acknowledgments" class="form-control" id="acknowledgments_title" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="introduction_title">Introduction</label>
                        <textarea v-model="introduction" class="form-control" id="introduction_title" rows="3"></textarea>
                    </div>

                    <!--<div class="form-group">
                        <label for="">Front Cover</label>
                        <v-file-input v-model="front_image" @change="fileChanged(1)"  label="Add an image" />
                    </div>-->

                    <!--<div v-if="front_image !== null" class="form-group photo-gallery">
                        <div class="photo-wrapper">
                            <img class="img-badge"  :src="front_image.url" alt="">
                            <span :data-photo-id="front_image.id" class="delete_photo_btn" @click="deletePhoto(front_image.id,1)">X</span>
                        </div>
                    </div>-->

                    <div class="form-group">
                        <label for="">Back Cover</label>
                        <v-file-input v-model="back_image" @change="fileChanged(2)"  label="Add an image" />
                    </div>

                    <div v-if="back_image !== null" class="form-group photo-gallery">
                        <div class="photo-wrapper">
                            <img class="img-badge" :src="back_image.url" alt="">
                            <span :data-photo-id="back_image.id" class="delete_photo_btn" @click="deletePhoto(back_image.id,2)">X</span>
                        </div>
                    </div>


                    <br>

                    <div class="form-group">
                        <label>Add Recipes</label>
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
                                close
                                chips
                        >

                        </v-combobox>
                    </div>
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
                loading:false,
                dedication:'',
                acknowledgments:'',
                introduction:'',
                //front_image: null,
                back_image: null,
                recipe: null,
                search:null,
                selected_recipes:[]
            }
        },
        created(){
            if(parseFloat(this.edit_mode) > 0){
                this.getCookbook();
            }
        },
        setDefaults(){
            this.title = "";
            this.dedication = "";
            this.acknowledgments= "";
            this.introduction= "";
            //this.front_image = null;
            this.back_image = null;
            this.recipe = null;
            this.search = null;
            this.selected_recipes = [];
        },
        computed:{
            myRecipes: function(){
                return this.recipes
            },
            editMode(){
                if(this.edit_mode > 0){
                    return true;
                }else{
                    return false
                }
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
            goViewCookbook(){
                if(this.edit_mode > 0){
                    this.$emit('goViewCookbook');
                }else{
                    this.goBack();
                }
            },
            goBack(){
                this.$emit('goBack');
            },
            checkForm(){
                if(this.title !== '' && this.dedication !== '' && this.acknowledgments !== '' && this.introduction !== '' && this.selected_recipes.length > 0 ){
                    return true;
                }
                return false;
            },
            addCookbook(){
                if(this.checkForm() ){
                    const formData = new FormData();
                    formData.append('action', 'add_cookbook');
                    formData.append('title', this.title);
                    formData.append('dedication', this.dedication);
                    formData.append('acknowledgments', this.acknowledgments);
                    formData.append('introduction', this.introduction);
                    formData.append('back', this.back_image !== null ? this.back_image.id : -1);
                   //formData.append('front', this.front_image !== null ? this.front_image.id : -1);
                    formData.append('recipes', this.getTheRecipesIDs());
                    formData.append('author_id', parameters.owner.ID);
                    formData.append('edit', this.edit_mode);

                    this.loading= true;

                    axios.post(parameters.ajax_url, formData)
                        .then( response => {
                            if(response.data.success){
                                if(this.edit_mode > 0){
                                    toastr.success('The cookbook has been updated', 'Cookbook Updated!');
                                }else{
                                    toastr.success('The cookbook has been created', 'Cookbook Created!');
                                    this.goCookbookWithId(response.data.id)
                                }

                            }else{
                                toastr.error('The cookbook was not inserted', 'Error');
                            }
                            this.loading= false;
                        })
                }else{
                    toastr.warning('You have some errors, please correct them.', 'Error');
                }
            },
            fileChanged(type,e){

                const formData = new FormData();
                formData.append('action', 'add_photo');
                formData.append('image', type == 1 ? this.front_image : this.back_image);

                if((type === 1 && this.front_image !== null) || type === 2 && this.back_image !== null){
                    this.loading = true;
                    axios.post(parameters.ajax_url, formData)
                    .then( response => {
                        if(response.data.success){
                            if(type == 1){
                                /*this.front_image.id = response.data.image.id;
                                this.front_image.url = response.data.image.url;*/
                            }else{
                                this.back_image.id = response.data.image.id;
                                this.back_image.url = response.data.image.url;
                            }

                        }else{
                            toastr.warning('The photo was not inserted', 'Error');
                        }

                        this.loading = false;
                    });
                }else{
                    if(type == 1){
                        //this.front_image = null;
                    }else{
                        this.back_image = null;
                    }
                }
            },
            deletePhoto(photo_id,type){
                if(type == 1){
                    //this.front_image = null;
                }else{
                    this.back_image = null;
                }
            },
            getCookbook(){
                const formData = new FormData();
                formData.append('action', 'get_cookbook');
                formData.append('id', this.edit_mode);
                this.loading = true;
                axios.post(parameters.ajax_url, formData)
                    .then( response => {
                        if(response.data.success){
                            this.title = response.data.cookbook.post_title;
                            this.dedication = response.data.cookbook.dedication;
                            this.introduction = response.data.cookbook.introduction;
                            this.acknowledgments = response.data.cookbook.acknowledgments;
                            this.selected_recipes = response.data.cookbook.selected_recipes;
                            //this.front_image = response.data.cookbook.front_image;
                            this.back_image = response.data.cookbook.back_image;

                        }else{
                            toastr.warning('We could not get the recipe categories', 'Error');
                        }
                        this.loading = false;
                    });
            },
            goCookbookWithId(id){
                this.$emit('goCookbookWithId',id);
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
