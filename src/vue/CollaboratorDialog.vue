<template>
    <v-dialog
            v-model="modal"
            scrollable

            width="800"
            height="300"
    >
        <v-card>
            <v-card-title class="headline" primary-title>Add Collaborator </v-card-title>

            <v-spacer></v-spacer>

            <v-card-text style="height: 500px">
                <v-form v-model="valid" ref="form">
                    <v-container>
                        <v-row>
                            <v-col sm="12" cols="12" md="4" lg="4">
                                <input v-model="first" type="text" class="form-control" required placeholder="First Name">
                            </v-col>
                            <v-col sm="12" cols="12" md="4" lg="4">
                                <input v-model="last" type="text" class="form-control" required placeholder="Last Name">
                            </v-col>
                            <v-col sm="12" cols="12" md="4" lg="4">
                                <input v-model="email" type="email" class="form-control" required placeholder="Email">
                            </v-col>

                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                        color="primary"
                        text
                        @click="closeDialog"
                >
                    Close
                </v-btn>
                <v-btn
                        color="primary"
                        text
                        :disabled="!checkForm()"
                        @click="addColllaborator"
                >
                    Add Collaborator
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    const axios = require('axios');

    export default {
        props:['dialogCollaborator'],
        computed:{

            modal(){
                return this.dialogCollaborator;
            }
        },
        data () {
            return {
              //  dialog: false,
                valid:false,
                first:'',
                last:'',
                email:'',
                firstRules: [
                    v => !!v || 'First Name is required',
                ],
                lastRules: [
                    v => !!v || 'Last Name is required',
                ],
                emailRules: [
                    v => !!v || 'Email is required',
                ]
            }
        },
        methods:{
            closeDialog(){
                this.$emit('closeDialog');
            },
            setDefaults(){
                this.first = '';
                this.last = '';
                this.email = '';
            },
            addColllaborator(key){
                if(this.checkForm() ){
                    const formData = new FormData();
                    formData.append('action', 'add_collaborator');
                    formData.append('first', this.first);
                    formData.append('last', this.last);
                    formData.append('email', this.email);
                    formData.append('author_id', parameters.account_selected.id)

                    axios.post(parameters.ajax_url, formData)
                        .then( response => {
                            if(response.data.success){
                                toastr.success('Link invitation sent!', 'Collaborator inserted!');
                                console.log(response);
                                this.$emit('addCollaborator',response.data.collaborator);
                                this.setDefaults();
                                this.closeDialog();

                            }else{
                                toastr.error(response.data.msg, 'Error');
                            }
                        });
                }else{
                    toastr.warning('you have some errors, please correct them.', 'Error');
                }
            },
            checkForm(){
           /* && this.email !== '' && /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email*/
                if(this.first !== '' && this.last !== '' && this.email !== '' && /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email) ){
                    return true;
                }
                return false;
            }
        }

    }
</script>


<style scoped>
.v-dialog{
    height: 230px !important;
}
</style>
