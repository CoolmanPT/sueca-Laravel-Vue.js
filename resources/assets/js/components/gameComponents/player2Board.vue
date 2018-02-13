<template>
    <div>
        <div>
            <h2 class="text-center bg-primary text-white">Game {{ game.gameID }}</h2>
            <br>
        </div>
        <div class="board container-fluid">
            <!-- TEAMMATE HAND -->
            <div class="row">
                <div class="col-md-12" style="text-align:center">
                    <p>{{game.players[0].name}}</p>
                    <!-- <img :src="avatarURL(game.players[0].avatar)" class="img-circle avatarGame"> -->
                    
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="text-align:center">
                    <!-- <img v-for="i in 10" v-bind:src="cardImageURL('semFace')" class="myHand"> -->
                    <div v-if="game.gameStarted">
                        <img v-for="card of game.players[0].hand" v-bind:src="cardImageURL(card.imageToShow)" class="teamMateHand">
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <span>{{game.players[3].name}}</span>
                    <!-- <img :src="avatarURL(game.players[0].avatar)" class="img-circle avatarGame"> -->
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div v-if="game.gameStarted">
                                    <img v-for="card of game.players[3].hand" v-bind:src="cardImageURL(card.imageToShow)" class="oponentsHand">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- CENTER ZONE -->
                <div class="col-md-4">
                    <!-- TEAMMATE CARD -->
                    <div class="row">
                        <div class="col-md-12" style="text-align:center">
                            <img v-if="!game.players[0].cardTable" v-bind:src="cardImageURL('semFace')" class="playedCard">
                            <img v-if="game.players[0].cardTable" v-bind:src="cardImageURL(game.players[0].cardTable.image)" class="playedCard">
                        </div>
                    </div>
                    <div class="row">
                        <!-- LEFT PLAYER CARD -->
                        <div class="col-md-6" style="text-align:center">
                            <img v-if="!game.players[3].cardTable" v-bind:src="cardImageURL('semFace')" class="playedCard">
                            <img v-if="game.players[3].cardTable" v-bind:src="cardImageURL(game.players[3].cardTable.image)" class="playedCard">
                        </div>
                        <!-- RIGHT PLAYER CARD -->
                        <div class="col-md-6" style="text-align:center">
                            <img v-if="!game.players[2].cardTable" v-bind:src="cardImageURL('semFace')" class="playedCard">
                            <img v-if="game.players[2].cardTable" v-bind:src="cardImageURL(game.players[2].cardTable.image)" class="playedCard">
                        </div>
                    </div>
                    <!-- MY CARD -->
                    <div class="row">
                        <div class="col-md-12" style="text-align:center">
                            <img v-if="!game.players[1].cardTable" v-bind:src="cardImageURL('semFace')" class="playedCard">
                            <img v-if="game.players[1].cardTable" v-bind:src="cardImageURL(game.players[1].cardTable.image)" class="playedCard">
                        </div>
                    </div>
                </div>
                <!-- RIGHT PLAYER HAND -->
                <div class="col-md-4">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <span>{{game.players[2].name}}</span>
                    <!-- <img :src="avatarURL(game.players[0].avatar)" class="img-circle avatarGame"> -->
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div v-if="game.gameStarted">
                                    <img v-for="card of game.players[2].hand" v-bind:src="cardImageURL(card.imageToShow)" class="oponentsHand">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- OUR HAND -->
            <div class="row">
                <div class="col-md-12" style="text-align:center">
                    <div v-if="game.gameStarted">
                        <img v-for="(card, index) of game.players[1].hand" v-bind:src="cardImageURL(card.image)" class="myHand" v-on:click.prevent="play(index)">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="text-align:center">
                    <span>{{game.players[1].name}}</span>
                    <!-- <img :src="avatarURL(game.players[0].avatar)" class="img-circle avatarGame"> -->
                    
                </div>
            </div>
        </div>
        <hr>
    </div>
</template>

<script type="text/javascript">
    export default {
        props: ['game'],
        data: function () {
            return {
                input: "",
                messages: []
            }
        },

        methods: {
            play(index) {
                this.$emit('play', index);
            },
            cardImageURL(cardNumber) {
                var imgSrc = String(cardNumber);
                return 'img/cards/' + imgSrc + '.png';
            },

        }
    }
</script>