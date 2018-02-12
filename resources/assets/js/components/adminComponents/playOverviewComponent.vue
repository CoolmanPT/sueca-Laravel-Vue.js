<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2 align-content-center">
                    <img :src="user.avatar" alt="avatar" class="img-fluid w-50 ml-auto mr-auto">
                </div>
                <div class="col-lg-3">
                    <p>Name: {{user.name}}</p>
                    <p class="">Nickname: {{user.nickname}}</p>
                    <p>Email: {{user.email}}</p>
                    <p>Status: {{status}}</p>
                    <button @click="changeStatus(user)" v-if="user.blocked === 1" class="btn btn-success">Unblock
                    </button>
                    <button @click="changeStatus(user)" v-else class="btn btn-danger">Block</button>
                    <button @click="deleteStatus(user)" class="btn btn-primary">Delete</button>
                </div>
                <div class="col-lg-7" v-if="showReasonbox === 1">
                    <textarea v-model="reason" placeholder="reason" class=" form-control" rows="5" cols="4"></textarea>
                    <button @click="cancel" class="btn btn-default  mt-1">Cancel</button>
                    <button @click="sendStatus" class="btn btn-dark float-right mt-1">Save</button>
                    <div class="alert alert-success" role="alert" v-if="success">
                        <p>{{successMessage}}</p>
                    </div>
                </div>
                <div class="col-lg-7" v-if="showReasonbox === 2">
                    <textarea v-model="reasonToDelete" placeholder="reason to delete" class=" form-control" rows="5" cols="4"></textarea>
                    <button @click="cancel" class="btn btn-default  mt-1">Cancel</button>
                    <button @click="deletePlayer" class="btn btn-dark float-right mt-1">Save</button>
                    <div class="alert alert-success" role="alert" v-if="success">
                        <p>{{successMessage}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    // Component code (not registered)
    module.exports = {
        props: ['user'],
        data: function () {
            return {
                status: '',
                reason: '',
                reasonToDelete: '',
                showReasonbox: 0,
                user1: this.user,
                success: false,
                successMessage: '',

            }
        },
        computed: {},
        components: {}
        ,
        methods: {
            getStringStatus: function () {
                if (this.user.blocked === 1) {
                    this.status = 'Blocked';
                } else {
                    this.status = 'Unblocked';
                }
            },
            changeStatus(user) {
                this.showReasonbox = 1;

            },
            deleteStatus(user) {
                this.showReasonbox = 2;

            },
            deletePlayer() {
                console.log(this.user.id);
                console.log(this.reasonToDelete);
                axios.delete('api/admin/user/'+ this.user.id +'/'+ this.reasonToDelete)
                .then(response => {
                    this.$parent.$parent.getUsers();
                    this.success = true;
                    this.successMessage = "user deleted!";
                    setTimeout(() => this.success = false, 3000);
                });

            },
            sendStatus() {
                const data = {
                    reason: this.reason,
                    user1: this.user,
                };
                console.log(data);
                axios.post('api/admin/user/state', data)
                .then(response => {
                    this.$parent.$parent.getUsers();
                    this.success = true;
                    this.successMessage = 'User changed';

                    setTimeout(() => this.success = false, 3000);
                });
            },
            cancel() {
                this.showReasonbox = 0;
            }
        },

        created: function () {
            this.getStringStatus();
        }


    }
    </script>