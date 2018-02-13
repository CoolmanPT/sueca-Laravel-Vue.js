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
              </div>
            </div>
            <div class="col-sm-9">
              <div class="form-group">
            <label for="inputName">Name</label>
            <input
                type="text" class="form-control" v-model="user.name"
                name="name" id="inputName" 
                placeholder="Fullname"/>
	        </div>
	        <div class="form-group">
	          <label for="inputEmail">Email</label>
	          <input
	            type="email" class="form-control" v-model="user.email"
	            name="email" id="inputEmail"
	            placeholder="Email address"/>
	        </div>
		      <div class="form-group">
	          <label for="inputNickname">Nickname</label>
	          <input
	            type="text" class="form-control" v-model="user.nickname"
	            name="inputNickname" id="inputNickname"
	            placeholder="Nickname"/>
	        </div>
		      <div class="form-group">
	          <label for="inputNickname">Password</label>
	          <input
	            type="password" class="form-control" v-model="user.password"
	            name="password" id="inputPassword"
	            placeholder="password"/>
	        </div>
	        <div class="form-group">
	          <a class="btn btn-success" v-on:click.prevent="saveUser()">Save</a>
	          <a class="btn btn-danger" v-on:click.prevent="cancelEdit()">Cancel</a>
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
      email: '',
      name: '',
      nickname:'',
      email:'',
      password:'',
      attemptSubmit: false,
      serverErrorCode: 0,
      serverErrorMessage: "",
      success: false,
    };
  },
  computed: {

  },
  methods: {
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
    saveUser: function(){
              const data = {
                
              }
	            axios.put('api/users/'+this.user.id, data)
	                .then(response=>{
	                	// Copy object properties from response.data.data to this.user
	                	// without creating a new reference
	                
	                });
	        },
	        cancelEdit: function(){
	        	
	        }
  },
  
};
</script>