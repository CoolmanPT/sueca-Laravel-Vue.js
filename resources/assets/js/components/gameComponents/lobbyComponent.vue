<template>
    <div>

            <h2>PLayer: {{user.nickname}}</h2>

            <h3 class="text-center">Lobby</h3>
            <p>
                <button class="btn btn-xs btn-success" v-on:click.prevent="createGame">Create a New Game</button>
            </p>
            <hr>
            <h4>Pending games (
                <a @click.prevent="loadLobby">Refresh</a>)</h4>
            <lobby :games="lobbyGames" :user="user" @join-click="join" @start-game="start"></lobby>
            <template v-for="game in activeGames">
                <game :game="game" @start-game="start" @play="play" :user="user" @send-message="sendMessage"></game>
            </template>
        
    </div>
</template>

<script type="text/javascript">
    import Lobby from './lobbyList.vue';
    import Game from './game.vue';

    export default {
        props: ['user'],
        data: function () {
            return {
                title: 'Sueca',
                lobbyGames: [],
                activeGames: [],
                socketId: "",

            }
        },
        sockets: {
            connect() {
                //    console.log('socket connected');
                this.socketId = this.$socket.id;
            },
            discconnect() {
                //    console.log('socket disconnected');
                this.socketId = "";
            },
            lobby_changed() {
                this.loadLobby();
            },
            my_active_games_changed() {
                this.loadActiveGames();
            },
            my_active_games(games) {
                //console.table(games);
                this.activeGames = games;
            },
            my_lobby_games(games) {
                this.lobbyGames = games;
            },
            invalid_play(errorObject) {
                if (errorObject.type == 'Invalid_Game') {
                    alert("Error: Game does not exist on the server");
                } else if (errorObject.type == 'Invalid_Player') {
                    alert("Error: Player not valid for this game");
                } else if (errorObject.type == 'Invalid_Play') {
                    alert("Error: Move is not valid or it's not your turn");
                } else if (errorObject.type == 'Wrong_Turn') {
                    alert("Error: It's not your turn");
                } else {
                    alert("Error: " + errorObject.type);
                }

            },
            game_changed(game) {
                for (var lobbyGame of this.lobbyGames) {
                    if (game.gameID == lobbyGame.gameID) {
                        Object.assign(lobbyGame, game);
                        break;
                    }
                }
                for (var activeGame of this.activeGames) {
                    if (game.gameID == activeGame.gameID) {

                        Object.assign(activeGame, game);
                        console.table(game.players[0].cardTable);
                        if (activeGame.gameEnded) {
                            alert("Game " + activeGame.gameID + " has Ended \n The winner is: " +
                                activeGame.players[activeGame.winner - 1].playerName);
                        }
                        break;
                    }
                }
            },

        },
        methods: {
            loadLobby() {
                this.$socket.emit('get_my_lobby_games');
            },
            loadActiveGames() {
                this.$socket.emit('get_my_activegames');
            },
            createGame() {
                console.log("MY ID: " + this.user.id);
                if (this.user.id == "") {
                    alert('Current Player is Empty - Cannot Create a Game');
                    return;
                } else {
                    this.$socket.emit('create_game', {
                        playerName: this.user.nickname,
                        playerID: this.user.id,
                    });
                }
            },
            join(game) {
                console.log("MY ID: " + this.user.id);

                if (game.player1 == this.user.id) {
                    alert('Cannot join a game because your name is the same as Player 1');
                    return;
                }
                this.$socket.emit('join_game', {
                    gameID: game.gameID,
                    playerID: this.user.id,
                    playerName: this.user.nickname,
                });
            },
            play(data) {
              
                    this.$socket.emit('play', {
                        gameID: data.gameID,
                        playerID: this.user.id,
                        index: data.index,
                    });
                
                //console.log("CARDINDEX: "+ index);

            },
            close(game) {
                this.$socket.emit('remove_game', {
                    gameID: game.gameID
                });
            },
            start(game) {

                this.$socket.emit('start_game', {
                    gameID: game.gameID,
                });
            },
            sendMessage(data) {
                this.$socket.emit("sendMessage", data);
            }
        },
        components: {
            'lobby': Lobby,
            'game': Game,
        },
        mounted() {
            this.currentPlayer = this.user.id;
            this.loadLobby();
        }

    }
</script>

<style>
</style>