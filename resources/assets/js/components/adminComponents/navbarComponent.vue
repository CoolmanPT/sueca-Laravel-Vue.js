<template>
    <header class="header">
        <nav class="navbar">
            <!-- Search Box-->
            <div class="container-fluid">
                <div class="navbar-holder d-flex align-items-center justify-content-between">
                    <!-- Navbar Header-->
                    <div class="navbar-header">
                        <!-- Navbar Brand --><a href="index.html" class="navbar-brand">
                        <div class="brand-text brand-big"><span>Recurso </span><strong>Sueca</strong></div>
                        <div class="brand-text brand-small"><strong>DAD</strong></div></a>
                        <!-- Toggle Button--><a id="toggle-btn" href="#" class="menu-btn active"><span></span><span></span><span></span></a>
                    </div>
                    <!-- Navbar Menu -->
                    <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                        <!-- Search-->
                        <li class="nav-item d-flex align-items-center"><a id="search" href="#"><i class="icon-search"></i></a></li>
                        <!-- Notifications-->
                        <li class="nav-item dropdown"> <a id="notifications" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link"><i class="fa fa-bell-o"></i><span class="badge bg-red">12</span></a>
                            <ul aria-labelledby="notifications" class="dropdown-menu">
                                <li><a rel="nofollow" href="#" class="dropdown-item">
                                    <div class="notification">
                                        <div class="notification-content"><i class="fa fa-envelope bg-green"></i>You have 6 new messages </div>
                                        <div class="notification-time"><small>4 minutes ago</small></div>
                                    </div></a></li>
                                <li><a rel="nofollow" href="#" class="dropdown-item">
                                    <div class="notification">
                                        <div class="notification-content"><i class="fa fa-twitter bg-blue"></i>You have 2 followers</div>
                                        <div class="notification-time"><small>4 minutes ago</small></div>
                                    </div></a></li>
                                <li><a rel="nofollow" href="#" class="dropdown-item">
                                    <div class="notification">
                                        <div class="notification-content"><i class="fa fa-upload bg-orange"></i>Server Rebooted</div>
                                        <div class="notification-time"><small>4 minutes ago</small></div>
                                    </div></a></li>
                                <li><a rel="nofollow" href="#" class="dropdown-item">
                                    <div class="notification">
                                        <div class="notification-content"><i class="fa fa-twitter bg-blue"></i>You have 2 followers</div>
                                        <div class="notification-time"><small>10 minutes ago</small></div>
                                    </div></a></li>
                                <li><a rel="nofollow" href="#" class="dropdown-item all-notifications text-center"> <strong>view all notifications                                            </strong></a></li>
                            </ul>
                        </li>
                        <!-- Messages                        -->
                        <li class="nav-item dropdown"> <a id="messages" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link"><i class="fa fa-cogs"></i></a>
                            <ul aria-labelledby="" class="dropdown-menu">
                                <li class="align-items-center align-content-center"><router-link to="/profile" class="dropdown-item d-flex align-items-center align-content-center"> <div class="msg-profile align-items-center align-content-center d-flex"> <i class="fa fa-user fa-fw"></i></div>
                                    <div class="msg-body">
                                        <h6 class="">Profile</h6></div></router-link></li>
                                <li class="align-items-center align-content-center"><router-link to="/platform" class="dropdown-item d-flex align-items-center align-content-center"> <div class="msg-profile align-items-center align-content-center d-flex"> <i class="fa fa-lock fa-fw"></i></div>
                                    <div class="msg-body">
                                        <h6 class="">Platform</h6></div></router-link></li>


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
        data: function(){
            return {}
        },
        methods: {
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