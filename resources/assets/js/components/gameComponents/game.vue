<template>
    <div>


        <player1-board :deckName="deckName" :game="game" v-if="game.gameStarted && game.players[0] && game.players[0].playerID == user.id"
            @play="play" @desconfiar="desconfiar"></player1-board>
        <player2-board :deckName="deckName" :game="game" v-if="game.gameStarted && game.players[1] && game.players[1].playerID == user.id"
            @play="play" @desconfiar="desconfiar"></player2-board>
        <player3-board :deckName="deckName" :game="game" v-if="game.gameStarted && game.players[2] && game.players[2].playerID == user.id"
            @play="play" @desconfiar="desconfiar"></player3-board>
        <player4-board :deckName="deckName" :game="game" v-if="game.gameStarted && game.players[3] && game.players[3].playerID == user.id"
            @play="play" @desconfiar="desconfiar"></player4-board>

    </div>
</template>

<script type="text/javascript">
import Player1Board from "./player1Board.vue";
import Player2Board from "./player2Board.vue";
import Player3Board from "./player3Board.vue";
import Player4Board from "./player4Board.vue";

export default {
  props: ["game", "user"],
  data: function() {
    return {
      input: "",
      messages: [],
      deckName: ""
    };
  },
  sockets: {
    message_received(data) {
      if (this.game.gameID == data.gameID) {
        let playerAndMessage = {
          playerName: data.playerName,
          message: data.message
        };
        this.messages.push(playerAndMessage);
      }
    }
  },
  computed: {
    ownPlayerNumber() {
      if (this.game.players[0].playerID == this.user.id) {
        return 1;
      } else if (this.game.players[1].playerID == this.user.id) {
        return 2;
      } else if (this.game.players[2].playerID == this.user.id) {
        return 3;
      } else if (this.game.players[3].playerID == this.user.id) {
        return 4;
      }

      return 0;
    }
  },
  methods: {
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

    play(card_index) {
      console.log("Player Turn: " + this.game.playerTurn);

      if (this.game.playerTurn != this.user.id) {
        alert("It's not your turn to play!");
      } else {
        this.$emit("play", {
          gameID: this.game.gameID,
          index: card_index
        });
      }
    },

    desconfiar() {
      console.log(this.game.gameID + " " + this.user.id);
      if (this.game.playerTurn == this.user.id) {
        this.$emit("desconfiar", {
          gameID: this.game.gameID,
          playerID: this.user.id
        });
      }
    },

    sendMessage() {
      let data = {
        gameID: this.game.gameID,
        playerName: this.ownPlayerName,
        message: this.input
      };
      this.$emit("send-message", data);
      this.input = "";
    }
  },
  created(){
      this.getDeckName();
  },
  components: {
    player1Board: Player1Board,
    player2Board: Player2Board,
    player3Board: Player3Board,
    player4Board: Player4Board
  }
};
</script>

<style scoped>
.gameseparator {
  border-style: solid;
  border-width: 2px 0 0 0;
  border-color: black;
}
</style>