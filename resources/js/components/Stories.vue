<template>
    <div class="container">
		<!--v-if condicion para no motrar por roles-->
        <div class="row" v-if="$gate.isAdminOrAuthor()">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Stories Table</h3>
						<div class="card-tools">
						<button class="btn btn-success" @click="newModal">Add new&nbsp;<i class="fas fa-book  fa-fw"></i></button>
						</div>
					</div>
					<!-- INFO TO SHOW FOR ENTITY-->
					<div class="card-body table-responsive p-0">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>Title</th>
									<th>Content</th>
                                    <th>Last User</th>
                                    <th>State</th>
									<th>Votes</th>
                                    <th>Turns</th>
                                    <th>Date Ini</th>
                                    <th>Date End</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="story in stories.data" :key="story.id">
									<td>{{ story.id }}</td>
									<td>{{ story.title }}</td>
                                    <td>{{ story.content }}</td>
                                    <td>{{ story.last_user_id }}</td>
                                    <td>{{ story.state }}</td>
                                    <td>{{ story.votes }}</td>
                                    <td>{{ story.turns }}</td>
									<td>{{ story.date_ini|formatDate }}</td>
                                    <td>{{ story.date_end|formatDate }}</td>
									<td>
										<a href="#" @click="editModal( story )">
											<i class="fa fa-edit blue"></i>
										</a>
										&nbsp;
										<a href="#" @click="deleteStory(story.id)">
											<i class="fa fa-trash red"></i>
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- END INFO TO SHOW FOR ENTITY-->
					<div class="card-footer">
							<pagination :data="stories" @pagination-change-page="getResults"></pagination>
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
						<h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Story's info</h5>
						<h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add new</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="editMode ? updateStory() : createStory()"> <!--Conditionals for edit o create-->
						<!--Form del STORY-->
						<div class="modal-body">
							<div class="form-group">
								<input v-model="form.title" type="text" name="title" placeholder='title'
								class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
								<has-error :form="form" field="title"></has-error>
							</div>
						</div>
                        <div class="modal-body">
							<div class="form-group">
								<textarea v-model="form.content" type="text" name="content" placeholder='content'
								class="form-control" :class="{ 'is-invalid': form.errors.has('content') }"></textarea>
								<has-error :form="form" field="content"></has-error>
							</div>
						</div>
                        <div class="modal-body">
							<div class="form-group">
								<input v-model="form.votes" type="text" name="votes" placeholder='votes'
								class="form-control" :class="{ 'is-invalid': form.errors.has('votes') }">
								<has-error :form="form" field="votes"></has-error>
							</div>
						</div>
                        <div class="modal-body">
							<div class="form-group">
								<input v-model="form.turns" type="text" name="turns" placeholder='turns'
								class="form-control" :class="{ 'is-invalid': form.errors.has('turns') }">
								<has-error :form="form" field="turns"></has-error>
							</div>
						</div>
                        <!--fin Form del STORY-->
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
				stories : {},
				form: new Form({
					id: '',
                    title: '',
                    content:'',
                    votes:'',
                    turns:''
				})
			}
		},
		methods: {
			getResults(page = 1) {
					axios.get('api/story?page=' + page).then(response => {
					this.stories = response.data;
				});
			},
			updateStory(){
				this.$Progress.start();
				//console.log("Editing");
				//this.form.put the lleva a la funcion update
				this.form.put('api/story/'+this.form.id)
				.then(() => {
					$('#addNew').modal('hide');
					swal.fire(
						'Updated!',
						'Your story has been updated.',
						'success'
					)
					Fire.$emit('AfterCreate');
					this.$Progress.finish();
				})
				.catch(() => {
					this.$Progress.fail(); //change color to red
				});
			},
			editModal(story){
				this.editMode=true;
				this.form.reset(); //Clear errors
				$('#addNew').modal('show');
				this.form.fill(story);
			},
			newModal(){
				this.editMode=false;
				this.form.reset(); //Clear errors
				$('#addNew').modal('show');
			},
			deleteStory(id){
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
							this.form.delete('api/story/'+id).then(()=>{
							swal.fire(
								'Deleted!',
								'Your story has been deleted.',
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
			loadStories(){
				if (this.$gate.isAdminOrAuthor()){
                    axios.get('api/story').then(({ data })=>(this.stories = data));
				}
			},
			createStory(){
				this.$Progress.start();
				//send data to laravel controller
				this.form.post('api/story')
				.then(() => {
					//Register event and emit the event
					Fire.$emit('AfterCreate');
					//close modal
					$('#addNew').modal('hide');
					toast.fire({
						icon: 'success',
						title: 'Story created in successfully'
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
            this.loadStories();
			//Lister for the function AfterCreate
            
            Fire.$on('AfterCreate',()=>{
				this.loadStories();
            });
            
            //Custom event listener
            
			Fire.$on('searching',()=>{
				let query=this.$parent.search;
				axios.get('api/findStory?q='+query)
				.then((data) =>{
					this.stories=data.data;
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
