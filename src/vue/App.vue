<template>
    <v-app>
        <div class="container dashboard" v-if="active_screen == 'dashboard'">
            <loading-dialog :loading="loading"></loading-dialog>
            <div class="row mb-6">
                <div class="col-md-6 text-center box-panel">
                    <div class="panel-wrapper" v-show="!premium_account && account_type == 1">
                        <h4 class="">
                            Upgrade to start creating a cookbook!
                        </h4>
                        <br>
                        <p class="">Nam porttitor blandit accumsan. Ut vel
                            dictum sem, a pretium dui. In malesuada
                            enim in dolor euismod,Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam aperiam aspernatur assumenda, blanditiis corporis delectus dolor eius est eveniet laboriosam libero minima molestiae nam numquam odit qui veritatis vero voluptates.</p>
                        <br>
                        <button @click="goToUpgradeMembership()" class="btn-normal">Upgrade Account</button>
                    </div>
                    <div class="panel-wrapper" v-show="premium_account">
                        <div v-if="cookbooks.length > 0" class="cookbooks_list">
                            <h4 class="mb-4 inline_header">Your Cookbooks</h4>
                            <button @click="changeScreen('add-cookbook')" class="btn-normal float-right mr-3">Create</button>
                            <div class="container recipe-wrapper">
                                <div v-for="cookbook in cookbooks" :key="cookbook.ID"  class="row recipe">
                                    <!--<div class="col-md-2"><img class="recipe_img" :src="recipe.photo_url" alt=""></div>-->
                                    <div class="col-md-8 centered_col"><p>{{ cookbook.post_title }}</p></div>
                                    <div v-if="cookbook.state != 2" class="col-md-1 centered_col"><button class="btn-normal" @click="changeScreen('add-cookbook', cookbook.ID)">Edit</button></div>
                                    <div class="col-md-1 centered_col"><button class="btn-normal ml-5" @click="changeScreen('view-cookbook',cookbook.ID)">View</button></div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row ">
                                    <div class="col-12 d-flex justify-content-between">
                                        <!--<button @click="changeScreen('add-recipe')" class="btn-normal">Create a Recipe</button>-->
                                        <!--<button @click="changeScreen('all-recipe')" class="btn-normal">All Recipes</button>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="cookbooks.length == 0" class="no-cookbooks">
                            <h4 class="">
                                Start creating a cookbook!
                            </h4>
                            <br>
                            <p class="">Nam porttitor blandit accumsan. Ut vel
                                dictum sem, a pretium dui. In malesuada
                                enim in dolor euismod,</p>
                            <br>
                            <button class="btn-normal"><a href="/welcome/?screen=add-cookbook">Create a Cookbook</a></button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 text-center box-panel">
                    <div class="panel-wrapper">
                        <h4 class="">Help me design my cookbook.</h4>
                        <br>
                        <p class="">Nam porttitor blandit accumsan. Ut vel dictum sem,
                            a pretium dui. In malesuada enim in dolor euismod,</p>
                        <br>
                        <button class="btn-normal">Get started</button>
                    </div>
                </div>
            </div>
            <div class="row mt-6" v-if="recipes.length == 0">
                <div class="col-12 text-center box-panel">
                    <div class="panel-wrapper">
                        <h4 class="">Your Recipes</h4>
                        <p class="">To begin building your recipe library, start by adding your first recipe.</p>
                        <br>
                        <button @click="changeScreen('add-recipe')" class="btn-normal">Create a Recipe</button>
                    </div>
                </div>
            </div>

            <div class="row mt-6">
                <div class="col-md-8 box-panel">
                    <div class="panel-wrapper">
                        <h4 class="mb-4 inline_header">Your Recipes</h4>
                        <button @click="changeScreen('add-recipe')" class="btn-normal float-right mr-3">Create</button>
                        <div class="container recipe-wrapper">
                            <div v-for="recipe in recipes" :key="recipe.ID"  class="row recipe">
                                <div class="col-md-2"><img class="recipe_img" :src="recipe.photo_url" alt=""></div>
                                <div class="col-md-8 centered_col"><p>{{ recipe.post_title }}</p></div>
                                <div class="col-md-1 centered_col"><button class="btn-normal" @click="changeScreen('add-recipe', recipe.ID)">Edit</button></div>
                                <div class="col-md-1 centered_col"><button class="btn-normal" :disabled="recipes.incomplete" @click="changeScreen('view-recipe',recipe.ID)">View</button></div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row ">
                                <div class="col-12 d-flex justify-content-between">
                                    <!--<button @click="changeScreen('add-recipe')" class="btn-normal">Create a Recipe</button>-->
                                    <!--<button @click="changeScreen('all-recipe')" class="btn-normal">All Recipes</button>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 box-panel text-center">
                    <div class="panel-wrapper">
                        <h4 class="mb-5 pb-5">Add / Promotion Space</h4>
                        <p class="">As a user continues to build out their
                            dashboard with content, we could
                            trickle in CTA spaces for promotions
                            or ads. </p>
                        <button class="btn-normal">Promote</button>
                    </div>
                </div>
            </div>
        </div>

        <add-recipe :edit_mode="edit_recipe" v-if="active_screen == 'add-recipe'" @goCookbookEditWithId="goCookbookEditWithId" @goViewRecipe="changeScreen('view-recipe',edit_recipe)" @goViewRecipeWithId="goViewRecipeWithId" @goBack="changeScreen('dashboard')" ></add-recipe>
        <add-cookbook  :edit_mode="edit_cookbook" v-if="active_screen == 'add-cookbook'" @goViewCookbook="changeScreen('view-cookbook',edit_cookbook)" @goCookbookWithId="goCookbookWithId"  :recipes="recipes" @goBack="changeScreen('dashboard')" ></add-cookbook>
        <view-recipe :edit_mode="edit_recipe" v-if="active_screen == 'view-recipe'" :recipes="recipes" @goEditRecipe="changeScreen('add-recipe',edit_recipe)" @goBack="changeScreen('dashboard')" ></view-recipe>
        <view-cookbook  :edit_mode="edit_cookbook" v-if="active_screen == 'view-cookbook'" @goEditCookbook="changeScreen('add-cookbook',edit_cookbook)"  :recipes="recipes" @goBack="changeScreen('dashboard')" ></view-cookbook>
        <collaborators  v-if="active_screen == 'collaborators'"></collaborators>
    </v-app>
</template>

<script>
    const axios = require('axios');

    export default {
        data () {
            return {
               loading:false,
               active_screen: 'dashboard',
               recipes:[],
               edit_recipe: -1,
               edit_cookbook: -1,
               premium_account: false,
               cookbooks:0,
               account_type: 0
            }
        },
        created(){
            this.premium_account = parameters.premium;
            this.account_type = parameters.account_type;
            //Determine what flow the app will have depending on the account type and the user role
            this.secureAccount();
            //Change screen in case there is a query parameter
            this.queryParameterChangeScreen();

            this.getYourRecipes();
        },
        methods:{
            queryParameterChangeScreen(){
                let query_parameter = window.location.search.substring(1).length > 0 ? window.location.search.substring(1).split('=')[1] : 'dashboard';
                this.changeScreen(query_parameter);
            },
            changeScreen(screen, id){
                if(screen === 'dashboard'){
                    this.getYourRecipes();
                    this.getYourCookbooks();
                }

                if(screen === 'add-recipe' || screen === 'view-recipe'){
                    this.edit_recipe = id;
                }

                if(screen === 'add-cookbook' || screen === 'view-cookbook'){
                    this.edit_cookbook = id;
                }
                this.active_screen = screen;
            },
            goViewRecipeWithId(id){
                this.edit_recipe = id;
                this.active_screen = 'view-recipe';
            },
            goCookbookWithId(id){
                this.edit_cookbook = id;
                this.active_screen = 'view-cookbook';
            },
            goCookbookEditWithId(id){
                this.edit_cookbook = id;
                this.active_screen = 'add-cookbook';
            },
            getYourRecipes(){
                const formData = new FormData();
                formData.append('action', 'get_your_recipes');
                formData.append('author_id', parameters.owner.ID);
                this.loading= true;
                axios.post(parameters.ajax_url, formData)
                    .then( response => {
                        if(response.data.success){
                            this.recipes =  response.data.recipes;
                        }else{
                            toastr.warning('We could not get your recipes', 'Error');
                        }
                        this.loading= false;
                    });
            },
            getYourCookbooks(){
                const formData = new FormData();
                formData.append('action', 'get_user_cookbooks');
                formData.append('author_id', parameters.owner.ID);
                this.loading= true;
                axios.post(parameters.ajax_url, formData)
                    .then( response => {
                        if(response.data.success){
                            this.cookbooks =  response.data.cookbooks;
                        }else{
                            toastr.warning('We could not get your cookbooks', 'Error');
                        }
                        this.loading= false;
                    });
            },
            viewRecipe(id){
            },
            goToUpgradeMembership(){
                window.location = '/register/?registration_type=upgrade';
            },
            secureAccount(){

            }
        }

    }
</script>


<style>
    body #app,h4,h3,h5,h2{
        color: #78849c !important;
    }

    .toast-title{font-weight:700}.toast-message{-ms-word-wrap:break-word;word-wrap:break-word}.toast-message a,.toast-message label{color:#FFF}.toast-message a:hover{color:#CCC;text-decoration:none}.toast-close-button{position:relative;right:-.3em;top:-.3em;float:right;font-size:20px;font-weight:700;color:#FFF;-webkit-text-shadow:0 1px 0 #fff;text-shadow:0 1px 0 #fff;opacity:.8;-ms-filter:progid:DXImageTransform.Microsoft.Alpha(Opacity=80);filter:alpha(opacity=80);line-height:1}.toast-close-button:focus,.toast-close-button:hover{color:#000;text-decoration:none;cursor:pointer;opacity:.4;-ms-filter:progid:DXImageTransform.Microsoft.Alpha(Opacity=40);filter:alpha(opacity=40)}.rtl .toast-close-button{left:-.3em;float:left;right:.3em}button.toast-close-button{padding:0;cursor:pointer;background:0 0;border:0;-webkit-appearance:none}.toast-top-center{top:0;right:0;width:100%}.toast-bottom-center{bottom:0;right:0;width:100%}.toast-top-full-width{top:0;right:0;width:100%}.toast-bottom-full-width{bottom:0;right:0;width:100%}.toast-top-left{top:12px;left:12px}.toast-top-right{top:12px;right:12px}.toast-bottom-right{right:12px;bottom:12px}.toast-bottom-left{bottom:12px;left:12px}#toast-container{position:fixed;z-index:999999;pointer-events:none}#toast-container *{-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box}#toast-container>div{position:relative;pointer-events:auto;overflow:hidden;margin:0 0 6px;padding:15px 15px 15px 50px;width:300px;-moz-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;background-position:15px center;background-repeat:no-repeat;-moz-box-shadow:0 0 12px #999;-webkit-box-shadow:0 0 12px #999;box-shadow:0 0 12px #999;color:#FFF;opacity:.8;-ms-filter:progid:DXImageTransform.Microsoft.Alpha(Opacity=80);filter:alpha(opacity=80)}#toast-container>div.rtl{direction:rtl;padding:15px 50px 15px 15px;background-position:right 15px center}#toast-container>div:hover{-moz-box-shadow:0 0 12px #000;-webkit-box-shadow:0 0 12px #000;box-shadow:0 0 12px #000;opacity:1;-ms-filter:progid:DXImageTransform.Microsoft.Alpha(Opacity=100);filter:alpha(opacity=100);cursor:pointer}#toast-container>.toast-info{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAGwSURBVEhLtZa9SgNBEMc9sUxxRcoUKSzSWIhXpFMhhYWFhaBg4yPYiWCXZxBLERsLRS3EQkEfwCKdjWJAwSKCgoKCcudv4O5YLrt7EzgXhiU3/4+b2ckmwVjJSpKkQ6wAi4gwhT+z3wRBcEz0yjSseUTrcRyfsHsXmD0AmbHOC9Ii8VImnuXBPglHpQ5wwSVM7sNnTG7Za4JwDdCjxyAiH3nyA2mtaTJufiDZ5dCaqlItILh1NHatfN5skvjx9Z38m69CgzuXmZgVrPIGE763Jx9qKsRozWYw6xOHdER+nn2KkO+Bb+UV5CBN6WC6QtBgbRVozrahAbmm6HtUsgtPC19tFdxXZYBOfkbmFJ1VaHA1VAHjd0pp70oTZzvR+EVrx2Ygfdsq6eu55BHYR8hlcki+n+kERUFG8BrA0BwjeAv2M8WLQBtcy+SD6fNsmnB3AlBLrgTtVW1c2QN4bVWLATaIS60J2Du5y1TiJgjSBvFVZgTmwCU+dAZFoPxGEEs8nyHC9Bwe2GvEJv2WXZb0vjdyFT4Cxk3e/kIqlOGoVLwwPevpYHT+00T+hWwXDf4AJAOUqWcDhbwAAAAASUVORK5CYII=)!important}#toast-container>.toast-error{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAHOSURBVEhLrZa/SgNBEMZzh0WKCClSCKaIYOED+AAKeQQLG8HWztLCImBrYadgIdY+gIKNYkBFSwu7CAoqCgkkoGBI/E28PdbLZmeDLgzZzcx83/zZ2SSXC1j9fr+I1Hq93g2yxH4iwM1vkoBWAdxCmpzTxfkN2RcyZNaHFIkSo10+8kgxkXIURV5HGxTmFuc75B2RfQkpxHG8aAgaAFa0tAHqYFfQ7Iwe2yhODk8+J4C7yAoRTWI3w/4klGRgR4lO7Rpn9+gvMyWp+uxFh8+H+ARlgN1nJuJuQAYvNkEnwGFck18Er4q3egEc/oO+mhLdKgRyhdNFiacC0rlOCbhNVz4H9FnAYgDBvU3QIioZlJFLJtsoHYRDfiZoUyIxqCtRpVlANq0EU4dApjrtgezPFad5S19Wgjkc0hNVnuF4HjVA6C7QrSIbylB+oZe3aHgBsqlNqKYH48jXyJKMuAbiyVJ8KzaB3eRc0pg9VwQ4niFryI68qiOi3AbjwdsfnAtk0bCjTLJKr6mrD9g8iq/S/B81hguOMlQTnVyG40wAcjnmgsCNESDrjme7wfftP4P7SP4N3CJZdvzoNyGq2c/HWOXJGsvVg+RA/k2MC/wN6I2YA2Pt8GkAAAAASUVORK5CYII=)!important}#toast-container>.toast-success{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAADsSURBVEhLY2AYBfQMgf///3P8+/evAIgvA/FsIF+BavYDDWMBGroaSMMBiE8VC7AZDrIFaMFnii3AZTjUgsUUWUDA8OdAH6iQbQEhw4HyGsPEcKBXBIC4ARhex4G4BsjmweU1soIFaGg/WtoFZRIZdEvIMhxkCCjXIVsATV6gFGACs4Rsw0EGgIIH3QJYJgHSARQZDrWAB+jawzgs+Q2UO49D7jnRSRGoEFRILcdmEMWGI0cm0JJ2QpYA1RDvcmzJEWhABhD/pqrL0S0CWuABKgnRki9lLseS7g2AlqwHWQSKH4oKLrILpRGhEQCw2LiRUIa4lwAAAABJRU5ErkJggg==)!important}#toast-container>.toast-warning{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAGYSURBVEhL5ZSvTsNQFMbXZGICMYGYmJhAQIJAICYQPAACiSDB8AiICQQJT4CqQEwgJvYASAQCiZiYmJhAIBATCARJy+9rTsldd8sKu1M0+dLb057v6/lbq/2rK0mS/TRNj9cWNAKPYIJII7gIxCcQ51cvqID+GIEX8ASG4B1bK5gIZFeQfoJdEXOfgX4QAQg7kH2A65yQ87lyxb27sggkAzAuFhbbg1K2kgCkB1bVwyIR9m2L7PRPIhDUIXgGtyKw575yz3lTNs6X4JXnjV+LKM/m3MydnTbtOKIjtz6VhCBq4vSm3ncdrD2lk0VgUXSVKjVDJXJzijW1RQdsU7F77He8u68koNZTz8Oz5yGa6J3H3lZ0xYgXBK2QymlWWA+RWnYhskLBv2vmE+hBMCtbA7KX5drWyRT/2JsqZ2IvfB9Y4bWDNMFbJRFmC9E74SoS0CqulwjkC0+5bpcV1CZ8NMej4pjy0U+doDQsGyo1hzVJttIjhQ7GnBtRFN1UarUlH8F3xict+HY07rEzoUGPlWcjRFRr4/gChZgc3ZL2d8oAAAAASUVORK5CYII=)!important}#toast-container.toast-bottom-center>div,#toast-container.toast-top-center>div{width:300px;margin-left:auto;margin-right:auto}#toast-container.toast-bottom-full-width>div,#toast-container.toast-top-full-width>div{width:96%;margin-left:auto;margin-right:auto}.toast{background-color:#030303}.toast-success{background-color:#51A351}.toast-error{background-color:#BD362F}.toast-info{background-color:#2F96B4}.toast-warning{background-color:#F89406}.toast-progress{position:absolute;left:0;bottom:0;height:4px;background-color:#000;opacity:.4;-ms-filter:progid:DXImageTransform.Microsoft.Alpha(Opacity=40);filter:alpha(opacity=40)}@media all and (max-width:240px){#toast-container>div{padding:8px 8px 8px 50px;width:11em}#toast-container>div.rtl{padding:8px 50px 8px 8px}#toast-container .toast-close-button{right:-.2em;top:-.2em}#toast-container .rtl .toast-close-button{left:-.2em;right:.2em}}@media all and (min-width:241px) and (max-width:480px){#toast-container>div{padding:8px 8px 8px 50px;width:18em}#toast-container>div.rtl{padding:8px 50px 8px 8px}#toast-container .toast-close-button{right:-.2em;top:-.2em}#toast-container .rtl .toast-close-button{left:-.2em;right:.2em}}@media all and (min-width:481px) and (max-width:768px){#toast-container>div{padding:15px 15px 15px 50px;width:25em}#toast-container>div.rtl{padding:15px 50px 15px 15px}}

    .recipe-wrapper{
        max-height: 220px;
        overflow-y: scroll;
    }

    .recipe-wrapper .recipe{
        border-bottom: 2px solid #78849c;
    }

    .recipe-wrapper img{
        width: 50px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .inline_header{
        display: inline;
    }

    .centered_col{
        display: flex;
        align-items: center;
    }

    /* App Global css  */
    .section-info{
        margin-bottom: 50px;
    }

    label.label-info-header{
        color: #78849c;
    }


</style>
