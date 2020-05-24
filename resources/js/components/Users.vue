<template>
    <div class="container">
		<!--v-if condicion para no motrar por roles-->
        <div class="row" v-if="$gate.isAdminOrAuthor()">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Users Table</h3>
						<div class="card-tools">
						<button class="btn btn-success" @click="newModal">Add new&nbsp;<i class="fas fa-user-plus fa-fw"></i></button>
						</div>
					</div>
					<!-- /.card-header -->
					<div class="card-body table-responsive p-0">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>Email</th>
									<th>Type</th>
									<th>Registerad</th>
									<th>Modify</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="user in users.data" :key="user.id">
									<td>{{ user.id }}</td>
									<td>{{ user.name }}</td>
									<td>{{ user.email }}</td>
									<td><span class="tag tag-success gray">{{ user.type|uptext }}</span></td>
									<td>{{ user.created_at|formatDate }}</td>
									<td>
										<a href="#" @click="editModal( user )">
											<i class="fa fa-edit blue"></i>
										</a>
										&nbsp;
										<a href="#" @click="deleteUser(user.id)">
											<i class="fa fa-trash red"></i>
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- /.card-body -->
					<div class="card-footer">
							<pagination :data="users" @pagination-change-page="getResults"></pagination>
					</div>
				</div>
				<!-- /.card -->
			</div>
		</div>
		<!--NOT FOUND-->
		<div v-if="!$gate.isAdminOrAuthor()">
			<not-found></not-found>
		</div>		
		<!--MODAL-->
		<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 v-show="editMode" class="modal-title" id="addNewLabel">Update User's info</h5>
						<h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add new</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="editMode ? updateUser() : createUser()"> <!--Conditionals for edit o create-->
						<!--Form del user-->
						<div class="modal-body">
							<div class="form-group">
								<input v-model="form.name" type="text" name="name" placeholder='Name'
								class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
								<has-error :form="form" field="name"></has-error>
							</div>
							<div class="form-group">
								<input v-model="form.email" type="text" name="email" placeholder='Email Address'
								class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
								<has-error :form="form" field="email"></has-error>
							</div>
							<div class="form-group">
								<textarea v-model="form.bio" type="text" name="bio" placeholder='Biography'
								class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
								<has-error :form="form" field="bio"></has-error>
							</div>
							<div class="form-group">
								<select name="type" v-model="form.type" id="type" class="form-control"
								:class="{ 'is-invalid': form.errors.has('type') }">
									<option value="">Select User Role</option>
									<option value="admin">Admin</option>
									<option value="user">Standard User</option>
									<option value="author">Author</option>
								</select>
								<has-error :form="form" field="type"></has-error>
							</div>
							<div class="form-group">
								<input v-model="form.password" type="password" name="password" placeholder='Password'
								class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
								<has-error :form="form" field="password"></has-error>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							<button v-show="editMode" type="subtmit" class="btn btn-primary">Update</button>
							<button v-show="!editMode" type="subtmit" class="btn btn-success">Create</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
    export default {
		data(){
			return{
				editMode : false,
				users : {},
				form: new Form({
					id: '',
					name: '',
					email:	'',
					password: '',
					type:'',
					bio:'',
					photo:''
				})
			}
		},
		methods: {
			getResults(page = 1) {
					axios.get('api/user?page=' + page)					.then(response => {
					this.users = response.data;
				});
			},
			updateUser(){
				this.$Progress.start();
				//console.log("Editing");
				//this.form.put the lleva a la funcion update
				this.form.put('api/user/'+this.form.id)
				.then(() => {
					$('#addNew').modal('hide');
					swal.fire(
						'Updated!',
						'Your information has been updated.',
						'success'
					)
					Fire.$emit('AfterCreate');
					this.$Progress.finish();
				})
				.catch(() => {
					this.$Progress.fail(); //change color to red
				});
			},
			editModal(user){
				this.editMode=true;
				this.form.reset(); //Clear errors
				$('#addNew').modal('show');
				this.form.fill(user);
			},
			newModal(){
				this.editMode=false;
				this.form.reset(); //Clear errors
				$('#addNew').modal('show');
			},
			deleteUser(id){
				swal.fire({
					title: 'Are you sure?',
					text: "You won't be able to revert this!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!'
					}).then((result) => {
					//Send Request to server to the server	
						if (result.value){
							this.form.delete('api/user/'+id).then(()=>{
							swal.fire(
								'Deleted!',
								'Your file has been deleted.',
								'success'
							)
							Fire.$emit('AfterCreate');
							}).catch(()=>{
								swal("Failed!!","There was something wrong.","Warning");
							})
						}
					})
					.catch(()=>{
							toast.fire({
								icon: 'error',
								title: 'Oops...',
								text: 'You can\'t do this.'
							});
						}
					)
			},
			loadUsers(){
				if (this.$gate.isAdminOrAuthor()){
					axios.get('api/user').then(({ data })=>(this.users = data));
				}
			},
			createUser(){
				this.$Progress.start();
				//send data to laravel controller
				this.form.post('api/user')
				.then(() => {
					//Register event and emit the event
					Fire.$emit('AfterCreate');
					//close modal
					$('#addNew').modal('hide');
					toast.fire({
						icon: 'success',
						title: 'User created in successfully'
					})
					
				})
				.catch(()=>{
					//Error sectioon
				})
				this.$Progress.finish();
			}
		},
		created() {
			/*Call this funstion on create*/
            this.loadUsers();
			//Lister for the function AfterCreate
			Fire.$on('AfterCreate',()=>{
				this.loadUsers();
			});
			//Custom event listener
			Fire.$on('searching',()=>{
				let query=this.$parent.search;//app.js es padre de users.vue
				axios.get('api/findUser?q='+query)
				.then((data) =>{
					this.users=data.data;
				})
				.catch(() => {});
			});
			//one way to refresh data
			//setInterval(() -> this.loadUsers(),3000);
		},
        mounted() {
            console.log('Component mounted.')
		}
	}
</script>
