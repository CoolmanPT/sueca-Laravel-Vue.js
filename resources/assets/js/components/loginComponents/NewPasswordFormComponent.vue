<template>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-sm-12  mt-5 ml-auto mr-auto">
                    <div class="card">
                        <div class="card-header bg-dark">
                            <h1 class="text-center text-light">
                                New Password
                            </h1>
                        </div>
                        <div class="card-body pb-1">
                            <form method="post" v-on:submit.prevent="validateForm">


                                <div class="form-group">
                                    <input type="password" name="password" v-model="password"
                                           v-bind:class="{ 'is-invalid': missingPassword || invalidPassword }"
                                           class="form-control" placeholder="New Password"/>
                                    <div class="alert-danger" role="alert" v-cloak v-show="isFormInvalid">
                                        <p v-if="missingPassword">Fill Password</p>
                                    </div>
                                    <div class="alert-danger">
                                        <p v-if="invalidPassword">Password must be at least 6 digits</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="passwordConfirmation" v-model="passwordConfirmation"
                                           v-bind:class="{ 'is-invalid': missingPasswordConfirmation || wrongPasswordConfirmation }"
                                           class="form-control" placeholder="Confirm Password"/>
                                    <div class="alert-danger">
                                        <p v-if="missingPasswordConfirmation">Confirm Password</p>
                                    </div>
                                    <div class="alert-danger">
                                        <p v-if="wrongPasswordConfirmation">Passwords Mismatch</p>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-dark btn-block mt-3">Change</button>
                                <div class="float-right  mt-3 mb-0">
                                    <p class="">Go to
                                        <router-link to="/login" class="">login</router-link>
                                    </p>
                                </div>
                                    <div class="alert alert-success" role="alert" v-cloak v-show="success">
                                        <p>Password alterada com sucesso! Faça login.</p>
                                    </div>
                                    <div class="alert-danger">
                                        <p v-if="serverError">{{ serverErrorMessage }}</p>
                                    </div>


                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default {
        props: ['token', 'user'],
        data: function () {
            return {
                password: '',
                passwordConfirmation: '',
                attemptSubmit: false,
                serverError: false,
                serverErrorMessage: '',
                success: false
            }
        },
        computed: {
            missingPassword: function () {
                return this.password.trim() === '' && !this.hasServerError && this.attemptSubmit;
            },
            invalidPassword: function () {
                return !this.missingPassword && !this.validatePasswordStructure(this.password.trim()) && !this.hasServerError && this.attemptSubmit;
            },
            missingPasswordConfirmation: function () {
                return !this.missingPassword && !this.invalidPassword && this.passwordConfirmation.trim() === '' && !this.hasServerError && this.attemptSubmit;
            },
            wrongPasswordConfirmation: function () {
                return !this.missingPassword && !this.invalidPassword && !this.missingPasswordConfirmation && (this.passwordConfirmation.trim() != this.password.trim()) && !this.hasServerError && this.attemptSubmit;
            },
            hasClientError: function () {
                return (this.missingEmail || this.invalidEmail || this.missingPassword || this.invalidPassword || this.wrongPasswordConfirmation || this.missingPasswordConfirmation);
            },
            hasServerError: function () {
                return this.serverError;
            },
            isFormInvalid: function () {
                return (this.hasClientError || this.hasServerError) && this.attemptSubmit;
            },
        },
        methods: {
            validatePasswordStructure: function (password) {
                var re = /^[a-zA-Z0-9]{6,}$/;
                return re.test(password);
            },
            validateForm: function () {
                //CLEARS SERVER ERROR'S
                this.serverError = false;
                this.success = false;

                //PREVENT FORM
                event.preventDefault();

                //FORM SUBMITED
                this.attemptSubmit = true;

                //IF FORM IS VALID MAKE API REQUEST FOR LOGIN
                if (!this.isFormInvalid) {
                    const data = {
                        email: this.user,
                        token: this.token,
                        password: this.password
                    };
                    console.log(data);
                    axios.post('/api/password/reset', data)
                        .then((response) => {

                            this.success = true;
                            this.attemptSubmit = false;
                            this.email = '';
                            this.password = '';
                            this.passwordConfirmation = '';


                        })
                        .catch((error) => {
                            this.serverError = true;
                            this.serverErrorMessage = "server" + error.response.data.msg;
                        });
                    setTimeout( () => this.$router.push({ path: '/login'}), 5000);

                }
            },
        }
    }
</script>