<template>
  <div>
      <div class="card-header">
          <h3 class="h4 text-dark">Change Player Settings</h3>
      </div>
      <div class="card-body">
          <div class="row">
              <div class="col-sm-3">
                  <div class="form-group text-center">
                    <img :src="user.avatar" alt="" class="img-fluid w-25">
                   <input type="file" v-on:change="onFileChange" class="form-control-file ">
                   <button class="btn btn-dark" @click.prevent="upload">Upload</button>
                   <span  class="alert alert-danger" v-if="serverErrorCode == 1">{{serverErrorMessage}}</span>
              </div>
            </div>
            <div class="col-sm-9">
              <div class="form-group">
            
            <input
                type="text" class="form-control" v-model="user.name"
                placeholder="Fullname"/>
	        </div>
	        <div class="form-group">
	          
	          <input
	            type="email" class="form-control" v-model="user.email"
	            placeholder="Email address"/>
	        </div>
		      <div class="form-group">
	          
	          <input
	            type="text" class="form-control" v-model="user.nickname"
	            
	            placeholder="Nickname"/>
	        </div>
		      <div class="form-group">
	          
	          <input
	            type="password" class="form-control" v-model="user.password"
	            placeholder="password"/>
	        </div>
	        <div class="form-group">
	          <a class="btn btn-success" v-on:click.prevent="saveUser()">Save</a>
	          <a class="btn btn-danger" v-on:click.prevent="cancelEdit()">Cancel</a>
             <span  class="alert alert-danger" v-if="serverErrorCode == 2">{{serverErrorMessage}}</span>
	        </div>
            </div>
          </div>
          
      </div>
  </div>
</template>
<script type="text/javascript">
export default {
  props: ["user"],
  data: function() {
    return {
      email: "",
      name: "",
      nickname: "",
      email: "",
      password: "",
      attemptSubmit: false,
      serverErrorCode: 0,
      serverErrorMessage: "",
      success: false
    };
  },
  computed: {},
  methods: {
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
        user: this.user
      };
      console.log(data);
      axios
        .post("/api/user/upload/avatar", data)
        .then(response => {
          this.$parent.getUser();
          this.serverErrorCode = 1;
          this.serverErrorMessage = "User changed";
        })
        .catch(error => {
          this.serverErrorCode = error.response.data.msg;
        });
    },
    saveUser: function() {
      const data = {};
      axios.put("api/users/" + this.user.id, data).then(response => {
        // Copy object properties from response.data.data to this.user
        // without creating a new reference
        this.serverErrorCode = 2;
        this.serverErrorMessage = "User changed";
      });
    },
    cancelEdit: function() {
      this.$parent.getUser();
    }
  }
};
</script>