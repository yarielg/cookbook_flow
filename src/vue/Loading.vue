<template>
    <div class="text-center">

        <v-dialog
                v-model="modal"
                hide-overlay
                persistent
                width="300"
        >
            <v-card
                    color="primary"
                    dark
            >
                <v-card-text>
                    Loading
                    <v-progress-linear
                            indeterminate
                            color="white"
                            class="mb-0"
                    ></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>


<script>
    export default {
        props:['loading'],
        data () {
            return {

            }
        },
        computed:{
            modal(){
                return this.loading;
            }
        },
        watch: {
            dialog (val) {
                if (!val) return

                setTimeout(() => (this.dialog = false), 4000)
            },
        },
    }
</script>