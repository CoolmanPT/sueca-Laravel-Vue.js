<template>
    <nav class="side-navbar">
        <!-- Sidebar Header-->
        <div class="sidebar-header d-flex align-items-center">
            <div class="avatar"><img :src="user.avatar" alt="..." class="img-fluid rounded-circle"></div>
            <div class="title">
                <h1 class="h4">{{ user.nickname }}</h1>
                <p>{{ user.name }}</p>
            </div>
        </div>
        <!-- Sidebar Navidation Menus--><span class="heading">Main</span>
        <ul class="list-unstyled sidebar-list">
            <li class=""><router-link to="/dashboard"><i class="icon-grid fa fa-home fa-fw"></i>Dashboard</router-link></li>
            <li class=""><router-link to="/users"><i class="icon-grid fa fa-users fa-fw"></i>Users</router-link></li>
            <li><router-link to="/decks"><i class="icon-grid fa fa-puzzle-piece fa-fw"></i>Decks</router-link></li>
            <li><router-link to="/cards"><i class="icon-grid fa fa-play fa-fw"></i>Cards</router-link></li>
            <li><router-link to="/statistics"><i class="icon-grid fa fa-area-chart fa-fw"></i>Statistics</router-link></li>

        </ul>

    </nav>
</template>
<script type="text/javascript">

    export default {
        props: ['user'],
        data: function(){

            return {


            }
        },
        methods: {

        },
        computed: {

        },
        components: {

        },
        created: function(){
        }


    }
</script>