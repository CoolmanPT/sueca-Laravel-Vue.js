<template>
    <div class="page">
        <navbar-component></navbar-component>

        <div class="page-content d-flex align-items-stretch">
            <sidebar-component :user="user"></sidebar-component>
            <div class="content-inner">
                <!-- Page Header-->
                <header class="page-header">
                    <div class="container-fluid">
                        <h2 class="no-margin-bottom">Edit Cards of deck: <strong>{{deck.name}}</strong></h2>
                    </div>
                </header>
                <!-- Platform Email Section-->
                <section class="tables">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                              
                                <div class="card">
                                    <div class="card-header">
                                      <h3>Cards</h3>
                                    </div>
                                    <div class="card-body">
                                      <ul class="list-inline">
                                      <li class="list-inline-item m-2" v-for="card in cards" v-bind:key="card.id">
                                          <img :src="card.path" :alt="card.path" v-cloak>
                                          <input type="file" v-on:change="onFileChange" class="form-control-file">
                                          <button class="btn btn-dark" @click.prevent="upload(card)">Upload</button>
                                      </li>
                                      </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Page Footer-->
                <footer class="main-footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <p>Recurso Sueca | DAD</p>
                            </div>
                            <div class="col-sm-6 text-right">
                                <p>Alberto Lalanda | Bruno Pereira | Fábio Lage</p>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</template>
<script>
module.exports = {
  data: function() {
    return {
      user: "",
      deck: "",
      cards: [],
      image:'',
      suites: ["Heart", "Diamond", "Club", "Spade"]
    };
  },
  
  methods: {
    getUser: function() {
      axios
        .get("/api/user")
        .then(response => {
          this.user = response.data;
        })
        .catch(error => {});
    },
    getCurrentDeck: function() {
      axios
        .get("api/getcurrentdeck/" + this.$route.params.deckid)
        .then(response => {
          this.deck = response.data;
          this.cards = this.deck.cards;
        });
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
    upload(card) {
      const data = {
        image: this.image,
        deck: this.deck,
        cards: this.deck.cards,
      };
      axios
        .put("/api/editcard/" + card.id, data)
        .then(response => {
        })
        .catch(error => {
          this.serverErrorCode = error.response.data;
          console.log(this.serverErrorCode);
        });
    }
  },

  created: function() {
    this.getCurrentDeck();
    this.getUser();
  }

};
</script>