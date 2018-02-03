<template>
    <div>
        <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">
                <div class="row bg-white has-shadow">
                    <!-- Item -->
                    <div class="col-xl-3 col-sm-6">
                        <div class="item d-flex align-items-center">
                            <div class="icon bg-violet"><i class="icon-user"></i></div>
                            <div class="title"><span>Registered<br>Players</span>
                            </div>
                            <div class="number" v-cloak ><strong>{{ numPlayers }}</strong></div>
                        </div>
                    </div>
                    <!-- Item -->
                    <div class="col-xl-3 col-sm-6">
                        <div class="item d-flex align-items-center">
                            <div class="icon bg-red"><i class="icon-padnote"></i></div>
                            <div class="title"><span>Blocked<br>Users</span>

                            </div>
                            <div class="number" v-cloak><strong>{{ blockedPlayers }}</strong></div>
                        </div>
                    </div>
                    <!-- Item -->
                    <div class="col-xl-3 col-sm-6">
                        <div class="item d-flex align-items-center">
                            <div class="icon bg-green"><i class="icon-bill"></i></div>
                            <div class="title"><span>New<br>Users</span>
                            </div>
                            <div class="number" v-cloak><strong>{{ newPlayers }}</strong></div>
                        </div>
                    </div>
                    <!-- Item -->
                    <div class="col-xl-3 col-sm-6">
                        <div class="item d-flex align-items-center">
                            <div class="icon bg-orange"><i class="icon-check"></i></div>
                            <div class="title"><span>Games<br>Today</span>

                            </div>
                            <div class="number" v-cloak><strong>{{ gamesPerDay }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script type="text/javascript">

    export default {
        data: function(){
            return {
                users: '',
                numPlayers: '',
                blockedPlayers: '',
                newPlayers: '',
                gamesPerDay: '',
            }
        },
        methods: {
            getUsers: function () {
                axios.get('api/users')
                    .then(response => {
                        this.users = response.data.data;
                        this.numPlayers = this.users.length;
                    });
            },
            getBlockedUsers: function () {
                axios.get('api/blockedusers')
                    .then(response => {
                        this.blockedPlayers = response.data.data.length;
                    });
            },
            getNewUsers: function () {
                axios.get('api/newusers')
                    .then(response => {
                        this.newPlayers = response.data.data.length;
                    });
            },
        },
        computed: {

        },
        components: {

        },
        mounted: function(){
            this.getUsers();
            this.getNewUsers();
            this.getBlockedUsers();
        }


    }
</script>