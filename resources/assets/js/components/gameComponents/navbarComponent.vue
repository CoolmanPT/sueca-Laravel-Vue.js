<template>
    <header class="header">
        <nav class="navbar">
            <!-- Search Box-->
            <div class="container-fluid">
                <div class="navbar-holder d-flex align-items-center justify-content-between">
                    <!-- Navbar Header-->
                    <div class="navbar-header">
                        <router-link to="/lobby"><div class="brand-text brand-big h4"><span>Recurso Sueca</span></div>
                            <div class="brand-text brand-small"><strong>Sueca</strong></div>
                        </router-link>
                        <!-- Toggle Button--><a v-on:click.prevent="toggleSidebar" class="menu-btn active"><span></span><span></span><span></span></a>
                    </div>
                    <!-- Navbar Menu -->
                    <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">

                        <!-- SETTINGS                       -->
                        <li class="nav-item dropdown"> <a id="messages" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link"><i class="fa fa-cogs"></i></a>
                            <ul aria-labelledby="" class="dropdown-menu">
                                <li class="align-items-center align-content-center">
                                    <router-link to="/profile" class="dropdown-item d-flex align-items-center align-content-center"> <div class="align-items-center align-content-center d-flex"> <i class="fa fa-user fa-fw bg-dark"></i></div>
                                    <div class="msg-body">
                                        <h6 class="">Profile</h6></div></router-link></li>
                                <li class="align-items-center align-content-center">
                                    <router-link :to="{ path: 'statistics/' + user.id}" class="dropdown-item d-flex align-items-center align-content-center"> <div class="align-items-center align-content-center d-flex"> <i class="fa fa-user fa-fw bg-dark"></i></div>
                                    <div class="msg-body">
                                        <h6 class="">Statistics</h6></div></router-link></li>

                            </ul>
                        </li>

                        <!-- Logout    -->
                        <li class="nav-item logout_link"><a v-on:click="logout" class="nav-link ">Log Out</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
</template>
<script type="text/javascript">

    export default {
        props: ['user'],
        data: function(){

            return {}
        },
        methods: {
            goToHome(){
                this.$router.push({ path: '/dashboard'})
            },
            toggleSidebar() {
                    $('.menu-btn').toggleClass('active');

                    $('.side-navbar').toggleClass('shrinked');
                    $('.content-inner').toggleClass('active');
                    $(document).trigger('sidebarChanged');

                    if ($(window).outerWidth() > 900) {
                        console.log("test");
                        if ($('.menu-btn').hasClass('active')) {
                            $('.navbar-header .brand-small').hide();
                            $('.navbar-header .brand-big').show();
                        } else {
                            console.log("test2");
                            $('.navbar-header .brand-small').show();
                            $('.navbar-header .brand-big').hide();
                        }
                    }

                    if ($(window).outerWidth() < 1183) {
                        $('.navbar-header .brand-small').show();
                    }

            },
            logout: function () {
                axios.post('/api/logout')
                    .then((response) => {
                        localStorage.removeItem('access_token');
                        window.location.href = '/'
                    })
                    .catch((error) => {
                        window.location.href = '/'
                    });
            },
        },
        computed: {

        },
        components: {

        },


    }


</script>