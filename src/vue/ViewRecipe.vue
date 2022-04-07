<template>
    <div class="container">
        <loading-dialog :loading="loading"></loading-dialog>
        <div class="row">
            <div class="col-12">
                <v-icon class="pr-1" @click="goBack()">
                    mdi-arrow-left
                </v-icon> Back
            </div>

        </div>
        <div class="row">
            <div class="col-md-4 left-panel pl-5">
                <h4 class="text-center mt-2 pt-2">Recipe Information</h4>
                <hr>
                <label @click="editRecipe()" class="label-icon-edit mt-2 pt-2" for="">Cookbook used in:</label>
                <p class="left-info"><strong>{{ first_cookbook_name }}</strong></p>
                <p>{{ other_cookbooks_count }}</p>
                <br>
                <label @click="editRecipe()" for="" class="label-icon-edit">Recipe Type:</label>
                <h4 class="left-info">{{status}} Recipe</h4>
                <br>

                <div class="action_btn_icon" @click="editRecipe()" >
                    <label class="label-icon-edit" for=""></label>
                    <span class="action_icon">Edit Recipe</span>
                </div>
                <br>

                <span @click="deleteRecipe" class="action_secondary delete-icon mt-5 pt-5">Delete Recipe</span>

                <br><br><br>
                <label for="">Share Options:</label>
                <br>
                <div class="share_section row mt-1 text-center">
                    <div class="col-12 text-center"  v-if="status !== 'Draft' && !share">
                        <button class="btn-normal" @click="postcard_dialog = true">Send a Postcard from the Kitchen</button>
                        <p>Your recipe will be automatically sent in an email!</p>
                    </div>
                    <postcard-dialog :dashboard="false" :recipe="recipe" @closePostCardDialog="closePostCardDialog" :postcard_dialog="postcard_dialog"></postcard-dialog>
                    <!--<div class="col-12">
                        <div class="row">
                            <div v-if="share" class="form-group mt-5 col-9">
                                <input placeholder="Enter email" v-model="share_email" type="email" class="form-control" id="recipe_title">
                            </div>
                            <button v-if="status !== 'Draft' && share" @click="shareRecipe()" class="col-3">Send</button>
                        </div>
                    </div>-->
                </div>

                <div class="share_section row mt-1">
                    <div class="col-12 text-center">
                        <button @click="copyLink()" class="btn-normal">Copy Recipe Link</button>
                    </div>
                </div>
            </div>

            <div class="col-md-8 main-panel pl-4" >
                <div class="section-info" v-if="featured_image !== ''">
                    <img class="featured_image" :src="featured_image" alt="">
                </div>
                <div class="section-info">
                    <label class="label-info-header" v-if="category !== ''" for="">{{ category }}</label>
                    <h2>{{ title }}</h2>
                </div>

                <div class="section-info">
                    <label class="label-info-header" v-if="country_name !== -1" for="">COUNTRY</label>
                    <h2>{{ country_name }}</h2>
                </div>

                <div v-if="ingredients !== '<p><br></p>'" class="section-info">
                    <label class="label-info-header" for="">RECIPE INGREDIENTS</label>
                    <div v-html="ingredients"></div>
                </div>

                <div v-if="instructions !== '<p><br></p>'" class="section-info">
                    <label class="label-info-header" for="">INSTRUCTIONS</label>
                    <div v-html="instructions"></div>
                </div>
                <!--<div class="section-info" v-if="photos.length > 0">
                    <vueper-slides
                            class="no-shadow"
                            :visible-slides="3"
                            slide-multiple
                            :gap="3"
                            :slide-ratio="1 / 4"
                            :dragging-distance="200"
                            :breakpoints="{ 800: { visibleSlides: 2, slideMultiple: 2 } }">
                        <vueper-slide v-for="photo in photos" :key="photo.id" :image="photo.url" />
                    </vueper-slides>
                </div>-->
                <br>
                <div v-show="headline_story !== ''" class="section-info">
                    <label class="label-info-header" for="">HEADLINE STORY</label>
                    <div v-html="headline_story"></div>
                </div>
                <div class="section-info" v-show="story_photos !== ''">
                    <img class="featured_image" :src="featured_image_story" alt="">
                </div>
                <div v-if="story !== ''" class="section-info">
                    <label class="label-info-header" for="">RECIPE STORY</label>
                    <div v-html="story"></div>
                </div>
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
                loading:false,
                editor: null,
                categories:[],
                status: 'Draft',
                category: -1,
                country_name: '',
                share_email: '',
                title: '',
                url:'',
                share:false,
                //ingredients:[],
                ingredients:'',
                instructions:'',
                story:'',
                headline_story:'',
                featured_image:'',
                featured_image_story: '',
                photos:[],
                story_photos:[],
                cookbook_id: -1,
                cookbooks_selected: [],
                recipe: {
                    post_title: '',
                    ID: -1,
                    url:'',
                    story:'',
                },
                postcard_dialog: false
            }
        },
        created(){
            if(parseFloat(this.edit_mode) > 0){
                this.getRecipe();
            }
        },
        computed:{
            recipe_id(){
                return this.edit_mode;
            },
            first_cookbook_name(){
                if(this.cookbooks_selected.length > 0){
                    return this.cookbooks_selected[0].post_title;
                }else{
                    return ''
                }
            },
            other_cookbooks_count(){
                if(this.cookbooks_selected.length > 1){
                    var count = this.cookbooks_selected.length - 1;
                    return 'and ' + count + ' more'
                }
                return '';
            }
        },
        mounted(){

        },
        methods:{
            closePostCardDialog(){
              this.postcard_dialog= false;
            },
            goBack(){
                this.$emit('goBack');
            },
            editRecipe(){
                this.$emit('goEditRecipe');
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
                            this.recipe = response.data.recipe;
                            this.category =  response.data.recipe.category_name !== '' ? response.data.recipe.category_name.toUpperCase() : '';
                            this.title = response.data.recipe.post_title;
                            this.status = response.data.recipe.post_status;
                            this.country_name = response.data.recipe.country_name;
                            this.ingredients = response.data.recipe.ingredients_transformed;
                            this.story = response.data.recipe.story_transformed;
                            this.headline_story = response.data.recipe.headline_story_transformed;
                            this.photos = response.data.recipe.photos;
                            this.story_photos = response.data.recipe.story_photos;
                            this.instructions = response.data.recipe.instructions_transformed;
                            this.status = response.data.recipe.post_status
                            this.featured_image = this.photos.length > 0 ? this.photos[0].url : '';
                            this.featured_image_story = this.story_photos.length > 0 ? this.story_photos[0].url : '';
                            this.cookbooks_selected = response.data.recipe.cookbooks_selected;
                            this.url = response.data.recipe.url;
                        }else{
                            toastr.warning('We could not get the recipe categories', 'Error');
                        }
                        this.loading = false;
                    });
            },
            showShare(){
                this.share = !this.share;
            },
            copyLink(){

                var myTemporaryInputElement = document.createElement("input");
                myTemporaryInputElement.type = "text";
                myTemporaryInputElement.value = this.url;

                document.body.appendChild(myTemporaryInputElement);

                myTemporaryInputElement.select();
                document.execCommand("Copy");

                document.body.removeChild(myTemporaryInputElement);
                toastr.success('Recipe Url copied', 'Copied!');
            },
            deleteRecipe(){
                const formData = new FormData();
                formData.append('action', 'delete_recipe');
                formData.append('id', this.edit_mode);
                this.loading = true;
                axios.post(parameters.ajax_url, formData)
                    .then( response => {
                        if(response.data.success){
                            toastr.success('The recipe was deleted', 'Deleted!');
                            this.$emit('goBack');
                        }else{
                            toastr.warning('We could not get the recipe categories', 'Error');
                        }
                        this.loading = false;
                    });
            }
        }
    }
</script>

<style>
    .featured_image{
        width: 100%;
    }

    .share_section button {
        width: 200px;
    }
</style>
