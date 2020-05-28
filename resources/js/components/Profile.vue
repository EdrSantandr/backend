<style>
	.background-gradient{
	background-position: center center;
	background-image:linear-gradient(to bottom, rgba(245, 246, 252, 0.52),
	rgba(26, 26, 26, 0.73)), url('/img/background.jpg');
	background-size: cover;
    color: white;
    padding: 20px;
	}
</style>
<template>
    <div class="container">
        <div class="row ">
            <div class="col-md-12 mt-3">
				<!-- Widget: user widget style 1 -->
				<div class="card card-widget widget-user">
					<!-- Add the bg color to the header using any of the bg-* classes -->
					<div class="widget-user-header text-white background-gradient" >
						<h3 class="widget-user-username text-right">{{ this.form.name}}</h3>
						<h5 class="widget-user-desc text-right">{{ this.form.type}}</h5>
					</div>
					<div class="widget-user-image">
						<img class="img-circle" v-bind:src="userPhoto" alt="User Avatar">
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-sm-4 border-right">
								<div class="description-block">
									<h5 class="description-header">3,200</h5>
									<span class="description-text">SALES</span>
								</div>
								<!-- /.description-block -->
							</div>
							<!-- /.col -->
							<div class="col-sm-4 border-right">
								<div class="description-block">
									<h5 class="description-header">13,000</h5>
									<span class="description-text">FOLLOWERS</span>
								</div>
								<!-- /.description-block -->
							</div>
							<!-- /.col -->
							<div class="col-sm-4">
								<div class="description-block">
									<h5 class="description-header">35</h5>
									<span class="description-text">PRODUCTS</span>
								</div>
								<!-- /.description-block -->
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->
					</div>
				</div>
				<!-- /.widget-user -->
			</div>
			<!--User info in tabs-->
			<div class="col-md-12 mt-1">
				<div class="card">
					<div class="card-header p-2">
						<ul class="nav nav-pills">
							<li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>							
							<li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
						</ul>
					</div><!-- /.card-header -->
					<div class="card-body">
						<div class="tab-content">
							<div class="tab-pane" id="activity">
								<!-- Post -->
								<div class="post">
									<div class="user-block">
										<img class="img-circle img-bordered-sm" src="img/user2-160x160.jpg" alt="user image">
										<span class="username">
											<a href="#">Jonathan Burke Jr.</a>
											<a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
										</span>
										<span class="description">Shared publicly - 7:30 PM today</span>
									</div>
									<!-- /.user-block -->
									<p>
										Lorem ipsum represents a long-held tradition for designers,
										typographers and the like. Some people hate it and argue for
										its demise, but others ignore the hate as they create awesome
										tools to help create filler text for everyone from bacon lovers
										to Charlie Sheen fans.
									</p>
									
									<p>
										<a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
										<a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
										<span class="float-right">
											<a href="#" class="link-black text-sm">
												<i class="far fa-comments mr-1"></i> Comments (5)
											</a>
										</span>
									</p>
									
									<input class="form-control form-control-sm" type="text" placeholder="Type a comment">
								</div>
								<!-- /.post -->
								
								<!-- Post -->
								<div class="post clearfix">
									<div class="user-block">
										<img class="img-circle img-bordered-sm" src="img/user2-160x160.jpg" alt="User Image">
										<span class="username">
											<a href="#">Sarah Ross</a>
											<a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
										</span>
										<span class="description">Sent you a message - 3 days ago</span>
									</div>
									<!-- /.user-block -->
									<p>
										Lorem ipsum represents a long-held tradition for designers,
										typographers and the like. Some people hate it and argue for
										its demise, but others ignore the hate as they create awesome
										tools to help create filler text for everyone from bacon lovers
										to Charlie Sheen fans.
									</p>
									
									<form class="form-horizontal">
										<div class="input-group input-group-sm mb-0">
											<input class="form-control form-control-sm" placeholder="Response">
											<div class="input-group-append">
												<button type="submit" class="btn btn-danger">Send</button>
											</div>
										</div>
									</form>
								</div>
								<!-- /.post -->
								
								
							</div>
							
							<div class="active tab-pane" id="settings">
								<form @submit.prevent="editMode ? updateUser() : createUser()"> <!--Conditionals for edit o create-->
									<!--Form del user-->
									<div class="modal-body">
										<div class="form-group">
											<label for="name" class="col-form-label">{{ __('master.profileName') }}</label>
											<input v-model="form.name" type="text" name="name" placeholder='Name'
											class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
											<has-error :form="form" field="name"></has-error>
										</div>
										<div class="form-group">
											<label for="Email" class="col-form-label">{{ __('master.profileEmail') }}</label>
											<input v-model="form.email" type="text" name="email" placeholder='Email Address'
											class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
											<has-error :form="form" field="email"></has-error>
										</div>
										<div class="form-group">
											<label for="Bio" class="col-form-label">{{ __('master.profileBiography') }}</label>
											<textarea v-model="form.bio" type="text" name="bio" placeholder='Biography'
											class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
											<has-error :form="form" field="bio"></has-error>
										</div>
										<!--Modification Select box from database-->
										<div class="form-group">
											<label for="type_id" class="col-form-label">{{ __('master.profileTypeUser') }}</label>
											
											<select name="type_id" v-model="form.type_id" class="form-control" :v-bind="form.type_id">
												<option  v-for="type in types" :value="type.id" v-bind:key="type">{{ type.name }}</option>
											</select>
											<has-error :form="form" field="type_id"></has-error>

										</div>
										<div class="form-group">
											<label for="photo" class="col-form-label">Profile Photo</label>
											<div class="input-group">
												<div class="custom-file">
													<input type="file" @change="updateProfilePhoto" 
													class="custom-file-input" id="exampleInputFile" name="photo">
													<label class="custom-file-label" for="exampleInputFile">Choose file</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label for="password" class="col-form-label">Password</label>
											<input v-model="form.password" type="password" name="password" placeholder='Password'
											class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
											<has-error :form="form" field="password"></has-error>
										</div>
									</div>
									<div class="modal-footer">
										<div class="col-sm-offset-2 col-sm-12">
											<button @click.prevent="updateInfo" type="subtmit" class="btn btn-primary ">Update</button>
										</div>
									</div>
								</form>
								
								
							</div>
							<!-- /.tab-pane -->
						</div>
						<!-- /.tab-content -->
					</div><!-- /.card-body -->
				</div>
			</div>
		</div>
	</div>
</template>

<script>
    export default {
		data() {
			return {
				userPhoto:'',
				types:{},
				form: new Form({
					id: '',
					name: '',
					email:	'',
					password: '',
					type:'',
					bio:'',
					photo:'',
					type_id:'',
				})
			}
		},
        mounted() {
            bsCustomFileInput.init();
			console.log('Component mounted.');
		},
		methods:{
			getProfilePhoto(){
				return "img/profile/"+this.userPhoto;
			},
			updateInfo(){
				this.$Progress.start()
				this.form.put('api/profile')
				.then(() => {
					this.$Progress.finish()
					//Toaster from Sweet Alert
					toast.fire({
						type: 'success',
						title: 'Profile updated!'
						})
					Fire.$emit('AfterUpdate');
				})
				.catch(() => {
					this.$Progress.fail()
					//Toaster from Sweet Alert
					toast.fire({
						type: 'error',
						title: 'Something went wrong!'
						})
				})
			},
			loadTypes(){
				if (this.$gate.isAdminOrAuthor()){
					axios.get('api/types').then(({ data })=>(this.types = data));
				}
			},
			updateProfilePhoto(e){
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
			this.loadTypes(); //Fill the select
			//axios.get('api/profile').then(({ data }) => (this.form.fill(data)));
			this.$Progress.start()
            axios.get('api/profile')
            .then(({ data }) => {
              this.userPhoto = "img/profile/" + data.photo;
				Fire.$on('AfterUpdate', () => {
					axios.get('api/profile')
					.then((data) => {
					let photo = data.data.photo
					this.userPhoto = "img/profile/" + photo;
					})
				})
				this.form.reset();
				this.form.fill(data)
				this.$Progress.finish();
            })
            .catch(() => {
              	this.$Progress.fail();
            })
		}
	}
</script>
