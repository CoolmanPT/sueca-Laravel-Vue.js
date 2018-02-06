<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2 align-content-center">
                    <img :src="deck.hidden_face_image_path" alt="img" class="img-fluid w-25 ml-auto mr-auto">
                </div>
                <div class="col-lg-3">
                    <p>Name: {{deck.name}}</p>
                    <p class="">Completed: {{complete}}</p>
                    <p>Active: {{active}}</p>

                </div>

            </div>
        </div>
    </div>
</template>
<script>
    // Component code (not registered)
    module.exports = {
        props: ['deck'],
        data: function () {
            return {
                complete: '',
                active: '',
            }
        },
        computed: {},
        components: {}
        ,
        methods: {
            getActiveStatus: function () {
                if (this.deck.active === 1) {
                    this.active = 'Yes';
                } else {
                    this.active = 'No';
                }
            },
            getCompleteStatus: function () {
                if (this.deck.complete === 1) {
                    this.complete = 'Yes';
                } else {
                    this.complete = 'No';
                }
            },
        },
        created: function () {
            this.getActiveStatus();
            this.getCompleteStatus();
            console.log(this.deck);
        }


    }
</script>