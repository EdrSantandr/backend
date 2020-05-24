import Router from 'vue-router'

Vue.use(Router)

export default new Router ({
	routes: [
		{
			path:	'/dashboard',
			name:	'dashboard',
			component:	require('./components/Dashboard.vue')
		},
		{
			path:	'/profile',
			name:	'profile',
			component:	require('./components/Profile.vue')
		}
	],
	mode:	'history'
})