<template>
    <v-dialog
            v-model="dialogIngredient"
            scrollable

            width="800">
        <v-card>
            <v-card-title class="headline" primary-title >Add Ingredients</v-card-title>

            <v-spacer></v-spacer>

            <v-card-text style="height: 500px">
                <v-form v-model="valid" ref="form">
                    <v-container>
                        <v-row v-for="ingredient in ingredientsList" :key="ingredient.key">
                            <v-col cols="2">
                                <v-text-field
                                        v-model="ingredient.quantity"
                                        required
                                        :rules="quantityRules"
                                        type="number"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="2">
                                <v-text-field
                                        v-model="ingredient.unit"
                                        required
                                        :rules="unitRules"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="7">
                                <v-text-field
                                        v-model="ingredient.name"
                                        required
                                        :rules="nameRules"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="1">
                                <v-icon @click="removeIngredient(ingredient.key)" aria-hidden="false">
                                    mdi-trash-can
                                </v-icon>
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
                        @click="addIngredient"
                >
                    Add more
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        props:['dialogIngredient','ingredients'],
        computed:{
            ingredientsList(){
                return this.ingredients;
            }
        },
        data () {
            return {
                dialog: false,
                valid:false,
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
            console.log(parameters)
        },
        methods:{
            closeDialog(){
                this.$emit('closeDialog');
            },
            removeIngredient(key){
                this.$emit('removeIngredient',key);
            },
            addIngredient(key){
                const isValid = this.$refs.form.validate();
                if(isValid){
                    this.$emit('addIngredient',key);
                }else{
                    alert('Please fix the errors first')
                }

            }
        }

    }
</script>


<style>

</style>
