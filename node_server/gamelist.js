var GameSueca = require('./gamemodel.js');
var axios = require('axios');

var apiBaseURL = "http://recurso.rip/api/";

class GameList {
	constructor() {
        this.games = new Map();
    }

    gameByID(gameID) {
    	let game = this.games.get(gameID);
    	return game;
    }

    createGame(playerID, playerName, socketID, callback) {
		//console.log("Gamelist: " + "" +playerID + "" +playerName + "" +socketID)
        let game = new GameSueca(playerID, playerName, socketID);
        const data = {
            'created_by': game.creatorId,
            'deck_used': game.deckId
        }
        axios.post(apiBaseURL + 'game/create', data)
        .then(response => {

            game.gameID = response.data.game['id'];

            this.games.set(game.gameID, game);

            callback(game);

        })
        .catch(error => {
         console.log(error.response.data); 
     });
    }

    joinGame(gameID, playerID, playerName, socketID) {
    	let game = this.gameByID(gameID);
    	if (game === null) {
    		return null;
        } else {
            if(game.join(playerID, playerName, socketID)){
                return game;
            }
            else{
                return false;
            }
        }
    }


    startGame(gameID){
      let game = this.gameByID(gameID);
      

      game.startGame();

      return game;
  }

  removeGame(gameID, socketID) {
   let game = this.gameByID(gameID);
   if (game===null) {
      return null;
  }
  if (game.player1SocketID == socketID) {
      game.player1SocketID = "";
  } else if (game.player2SocketID == socketID) {
      game.player2SocketID = "";
  } 
  if ((game.player1SocketID === "") && (game.player2SocketID === "")) {
      this.games.delete(gameID);
  }
  return game;
}

getConnectedGamesOf(socketID) {
   let games = [];
   for (var [key, value] of this.games) {
     value.players.forEach(function (player) {
        if (player.socketID == socketID) {
         games.push(value);
     }
 });
 }
 return games;
}

getLobbyGamesOf(socketID) {
   let games = [];
   for (var [key, value] of this.games) {
      if ((!value.gameStarted) && (!value.gameEnded))  {
         if ((value.player1SocketID != socketID) && (value.player2SocketID != socketID)&& (value.player3SocketID != socketID)&& (value.player4SocketID != socketID)) {
            games.push(value);
        }
    }
}
return games;
}
}

module.exports = GameList;