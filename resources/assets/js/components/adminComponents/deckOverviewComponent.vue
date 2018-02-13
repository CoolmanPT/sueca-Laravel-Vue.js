<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2 align-content-center">
                    <img :src="deck.hidden_face_image_path" alt="img" class="img-fluid ml-auto mr-auto">
                    <div class="form-group" v-if="deck.id != 1">
                        <input type="file" v-on:change="onFileChange" class="form-control-file">
                    <button class="btn btn-dark" @click.prevent="upload()">Upload</button>
                    </div>
                </div>
                <div class="col-lg-3">
                    <p>Name: {{deck.name}}</p>
                    <p class="">Completed: {{deck.complete == 1 ? 'Yes' : 'No'}}</p>
                    <p>Active: {{active}}</p>
                    <p>Number of cards: {{ deck.cards.length }}</p>

                </div>
                <div class="col-lg-7">
                    <div class="btn-group float-right">
                        <button @click="deleteDeck" class="btn btn-dark" v-if="deck.id != 1">Delete Deck</button>
                        <router-link :to="{ path: 'cards/' + deck.id}" v-if="deck.complete == 1 && deck.id != 1" class="btn btn-dark" >Edit Cards</router-link>
                        <button v-if="deck.complete == 0" @click="fillDeckWithCards" class="btn btn-dark">Complete Deck</button>
                    </div>

                </div>

            </div>
        </div>
    </div>
</template>
<script>
module.exports = {
  props: ["deck"],
  data: function() {
    return {
      complete: 0,
      active: "",
      numberOfCards: ""
    };
  },
  methods: {
    editCards() {
      this.$router.push("Cards");
    },
    deleteDeck() {
      console.log(this.deck.id);
      axios.delete("api/deletedeck/" + this.deck.id).then(response => {
        this.$parent.$parent.getDecks();
        this.success = true;
        this.successMessage = "Deck changed";
      });
    },
    fillDeckWithCards() {
      const data = {
        deck: this.deck,
        complete: this.complete
      };
      console.log(data);
      axios.post("api/addallcards", data).then(response => {
        this.$parent.$parent.getDecks();
        this.success = true;
        this.complete = 1;
        this.successMessage = "Deck changed";
        this.getCompleteStatus();
      });
    },
    editCards() {},

    getActiveStatus: function() {
      if (this.deck.active === 1) {
        this.active = "Yes";
      } else {
        this.active = "No";
      }
    },
    getCompleteStatus: function() {
      if (this.deck.complete === 1 && this.numberOfCards === 40) {
        this.complete = "Yes";
      } else {
        this.complete = "No";
      }
    },
    getNumberofCards: function() {
      this.numberOfCards = this.deck.cards.length;
    },
    onFileChange(e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.createImage(files[0]);
    },
    createImage(file) {
      let reader = new FileReader();
      let vm = this;
      reader.onload = e => {
        vm.image = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    upload() {
      const data = {
        image: this.image,
      };
      axios
        .put("/api/editdeckimg/" + this.deck.id, data)
        .then(response => {
        })
        .catch(error => {
          this.serverErrorCode = error.response.data;
          console.log(this.serverErrorCode);
        });
    }
  },
  created: function() {
    this.getNumberofCards();
    this.getActiveStatus();
    this.getCompleteStatus();
  }
};
</script>