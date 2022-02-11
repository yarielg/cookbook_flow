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
                <label v-if="state != 2" @click="editCookbook()" class="label-icon-edit mt-2 pt-2" for="">Edit</label>
                <br>
                Author: {{ author_name }}
                <br>
                <br>
                <button class="btn-normal" @click="publishing = true" v-if="checkPublish() && state != 2 && !publishing">Publish Cookbook</button>
                <p v-if="state == 2"><strong>Note:</strong> This cookbook was sent to be published, actions are not allowed at this time</p>

                <div class="chat_main" v-if="state == 2">
                    <div class="chat_canvas">

                        <p class="cbf-comment" v-for="comment in comments" :key="comment.id" :class="comment.admin == 1 ? 'right' : 'left'">{{ comment.comment }} <span class="time">{{ formattedCommentTime(comment.created) }}</span></p>
                    </div>
                    <textarea v-model="comment" placeholder="Write a message..." name="" id="cbf_message_value" cols="42" rows="2"></textarea>
                    <br>
                    <button @click="addComment" class="btn-normal" type="button" data-admin="1" data-cookbook_id="<?php echo $cookbook_id ?>" id="cookbook_send_comment">Sent</button>
                </div>
                <br><br>
                <div class="preview_pdf text-center" v-if="preview_pdf !== null">
                    <h5>
                        Preview Cookbook
                    </h5>
                    <br>
                    <a href="" target="_blank" :href="preview_pdf">
                        <iframe :src="preview_pdf" style="width:100px; height:100px;"></iframe>
                    </a>
                    <br>
                    <a href="" target="_blank" :href="preview_pdf">Download</a>

                </div>
            </div>
            <div class="col-md-8 main-panel pl-5" >
                <publish-view v-if="publishing" :cookbook_id="cookbook_id" @goBackToView="goBackToView"></publish-view>
                <div class="view-cookbook-info" v-if="!publishing">
                    <div class="row">
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
                        <label class="label-info-header" for="">ACKNOWLEDGEMENTS</label>
                        <p>{{ acknowledgments }}</p>
                    </div>

                    <div class="section-info" v-if="dedication">
                        <label class="label-info-header" for="">DEDICATIONS</label>
                        <p>{{ dedication }}</p>
                    </div>
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
                comments:[],
                acknowledgments: '',
                dedication: '',
                preview_pdf: null,
                comment: '',
                author_name: '',
                recipes: [],
                back_image: null,
                publishing: false,
            }
        },
        created(){
            if(parseFloat(this.edit_mode) > 0){
                this.getCookbook();
                this.getComments();
            }

            this.author_name = parameters.owner.data.display_name.charAt(0).toUpperCase() + parameters.owner.data.display_name.slice(1);
        },
        computed:{
            cookbook_id(){
                return this.edit_mode;
            }
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
                            //this.front_image = response.data.cookbook.front_image;
                            this.back_image = response.data.cookbook.back_image;
                            this.state = response.data.cookbook.state;
                            this.preview_pdf = response.data.cookbook.preview_pdf;

                        }else{
                            toastr.warning('We could not get the recipe categories', 'Error');
                        }
                        this.loading = false;
                    });
            },
            getComments(){
                const formData = new FormData();
                formData.append('action', 'get_comments');
                formData.append('admin', 0);
                formData.append('cookbook_id', this.edit_mode);
                this.loading = true;
                axios.post(parameters.ajax_url, formData)
                    .then( response => {
                        if(response.data.success){
                            this.comments = response.data.comments;
                        }else{
                            toastr.warning('We could not get the last messages', 'Error');
                        }
                        this.loading = false;
                    });
            },
            addComment(){
                const formData = new FormData();
                formData.append('action', 'add_comment');
                formData.append('admin', 0);
                formData.append('cookbook_id', this.edit_mode);
                formData.append('comment', this.comment);
                this.loading = true;
                axios.post(parameters.ajax_url, formData)
                    .then( response => {
                        if(response.data.success){
                            this.comment = '';
                            this.getComments();
                            /*var myDiv = document.getElementById("chat_canvas");
                            myDiv.scrollTop = -100000;*/
                        }else{
                            toastr.warning('We could not get the last messages', 'Error');
                        }
                        this.loading = false;
                    });
            },
            formattedCommentTime(comment){

                var date = new Date(comment);

                var year = date.getFullYear();
                var month = date.getMonth() + 1;
                var day = date.getDate();
                var hours = date.getHours();
                var minutes = date.getMinutes();
                var seconds = date.getSeconds();
                var formatted = year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds;
                return formatted;
            },
            checkPublish(){
                if(this.title !== '' && this.dedication !== '' && this.acknowledgments !== '' && this.introduction !== '' && this.front_image !== null && this.back_image !== null){
                    return true;
                }
                return false;
            },
            goBackToView(){
                this.publishing = false;
                console.log(this.publishing);
            }
        }
    }
</script>

<style>
    .featured_image{
        width: 100%;
    }
    .chat_canvas{
        height: 300px;
        background: white;
        padding: 20px;
        display: flex;
        flex-direction: column;
        border-radius: 8px;
        border: 1px solid black;
        overflow-y: scroll;
    }
    .no-comments{
        text-align: center;
        font-weight: 500;
        margin: auto;
    }
    .cbf-comment{
        background: #51A351;
        padding: 8px;
        border-radius: 4px;
        color: white;
        display: block;
        clear: bottom;
        width: fit-content;
        margin: 2px;
        font-size: 12px;
    }
    .cbf-comment.right{
        float: right;
        background: black;
        align-self: end;
    }
    .cbf-comment > span.time{
        font-size: 8px;
        margin-left: 12px;
    }

    @media screen and (min-width: 920px) {
        .left-panel{
            height: 100vh;
        }
    }


</style>
