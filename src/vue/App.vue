<template>
  <v-app id="inspire">
     <h1>{{ msg }}</h1>
  </v-app>
</template>

<script>
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
        msg : 'This message cam from vue'
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
</script>

<style>
  #gjs > .panel_wrae {
    width: 90% !important;
    max-width: 700px !important;
    border-radius: 3px !important;
    padding: 30px 20px !important;
    margin: 150px auto 0px !important;
    background-color: #d983a6 !important;
    box-shadow: 0px 3px 10px 0px rgba(0,0,0,0.25) !important;
    color:rgba(255,255,255,0.75) !important;
    font: caption !important;
    font-weight: 100 !important;
  }

  .welcome {
    text-align: center;
    font-weight: 100;
    margin: 0px;
  }

</style>
