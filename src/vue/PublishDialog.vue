<template>
    <v-dialog
            v-model="modal"
            scrollable

            width="800">
        <v-card>
            <v-card-title class="headline" primary-title >Publish your cookbook</v-card-title>

            <v-spacer></v-spacer>
            <loading-dialog :loading="loading"></loading-dialog>
            <v-card-text style="height: 500px">
                    <v-container>
                        <v-row v-if="current_step === 1">
                            <v-col cols="5" class="text-center">
                                <h5>Select a template</h5>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A ab accusamus aliquid aspernatur assumenda dignissimos, eaque eligendi fuga magni, minus nisi perspiciatis placeat, quaerat repellat veritatis. Consectetur molestias quam quidem.</p>
                                <button @click="chooseOption(1)" class="btn-normal">Continue with a template</button>
                            </v-col>
                            <v-col cols="2" class="text-center">OR</v-col>
                            <v-col cols="5" class="text-center">
                                <h5>Choose services</h5>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A ab accusamus aliquid aspernatur assumenda dignissimos, eaque eligendi fuga magni, minus nisi perspiciatis placeat, quaerat repellat veritatis. Consectetur molestias quam quidem.</p>
                                <button @click="chooseOption(2)" class="btn-normal">Customize cookbook</button>
                            </v-col>
                        </v-row>
                        <v-row v-if="current_step === 2">
                            <v-col v-if="option === 1" cols="12">
                                <h5 class="text-center mb-5 pb-5">Choose a template</h5>
                                <v-row>
                                    <v-col class="text-center" cols="6" v-for="template in templates" :key="template.id">
                                        <input @click="" :value="template.id" :id="'template_' + template.id" type="radio" class="template_option" v-model="selected_template">
                                        <label :for="'template_' + template.id" class="select_template">
                                            <img class="template_image" :src="template.url" alt="">
                                            <p class="text-center mt-2">{{ template.name }}</p>
                                        </label>

                                    </v-col>
                                </v-row>
                            </v-col>

                            <v-col cols="12" v-if="option === 2">
                                <h5 class="text-center mb-5 pb-5" >Choose Services</h5>
                                <v-row>
                                    <v-col cols="6" class="text-center" v-for="service in services" :key="service.id">
                                        <input v-model="selected_services" :value="service.id" type="checkbox" class="service_option" :id="'service_'+service.id">
                                        <label :for="'service_'+service.id" class="select_service">
                                            <img class="service_image" :src="service.url" alt="">
                                            <p class="mt-2">{{ service.name }}</p>
                                        </label>

                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-container>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" text @click="closeDialog">Cancel</v-btn>
                <v-btn color="primary" v-if="current_step == 2" text @click="previous()">Previous</v-btn>
                <v-btn color="primary" v-if="current_step == 2" text @click="proceed()">Proceed</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    const axios = require('axios');

    export default {
        props:['publish_dialog','cookbook_id'],
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
                nameRules: [
                    v => !!v || 'Name is required',
                ],
                quantityRules: [
                    v => !!v || 'Quantity is required',
                ],
                unitRules: [
                    v => !!v || 'Unit is required',
                ]
            }
        },
        created(){
            this.getTemplates();
            this.getServices();
        },
        methods:{
            closeDialog(){
                this.current_step = 1;
                this.$emit('closeDialogPublish');
            },
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
                formData.append('template', this.selected_template);
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
</style>
