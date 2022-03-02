<template>
    <v-dialog
            @click:outside="closeDialog"
            v-model="modal"
            scrollable
            width="970"
            content-class="wrech modal-ingredients">
        <v-card class="wrech">
            <v-card-title class="headline" primary-title >Add Ingredients</v-card-title>

            <v-spacer></v-spacer>

            <v-card-text style="height: 500px">
                <v-form v-model="valid" ref="form">
                    <v-container>
                        <v-row v-for="ingredient in ingredientsList" :key="ingredient.key" class="ingredient-row">
                            <v-col sm="6" cols="6" md="2" lg="2">
                                <input v-model="ingredient.quantity" type="number" class="form-control" required>
                            </v-col>

                            <v-col sm="6" cols="6" md="2" lg="2">
                                <select v-model="ingredient.unit" name="recipe_category" class="form-control" id="recipe_category">
                                    <option value="oz" selected >oz</option>
                                    <option value="ml">ml</option>
                                    <option value="cup">cup</option>
                                </select>
                            </v-col>

                            <v-col sm="10" cols="10" md="7" lg="7">
                                <input required v-model="ingredient.name" type="text" class="form-control" >
                            </v-col>
                            
                            <v-col sm="2" cols="2" md="1" lg="1">
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
                        color="default"
                        text
                        @click="closeDialog"
                >
                    Cancel
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


<style scoped>
    .ingredient-row{
        border-bottom: 1px solid #78849c
    }


</style>
