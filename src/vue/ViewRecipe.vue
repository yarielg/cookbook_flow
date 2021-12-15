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
                <h4>Recipe Information</h4>
                <hr>
                <label class="label-icon-edit" for="">Cookbook used in:</label>
                <h4>A Cookbook Title</h4>
                <br>
                <label for="">Recipe Type:</label>
                <h4>{{status}} Recipe</h4>
                <br>

                <span @click="editRecipe()" class="action_icon">Edit Recipe</span>

            </div>
            <div class="col-md-8">
                <div class="section-info">
                    <img class="featured_image" :src="featured_image" alt="">
                </div>
                <div class="section-info">
                    <label class="label-info-header" for="">{{ category }}</label>
                    <h3>{{ title }}</h3>
                </div>
                <div class="section-info">
                    <label class="label-info-header" for="">INGREDIENTS</label>
                    <ul>
                        <li v-for="ingredient in ingredients" :key="ingredient.key" v-if="ingredient.name && ingredient.unit && ingredient.quantity">
                            <h5>{{ ingredient.quantity }} {{ ingredient.unit }} {{ ingredient.name }}</h5>
                        </li>
                    </ul>
                </div>
                <div class="section-info">
                    <label class="label-info-header" for="">INSTRUCTIONS</label>
                    <div v-html="instructions"></div>
                </div>
                <div class="section-info">
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
                editor: null,
                categories:[],
                status: false,
                category: -1,
                title: '',
                ingredients:[
                ],
                instructions:'',
                featured_image:'',
                photos:[]

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
                axios.post(parameters.ajax_url, formData)
                    .then( response => {
                        console.log(response.data.recipe)
                        if(response.data.success){
                            this.category =  response.data.recipe.category_name.toUpperCase();
                            this.title = response.data.recipe.post_title;
                            this.status = response.data.recipe.post_status;
                            this.ingredients = response.data.recipe.ingredients;
                            this.photos = response.data.recipe.photos;
                            this.instructions = response.data.recipe.post_content;
                            this.status = response.data.recipe.post_status === "publish" ? true : false;
                            this.featured_image = this.photos[0].url;
                        }else{
                            toastr.warning('We could not get the recipe categories', 'Error');
                        }

                    });
            }
        }

    }
</script>

<style>
    .featured_image{
        width: 100%;
    }
</style>
