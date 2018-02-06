<template>
    <div class="page">
        <navbar-component></navbar-component>

        <div class="page-content d-flex align-items-stretch">
            <sidebar-component :user="user"></sidebar-component>


            <div class="content-inner">
                <!-- Page Header-->
                <header class="page-header">
                    <div class="container-fluid">
                        <h2 class="no-margin-bottom">Administrator Settings</h2>
                    </div>
                </header>
                <!-- Platform Email Section-->
                <section class="forms">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <admin-change-email-component></admin-change-email-component>

                                </div>
                            </div>
                            <div class="col-lg-6">
                               <div class="card">
                                   <admin-avatar-change-component :user="user"></admin-avatar-change-component>
                               </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <admin-change-password-component></admin-change-password-component>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Page Footer-->
                <footer class="admin-footer footer--fixed fixed-bottom">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <p>Recurso DAD - Sueca</p>
                            </div>
                            <div class="col-sm-6 text-right">
                                <p>Bruno Pereira - 2151219 ||
                                </p>
                                <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">

    export default {
        data: function () {

            return {
                user: '',

            }
        },
        methods: {
            getUser: function () {
                axios.get('/api/user')
                    .then((response) => {
                        this.user = response.data;


                    })
                    .catch((error) => {

                    });
            },
        },
        computed: {},
        components: {},
        created: function () {
            this.getUser();
        }


    }
    $('#toggle-btn').on('click', function (e) {
        e.preventDefault();
        $(this).toggleClass('active');

        $('.side-navbar').toggleClass('shrinked');
        $('.content-inner').toggleClass('active');
        $(document).trigger('sidebarChanged');

        if ($(window).outerWidth() > 1183) {
            if ($('#toggle-btn').hasClass('active')) {
                $('.navbar-header .brand-small').hide();
                $('.navbar-header .brand-big').show();
            } else {
                $('.navbar-header .brand-small').show();
                $('.navbar-header .brand-big').hide();
            }
        }

        if ($(window).outerWidth() < 1183) {
            $('.navbar-header .brand-small').show();
        }
    });

</script>