<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-12 col-md-6 m-auto">
                <div class="card">
                    <div class="card-header bg-dark">
                        <h1 class="text-center text-light">Create User</h1>
                    </div>
                    <div class="card-body">
                        <form method="post" v-on:submit.prevent="validateForm">

                            <div class="alert alert-success" role="alert" v-cloak v-show="success">
                                <p>User Created, check email for activation</p>
                            </div>

                            <div class="form-group">
                                <input type="text" name="name" v-model="name" v-bind:class="{ 'is-invalid': missingName  }"
                                       class="form-control" placeholder="Name"/>
                                <div class="alert-danger" role="alert" v-cloak v-show="isFormInvalid" ><p v-if="missingName">Fill Name</p></div>
                            </div>

                            <div class="form-group">
                                <input type="text" name="nickname" v-model="nickname" v-bind:class="{ 'is-invalid': missingNickname || nicknameAlreadyUsed  }"
                                       class="form-control" placeholder="Nickname"/>
                                <div class="alert-danger" role="alert" v-cloak v-show="isFormInvalid"><p v-if="missingNickname">Fill Nickname</p></div>
                            </div>

                            <div class="form-group">
                                <input type="text" name="email" v-model="email"
                                       v-bind:class="{ 'is-invalid': missingEmail || invalidEmail || emailAlreadyUsed  }" class="form-control"
                                       placeholder="Email"/>
                                <div class="alert-danger" role="alert" v-cloak v-show="isFormInvalid"><p v-if="missingEmail">Fill Email</p></div>
                                <div class="alert-danger" role="alert" v-cloak v-show="isFormInvalid"><p v-if="invalidEmail">Invalid Email</p></div>
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" autocomplete="new-password" v-model="password"
                                       v-bind:class="{ 'is-invalid': missingPassword || invalidPassword }" class="form-control"
                                       placeholder="Password"/>
                                <div class="alert-danger" role="alert" v-cloak v-show="isFormInvalid"><p v-if="missingPassword">Fill Password</p></div>
                                <div class="alert-danger" role="alert" v-cloak v-show="isFormInvalid"><p v-if="invalidPassword">Password must be 6 digits long</p></div>

                            </div>
                            <div class="form-group">
                                <input type="password" name="passwordConfirmation" v-model="passwordConfirmation"
                                       v-bind:class="{ 'is-invalid': missingPasswordConfirmation || wrongPasswordConfirmation }"
                                       class="form-control" placeholder="Confirm Password"/>
                                <div class=" alert-danger" role="alert" v-cloak v-show="isFormInvalid"><p v-if="missingPasswordConfirmation">Confirm Password</p></div>
                                <div class=" alert-danger" role="alert" v-cloak v-show="isFormInvalid"> <p v-if="wrongPasswordConfirmation">Password Mismatch</p></div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-success  m-auto btn-block">Register</button>

                                </div>
                                <div class="col-sm-6">
                                    <button type="button" @click="clear" class="btn btn-danger m-auto btn-block">Cancel</button>
                                </div>
                            </div>

                            <div class="clearfix">
                                <div class="alert-danger" role="alert" v-cloak v-show="isFormInvalid"><p v-if="hasServerError">{{ serverErrorMessage }}</p></div>
                            </div>

                                <p class="text-center mt-4 pb-0">
                                    Back to
                                    <router-link to="/login" class="text-primary">Login</router-link>

                                </p>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script type="text/javascript">
    export default {
        data: function () {
            return {
                name: '',
                nickname: '',
                email: '',
                password: '',
                passwordConfirmation: '',
                attemptSubmit: false,
                serverErrorCode: 0,
                serverErrorMessage: '',
                success: false
            }
        },
        computed: {
            missingName: function () {
                return this.name.trim() === '' && !this.hasServerError && this.attemptSubmit;
            },
            missingEmail: function () {
                return this.email.trim() === '' && !this.hasServerError && this.attemptSubmit;
            },
            invalidEmail: function () {
                return !this.missingEmail && !this.validateEmail(this.email.trim()) && !this.hasServerError && this.attemptSubmit;
            },
            emailAlreadyUsed: function () {
                return this.serverErrorCode == 1;
            },
            missingNickname: function () {
                return this.nickname.trim() === '' && !this.hasServerError && this.attemptSubmit;
            },
            nicknameAlreadyUsed: function () {
                return this.serverErrorCode == 2;
            },
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
                return (this.missingName || this.missingEmail || this.missingNickname || this.missingPassword || this.invalidPassword || this.wrongPasswordConfirmation || this.missingPasswordConfirmation || this.invalidEmail);
            },
            hasServerError: function () {
                return this.serverErrorCode == -1 || this.emailAlreadyUsed || this.nicknameAlreadyUsed;
            },
            isFormInvalid: function () {
                return (this.hasClientError || this.hasServerError) && this.attemptSubmit;
            },
        },
        methods: {
            validateEmail: function (email) {
                var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },
            validatePasswordStructure: function (password) {
                var re = /^[a-zA-Z0-9]{6,}$/;
                return re.test(password);
            },
            clear: function () {
                this.name = '';
                this.nickname = '';
                this.email = '';
                this.password = '';
                this.passwordConfirmation = '';
                this.attemptSubmit = false;
                this.serverErrorCode = 0;
            },
            validateForm: function () {
                //CLEARS SERVER ERROR'S
                this.serverErrorCode = 0;
                this.success = false;

                //PREVENT FORM
                event.preventDefault();

                //FORM SUBMITED
                this.attemptSubmit = true;

                //IF FORM IS VALID MAKE API REQUEST FOR LOGIN
                if (!this.isFormInvalid) {
                    const data = {
                        name: this.name,
                        nickname: this.nickname,
                        email: this.email,
                        password: this.password
                    };
                    axios.post('/api/register', data)
                        .then((response) => {
                            this.success = true;
                            this.clear();
                        })
                        .catch((error) => {
                            this.serverErrorCode = error.response.data.errorCode;
                            this.serverErrorMessage = error.response.data.msg;
                        });
                    setTimeout( () => this.$router.push({ path: '/login'}), 5000);
                }
            },
        }
    }
</script>