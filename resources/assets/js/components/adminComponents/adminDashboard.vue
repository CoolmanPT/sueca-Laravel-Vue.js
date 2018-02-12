<template>
    <div class="page">
        <navbar-component></navbar-component>

        <div class="page-content d-flex align-items-stretch">
            <sidebar-component  :user="user"></sidebar-component>


            <div class="content-inner">
                <!-- Page Header-->
                <header class="page-header">
                    <div class="container-fluid">
                        <h2 class="no-margin-bottom">Dashboard</h2>
                    </div>
                </header>
                <!-- Dashboard Counts Section-->
                <counter-info-component></counter-info-component>
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
            getUser: function () {
                axios.get('/api/user')
                    .then((response) => {
                        this.user = response.data;
                        console.log(this.user);

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