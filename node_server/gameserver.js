var app = require('http').createServer();



var io = require('socket.io')(app);

var SuecaGame = require('./gamemodel.js');
var GameList = require('./gamelist.js');


app.listen(8080, function () {
	console.log('listening on *:8080');
});

// ------------------------
// Estrutura dados - server
// ------------------------

let games = new GameList();


io.on('connection', function (socket) {
	console.log('client has connected');

	socket.on('create_game', function (data) {
		let game = games.createGame(data.playerID, data.playerName, socket.id, data.avatar);
		socket.join(game.gameID);
		// Notifications to the client
		socket.emit('my_active_games_changed');
		io.emit('lobby_changed');
	});

	socket.on('start_game', function (data) {
		let game = games.startGame(data.gameID);
		io.to(game.gameID).emit('game_started');
		io.to(game.gameID).emit('my_active_games_changed');
	});

	socket.on('join_game', function (data) {
		let game = games.joinGame(data.gameID, data.playerID, data.playerName, socket.id);
		if (game){
			socket.join(game.gameID);
			io.to(game.gameID).emit('my_active_games_changed');
		io.emit('lobby_changed');
		}
		
	});

	socket.on('remove_game', function (data) {
		let game = games.removeGame(data.gameID, socket.id);
		socket.emit('my_active_games_changed');
	});

	socket.on('play', function (data) {
		console.log("PlayerID on gameserver.js " + data.playerID);
		let game = games.gameByID(data.gameID);				
		if (game.play(data.playerID, data.index)) {			
			io.to(game.gameID).emit('game_changed', game);
			
			
			
			if(game.cardsOnTable == 4){
				var timer = setTimeout(function(){
					game.finishRound();
					io.to(game.gameID).emit('game_changed', game);
				},2000, game);

			}



						
		} else {
			socket.emit('invalid_play', {
				'type': 'Wrong_Turn',
				'game': game
			});
			return;
		}
	});

	socket.on('get_game', function (data) {
		let game = games.gameByID(data.gameID);
		socket.emit('game_changed', game);
	});

	socket.on('get_my_activegames', function (data) {
		var my_games = games.getConnectedGamesOf(socket.id);
		socket.emit('my_active_games', my_games);
	});

	socket.on('get_my_lobby_games', function () {
		var my_games = games.getLobbyGamesOf(socket.id);
		socket.emit('my_lobby_games', my_games);
	});

	socket.on("sendMessage", function (data){
		console.log("Message received on server");
		io.to(data.gameID).emit("message_received", data);
	});
	

});