<template>
    <div>
        <div class="alert" :class="alertType">
            <span v-cloak><strong>{{ messages }}</strong> &nbsp;&nbsp;&nbsp;&nbsp;</span>
        </div>
        <div>
            <h2 class="text-center bg-primary text-white">Game {{ game.gameID }}</h2>
            <br>

             <div v-if="game.players[2].playerID != game.playerTurn" v-for="(player, index) in game.players">
                <h3 v-if="player.playerID === game.playerTurn" class="text-center bg-info text-white">{{player.name}} Turn</h3>
            </div>
            <div v-if="game.players[2].playerID == game.playerTurn">
                <h3 class="text-center bg-success text-white font-weight-bold">Your Turn</h3>
            </div>
            <h3 class="text-center bg-danger text-white font-weight-bold">PONTOS DA TUA EQUIPA - {{ game.team2Points }}</h3>

        </div>
        <div class="board container-fluid">
            <!-- TEAMMATE HAND -->
            <div class="row">
                <div class="col-md-12" style="text-align:center">
                    <span>{{game.players[3].name}}</span>
                    <!-- <img :src="avatarURL(game.players[0].avatar)" class="img-circle avatarGame"> -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="text-align:center">
                    <!-- <img v-for="i in 10" v-bind:src="cardImageURL('semFace')" class="myHand"> -->
                    <div v-if="game.gameStarted">
                        <img v-for="card of game.players[3].hand" v-bind:src="cardImageURL(card.imageToShow)" class="teamMateHand">
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <span>{{game.players[1].name}}</span>
                    <!-- <img :src="avatarURL(game.players[0].avatar)" class="img-circle avatarGame"> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div v-if="game.gameStarted">
                                    <img v-for="card of game.players[1].hand" v-bind:src="cardImageURL(card.imageToShow)" class="oponentsHand">
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
                            <div v-if="!game.players[3].cardTable" class="noCard"></div>
                            <img v-if="game.players[3].cardTable" v-bind:src="cardImageURL(game.players[3].cardTable.image)" class="playedCard">
                        </div>
                    </div>
                    <div class="row">
                        <!-- LEFT PLAYER CARD -->
                        <div class="col-md-6" style="text-align:center">
                            <div v-if="!game.players[1].cardTable" class="noCard"></div>
                            <img v-if="game.players[1].cardTable" v-bind:src="cardImageURL(game.players[1].cardTable.image)" class="playedCard">
                        </div>
                        <!-- RIGHT PLAYER CARD -->
                        <div class="col-md-6" style="text-align:center">
                           <div v-if="!game.players[0].cardTable" class="noCard"></div>
                            <img v-if="game.players[0].cardTable" v-bind:src="cardImageURL(game.players[0].cardTable.image)" class="playedCard">
                        </div>
                    </div>
                    <!-- MY CARD -->
                    <div class="row">
                        <div class="col-md-12" style="text-align:center">
                            <div v-if="!game.players[2].cardTable" class="noCard"></div>
                            <img v-if="game.players[2].cardTable" v-bind:src="cardImageURL(game.players[2].cardTable.image)" class="playedCard">
                        </div>
                    </div>
                </div>
                <!-- RIGHT PLAYER HAND -->
                <div class="col-md-4">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <span>{{game.players[0].name}}</span>
                    <!-- <img :src="avatarURL(game.players[0].avatar)" class="img-circle avatarGame"> -->
                               
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div v-if="game.gameStarted">
                                    <img v-for="card of game.players[0].hand" v-bind:src="cardImageURL(card.imageToShow)" class="oponentsHand">
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
                        <img v-for="(card, index) of game.players[2].hand" v-bind:src="cardImageURL(card.image)" class="myHand" v-on:click.prevent="play(index)">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="text-align:center">
                    <span>{{game.players[2].name}}</span>
                    <!-- <img :src="avatarURL(game.players[0].avatar)" class="img-circle avatarGame"> -->
                    
                </div>
            </div>
            <div class="row">
                <div class="alert-info" v-show="game.gameStarted && !game.gameEnded">
                    <button type="button" :class="{'disabled': isDisabled}" class="btn btn-primary" v-on:click="desconfiar">Desconfiar</button>
                </div>
            </div>
        </div>
        <hr>
    </div>
</template>

<script type="text/javascript">
export default {
  props: ["game",'deckName'],
  data: function() {
    return {
      input: "",
      messages: "",
      alertType: "alert-info",
      deckName:'',
    };
  },
  computed: {
    isDisabled: function() {
      return this.game.playerTurn != this.game.players[2].playerID;
    },
    message: function() {
      if (!this.game.gameStarted) {
        messages = "Game not started yet. Waiting for players... ";
      } else if (this.game.gameEnded) {
        if (this.game.winnerTeam === 1) {
          this.alertType = "alert-success";
          messages = "Your TEAM LOST!!";
        } else {
          if (this.game.winnerTeam === 2) {
            this.alertType = "alert-danger";
            messages = "You TEAM won!!";
          } else {
            if (this.game.teamsTied) {
              this.alertType = "alert-warning";
              messages = "You TIED!!";
            }
          }
        }
      }
      return "";
    }
  },
  methods: {
    play(index) {
      this.$emit("play", index);
    },
    desconfiar() {
      this.$emit("desconfiar");
    },
         getDeckName() {
      axios
        .get("/api/deckname/" + this.game.deck)
        .then(response => {
          return (this.deckName = response.data.name);
        })
        .catch(error => {
          this.serverErrorCode = error.response.data.msg;
        });
    },
    cardImageURL(cardNumber) {
      var imgSrc = String(cardNumber);
      return "img/decks/" + this.deckName + "/" + imgSrc + ".png";
    }
  },
};
</script>