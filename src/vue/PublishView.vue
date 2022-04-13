<template>
    <div class="row">
        <template-dialog :template="selected_template" @closeTemplateDialog="closeTemplateDialog" @proceed="proceed" :template_dialog="template_dialog"></template-dialog>
        <loading-dialog :loading="loading"></loading-dialog>
        <div class="container">
            <div class="row" v-if="current_step === 1">
                <div class="col-12 text-center mt-2">
                    <h5>Select a template</h5>
                    <p>We offer professionally designed templates for your cookbook. Choose the one you like best, and keep in mind that we’ll be posting even more beautiful new templates in the very near future. We want to publish a book you'll be proud to see your name on. After choosing your template, you can expect to see a proof of your book in about three working days.</p>
                    <button @click="chooseOption(1)" class="btn-normal">Continue with a template</button>
                </div>
                <!--<div class="col-md-2 text-center"> OR </div>
                <div class="col-md-5 text-center mt-2">
                    <h5>Choose services</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A ab accusamus aliquid aspernatur assumenda dignissimos, eaque eligendi fuga magni, minus nisi perspiciatis placeat, quaerat repellat veritatis. Consectetur molestias quam quidem.</p>
                    <button @click="chooseOption(2)" class="btn-normal">Customize cookbook</button>
                </div>-->
            </div>
            <div class="row" v-if="current_step === 2">
                <div class="col-12" v-if="option === 1" >
                    <p>PUBLISH YOUR COOKBOOK</p>
                    <h5 class="mb-5 pb-5">Choose a Cookbook Template</h5>
                    <div class="row">
                        <div class="col-md-6" v-for="template in templates" :key="template.id">
                            <div class="card-panel">
                                <img @click="selectTemplate(template)" class="card-img-top template_image" :src="template.url" alt="...">
                                <div class="card-body">
                                    <h4>{{template.name}}</h4>
                                    <p>{{template.caption}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12" v-if="option === 2">
                    <p>PUBLISH YOUR COOKBOOK</p>
                    <h5 class="mb-5 pb-5" >Choose Services for you Cookbook</h5>
                    <div class="row">
                        <div class="text-center col-md-6" v-for="service in services" :key="service.id">
                            <input v-model="selected_services" :value="service.id" type="checkbox" class="service_option" :id="'service_'+service.id">
                            <label :for="'service_'+service.id" class="select_service">
                                <img class="service_image" :src="service.url" alt="">
                                <p class="mt-2">{{ service.name }}</p>
                            </label>

                        </div>
                    </div>
                </div>
            </div>
            <br><br>
            <div class="row m2-5">
                <div class="col-12 text-center">
                    <!--<v-btn color="primary" v-if="current_step == 2" text @click="previous()">Previous</v-btn>-->
                    <button type="button" class="btn-normal" v-if="current_step == 2 && option==2" text @click="proceed()">Proceed</button>
                    <v-btn color="primary" text @click="goBackToView()">Go Back</v-btn>
                </div>
            </div>
        </div>
</div>
</template>

<script>
    const axios = require('axios');

    export default {
        props:['cookbook_id'],
        computed:{
            ingredientsList(){
                return this.ingredients;
            },
            modal(){
                return this.publish_dialog;
            },
            cookbook(){
                return this.cookbook_id;
            }
        },
        data () {
            return {
                //  dialog: false,
                valid:false,
                current_step : 1,
                option:null,
                selected_template: null,
                services: [],
                selected_services:[],
                loading:false,
                templates:[],
                template_dialog: false,
            }
        },
        created(){
            this.getTemplates();
            this.getServices();
        },
        methods:{
            previous(){
                this.current_step --;
            },
            next(){
                this.current_step ++;
            },
            proceed(){
                if(this.option === 1 && this.selected_template === null){
                    toastr.warning('Select a template to continue', 'Error');
                    return
                }

                if(this.option === 2 && this.services.length === 0){
                    toastr.warning('Select at least a service to continue', 'Error');
                    return;
                }
                const formData = new FormData();
                formData.append('action', 'publish_cookbook');
                formData.append('cookbook_id', this.cookbook);
                formData.append('services', JSON.stringify(this.selected_services));
                formData.append('template', this.selected_template !== null ? this.selected_template.id : '');
                formData.append('option', this.option);

                this.loading = true;
                axios.post(parameters.ajax_url, formData)
                    .then( response => {
                        if(response.data.success){
                            window.location = response.data.checkout_url;
                        }else{
                            toastr.warning('We could not publish the cookbook', 'Error');
                        }
                        this.loading = false;
                    });
            },
            chooseOption(option){
                if(option === 1){
                    this.selected_services = [];
                }else {
                    this.selected_template = null;
                }
                this.current_step = 2;
                this.option = option;
            },
            getTemplates(){
                const formData = new FormData();
                formData.append('action', 'get_templates');

                this.loading = true;
                axios.post(parameters.ajax_url, formData)
                    .then( response => {
                        if(response.data.success){
                            this.templates = response.data.templates;
                        }else{
                            toastr.warning('We could not get the templates', 'Error');
                        }
                        this.loading = false;
                    });
            },
            getServices(){
                const formData = new FormData();
                formData.append('action', 'get_services');

                this.loading = true;
                axios.post(parameters.ajax_url, formData)
                    .then( response => {
                        if(response.data.success){
                            this.services = response.data.services;
                        }else{
                            toastr.warning('We could not get the templates', 'Error');
                        }
                        this.loading = false;
                    });
            },
            goBackToView(){
                this.current_step = 1;
                this.$emit('goBackToView');
            },
            closeTemplateDialog(){
                this.template_dialog = false;
                this.selected_template = null;
            },
            selectTemplate(template){
                this.selected_template = template;
                this.template_dialog = true;
            }
        }

    }
</script>

<style scoped>
    input[type='checkbox'], input[type='radio']{
        display: none !important;
    }

    input[type="radio"]:checked + label img, input[type="checkbox"]:checked + label img{
        border: 2px solid black !important;
        filter: drop-shadow(2px 4px 6px black) !important;
    }

    .template_image:hover, .service_image:hover{
        filter: drop-shadow(2px 4px 6px black) !important;
    }

    .card-panel{
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0,0,0,.125);
        border-radius: 0.25rem;
    }
</style>
