<template>
    <div class="container">
		<!--v-if condicion para no motrar por roles-->
        <div class="row" v-if="$gate.isAdminOrAuthor()">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Types Table</h3>
						<div class="card-tools">
						<button class="btn btn-success" @click="newModal">Add new&nbsp;<i class="fas fa-user-friends fa-fw"></i></button>
						</div>
					</div>
					<!-- /.card-header -->
					<div class="card-body table-responsive p-0">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>Register date</th>
									<th>Modify</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="type in types.data" :key="type.id">
									<td>{{ type.id }}</td>
									<td>{{ type.name }}</td>
									<td>{{ type.created_at|formatDate }}</td>
									<td>
										<a href="#" @click="editModal( type )">
											<i class="fa fa-edit blue"></i>
										</a>
										&nbsp;
										<a href="#" @click="deleteUser(type.id)">
											<i class="fa fa-trash red"></i>
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- /.card-body -->
					<div class="card-footer">
							<pagination :data="types" @pagination-change-page="getResults"></pagination>
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
						<h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Type's's info</h5>
						<h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add new</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="editMode ? updateType() : createType()"> <!--Conditionals for edit o create-->
						<!--Form del user-->
						<div class="modal-body">
							<div class="form-group">
								<input v-model="form.name" type="text" name="name" placeholder='Name'
								class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
								<has-error :form="form" field="name"></has-error>
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
				types : {},
				form: new Form({
					id: '',
					name: ''
				})
			}
		},
		methods: {
			getResults(page = 1) {
					axios.get('api/type?page=' + page).then(response => {
					this.types = response.data;
				});
			},
			updateType(){
				this.$Progress.start();
				//console.log("Editing");
				//this.form.put the lleva a la funcion update
				this.form.put('api/type/'+this.form.id)
				.then(() => {
					$('#addNew').modal('hide');
					swal.fire(
						'Updated!',
						'Your type has been updated.',
						'success'
					)
					Fire.$emit('AfterCreate');
					this.$Progress.finish();
				})
				.catch(() => {
					this.$Progress.fail(); //change color to red
				});
			},
			editModal(type){
				this.editMode=true;
				this.form.reset(); //Clear errors
				$('#addNew').modal('show');
				this.form.fill(type);
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
								'Your type has been deleted.',
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
			loadTypes(){
				if (this.$gate.isAdminOrAuthor()){
                    axios.get('api/type').then(({ data })=>(this.types = data));
				}
			},
			createType(){
				this.$Progress.start();
				//send data to laravel controller
				this.form.post('api/type')
				.then(() => {
					//Register event and emit the event
					Fire.$emit('AfterCreate');
					//close modal
					$('#addNew').modal('hide');
					toast.fire({
						icon: 'success',
						title: 'Type created in successfully'
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
            this.loadTypes();
			//Lister for the function AfterCreate
			Fire.$on('AfterCreate',()=>{
				this.loadTypes();
			});
            //Custom event listener
            
			Fire.$on('searching',()=>{
				let query=this.$parent.search;
				axios.get('api/findType?q='+query)
				.then((data) =>{
					this.types=data.data;
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
