<template>
    <div class="container">
		<!--v-if condicion para no motrar por roles-->
        <div class="row" v-if="$gate.isAdminOrAuthor()">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Games Table</h3>
						<div class="card-tools">
						<button class="btn btn-success" @click="newModal">Add new&nbsp;<i class="fas fa-gamepad  fa-fw"></i></button>
						</div>
					</div>
					<!-- INFO TO SHOW FOR ENTITY-->
					<div class="card-body table-responsive p-0">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>Title</th>
									<th>description</th>
                                    <th>Tiempo min</th>
                                    <th>Tiempo max</th>
									<th>Chars min</th>
                                    <th>Chars max</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="game in games.data" :key="game.id">
									<td>{{ game.id }}</td>
									<td>{{ game.title }}</td>
                                    <td>{{ game.description }}</td>
                                    <td>{{ game.time_min }}</td>
                                    <td>{{ game.time_max }}</td>
                                    <td>{{ game.characters_min }}</td>
                                    <td>{{ game.characters_max }}</td>
									<td>
										<a href="#" @click="editModal( game )">
											<i class="fa fa-edit blue"></i>
										</a>
										&nbsp;
										<a href="#" @click="deleteGame(game.id)">
											<i class="fa fa-trash red"></i>
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- END INFO TO SHOW FOR ENTITY-->
					<div class="card-footer">
							<pagination :data="games" @pagination-change-page="getResults"></pagination>
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
						<h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Game's info</h5>
						<h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add new</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="editMode ? updateGame() : createGame()"> <!--Conditionals for edit o create-->
						<!--Form del Game-->
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
								<input v-model="form.time_min" type="number" name="time_min" placeholder='time_min'
								class="form-control" :class="{ 'is-invalid': form.errors.has('time_min') }">
								<has-error :form="form" field="time_min"></has-error>
							</div>
						</div>
                        <div class="modal-body">
							<div class="form-group">
								<input v-model="form.time_max" type="number" name="time_max" placeholder='time_max'
								class="form-control" :class="{ 'is-invalid': form.errors.has('time_max') }">
								<has-error :form="form" field="time_max"></has-error>
							</div>
						</div>
                        <div class="modal-body">
							<div class="form-group">
								<input v-model="form.characters_min" type="number" name="characters_min" placeholder='characters_min'
								class="form-control" :class="{ 'is-invalid': form.errors.has('characters_min') }">
								<has-error :form="form" field="characters_min"></has-error>
							</div>
						</div>
                        <div class="modal-body">
							<div class="form-group">
								<input v-model="form.characters_max" type="number" name="characters_max" placeholder='characters_max'
								class="form-control" :class="{ 'is-invalid': form.errors.has('characters_max') }">
								<has-error :form="form" field="characters_max"></has-error>
							</div>
						</div>
                        <!--fin Form del Game-->
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
				games : {},
				form: new Form({
					id: '',
                    description: '',
                    time_min:'',
                    time_max:'',
                    characters_min:'',
                    characters_max:''
				})
			}
		},
		methods: {
			getResults(page = 1) {
					axios.get('api/game?page=' + page).then(response => {
					this.games = response.data;
				});
			},
			updateGame(){
				this.$Progress.start();
				//console.log("Editing");
				//this.form.put the lleva a la funcion update
				this.form.put('api/game/'+this.form.id)
				.then(() => {
					$('#addNew').modal('hide');
					swal.fire(
						'Updated!',
						'Your game has been updated.',
						'success'
					)
					Fire.$emit('AfterCreate');
					this.$Progress.finish();
				})
				.catch(() => {
					this.$Progress.fail(); //change color to red
				});
			},
			editModal(game){
				this.editMode=true;
				this.form.reset(); //Clear errors
				$('#addNew').modal('show');
				this.form.fill(game);
			},
			newModal(){
				this.editMode=false;
				this.form.reset(); //Clear errors
				$('#addNew').modal('show');
			},
			deleteGame(id){
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
							this.form.delete('api/game/'+id).then(()=>{
							swal.fire(
								'Deleted!',
								'Your Game has been deleted.',
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
			loadGames(){
				if (this.$gate.isAdminOrAuthor()){
                    axios.get('api/game').then(({ data })=>(this.games = data));
				}
			},
			createGame(){
				this.$Progress.start();
				//send data to laravel controller
				this.form.post('api/game')
				.then(() => {
					//Register event and emit the event
					Fire.$emit('AfterCreate');
					//close modal
					$('#addNew').modal('hide');
					toast.fire({
						icon: 'success',
						title: 'Game created in successfully'
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
            this.loadGames();
			//Lister for the function AfterCreate
            
            Fire.$on('AfterCreate',()=>{
				this.loadGames();
            });
            
            //Custom event listener
            
			Fire.$on('searching',()=>{
				let query=this.$parent.search;
				axios.get('api/findGame?q='+query)
				.then((data) =>{
					this.games=data.data;
				})
				.catch(() => {});
            });
            
			//one way to refresh data			
		},
        mounted() {
            console.log('Component mounted.')
		}
	}
</script>
