<template>
    <v-dialog
            v-model="modal"
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
                                <input v-model="ingredient.quantity" type="number" class="form-control" required>
                            </v-col>

                            <v-col cols="2">
                                <select v-model="ingredient.unit" name="recipe_category" class="form-control" id="recipe_category">
                                    <option value="oz" selected >oz</option>
                                    <option value="ml">ml</option>
                                    <option value="cup">cup</option>
                                </select>
                            </v-col>
                            <v-col cols="7">
                                <input required v-model="ingredient.name" type="text" class="form-control" >
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
                    Add
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
            },
            modal(){
                return this.dialogIngredient;
            }
        },
        data () {
            return {
              //  dialog: false,
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

        },
        methods:{
            closeDialog(){
                this.$emit('closeDialog');
            },
            removeIngredient(key){
                this.$emit('removeIngredient',key);
            },
            addIngredient(key){
                this.$emit('addIngredient',key);
            }
        }

    }
</script>


<style>

</style>
