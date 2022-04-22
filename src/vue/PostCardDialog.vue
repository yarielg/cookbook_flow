<template>

    <v-dialog
            v-model="modal"
            scrollable
            persistent
            width="720px"
            height="350px">
        <v-card>
            <div class="loading_postcard" v-show="loading">
                <span>Loading...</span>
            </div>
            <v-card-text style="height: 350px" >
                <div class="row mt-4">
                    <div class="col-5 left-postcard">
                        <h5 class="text-center">{{recipe_obj.post_title}}</h5>

                        <div class="form-group" v-if="is_dashboard">
                            <label for="msg_recipient" >Select recipe</label>
                            <select v-model="selected_recipe" class="form-control" id="recipes">
                                <option v-for="recipe in recipes" :key="recipe.ID" :value="recipe">{{ recipe.post_title}}</option>
                            </select>
                        </div>

                        <form>
                            <div class="form-group">
                                <label for="msg_recipient" >Message to recipient</label>
                                <textarea class="form-control" v-model="message" id="msg_recipient" rows="3"></textarea>
                            </div>
                        </form>

                        <!--<div v-html="recipe_obj.story"></div>-->
                        <!--<p class="text-center"><strong>Link:</strong> <a :href="recipe_obj.url">{{ recipe_obj.post_title }}</a></p>-->
                    </div>
                    <div class="col-2">
                        <div class="line_or">
                        </div>
                    </div>
                    <div class="col-5">
                        <img class="post_card_img pb-3" :src="plugin_path + '/assets/images/postcard.png'" alt="">

                        <div class="postcard_form mt-5 pt-5">
                            <form>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Name*" v-model="name">
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email*" v-model="share_email">
                                </div>
                            </form>
                            <button class="btn-normal mt-3" @click="shareRecipe">Send</button>
                            <button class="btn-normal" @click="closeDialog()">Close</button>
                        </div>
                    </div>
                </div>
            </v-card-text>

        </v-card>
    </v-dialog>
</template>

<script>
    const axios = require('axios');

    export default {
        props:['recipe','postcard_dialog','dashboard'],
        created(){
            if(this.dashboard){
                this.getYourRecipes();
            }

        },
        computed:{
            modal(){
                return this.postcard_dialog;
            },
            recipe_obj(){
                return this.is_dashboard ? this.selected_recipe : this.recipe;
            },
            is_dashboard(){
                return this.dashboard;
            }
        },
        data () {
            return {
                plugin_path: parameters.plugin_path,
                loading: false,
                share_email: '',
                message: '',
                name: '',
                recipes: [],
                selected_recipe: {
                    post_title: '',
                    ID: -1,
                    url:'',
                    story:'',
                }
            }
        },
        methods:{
            closeDialog(){
                this.$emit('closePostCardDialog');
            },
            shareRecipe(){
                if(this.share_email !== '' &&  /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.share_email)){
                    const formData = new FormData();
                    formData.append('action', 'share_recipe');
                    formData.append('id', this.recipe_obj.ID);
                    formData.append('email', this.share_email);
                    formData.append('name', this.name);
                    formData.append('sender_name', parameters.user.data.display_name);
                    formData.append('message', this.message);
                    this.loading = true;
                    axios.post(parameters.ajax_url, formData)
                        .then( response => {
                            if(response.data.success){
                                toastr.success(response.data.msg, 'Shared!');
                                this.share_email = '';
                                this.message = '';
                                this.name = '';
                                this.closeDialog();

                                if(this.is_dashboard){
                                    setTimeout(function(){
                                        window.location = '/welcome';
                                    },2000);
                                }

                            }else{
                                toastr.warning(response.data.msg, 'Error');
                            }

                        });
                }else{
                    toastr.warning('Please enter a valid email', 'Error');
                }
                this.loading = false;

            },
            getYourRecipes(){
                const formData = new FormData();
                formData.append('action', 'get_your_recipes');
                formData.append('author_id', parameters.account_selected.id);
                this.loading = true;
                axios.post(parameters.ajax_url, formData)
                    .then( response => {
                        if(response.data.success){
                            this.recipes =  response.data.recipes;
                        }else{
                            toastr.warning('We could not get your recipes', 'Error');
                        }
                });
                this.loading= false;
            }
        }

    }
</script>


<style>
    .post_card_img{
        width: 200px;
        float: right;
    }

    .left-postcard{
        margin: auto;
    }

    .loading_postcard{
        background: black;
        height: 100%;
        width: 100%;
        position: absolute;
        z-index: 9999;
        opacity: 0.3;
    }
</style>
