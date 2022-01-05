<template>
    <div class="container">
        <loading-dialog :loading="loading"></loading-dialog>
        <div class="row">
            <div class="col-2">
                <v-icon @click="goBack()">
                    mdi-arrow-left
                </v-icon> Back
            </div>
            <div class="col-10">
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

                <span class="action_secondary delete-icon mt-5 pt-5">Delete Recipe</span>

            </div>
            <div class="col-md-8 main-panel pt-0" >
                <div class="section-info" v-if="featured_image !== ''">
                    <img class="featured_image" :src="featured_image" alt="">
                </div>
                <div class="section-info">
                    <label class="label-info-header" v-if="category !== ''" for="">{{ category }}</label>
                    <h2>{{ title }}</h2>
                </div>
                <div class="section-info" v-if="ingredients.length > 0">
                    <label class="label-info-header" for="">INGREDIENTS</label>
                    <ul>
                        <li v-for="ingredient in ingredients" :key="ingredient.key" v-if="ingredient.name && ingredient.unit && ingredient.quantity">
                            <h5>{{ ingredient.quantity }} {{ ingredient.unit }} {{ ingredient.name }}</h5>
                        </li>
                    </ul>
                </div>
                <div v-if="instructions !== '<p><br></p>'" class="section-info">
                    <label class="label-info-header" for="">INSTRUCTIONS</label>
                    <div v-html="instructions"></div>
                </div>
                <div class="section-info" v-if="photos.length > 0">
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
                </div>
                <div v-if="story !== '<p><br></p>'" class="section-info">
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
                title: '',
                ingredients:[
                ],
                instructions:'',
                story:'',
                featured_image:'',
                photos:[],
                cookbook_id: -1,
                cookbooks_selected: []
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
                            this.category =  response.data.recipe.category_name !== '' ? response.data.recipe.category_name.toUpperCase() : '';
                            this.title = response.data.recipe.post_title;
                            this.status = response.data.recipe.post_status;
                            this.ingredients = response.data.recipe.ingredients;
                            this.story = response.data.recipe.story;
                            this.photos = response.data.recipe.photos;
                            this.instructions = response.data.recipe.post_content;
                            this.status = response.data.recipe.post_status
                            this.featured_image = this.photos.length > 0 ? this.photos[0].url : '';
                            this.cookbooks_selected = response.data.recipe.cookbooks_selected;
                        }else{
                            toastr.warning('We could not get the recipe categories', 'Error');
                        }
                        this.loading = false;
                    });
            },
        }

    }
</script>

<style>
    .featured_image{
        width: 100%;
    }
</style>
