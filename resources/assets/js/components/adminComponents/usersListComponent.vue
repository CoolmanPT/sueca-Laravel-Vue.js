<template>
    <div>
        <div class="card-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-auto">
                        <h3 class="h4 text-dark">List of Players</h3>
                        <span class="text-muted text-small font-italic pl-2">click on a row to view the Player in detail</span>
                    </div>

                </div>
            </div>
        </div>
        
        <div class="card-body">
            <table class="table table-hover table-responsive-sm">
                <thead>
                    <tr>
                        <th class="bg-dark text-light">Name</th>
                        <th class="bg-dark text-light">Email</th>
                        <th class="bg-dark text-light">Nickname</th>
                    </tr>
                </thead>

                <tbody v-for="user in users" :key="user.id">

                    <tr data-toggle="collapse" :data-target="'#accordion'+user.id" class="clickable collapse-row collapsed">
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.nickname }}</td>
                    </tr>
                    <tr>
                        <td :colspan="4">
                            <div class="collapse fade" :id="'accordion'+user.id">
                                <player-overview-component :user="user"></player-overview-component>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

</template>
<script type="text/javascript">
    // Component code (not registered)
    module.exports = {
        props: ['users'],
        data: function () {
            return {
                name: '',
                contentVisible: false,
            }
        },
        components: {},
        methods: {},


    }
</script>
