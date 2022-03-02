<template>
    <v-dialog
            @click:outside="closeDialog"
            v-model="modal"
            scrollable
            width="970"
            content-class="wrech modal-ingredients">
        <v-card class="wrech">
            <v-row class="no-gutters modal-ingredients-heading align-items-center">
                <v-col>
                    <v-card-actions class="top-add-more-button p-0">
                        <v-btn
                                color="primary"
                                text
                                @click="addIngredient"
                        >
                            Add more
                        </v-btn>
                    </v-card-actions>
                </v-col>

                <v-col>
                    <v-card-title class="headline" primary-title >Add Ingredients</v-card-title>
                </v-col>

                <v-col class="close-button">
                    <v-btn
                            color="default"
                            text
                            @click="closeDialog"
                    >
                        <v-icon aria-hidden="false">
                            mdi-close
                        </v-icon>
                    </v-btn>                    
                </v-col>
            </v-row>
            <v-spacer></v-spacer>

            <v-card-text class="ingredient-main-container" style="height: 500px">
                <v-form v-model="valid" ref="form">
                    <v-container>
                        <v-row v-for="ingredient in ingredientsList" :key="ingredient.key" class="ingredient-row no-gutters">
                            <v-card-text>
                                <input v-model="ingredient.quantity" type="number" class="form-control" required>
                            </v-card-text>

                            <v-card-text>
                                <select v-model="ingredient.unit" name="recipe_category" class="form-control" id="recipe_category">
                                    <option value="oz" selected >oz</option>
                                    <option value="ml">ml</option>
                                    <option value="cup">cup</option>
                                </select>
                            </v-card-text>

                            <v-card-text class="flex-1">
                                <input required v-model="ingredient.name" type="text" class="form-control" >
                            </v-card-text>

                            <v-card-text>
                                <v-icon @click="removeIngredient(ingredient.key)" aria-hidden="false">
                                    mdi-trash-can
                                </v-icon>
                            </v-card-text>
                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>

            <v-card-actions class="bottom-add-buttons">
                <v-spacer></v-spacer>
                <v-btn
                        color="default"
                        text
                        @click="closeDialog"
                >
                    Cancel
                </v-btn>
                <v-btn
                        color="primary"
                        text
                        @click="closeDialog"
                >
                    Save Ingredients
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


<style scoped>
</style>
