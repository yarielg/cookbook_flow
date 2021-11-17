<template>
  <div>
      <h5>This text is coming from vue</h5>
      <v-form
              ref="form"
              v-model="valid"
              lazy-validation
      >
          <v-text-field
                  v-model="name"
                  :counter="10"
                  :rules="nameRules"
                  label="Name"
                  required
          ></v-text-field>

          <v-text-field
                  v-model="email"
                  :rules="emailRules"
                  label="E-mail"
                  required
          ></v-text-field>

          <v-select
                  v-model="select"
                  :items="items"
                  :rules="[v => !!v || 'Item is required']"
                  label="Item"
                  required
          ></v-select>

          <v-checkbox
                  v-model="checkbox"
                  :rules="[v => !!v || 'You must agree to continue!']"
                  label="Do you agree?"
                  required
          ></v-checkbox>

          <v-btn
                  :disabled="!valid"
                  color="success"
                  class="mr-4"
                  @click="validate"
          >
              Validate
          </v-btn>

          <v-btn
                  color="error"
                  class="mr-4"
                  @click="reset"
          >
              Reset Form
          </v-btn>

          <v-btn
                  color="warning"
                  @click="resetValidation"
          >
              Reset Validation
          </v-btn>
      </v-form>

      <v-row>
          <template>
              <v-card
                      class="mx-auto"
                      max-width="400"
              >
                  <v-list flat>
                      <v-list-item-group
                              v-model="model"
                              color="indigo"
                      >
                          <v-list-item
                                  v-for="(item, i) in itemsX"
                                  :key="i"
                          >
                              <v-list-item-icon>
                                  <v-icon v-text="item.icon"></v-icon>
                              </v-list-item-icon>

                              <v-list-item-content>
                                  <v-list-item-title v-text="item.text"></v-list-item-title>
                              </v-list-item-content>
                          </v-list-item>
                      </v-list-item-group>
                  </v-list>
              </v-card>
          </template>
      </v-row>


  </div>
</template>

<script>
    export default {
        data: () => ({
            valid: true,
            name: '',
            nameRules: [
                v => !!v || 'Name is required',
                v => (v && v.length <= 10) || 'Name must be less than 10 characters',
            ],
            email: '',
            emailRules: [
                v => !!v || 'E-mail is required',
                v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
            ],
            select: null,
            items: [
                'Item 1',
                'Item 2',
                'Item 3',
                'Item 4',
            ],
            checkbox: false,
            itemsX: [
                {
                    icon: 'mdi-wifi',
                    text: 'Wifi',
                },
                {
                    icon: 'mdi-bluetooth',
                    text: 'Bluetooth',
                },
                {
                    icon: 'mdi-chart-donut',
                    text: 'Data Usage',
                },
            ],
            model: 1,
        }),

        methods: {
            validate () {
                this.$refs.form.validate()
            },
            reset () {
                this.$refs.form.reset()
            },
            resetValidation () {
                this.$refs.form.resetValidation()
            },
        },
    }
</script>

<!--<script>
  const axios = require('axios');
  import $ from 'jquery';


  export default {
    components: {
    },
    data () {
      return {
        items: [
          ['mdi-shield-plus', 'New Template','new-template'],
          ['mdi-shield', 'Badges','badges'],
          ['mdi-cogs', 'Templates','templates'],

        ],
      }
    },
    mounted() {

    },
    methods:{
      loadDefaults(){
        $('#vwp-plugin-loading').css('display','none');
      },

      goToWordpress(){
        window.location.replace("/wp-admin");
      },
    },
    created() {
       this.loadDefaults();
    },

  }
</script>-->

<style>

</style>
