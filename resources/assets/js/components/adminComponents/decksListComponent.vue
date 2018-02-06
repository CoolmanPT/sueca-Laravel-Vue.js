<template>
    <div>
        <div class="card-header">
            <h3 class="h4 text-dark">List of Decks</h3>
        </div>
        <table class="table table-hover table-responsive-sm">
            <thead>
            <tr>
                <th class="bg-dark text-light">name</th>
            </tr>
            </thead>

            <tbody v-for="deck in decks" :key="deck.id">

            <tr data-toggle="collapse" :data-target="'#accordion'+deck.id" class="clickable collapse-row collapsed">
                <td>{{ deck.name }}</td>
            </tr>
            <tr>
                <td :colspan="4">
                    <div class="collapse fade" :id="'accordion'+deck.id">
                        <deck-overview-component :deck="deck"></deck-overview-component>
                    </div>
                </td>
            </tr>

            </tbody>
        </table>
    </div>

</template>
<script type="text/javascript">
    // Component code (not registered)
    module.exports = {
        props: ['decks'],
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
