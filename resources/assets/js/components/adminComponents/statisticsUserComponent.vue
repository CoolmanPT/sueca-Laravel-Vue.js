<template>
    <div>
        <div class="card-header">
            <h3 class="h4 text-dark">Statistics of Players</h3>
        </div>
        <div class="card-body">
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

            <tbody v-for="user in users" :key="user.id">

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

</template>
<script type="text/javascript">
    // Component code (not registered)
    module.exports = {
        props: ['users'],
        data: function () {
            return {
            }
        },
        components: {},
        methods: {},


    }

    
</script>
