<template>
    <div class="page">
        <navbar-component-game :user="user"></navbar-component-game>

        <div class="page-content d-flex align-items-stretch">
            
            <div class="content-inner-game">
                <!-- Page Header-->
                <header class="page-header">
                    <div class="container-fluid">
                        <h2 class="no-margin-bottom">Player Settings</h2>
                    </div>
                </header>
                <!-- Platform Email Section-->
                <section class="forms">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <player-change-settings-component :user="user"></player-change-settings-component>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Page Footer-->
                <footer class="main-footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <p>Recurso Sueca | DAD</p>
                            </div>
                            <div class="col-sm-6 text-right">
                                <p>Alberto Lalanda | Bruno Pereira | Fábio Lage</p>
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