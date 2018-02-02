<template>
    <div>
        <div class="card">
            <div class="card-header bg-dark">
                <h2 class="text-center text-light">Login</h2>
            </div>
            <div class="card-body">
                <form method="post" v-on:submit.prevent="submitForm">
                    <div class="form-group">
                        <input type="text" name="username" v-model="username" v-bind:class="{ 'is-invalid': missingUsername  }" class="form-control" placeholder="Username/Email"  />
                    </div>
                   <div class="clearfix">
                       <div class="alert alert-danger" role="alert" v-cloak v-show="isFormInvalid && missingUsername ">
                           <p v-if="missingUsername">Fill Username/Email</p>
                           <p v-if="serverError">{{serverErrorMessage}}</p>
                       </div>
                   </div>
                    <div class="form-group mb-0">
                        <input type="password" name="password" v-model="password" v-bind:class="{ 'is-invalid': missingPassword }" class="form-control margin-bottom0" placeholder="Password"  />
                    </div>
                    <div class="clearfix">
                        <div class="alert alert-danger" role="alert" v-cloak v-show="isFormInvalid && missingPassword">
                            <p v-if="missingPassword">Fill Password</p>
                            <p v-if="serverError">{{serverErrorMessage}}</p>
                        </div>
                    </div>

                    <router-link to="/password/reset" class="float-right text-muted mt-0 small font-italic" >Forgot Password?</router-link>

                    <button type="submit" class="btn btn-dark btn-block mt-4">Log in</button>
                    <p class="text-center mb-0 pb-0 mt-2">
                        Don't have a account? <router-link to="/register" class="">Create</router-link>
                    </p>

                </form>
            </div>
        </div>
    </div>
</template>

<script>


    export default {
        data: function() {
            return {
                username: '',
                password: '',
                attemptSubmit: false,
                serverError: false,
                serverErrorMessage: '',
            }
        },
        computed: {
            missingUsername: function () {
                return this.username.trim() === '' && !this.hasServerError && this.attemptSubmit;
            },
            missingPassword: function () {
                return this.password.trim() === '' && !this.hasServerError && this.attemptSubmit;
            },
            hasClientError: function () {
                return (this.missingUsername || this.missingPassword);
            },
            hasServerError: function () {
                return this.serverError;
            },
            isFormInvalid: function () {
                return (this.hasClientError || this.hasServerError) && this.attemptSubmit;
            },
        },
        methods: {
            submitForm: function (event) {
                this.serverError = false;
                this.attemptSubmit = true;

                if (!this.isFormInvalid) {
                    const data = {
                        username: this.username,
                        password: this.password,
                    };
                    console.log(this.username + this.password);
                    axios.post('/api/login', data)
                        .then((response) => {
                            localStorage.setItem('access_token', 'Bearer ' + response.data.access_token);
                            axios.get('/api/user', { headers: {"Authorization" : 'Bearer ' + response.data.access_token}})
                                .then((response) => {
                                    if(response.data.admin == 1){
                                        window.location.href = '/admin'
                                    } else if (response.data.admin == 0) {
                                        window.location.href = '/game'
                                    } else {
                                        this.serverError = true;
                                    }
                                })
                                .catch((error) => {
                                    this.serverError = true;
                                });
                        })
                        .catch((error) => {
                            this.serverError = true;
                            this.serverErrorMessage = error.response;
                        });
                }
            },
        }

    }
</script>
