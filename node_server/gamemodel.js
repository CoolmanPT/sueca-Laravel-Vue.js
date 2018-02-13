var shuffle = require('shuffle-array');
var axios = require('axios');

var apiBaseURL = "http://recurso.rip/api/";

class GameSueca {
    constructor(player_id, player1Name, socket_id) {
        this.gameID = undefined;
        this.gameEnded = false;
        this.gameStarted = false;
        this.winnerTeam = undefined;
        this.teamsTied=false;
        this.playerCount = undefined;
        this.playerTurn = undefined;
        this.trumpSuit = undefined;
        this.timer = undefined;
        this.players = [];
        this.team1Points= undefined;
        this.team2Points= undefined;
        this.cardsOnTable = undefined;
        this.rounds = 0;
        this.cardToAssist = undefined;
        
        this.deckId = 1;
        this.creatorId = player_id;

        let player = {
            playerID: player_id,
            socketID: socket_id,
            name: player1Name,
            score: 0,
            team: 1,
            hand: [],
            cardTable: undefined,
            renuncia: false
        }
        this.playerCount = 1;
        this.players.push(player);
        this.team1Points = 0;
        this.team2Points = 0;
        this.cardsOnTable = 0;

        this.team1MatchPoints = 0;
        this.team2MatchPoints = 0;

        this.teamRenunciaDesconfia = 0;
        this.teamRenunciou = 0;
    }

    //METHODS

    join(player_id, playerName, socket_id) {

        if (this.playerCount < 4) {
            let teamNumber = undefined;
            if (this.playerCount < 2) {
                teamNumber = 1;
            } else {
                teamNumber = 2;
            }
            let player = {
                playerID: player_id,
                socketID: socket_id,
                name: playerName,
                score: 0,
                team: teamNumber,
                hand: [],
                cardTable: undefined,
                renuncia: false
            }

            const data = {
                'user_id': player.playerID,
                'game_id': this.gameID,
                'team_number': player.team                  
            }
            axios.post(apiBaseURL + 'game/join', data)
            .then(response => {

            })
            .catch(error => {
             console.log(error.response.data); 
         });
            this.playerCount++; 
            this.players.push(player);
            return true;
        }else{
            return false;
        }

    }

    startGame() {
        const data = {
            'game_id': this.gameID
        }
        axios.put(apiBaseURL + 'game/start', data)
        .then(response => {
            console.log(response.data);
        })
        .catch(error => {
         console.log(error.response.data); 
     });


        let deck = this.createDeck();
        for (let i = 0; i < 40; i++) {
            shuffle(deck);
        }
        deck[0].imageToShow = deck[0].image;
        this.trumpSuit = deck[0].suit;
        let firstPlayerToReceiveCards = Math.floor(Math.random() * 4);

        this.nextPlayer(firstPlayerToReceiveCards);

        let drawCardsTo = firstPlayerToReceiveCards;

        for(let i = 0; i<4; i++){
         this.drawCards(drawCardsTo, deck);
         if(drawCardsTo == 3){
            drawCardsTo = 0;
        }
        else{
            drawCardsTo++;
        }
    }
    this.gameStarted = true;
}

drawCards(drawCardsTo, deck) {   
        //console.log(drawCardsTo);         
        for (let i = 0; i < 10; i++) {
            this.players[drawCardsTo].hand.push(deck[i]);                
        }
        deck.splice(0, 10);

        //console.log("Deck: " + JSON.stringify(deck));

    }

    play(player_id, cardIndex) {
        /*  console.log("PlayerID " + player_id);
         console.log("PlayerTURN " + this.playerTurn);
         console.log("CARD INDEX " + cardIndex); */
         if (player_id == this.playerTurn) {

           var playerIndex = this.players.findIndex(obj => obj.playerID == player_id);
           if (this.cardsOnTable == 0) {
               this.cardToAssist = this.players[playerIndex].hand[cardIndex].suit;
               //console.log("CARTA TO ASSIST " + this.cardToAssist);  

           } else {
               this.hasRenuncia(playerIndex, cardIndex);
           }
           this.players[playerIndex].cardTable = this.players[playerIndex].hand[cardIndex];
           this.players[playerIndex].hand.splice(cardIndex, 1);
           this.nextPlayer(playerIndex);
           this.cardsOnTable++;

            // console.log("Player " + playerIndex+1 + " renuncia: " + this.players[playerIndex].renuncia);
            return true;
        } else {
           return false;
       }
   }

   finishRound(){
    this.cardsOnTable = 0;

    var playerVencedorNumero = 0;
    var pontosDaRonda = 0;
    for (let i = 1; i < this.players.length; i++) {
        if(this.comparar(this.players[i].cardTable, this.players[playerVencedorNumero].cardTable) === 0){
            playerVencedorNumero = i;
            }//else continua igual
        }
        this.playerTurn = this.players[playerVencedorNumero].playerID;

        
        for (let i = 0; i < this.players.length; i++) {
            //console.log("PONTOS DA CARTA " + this.players[i].cardTable.points);
            //console.log("NOME DA CARTA " + this.players[i].cardTable.image);
            pontosDaRonda += this.players[i].cardTable.points;
        }

        //console.log("PONTOS DA RONDA " + pontosDaRonda);

        if (playerVencedorNumero === 0 || playerVencedorNumero === 1) {
            this.team1Points +=  pontosDaRonda;
        }else{
            this.team2Points +=  pontosDaRonda;
        }

        this.rounds++;
        console.log(this.rounds);
        if(this.rounds>=10){
            console.log("TEM 10 RONDAS!!!!!!!!!!");
            this.finishGame();
        }

        for (let i = 0; i < 4; i++) {
            this.players[i].cardTable = undefined;                        
        }
    }

    comparar(o1, o2){
        if(o1.suit === this.trumpSuit && o2.suit === this.trumpSuit){
            if(o1.points > o2.points){
                return 0;
            }else{
                return 1;
            }
        }else{
            if (o1.suit === this.trumpSuit && o2.suit !== this.trumpSuit) {
                return 0;
            }else{
                if (o1.suit !== this.trumpSuit && o2.suit === this.trumpSuit) {
                    return 1;
                }else{
                    if(o1.points > o2.points){
                        return 0;
                    }else{
                        return 1;
                    }
                }
            }
        }
    }


    hasRenuncia(playerIndex, cardIndex) {
        /* console.log("Suit " + this.players[playerIndex].hand[cardIndex].suit); */
        if (this.players[playerIndex].hand[cardIndex].suit != this.cardToAssist) {

            /* console.log("Player hand length: " + this.players[playerIndex].hand.length); */

            for (let i = 0; i < this.players[playerIndex].hand.length; i++) {
                if (this.players[playerIndex].hand[i].suit == this.cardToAssist) {
                    this.players[playerIndex].renuncia = true;
                    console.log("HAS RENUNCIA " + this.players[playerIndex].name);
                }
            }
        }
    }

    desconfiar(player_id){
        for(let i = 0; i < this.players.length; i++){ 
            if(this.players[i].playerID == player_id){
                if(this.players[i].team === 1){
                    if(this.players[2].renuncia === true || this.players[3].renuncia === true){
                        this.teamRenunciaDesconfia = 1;
                        this.teamRenunciou = 2;
                        this.team1MatchPoints = 4;
                        this.team2MatchPoints = -4;
                        this.winnerTeam = 1;
                        console.log("TEAM 1 GANHA RENUNCIA CONFIRMADA");
                    }else{
                         //equi+a 1 perde renuncia não confirmada
                         this.teamRenunciaDesconfia = 1;
                         this.teamRenunciou = 0;
                         this.team1MatchPoints = -4;
                         this.team2MatchPoints = 4;
                         this.winnerTeam = 2;
                         console.log("TEAM 1 PERDE RENUNCIA NÃO CONFIRMADA");
                     }
                 }else{
                    if(this.players[0].renuncia === true || this.players[1].renuncia === true){
                        //equi+a 2 ganha renuncia confirmada
                         this.teamRenunciaDesconfia = 2;
                         this.teamRenunciou = 1;
                         this.team1MatchPoints = -4;
                         this.team2MatchPoints = 4;
                         this.winnerTeam = 2;
                        console.log("TEAM 2 GANHA RENUNCIA CONFIRMADA");
                    }else{
                         this.teamRenunciaDesconfia = 2;
                         this.teamRenunciou = 0;
                         this.team1MatchPoints = 4;
                         this.team2MatchPoints = -4;
                         this.winnerTeam = 1;
                         console.log("TEAM 2 PERDE RENUNCIA NÃO CONFIRMADA");
                     }
                 }
             }
         }
         const renunciaInfo = {
                'game_id': this.gameID,
                'teamRenunciaDesconfia': this.teamRenunciaDesconfia,
                'teamRenunciou': this.teamRenunciou,
                'team1MatchPoints': this.team1MatchPoints, 
                'team2MatchPoints': this.team2MatchPoints,
                'team_winner': this.winnerTeam
            }

            axios.put(apiBaseURL+ 'game/renuncia', renunciaInfo)
                .then(response => {

                })
                .catch(error => {
                 console.log(error.response.data); 
             });
     }

     nextPlayer(actualPlayerIndex) {
        /*if (actualPlayerIndex == 3) {
           
        } else {
            this.playerTurn = this.players[actualPlayerIndex + 1].playerID;
        }*/

        switch (actualPlayerIndex) {
            case 0:
            this.playerTurn = this.players[2].playerID;
            break;
            case 1:
            this.playerTurn = this.players[3].playerID;
            break;
            case 2:
            this.playerTurn = this.players[1].playerID;    
            break;
            case 3:
            this.playerTurn = this.players[0].playerID;
            break;
            default:
            break;
        }
    }



    finishGame(){
        console.log("Dentro do metodo");
        while(true){
            console.log("Dentro do while");
            if (this.team1Points === this.team2Points && this.team1Points === 60) {
                this.team1MatchPoints = 1;
                this.team2MatchPoints = 1;
            }else{
                if (this.team1Points > this.team2Points){
                    if (this.team1Points >= 61 && this.team1Points <= 90) {
                        this.winnerTeam = 1;
                        this.team1MatchPoints = 1;
                        this.team2MatchPoints = 0;
                    }
                    if (this.team1Points >= 91 && this.team1Points <= 119) {
                        this.winnerTeam = 1;
                        this.team1MatchPoints = 2;
                        this.team2MatchPoints = 0;
                    }
                    if (this.team1Points ===120) {
                        this.winnerTeam = 1;
                        this.team1MatchPoints = 4;
                        this.team2MatchPoints = 0;
                    }
                }else{
                    if(this.team2Points > this.team1Points){
                        if (this.team2Points >= 61 && this.team2Points <= 90) {
                            this.winnerTeam = 2;
                            this.team1MatchPoints = 0;
                            this.team2MatchPoints = 1;
                        }
                        if (this.team2Points >= 91 && this.team2Points <= 119) {
                            this.winnerTeam = 2;
                            this.team1MatchPoints = 0;
                            this.team2MatchPoints = 2;
                        }
                        if (this.team2Points ===120) {
                            this.winnerTeam = 2;
                            this.team1MatchPoints = 0;
                            this.team2MatchPoints = 4;
                        }
                    }
                }
            }
            console.log("Antes do axios");
            const points = {
                'game_id': this.gameID,
                'team1Points': this.team1Points,
                'team2Points': this.team2Points,
                'team1MatchPoints': this.team1MatchPoints, 
                'team2MatchPoints': this.team2MatchPoints,
                'team_winner': this.winnerTeam
            }
            console.log(points);

            axios.put(apiBaseURL+ 'game/results', points)
                .then(response => {
                    console.log("axios sucesso");
                })
                .catch(error => {
                 console.log(error.response.data); 
             });


            console.log('estou dentro do loop of DOOM');
            break; // em principio n é necessário
        }
        //conn.gameTerminate(this.gameID);
        this.gameEnded=true;
    }

    createDeck(){
        let deck = [
            {points:0, image: "c2", imageToShow: "semFace", suit: "hearts", value: "2"}, // 2 of hearts
            {points:0, image: "c3", imageToShow: "semFace", suit: "hearts", value: "3"}, // 3 of hearts
            {points:0, image: "c4", imageToShow: "semFace", suit: "hearts", value: "4"}, // 4 of hearts
            {points:0, image: "c5", imageToShow: "semFace", suit: "hearts", value: "5"}, // 5 of hearts
            {points:0, image: "c6", imageToShow: "semFace", suit: "hearts", value: "6"}, // 6 of hearts
            {points:10, image: "c7", imageToShow: "semFace", suit: "hearts", value: "10"}, // 7 of hearts
            {points:3, image: "c11", imageToShow: "semFace", suit: "hearts", value: "3"}, // Jack of hearts
            {points:2, image: "c12", imageToShow: "semFace", suit: "hearts", value: "2"}, //  Queen hearts
            {points:4, image: "c13", imageToShow: "semFace", suit: "hearts", value: "4"}, // King of hearts
            {points:11, image: "c1", imageToShow: "semFace", suit: "hearts", value: "11"}, // Ace of hearts


            {points:0, image: "e2", imageToShow: "semFace", suit: "spades", value: "2"}, // 2 of spades
            {points:0, image: "e3", imageToShow: "semFace", suit: "spades", value: "3"}, // 3 of spades
            {points:0, image: "e4", imageToShow: "semFace", suit: "spades", value: "4"}, // 4 of spades
            {points:0, image: "e5", imageToShow: "semFace", suit: "spades", value: "5"}, // 5 of spades
            {points:0, image: "e6", imageToShow: "semFace", suit: "spades", value: "6"}, // 6 of spades
            {points:10, image: "e7", imageToShow: "semFace", suit: "spades", value: "10"}, // 7 of spades
            {points:3, image: "e11", imageToShow: "semFace", suit: "spades", value: "3"}, // Jack of spades
            {points:2, image: "e12", imageToShow: "semFace", suit: "spades", value: "2"}, //  Queen spades
            {points:4, image: "e13", imageToShow: "semFace", suit: "spades", value: "4"}, // King of spades
            {points:11, image: "e1", imageToShow: "semFace", suit: "spades", value: "11"}, // Ace of spades


            {points:0, image: "o2", imageToShow: "semFace", suit: "diamonds", value: "2"}, // 2 of diamonds
            {points:0, image: "o3", imageToShow: "semFace", suit: "diamonds", value: "3"}, // 3 of diamonds
            {points:0, image: "o4", imageToShow: "semFace", suit: "diamonds", value: "4"}, // 4 of diamonds
            {points:0, image: "o5", imageToShow: "semFace", suit: "diamonds", value: "5"}, // 5 of diamonds
            {points:0, image: "o6", imageToShow: "semFace", suit: "diamonds", value: "6"}, // 6 of diamonds
            {points:10, image: "o7", imageToShow: "semFace", suit: "diamonds", value: "10"}, // 7 of diamonds
            {points:3, image: "o11", imageToShow: "semFace", suit: "diamonds", value: "3"}, // Jack of diamonds
            {points:2, image: "o12", imageToShow: "semFace", suit: "diamonds", value: "2"}, //  Queen diamonds
            {points:4, image: "o13", imageToShow: "semFace", suit: "diamonds", value: "4"}, // King of diamonds
            {points:11, image: "o1", imageToShow: "semFace", suit: "diamonds", value: "11"}, // Ace of diamonds


            {points:0, image: "p2", imageToShow: "semFace", suit: "clubs", value: "2"}, // 2 of clubs
            {points:0, image: "p3", imageToShow: "semFace", suit: "clubs", value: "3"}, // 3 of clubs
            {points:0, image: "p4", imageToShow: "semFace", suit: "clubs", value: "4"}, // 4 of clubs
            {points:0, image: "p5", imageToShow: "semFace", suit: "clubs", value: "5"}, // 5 of clubs
            {points:0, image: "p6", imageToShow: "semFace", suit: "clubs", value: "6"}, // 6 of clubs
            {points:10, image: "p7", imageToShow: "semFace", suit: "clubs", value: "10"}, // 7 of clubs
            {points:3, image: "p11", imageToShow: "semFace", suit: "clubs", value: "3"}, // Jack of clubs
            {points:2, image: "p12", imageToShow: "semFace", suit: "clubs", value: "2"}, //  Queen clubs
            {points:4, image: "p13", imageToShow: "semFace", suit: "clubs", value: "4"}, // King of clubs
            {points:11, image: "p1", imageToShow: "semFace", suit: "clubs", value: "11"}, // Ace of clubs
            ]
            return deck;
        }
    }

    module.exports = GameSueca;