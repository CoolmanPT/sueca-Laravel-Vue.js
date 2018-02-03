<template>
    <nav class="side-navbar">
        <!-- Sidebar Header-->
        <div class="sidebar-header d-flex align-items-center">
            <div class="avatar"><img :src="user.avatar" alt="..." class="img-fluid rounded-circle"></div>
            <div class="title">
                <h1 class="h4">{{ user.nickName }}</h1>
                <p>{{ user.name }}</p>
            </div>
        </div>
        <!-- Sidebar Navidation Menus--><span class="heading">Main</span>
        <ul class="list-unstyled sidebar-list">
            <li class=""><router-link to="/users"><i class="icon-grid fa fa-users fa-fw"></i>Users</router-link></li>
            <li><router-link to="/decks"><i class="icon-grid fa fa-puzzle-piece fa-fw"></i>Decks</router-link></li>
            <li><router-link to="/cards"><i class="icon-grid fa fa-play fa-fw"></i>Cards</router-link></li>
        </ul>

    </nav>
</template>
<script type="text/javascript">

    export default {
        data: function(){
            return {
                user:{
                    nickName: '',
                    name:'',
                    avatar: '',
                },

            }
        },
        methods: {
            getUser: function () {
                axios.get('/api/user')
                    .then((response) => {
                        this.user.nickName = response.data.nickname;
                        this.user.avatar = response.data.avatar;
                        this.user.name = response.data.name;
                        console.log(response.data.name);
                    })
                    .catch((error) => {

                    });
            },
        },
        computed: {

        },
        components: {

        },
        created: function(){
            this.getUser();
        }


    }
</script>