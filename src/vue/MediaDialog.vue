<template>
    <v-dialog v-if="modal" @click:outside="closeDialog" v-model="modal" width="600">
        <v-card>
            <v-card-title v-if="!photo_update">Add Image </v-card-title>
            <v-card-title v-if="photo_update">Edit Image </v-card-title>
            <v-spacer></v-spacer>

            <v-card-text>
                <v-row>
                    <v-col cols="4">
                        <img :src="image_url" alt="">
                    </v-col>
                    <v-col cols="8">
                        <div class="form-group">
                            <label for="caption">CAPTION (optional)</label>
                            <textarea ref="caption" :value="image.caption"  class="form-control" id="caption" rows="3"></textarea>
                        </div>
                        <div class="form-group form-check">
                            <input ref="primary" :checked="image.primary" :value="image.primary"  type="checkbox" class="form-check-input" id="primary">
                            <label  for="primary" class="form-check-label">Use as recipe's feature image</label>
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>

                <button @click="closeDialog">Cancel</button>
                <button v-if="!photo_update" class="btn-normal ml-3" @click.stop="submit">Add</button>
                <button v-if="photo_update" class="btn-normal ml-3" @click.stop="submitEdit">Edit</button>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "Upload",
        props: ['dialogMedia','current_image','photo_update'],
        computed:{
            modal(){
                return this.dialogMedia;
            },
            image() {
                return this.current_image;
            },
            image_url(){
                if(this.current_image !== null && this.current_image !== ''){
                    return this.photo_update ? this.current_image.url : URL.createObjectURL(this.current_image)
                }
                else
                return null;
            },
            update(){
                return this.photo_update;
            }
        },
        data() {
            return {

            };
        },
        methods: {
            closeDialog(){
                this.$emit('closeDialog');
                this.setDefaults();
            },
            setDefaults(){
                /*this.caption = '';
                this.primary = false;*/
            },
            submit() {

                let image = {
                    caption: this.$refs.caption.value,
                    primary: this.$refs.primary.checked,
                    url: this.image_url
                }
                this.setDefaults();
                this.$emit('addImage',image);
            },
            submitEdit(){
                let image = {
                    caption: this.$refs.caption.value,
                    primary: this.$refs.primary.checked,
                }
                 this.setDefaults();
                this.$emit('updateImage',image);
            }
        }
    };
</script>