<template>
    <div>
        <div class="card-header d-flex align-items-center ">
            <h3 class="h4 text-dark">Administrator Avatar</h3>
        </div>
        <div class="card-body  d-flex align-items-center align-content-center">
                <div class="row">
                    <div class="col-3">
                        <img :src="user.avatar" alt="avatar" class="img-fluid ">

                    </div>
                    <div class="col-6 mt-auto mb-auto">
                        <div class="form-group">

                            <input type="file" v-on:change="onFileChange" class="form-control-file ">
                        </div>
                        <button class="btn btn-dark" @click.prevent="upload">Upload</button>
                        <p class="help is-danger" v-if="serverErrorCode" >{{serverErrorCode }}</p>

                    </div>
                </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        props: ['user'],
        data: function () {

            return {
                filename: '',
                serverErrorCode: '',
            }
        },
        computed: {
            serverError: function () {
                return this.serverErrorCode;
            },
        },
        methods: {
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            upload(){
                const data = {
                    image: this.image,
                    user: this.user,
                    
                }
                console.log(data)
                axios.post('/api/admin/upload/avatar',data).then(response => {
                   this.$parent.getUser();
                    this.serverErrorCode = response.data.msg;
                })
                    .catch((error) => {
                        this.serverErrorCode = error.response.data.msg;
                    });
            }
        }


    }
</script>