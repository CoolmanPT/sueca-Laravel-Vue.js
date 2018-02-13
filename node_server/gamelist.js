var GameSueca = require('./gamemodel.js');

class GameList {
	constructor() {
        this.contadorID = 0;
        this.games = new Map();
    }

    gameByID(gameID) {
    	let game = this.games.get(gameID);
    	return game;
    }

    createGame(playerID, playerName, socketID) {
		console.log("Gamelist: " + "" +playerID + "" +playerName + "" +socketID)
    	this.contadorID = this.contadorID+1;
    	var game = new GameSueca(this.contadorID, playerID, playerName, socketID);
		this.games.set(game.gameID, game);
		console.log(game);
    	return game;
    }

    joinGame(gameID, playerID, playerName, socketID) {
    	let game = this.gameByID(gameID);
    	if (game===null) {
    		return null;
		}
		if(game.join(playerID, playerName, socketID)){
			return game;
		}
		else{
			return false;
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
    		/* if ((value.player1SocketID == socketID) || (value.player2SocketID == socketID) || value.player3SocketID || value.player4SocketID) {
    			games.push(value);
			} */
			if ((value.players[0].socketID == socketID) || (value.players[1].socketID == socketID) || value.players[2].socketID || value.players[3].socketID) {
    			games.push(value);
    		}
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