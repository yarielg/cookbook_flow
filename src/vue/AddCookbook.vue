<template>
    <div class="container">
        <loading-dialog :loading="loading"></loading-dialog>
        <form
                method="post"
                action=""
                @submit.prevent="addCookbook()">
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

                <div v-show="front_image.id !== -1" class="img-enlarge-wrapper text-center">
                    <span>CLICK TO ENLARGE </span><br>
                    <img data-zoomable="" class="zoomable" :src="front_image.url" alt="">
                </div>

            </div>
            <div class="col-md-8">

                    <div class="form-group">
                        <label for="cookbook_title">Title</label>
                        <input @keydown="onKeyDown($event,title,60)" v-model="title" type="text" class="form-control" id="cookbook_title">
                    </div>

                    <div class="form-group">
                        <label for="cookbook_author_name">Author Name</label>
                        <input @keydown="onKeyDown($event,author_name,50)" v-model="author_name" type="text" class="form-control" id="cookbook_author_name">
                    </div>

                    <label>Add Front Cover Photo</label>
                    <div class="media_component">
                        <div @drop.prevent="onDrop('front')"
                             @dragover.prevent="dragover = true"
                             @dragenter.prevent="dragover = true"
                             @dragleave.prevent="dragover = false"
                             class="drop_media_zone"
                             @click="launchFilePickerFront('front')">
                            <img class="media_placeholder" :src="image_placeholder" alt="">
                            <p class="media-placeholder-title">Drag a photo here or <strong>upload.</strong></p>
                        </div>

                        <input type="file"
                               ref="file_front"
                               @change="onFileChange(
                         $event.target.name, $event.target.files,'front')"
                               style="display:none">
                    </div>


            </div>
        </div>

        <div class="row">
            <div class="col-md-4">

                <div v-show="front_image.id !== -1" class="img-enlarge-wrapper text-center">
                    <span>CLICK TO ENLARGE </span><br>
                    <img data-zoomable="" class="zoomable" :src="introduction_image.url" alt="">
                </div>

            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <label for="dedications_title">Dedications</label>
                    <textarea @keydown="onKeyDown($event,dedication,2300)"  v-model="dedication" class="form-control" id="dedications_title" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="introduction_headline">Introduction Headline</label>
                    <textarea @keydown="onKeyDown($event,introduction_headline,60)" v-model="introduction_headline" class="form-control" id="introduction_headline" rows="3"></textarea>
                </div>


                <div class="form-group">
                    <label for="introduction_title">Introduction</label>
                    <textarea @keydown="onKeyDown($event,back_cover_headline,2300)" v-model="introduction" class="form-control" id="introduction_title" rows="3"></textarea>
                </div>

                <label>Add Introduction Page Photo</label>
                <div class="media_component">
                    <div @drop.prevent="onDrop('introduction')"
                         @dragover.prevent="dragover = true"
                         @dragenter.prevent="dragover = true"
                         @dragleave.prevent="dragover = false"
                         class="drop_media_zone"
                         @click="launchFilePickerFront('introduction')">
                        <img class="media_placeholder" :src="image_placeholder" alt="">
                        <p class="media-placeholder-title">Drag a photo here or <strong>upload.</strong></p>
                    </div>

                    <input type="file"
                           ref="file_introduction"
                           @change="onFileChange(
                         $event.target.name, $event.target.files,'introduction')"
                           style="display:none">
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-4">
                <div v-show="front_image.id !== -1" class="img-enlarge-wrapper text-center">
                    <span>CLICK TO ENLARGE </span><br>
                    <img data-zoomable="" class="zoomable" :src="back_image.url" alt="">
                </div>

            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <label for="back_cover_headline">Back Cover Headline</label>
                    <textarea @keydown="onKeyDown($event,back_cover_headline,60)" v-model="back_cover_headline" class="form-control" id="back_cover_headline" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="back_cover_story">Back Cover Story</label>
                    <textarea @keydown="onKeyDown($event,back_cover_headline,2300)" v-model="back_cover_story" class="form-control" id="back_cover_story" rows="3"></textarea>
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

                <!--<div class="form-group">
                    <label for="">Back Cover</label>
                    <v-file-input v-model="back_image" @change="fileChanged(2)"  label="Add an image" />
                </div>-->

                <!--<div v-if="back_image !== null" class="form-group photo-gallery">
                    <div class="photo-wrapper">
                        <img class="img-badge" :src="back_image.url" alt="">
                        <span :data-photo-id="back_image.id" class="delete_photo_btn" @click="deletePhoto(back_image.id,2)">X</span>
                    </div>
                </div>-->


                <label>Add Back Cover Photo</label>
                <div class="media_component">
                    <div @drop.prevent="onDrop('back')"
                         @dragover.prevent="dragover = true"
                         @dragenter.prevent="dragover = true"
                         @dragleave.prevent="dragover = false"
                         class="drop_media_zone"
                         @click="launchFilePickerFront('back')">
                        <img class="media_placeholder" :src="image_placeholder" alt="">
                        <p class="media-placeholder-title">Drag a photo here or <strong>upload.</strong></p>
                    </div>

                    <input type="file"
                           ref="file_back"
                           @change="onFileChange(
                         $event.target.name, $event.target.files,'back')"
                           style="display:none">
                </div>

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
            </div>
        </div>
        </form>
        <media-dialog :caption="caption" @updateImage="editPhoto" :photo_update="photo_update" @addImage="fileChanged" :current_image="current_image" @closeDialog="closeMediaDialog()" :dialogMedia="dialogMedia" :multiple="true" ></media-dialog>

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
                author_name: '',
               // acknowledgments:'',
                introduction_headline: '',
                introduction:'',
                back_cover_headline: '',
                back_cover_story: '',
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
                recipe: null,
                search:null,
                selected_recipes:[],
                image_placeholder: '',
                current_image: null,
                photo_update: false,
                dialogMedia: false,
                caption: false,
                image_type: '',
                introduction_image_caption: ''
            }
        },
        created(){
            if(parseFloat(this.edit_mode) > 0){
                this.getCookbook();
            }
            this.image_placeholder = parameters.plugin_path + '/assets/images/image.png';
            mediumZoom('.zoomable')
        },
        setDefaults(){
            this.title = "";
            this.dedication = "";
            this.acknowledgments= "";
            this.introduction= "";
            this.back_image = null;
            this.recipe = null;
            this.search = null;
            this.selected_recipes = [];
        },
        computed:{
            /*myRecipes: function(){
                return this.recipes
            },*/
            editMode(){
                if(this.edit_mode > 0){
                    return true;
                }else{
                    return false
                }
            }
        },
        updated(){

        },
        mounted(){
            mediumZoom('.zoomable')
        },
        methods:{
            onKeyDown(evt,element,max){
                if (element.length >= max) {
                    if (evt.keyCode >= 48 && evt.keyCode <= 90) {
                        evt.preventDefault()
                        return
                    }
                }
            },
            editPhoto(photo){
                var photoIndex = this.photos.findIndex((obj => obj.id === this.current_image.id));
                photo.id = this.current_image.id;
                photo.url = this.current_image.url;
                this.photos[photoIndex] = photo;


                this.photo_update = false;
                this.dialogMedia = false;
                this.current_image = false;

            },
            closeMediaDialog(){
                this.current_image = null;
                this.photo_update = false;
                this.dialogMedia = false;
            },
            onFileChange(fieldName, file, type) {
                const { maxSize } = this
                let imageFile = file[0];

                if (file.length>0) {
                    let size = imageFile.size / maxSize / maxSize
                    if (!imageFile.type.match('image.*')) {
                        toastr.warning('Please choose an image file', 'Error');
                    } else if (size>1) {
                        toastr.warning('Your file is too big! Please select an image under 1MB', 'Error');
                    } else {
                        this.image_type = type;
                        this.current_image = imageFile;
                        this.dialogMedia = true;
                        if(this.image_type === 'introduction'){
                            this.caption = true;
                        }else{
                            this.caption = false;
                        }
                    }
                }
            },
            launchFilePickerFront(type){
                if(type === 'front'){
                    this.$refs.file_front.click();
                }
                else if(type === 'introduction'){
                    this.$refs.file_introduction.click();
                } else{
                    this.$refs.file_back.click();
                }

            },
            onDrop(e, type) {
                if (e.dataTransfer.files.length > 1) {
                    toastr.warning("Only one file can be uploaded at a time..", 'Error');
                    return;
                }

                this.image_type = type;

                this.current_image = e.dataTransfer.files[0];
                this.dialogMedia = true;

            },
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
                if(this.title !== '' && this.selected_recipes.length > 0 ){
                    return true;
                }
                return false;
            },
            addCookbook(){
                console.log('Add Cookbook')
                if(this.checkForm() ){
                    console.log('Enter Add Cookbook')
                    const formData = new FormData();
                    formData.append('action', 'add_cookbook');
                    formData.append('title', this.title);
                    formData.append('author', this.author_name);
                    formData.append('dedication', this.dedication);
                    formData.append('introduction_headline', this.introduction_headline);
                    //formData.append('acknowledgments', this.acknowledgments);
                    formData.append('introduction', this.introduction);
                    formData.append('back_cover_headline', this.back_cover_headline);
                    formData.append('back_cover_story', this.back_cover_story);
                    formData.append('front_image', this.front_image.id);
                    formData.append('introduction_image', this.introduction_image.id);
                    formData.append('back_image', this.back_image.id);
                    formData.append('recipes', this.getTheRecipesIDs());
                    formData.append('author_id', parameters.owner.ID);
                    formData.append('edit', this.edit_mode);
                    formData.append('introduction_image_caption', this.introduction_image_caption);

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
            fileChanged(image){

                const formData = new FormData();
                formData.append('action', 'add_photo');
                formData.append('type', this.image_type);
                formData.append('image', this.current_image);

                this.loading = true;
                axios.post(parameters.ajax_url, formData)
                    .then( response => {
                        if(response.data.success){
                            if(this.image_type === 'front'){
                                this.front_image = {
                                    id: response.data.image.id,
                                    url: response.data.image.url
                                }
                            }else if(this.image_type === 'introduction'){
                                this.introduction_image = {
                                    id: response.data.image.id,
                                    url: response.data.image.url
                                }
                                this.introduction_image_caption = image.caption;
                            }else{
                                this.back_image = {
                                    id: response.data.image.id,
                                    url: response.data.image.url
                                }
                            }
                        }else{
                            toastr.warning('The photo was not inserted', 'Error');
                        }


                    });
                this.loading = false;
                this.closeMediaDialog()

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
                            this.author_name = response.data.cookbook.author;
                            this.dedication = response.data.cookbook.dedication;
                            this.introduction_headline = response.data.cookbook.introduction_headline;
                            this.back_cover_headline = response.data.cookbook.back_cover_headline;
                            this.back_cover_story = response.data.cookbook.back_cover_story;
                            this.introduction = response.data.cookbook.introduction;
                            this.acknowledgments = response.data.cookbook.acknowledgments;
                            this.selected_recipes = response.data.cookbook.selected_recipes;

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

    .img-enlarge-wrapper{
        width: 200px;
        margin-bottom: 30px;
    }

    textarea.form-control{
        resize: none;
    }
</style>
