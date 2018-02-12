<template>
    <div>
        <div class="card-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-auto">
                        <h3 class="h4 text-dark">List of Decks</h3>
                        <span class="text-muted text-small font-italic pl-2">click on a row to view the Deck in detail</span>
                    </div>
                    <div class="col-auto ml-auto mt-auto mb-auto">
                        <button @click="createNewDeckModal" class="btn btn-dark">Create New Deck</button>
                    </div>
                </div>
            </div>
        </div>
      <div class="card-body">
        <table class="table table-hover table-responsive-sm">
            <thead>
            <tr>
                <th class="bg-dark text-light">name</th>
                <th class="bg-dark text-light">Cards</th>
            </tr>
            </thead>

            <tbody v-for="deck in decks" :key="deck.id">

            <tr data-toggle="collapse" :data-target="'#accordion'+deck.id" class="clickable collapse-row collapsed">
                <td>{{ deck.name }}</td>
                <td>{{ deck.cards.length }}</td>
            </tr>
            <tr>
                <td :colspan="4">
                    <div class="collapse fade" :id="'accordion'+deck.id">
                        <deck-overview-component :deck="deck"></deck-overview-component>
                    </div>
                </td>
            </tr>

            </tbody>
        </table>
      </div>
        <div class="modal fade" id="newDeckModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Create New Deck</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input  type="text" name="name" id="name" v-model="name" class="form-control" placeholder="Name of deck">
                        </div>
                        <div class="form-group">
                            <input type="file" v-on:change="onFileChange" class="form-control " placeholder="Hidden face img">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" @click.prevent="upload">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



</template>
<script type="text/javascript">
    module.exports = {
        props: ['decks'],
        data: function () {
            return {
                active: '',
                complete: '',
                name:'',
                image:'',
                deck:'',

            }
        },
        components: {},
        methods: {
            createNewDeckModal() {
                $('#newDeckModal').modal('show');
            },
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            upload(){
                const data = {
                    image: this.image,
                    name: this.name,
                }
                console.log(data);
                 axios.post('/api/newDeck',data).then(response => {
                     this.$parent.getDecks();
                    this.serverErrorCode = response.data.error;
                     $('#newDeckModal').modal('hide');
                 })
                     .catch((error) => {
                         this.serverErrorCode = error.response.data.msg;
                        console.log(this.serverErrorCode);
                     });
            }
        },
        created: function () {

        }


    }
</script>
