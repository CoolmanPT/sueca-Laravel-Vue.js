<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2 align-content-center">
                    <img :src="deck.hidden_face_image_path" alt="img" class="img-fluid ml-auto mr-auto">
                </div>
                <div class="col-lg-3">
                    <p>Name: {{deck.name}}</p>
                    <p class="">Completed: {{deck.complete == 1 ? 'Yes' : 'No'}}</p>
                    <p>Active: {{active}}</p>
                    <p>Number of cards: {{ numberOfCards }}</p>

                </div>
                <div class="col-lg-7">
                    <div class="btn-group float-right">
                        <button @click="deleteDeck" class="btn btn-dark" :disabled="deck.id == 1">Delete Deck</button>
                        <button v-if="numberOfCards > 0" @click="editCards" class="btn btn-dark">Edit Cards</button>
                        <button v-if="deck.complete == 0" @click="fillDeckWithCards" class="btn btn-dark">Complete Deck</button>
                    </div>

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
                complete: 0,
                active: '',
                numberOfCards: '',
            }
        },
        methods: {
            deleteDeck() {
                console.log(this.deck.id)
                axios.delete('api/deletedeck/' + this.deck.id)
                    .then(response => {
                        this.$parent.$parent.getDecks();
                        this.success = true;
                        this.successMessage = 'Deck changed';


                    });
            },
            fillDeckWithCards(){
                const data = {
                    deck: this.deck,
                    complete: this.complete,
                };
                console.log(data);
                axios.post('api/addallcards', data)
                    .then(response => {
                        this.$parent.$parent.getDecks();
                        this.success = true;
                        this.complete = 1;
                        this.successMessage = 'Deck changed';
                        this.getCompleteStatus();
                    });


            },
            editCards(){},

            getActiveStatus: function () {
                if (this.deck.active === 1) {
                    this.active = 'Yes';
                } else {
                    this.active = 'No';
                }
            },
            getCompleteStatus: function () {
                if (this.deck.complete === 1 && this.numberOfCards === 40) {
                    this.complete = 'Yes';
                } else {
                    this.complete = 'No';
                }
            },
            getNumberofCards: function () {
                this.numberOfCards = this.deck.cards.length;
            }

        },
        created: function () {
            this.getNumberofCards();
            this.getActiveStatus();
            this.getCompleteStatus();


        },




    }
</script>