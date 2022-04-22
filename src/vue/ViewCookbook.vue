<template>
    <div class="container">
        <loading-dialog :loading="loading"></loading-dialog>
        <div class="row">
            <div class="col-12">
                <v-icon @click="goBack()">
                    mdi-arrow-left
                </v-icon>Back
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
                <div class="state_2" v-if="state == 2">
                    <!--<p><strong>Note:</strong> This cookbook was sent to be published, actions are not allowed at this time</p>-->

                    <div v-html="data.customer_support_message"></div>

                    <div class="chat_main">
                        <div class="chat_canvas">

                            <p class="cbf-comment" v-for="comment in comments" :key="comment.id" :class="comment.admin == 1 ? 'right' : 'left'">{{ comment.comment }} <span class="time">{{ formattedCommentTime(comment.created) }}</span></p>
                        </div>
                        <textarea v-model="comment" placeholder="Write a message..." name="" id="cbf_message_value" cols="42" rows="2"></textarea>
                        <br>
                        <button @click="addComment" class="btn-normal" type="button" data-admin="1" data-cookbook_id="<?php echo $cookbook_id ?>" id="cookbook_send_comment">Send</button>
                    </div>
                </div>
                <br><br>
                <div class="preview_pdf text-center" v-if="preview_pdf !== null">
                    <h5>
                        Preview Cookbook
                    </h5>
                    <br>
<!--                    <a href="" target="_blank" :href="preview_pdf">-->
<!--                        <iframe :src="preview_pdf" style="width:100px; height:100px;"></iframe>-->
<!--                    </a>-->
                    <br>
                    <a href="" target="_blank" :href="preview_pdf">Download</a>

                </div>
            </div>
            <div class="col-md-8 main-panel pl-5" >
                <publish-view v-if="publishing" :cookbook_id="cookbook_id" @goBackToView="goBackToView"></publish-view>
                <div class="view-cookbook-info" v-if="!publishing">

                    <div class="section-info" v-if="front_image.id !== -1">
                        <label class="label-info-header" for="">Front Cover Photo</label><br>
                        <img :src="front_image.url" alt="">
                    </div>

                    <div class="section-info">
                        <label class="label-info-header" for="">Title</label>
                        <p>{{ title }}</p>
                    </div>

                    <div class="section-info" v-if="author_name">
                        <label class="label-info-header" for="">Author Name</label>
                        <p>{{ author_name }}</p>
                    </div>

                    <div class="section-info" v-if="dedication">
                        <label class="label-info-header" for="">Dedications</label>
                        <p v-html="dedication"></p>
                    </div>

                    <div class="section-info" v-if="introduction_image.id !== -1">
                        <label class="label-info-header" for="">Introduction Page Photo</label><br>
                        <img :src="introduction_image.url" alt="">
                    </div>

                    <div class="section-info" v-if="introduction_headline">
                        <label class="label-info-header" for="">Introduction Headline</label>
                        <p v-html="introduction_headline"></p>
                    </div>

                    <div class="section-info" v-if="introduction">
                        <label class="label-info-header" for="">Introduction</label>
                        <p v-html="introduction"></p>
                    </div>


                    <div class="section-info" v-if="back_image.id !== -1">
                        <label class="label-info-header" for="">Back Cover  Photo</label><br>
                        <img :src="back_image.url" alt="">
                    </div>

                    <div class="section-info" v-if="back_cover_headline">
                        <label class="label-info-header" for="">Back Cover Headline</label>
                        <p v-html="back_cover_headline"></p>
                    </div>

                    <div class="section-info" v-if="back_cover_story">
                        <label class="label-info-header" for="">Back Cover Story</label>
                        <p v-html="back_cover_story"></p>
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
                introduction_headline:'',
                comments:[],
                dedication: '',
                preview_pdf: null,
                comment: '',
                data: [],
                back_cover_story: '',
                back_cover_headline: '',
                author_name: '',
                recipes: [],
                front_image: {
                    id: -1,
                    url: ''
                },
                introduction_image: {
                    id: -1,
                    url: ''
                },
                back_image: {
                    id: -1,
                    url: ''
                },
                publishing: false,
            }
        },
        created(){
            this.data = parameters.data;
            if(parseFloat(this.edit_mode) > 0){
                this.getCookbook();
                this.getComments();
            }

            setInterval(()=>{
                this.getComments();
            },5000)

            this.author_name = parameters.account_selected.username.charAt(0).toUpperCase() + parameters.account_selected.username.slice(1);
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
                            this.author = response.data.cookbook.author;
                            this.dedication = response.data.cookbook.dedication_transformed;
                            this.introduction = response.data.cookbook.introduction_transformed;
                            this.introduction_headline = response.data.cookbook.introduction_headline_transformed;
                            this.back_cover_headline = response.data.cookbook.back_cover_headline_transformed;
                            this.back_cover_story = response.data.cookbook.back_cover_story_transformed;
                            this.recipes = response.data.cookbook.selected_recipes;
                            this.state = response.data.cookbook.state;
                            this.preview_pdf = response.data.cookbook.preview_pdf;

                            if(response.data.cookbook.front_image !== -1){
                                this.front_image = {
                                    id: response.data.cookbook.front_image.id,
                                    url: response.data.cookbook.front_image.url,
                                }
                            }

                            if(response.data.cookbook.back_image !== -1){
                                this.back_image = {
                                    id: response.data.cookbook.back_image.id,
                                    url: response.data.cookbook.back_image.url,
                                }
                            }

                            if(response.data.cookbook.introduction_image !== -1){
                                this.introduction_image = {
                                    id: response.data.cookbook.introduction_image.id,
                                    url: response.data.cookbook.introduction_image.url,
                                }
                            }

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
                //this.loading = true;
                axios.post(parameters.ajax_url, formData)
                    .then( response => {
                        if(response.data.success){
                            this.comments = response.data.comments;
                        }else{
                            toastr.warning('We could not get the last messages', 'Error');
                        }
                        //this.loading = false;
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
                if(this.title){
                    return true;
                }
                return false;
            },
            goBackToView(){
                this.publishing = false;
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


</style>
