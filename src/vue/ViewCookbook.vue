<template>
    <div class="container">
        <loading-dialog :loading="loading"></loading-dialog>
        <publish-dialog @closeDialogPublish="closeDialogPublish" :publish_dialog="publish_dialog" :cookbook_id="cookbook_id"></publish-dialog>
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
                <label v-if="state != 2" @click="editCookbook()" class="label-icon-edit mt-2 pt-2" for="">Edit</label>
                <br>
                <button class="btn-normal" @click="publish_dialog = true" v-if="checkPublish() && state != 2">Publish Cookbook</button>
                <p v-if="state == 2"><strong>Note:</strong> This cookbook was sent to be published, actions are not allowed at this time</p>

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
                <div class="section-info" v-if="introduction">
                    <label class="label-info-header" for="">INTRODUCTION</label>
                    <p>{{ introduction }}</p>
                </div>

                <div class="section-info" v-if="acknowledgments">
                    <label class="label-info-header" for="">ACKNOWLEDGMENTS</label>
                    <p>{{ acknowledgments }}</p>
                </div>

                <div class="section-info" v-if="dedication">
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
                publish_dialog: false,
                title: '',
                loading: false,
                state: 1,
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
            cookbook_id(){
                return this.edit_mode;
            },
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
                            this.state = response.data.cookbook.state;

                        }else{
                            toastr.warning('We could not get the recipe categories', 'Error');
                        }
                        this.loading = false;
                    });
            },
            checkPublish(){
                if(this.title !== '' && this.dedication !== '' && this.acknowledgments !== '' && this.introduction !== '' && this.front_image !== null && this.back_image !== null){
                    return true;
                }
                return false;
            },
            closeDialogPublish(){
                this.publish_dialog = false;
            }
        }
    }
</script>

<style>
    .featured_image{
        width: 100%;
    }
</style>
