<template>
    <div>
       <section class="dashboard-header">
           <div class="container-fluid">
               <div class="row">
                   <div class="statistics col-lg-3 col-12">
                       <div class="statistic d-flex align-items-center bg-white has-shadow">
                           <div class="icon bg-violet"><i class="fa fa-users fa-fw"></i></div>
                           <div class="text"><span>Registered Players</span> </div>
                           <strong class="text-right ml-auto">{{ numPlayers }}</strong>
                       </div>


                       <div class="statistic d-flex align-items-center bg-white has-shadow">
                           <div class="icon bg-red"><i class="fa fa-lock fa-fw"></i></div>
                           <div class="text"><span>Blocked Users</span></div>
                           <strong class="text-right ml-auto">{{ blockedPlayers }}</strong>
                       </div>

                       <div class="statistic d-flex align-items-center bg-white has-shadow">
                           <div class="icon bg-green"><i class="fa fa-dollar fa-fw"></i></div>
                           <div class="text"><span>NewUsers</span></div>
                           <strong  class="text-right ml-auto">{{ newPlayers }}</strong>
                       </div>

                       <div class="statistic d-flex align-items-center bg-white has-shadow">
                           <div class="icon bg-orange"><i class="fa fa-play fa-fw"></i></div>
                           <div class="text"><span>Games played today</span></div>
                           <strong class="text-right ml-auto">{{ gamesPerDay }}</strong>
                       </div>

                       <div class="statistic d-flex align-items-center bg-white has-shadow">
                           <div class="icon bg-orange"><i class="fa fa-play fa-fw"></i></div>
                           <div class="text"><span>Active players</span></div>
                           <strong class="text-right ml-auto">{{ activePlayers }}</strong>
                       </div>
                   </div>
               </div>
           </div>
       </section>

    </div>
</template>
<script type="text/javascript">

    export default {
        data: function () {
            return {
                users: [],
                numPlayers: 0,
                blockedPlayers: 0,
                newPlayers: 0,
                gamesPerDay: 0,
                activePlayers: 0,
            }
        },
        methods: {
            getActive: function () {
                axios.get('api/active')
                    .then(response => {
                        this.activePlayers = response.data;
                    });

            },
            getUsers: function () {
                axios.get('api/users')
                    .then(response => {
                        this.users = response.data.data;
                        this.numPlayers = this.users.length;
                        this.getBlockedUsers();
                        this.getNewUsers();
                    });

            },
            getBlockedUsers: function () {
                let blocked = 0;
                this.users.forEach(function (user, key) {
                    if (user.blocked === 1) {
                        blocked++;
                    }
                });

                this.blockedPlayers = blocked;

            },
            getNewUsers: function () {
                let newp = 0;
                this.users.forEach(function (user, key) {
                    if (user.activated === 0 && user.blocked === 0) {
                        newp++;
                    }
                });

                this.newPlayers = newp;
            },
        },
        computed: {},
        components: {},
        mounted: function () {
            this.getUsers();
            this.getActive();
        }


    }
</script>