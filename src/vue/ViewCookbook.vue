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
                <h4 class="text-center mt-2 pt-2">Cookbook Information</h4>
                <hr>

                <h5>Recipes</h5>
                <span class="badge badge-secondary ml-3" v-for="recipe in recipes" :key="recipe.ID">{{ recipe.post_title }}</span>
                <br>
                <label @click="editCookbook()" class="label-icon-edit mt-2 pt-2" for="">Edit</label>

            </div>
            <div class="col-md-8 main-panel pt-0" >
                <div class="row">
                    <div class="col-md-6" v-if="front_image">
                        <img :src="front_image.url" alt="">
                    </div>
                    <div class="col-md-6" v-if="back_image">
                        <img :src="back_image.url" alt="">
                    </div>
                </div>
                <div class="section-info">
                    <h2>{{ title }}</h2>
                </div>
                <div class="section-info">
                    <label class="label-info-header" for="">INTRODUCTION</label>
                    <p>{{ introduction }}</p>
                </div>

                <div class="section-info">
                    <label class="label-info-header" for="">ACKNOWLEDGMENTS</label>
                    <p>{{ acknowledgments }}</p>
                </div>

                <div class="section-info">
                    <label class="label-info-header" for="">DEDICATIONS</label>
                    <p>{{ dedication }}</p>
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
                title: '',
                loading: false,
                introduction: '',
                acknowledgments: '',
                dedication: '',
                recipes: [],
                front_image: null,
                back_image: null

            }
        },
        created(){
            if(parseFloat(this.edit_mode) > 0){
                this.getCookbook();
            }
        },
        computed:{

        },
        mounted(){

        },
        methods:{
            goBack(){
                this.$emit('goBack');
            },
            editCookbook(){
                this.$emit('goEditCookbook');
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
                            this.recipes = response.data.cookbook.selected_recipes;
                            this.front_image = response.data.cookbook.front_image;
                            this.back_image = response.data.cookbook.back_image;

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
</style>
