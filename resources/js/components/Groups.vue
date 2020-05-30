<template>
    <div class="container">
		<!--v-if condicion para no motrar por roles-->
        <div class="row" v-if="$gate.isAdminOrAuthor()">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Groups Table</h3>
						<div class="card-tools">
						<button class="btn btn-success" @click="newModal">Add new&nbsp;<i class="fas fa-theater-masks fa-fw"></i></button>
						</div>
					</div>
					<!-- /.card-header -->
					<div class="card-body table-responsive p-0">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>Title</th>
									<th>Photo</th>
									<th>URL</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="group in groups.data" :key="group.id">
									<td>{{ group.id }}</td>
									<td>{{ group.title }}</td>
									<td>{{ group.photo }}</td>
									<td>{{ group.url }}</td>
									<td>
										<a href="#" @click="editModal( group )">
											<i class="fa fa-edit blue"></i>
										</a>
										&nbsp;
										<a href="#" @click="deleteGroup(group.id)">
											<i class="fa fa-trash red"></i>
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- /.card-body -->
					<div class="card-footer">
							<pagination :data="groups" @pagination-change-page="getResults"></pagination>
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
						<h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Group's's info</h5>
						<h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add new</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="editMode ? updateGroup() : createGroup()"> <!--Conditionals for edit o create-->
						<!--Form del group-->
						<div class="modal-body">
							<div class="form-group">
								<input v-model="form.title" type="text" name="title" placeholder='title'
								class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
								<has-error :form="form" field="title"></has-error>
							</div>
						</div>
                        <div class="modal-body">
							<div class="form-group">
								<textarea v-model="form.description" type="text" name="description" placeholder='description'
								class="form-control" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
								<has-error :form="form" field="description"></has-error>
							</div>
						</div>
                        <div class="modal-body">
							<div class="form-group">
								<div class="input-group">
									<div class="custom-file">
										<input type="file" @change="updateGroupPhoto" 
											class="custom-file-input" id="exampleInputFile" name="photo">
										<label class="custom-file-label" for="exampleInputFile">Choose file</label>
									</div>
								</div>
							</div>
						</div>
                        <div class="modal-body">
							<div class="form-group">
								<input v-model="form.url" type="text" name="url" placeholder='url'
								class="form-control" :class="{ 'is-invalid': form.errors.has('url') }">
								<has-error :form="form" field="url"></has-error>
							</div>
						</div>
						<!--FIN Form del group-->
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
				groups : {},
				form: new Form({
					id: '',
					title: '',
					description: '',
					photo: '',
					url: '',
				})
			}
		},
		methods: {
			getResults(page = 1) {
					axios.get('api/group?page=' + page).then(response => {
					this.groups = response.data;
				});
			},
			updateGroup(){
				this.$Progress.start();
				//console.log("Editing");
				//this.form.put the lleva a la funcion update
				this.form.put('api/group/'+this.form.id)
				.then(() => {
					$('#addNew').modal('hide');
					swal.fire(
						'Updated!',
						'Your Group has been updated.',
						'success'
					)
					Fire.$emit('AfterCreate');
					this.$Progress.finish();
				})
				.catch(() => {
					this.$Progress.fail(); //change color to red
				});
			},
			editModal(group){
				this.editMode=true;
				this.form.reset(); //Clear errors
				$('#addNew').modal('show');
				this.form.fill(group);
			},
			newModal(){
				this.editMode=false;
				this.form.reset(); //Clear errors
				$('#addNew').modal('show');
			},
			deleteGroup(id){
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
							this.form.delete('api/group/'+id).then(()=>{
							swal.fire(
								'Deleted!',
								'Your group has been deleted.',
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
			loadGroups(){
				if (this.$gate.isAdminOrAuthor()){
                    axios.get('api/group').then(({ data })=>(this.groups = data));
				}
			},
			createGroup(){
				this.$Progress.start();
				//send data to laravel controller
				this.form.post('api/group')
				.then(() => {
					//Register event and emit the event
					Fire.$emit('AfterCreate');
					//close modal
					$('#addNew').modal('hide');
					toast.fire({
						icon: 'success',
						title: 'Group created in successfully'
					})
					
				})
				.catch(()=>{
					//Error sectioon
				})
				this.$Progress.finish();
			},
			updateGroupPhoto(e){
				//console.log('Event uploading');
				let file = e.target.files[0];
				let reader = new FileReader();
				
				if(file['size'] < 2111775){
					reader.readAsDataURL(file);
					reader.onload = (file) => {
						//console.log(reader.result);
						this.form.photo = reader.result;
					};
					reader.onerror = (error) => {
						console.log('Error: ', error);
					};
				}else{
					swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'You can\'t upload a file larger than 2MB.'
					});
				}
			}
		},
		created() {
			/*Call this funstion on create*/
            this.loadGroups();
			//Lister for the function AfterCreate
			Fire.$on('AfterCreate',()=>{
				this.loadGroups();
			});
            //Custom event listener
            
			Fire.$on('searching',()=>{
				let query=this.$parent.search;
				axios.get('api/findGroup?q='+query)
				.then((data) =>{
					this.groups=data.data;
				})
				.catch(() => {});
            });
		},
        mounted() {
			/** Si hay algún input file recordar iniciar esto**/
			bsCustomFileInput.init();
            console.log('Component mounted.')
		}
	}
</script>
