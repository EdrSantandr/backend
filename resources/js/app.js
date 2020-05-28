/**
	* First we will load all of this project's JavaScript dependencies which
	* includes Vue and other libraries. It is a great starting point when
	* building robust, powerful web applications using Vue and Laravel.
*/

require('./bootstrap');

window.Vue = require('vue');

/**Import momment JS**/
import moment from 'moment'

/**INICIO Import for vform**/
import { Form, HasError, AlertError } from 'vform'

/** Localization **/
require('./languages')
Vue.mixin(require('./trans'))

/**Import Javascript para accesos**/
import Gate from "./Gate";
Vue.prototype.$gate= new Gate(window.user);


window.Form = Form;//De esta forma podemos acceder al Form desde cualquier parte de la aplicación

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
/** FIN VFORM**/


/**Progress bar**/
import VueProgressBar from 'vue-progressbar'
import Vue from 'vue'
/**Vue Router**/
import Router from 'vue-router'
/**Sweetalert**/
import swal from 'sweetalert2'

/**File input**/
import bsCustomFileInput from 'bs-custom-file-input'
window.bsCustomFileInput =bsCustomFileInput;

Vue.use(Router)

/**Progress bar configuration**/
const options = {
	color: '#bffaf3',
	failedColor: '#874b4b',
	thickness: '5px',
	transition: {
		speed: '0.2s',
		opacity: '0.6s',
		termination: 300
	},
	autoRevert: true,
	location: 'top',
	inverse: false
}
Vue.use(VueProgressBar, options)

/**Sweetalert**/
window.swal =swal;
const toast = swal.mixin({
	toast: true,
	position: 'top-end',
	showConfirmButton: false,
	timer: 3000,
	timerProgressBar: true,
	onOpen: (toast) => {
		toast.addEventListener('mouseenter', swal.stopTimer)
		toast.addEventListener('mouseleave', swal.resumeTimer)
	}
});
window.toast=toast;


const router = new Router ({
	mode:	'history',
	routes: [
		{
			path:	'/dashboard',
			name:	'dashboard',
			component:	require('./components/Dashboard.vue').default
		},
		{
			path:	'/developer',
			name:	'developer',
			component:	require('./components/Developer.vue').default
		},
		{
			path:	'/users',
			name:	'users',
			component:	require('./components/Users.vue').default
		},
		{
			path:	'/games',
			name:	'games',
			component:	require('./components/Games.vue').default
		},
		{
			path:	'/types',
			name:	'types',
			component:	require('./components/Types.vue').default
		},
		{
			path:	'/stories',
			name:	'stories',
			component:	require('./components/Stories.vue').default
		},
		{
			path:	'/profile',
			name:	'profile',
			component:	require('./components/Profile.vue').default
		},
		{
			path:	'/*',
			name:	'not-found',
			component:	require('./components/NotFound.vue').default
		}
	]
})

/*First letter uppercase*/
Vue.filter('uptext', function(text){
	return text.charAt(0).toUpperCase() + text.slice(1)
});

/**Format date**/
Vue.filter('formatDate', function(dateText){
	return moment(dateText).format("MMM Do YYYY")
});

//Event fire to custom event
window.Fire= new Vue();

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

Vue.component(
    'not-found',
    require('./components/NotFound.vue').default
);

/**COMPOMENTN FOR PAGINATION**/
Vue.component('pagination', require('laravel-vue-pagination'));

/**
	* The following block of code may be used to automatically register your
	* Vue components. It will recursively scan this directory for the Vue
	* components and automatically register them with their "basename".
	*
	* Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
*/

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
	* Next, we will create a fresh Vue application instance and attach it to
	* the page. Then, you may begin adding components to this application
	* or customize the JavaScript scaffolding to fit your unique needs.
*/


const app = new Vue({
    el: '#app',
	router,
	data:{
		search:""
	},
	methods:{
		searchit: _.debounce(() =>{ //DEBOUNCE es una funcion de la librería lodash
			Fire.$emit('searching');//create a custom event
		},1000) //Wait 1 seconds
	}
});
