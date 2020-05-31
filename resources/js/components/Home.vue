<template>
    	<div class="landing">
			<h1 class="titulo cairo-bold">Welcome to my landing</h1>
			<span id="down" @click="scroll"><i class="fas fa-theater-masks"></i></span>
			<div class="profile">
				<h1>eder</h1>
			</div>
			<div class="aos-all">
				<div data-id="1" class="aos-item" data-aos="fade-up"></div>
				<div data-id="2" class="aos-item" data-aos="fade-down"></div>
				<div data-id="3" class="aos-item" data-aos="zoom-out-down"></div>
				<div data-id="4" class="aos-item" data-aos="flip-down"></div>
				<div data-id="5" class="aos-item" data-aos="flip-up"></div>
				<div data-id="6" class="aos-item" data-aos="fade-down"></div>
				<div data-id="7" class="aos-item" data-aos="fade-in"></div>
				<div data-id="8" class="aos-item" data-aos="fade-down"></div>
				<div data-id="9" class="aos-item" data-aos="fade-in"></div>
				<div data-id="10" class="aos-item" data-aos="fade-down" data-aos-id="super-duper"></div>
				<div data-id="11" class="aos-item" data-aos="fade-up"></div>
				<div data-id="12" class="aos-item" data-aos="fade-down"></div>
				<div data-id="13" class="aos-item" data-aos="fade-in"></div>
				<div data-id="14" class="aos-item" data-aos="fade-up"></div>
				<div data-id="15" class="aos-item" data-aos="fade-in"></div>
				<div data-id="16" class="aos-item" data-aos="fade-up"></div>
				<div data-id="17" class="aos-item" data-aos="fade-down"></div>
				<div data-id="18" class="aos-item" data-aos="fade-up"></div>
				<div data-id="19" class="aos-item" data-aos="zoom-out"></div>
				<div data-id="20" class="aos-item" data-aos="fade-up"></div>
				<div data-id="21" class="aos-item" data-aos="zoom-out"></div>
				<div data-id="22" class="aos-item" data-aos="fade-in"></div>
				<div data-id="23" class="aos-item" data-aos="zoom-out-up"></div>
				<div data-id="24" class="aos-item" data-aos="zoom-out-down"></div>
			</div>
		</div>
		
    
</template>
<script>
      document.querySelector('html').classList.remove('no-js');
      if (!window.Cypress) {
        const scrollCounter = document.querySelector('.js-scroll-counter');

        AOS.init({
          mirror: true
        });

        document.addEventListener('aos:in', function(e) {
          console.log('in!', e.detail);
        });

        window.addEventListener('scroll', function() {
          scrollCounter.innerHTML = window.pageYOffset;
        });
      }
    </script>
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
            scroll(){
                console.log('scroll.')
                console.log('Width'+this.$vssWidth)
				console.log('Height'+this.$vssHeight)
                window.scrollTo(0,this.$vssHeight)
            }
		},
		created() {
			/*Call this funstion on create*/
        },
        
        mounted() {
			/** Si hay algún input file recordar iniciar esto**/
            //bsCustomFileInput.init();
            
            console.log('Component mounted.')
		}
	}
</script>
