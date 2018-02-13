<template>
    <div class="page">
        <navbar-component-game :user="user"></navbar-component-game>

        <div class="page-content d-flex align-items-stretch">
            


            <div class="content-inner-game">
                <!-- Page Header-->
                <header class="page-header">
                    <div class="container-fluid">
                        <h2 class="no-margin-bottom">User Statistics</h2>
                    </div>
                </header>
                <!-- Dashboard Counts Section-->
                <section class="tables">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
            <table class="table table-hover table-responsive-sm">
                <thead>
                <tr>
                    <th class="bg-dark text-light">Nickname</th>
                    <th class="bg-dark text-light">Games Played</th>
                    <th class="bg-dark text-light">Total Points</th>
                    <th class="bg-dark text-light">Wins</th>
                    <th class="bg-dark text-light">Ties</th>
                    <th class="bg-dark text-light">Losses</th>
                </tr>
                </thead>
            <tbody>

            <tr class="clickable collapse-row collapsed">
                <td>{{ user.nickname }}</td>
                <td>{{ user.total_points }}</td>
                <td>{{ user.total_games_played }}</td>
                <td>{{ user.wins }}</td>
                <td>{{ user.ties }}</td>
                <td>{{ user.losses }}</td>
            </tr>
            </tbody>
        </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Dashboard Header Section    -->



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
        data: function(){

            return {
                user:'',
                

            }
        },
        methods: {
            getUserStatistic: function () {
                const data = {
                    user2:this.$route.params.id,
                }
                console.log(this.$route.params.id);
                axios.post('/api/statistics/user', data)
                    .then((response) => {
                        this.user = response.data;

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
            this.getUserStatistic();
        }


    }
</script>