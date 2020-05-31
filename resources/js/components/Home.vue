<template>
	<div class="">
                <div class="button-group">
                    <button type="button" class="disabled-btn" @click="setDisabled()">Disabled move</button>
                    <button type="button" :class="{active:index ==0}" @click="moveTo(0)">first page</button>
                    <button type="button" :class="{active:index ==1}" @click="moveTo(1)">Second page</button>
                    <button type="button" :class="{active:index ==2}" @click="moveTo(2)">Third page</button>
                    <button type="button" v-for="btn in pageNum" :key="btn" :class="{active:index == btn + 2}" @click="moveTo(btn+2)">page {{btn+2}}</button>
                    <button type="button" @click="showPage()">add page</button>
                </div>
				<div class="button-bottom">
					
				</div>
                <div class="fullpage-vertical">
                    <div class="" v-fullpage="opts" ref="fullpage">
                    <div class="page-1 page">
                    	<div class="">
							<object class="svg-image animate__animated animate__tada animate__slower" type="image/svg+xml" data="img/app/improgamesfigure.svg">
                                <h3>Impro.games</h3>
                            </object>
							<object class="svg-image animate__animated animate__heartBeat animate__slow" type="image/svg+xml" data="img/app/improgameslogo.svg">
                                <h3>Impro.games</h3>
                            </object>
                    	    <div class="blank" ></div>
                            <a href="#" class="customButton center">registrarme</a>
                            <div class="blank" ></div>
                            <a href="#" class="customButton center">iniciar sesi&oacute;n</a>
						</div>
                    </div>
                    <div class="page-2 page">
                        <div class="blank" ></div>                        
                        <img class="custom-img custom-shadow" src="img/app/eder.jpg" alt="eder santander impro games">
                        <div class="blank" ></div>
                        <!--TEXTO-->
                        <p class="custom-paragraph"> Me llamo Eder, soy Ing. Inform&aacute;tico de profesi&oacute;n,
                            cocinero, pichanguero (me gusta el f&uacute;tbol) e improvisador
                            en formaci&oacute;n. Decid&iacute; unir mis dos pasiones en este proyecto
                            la impro y mi lado ingenieril. Usando las mejores tecnolog&iacute;as y
                            mi conocimiento de las din&aacute;micas de improvisaci&oacute;n teatral.
                            Espero que les guste

                        </p>

                    </div>
                    <div class="page-3 page" v-animate="{value: 'zoomInDown', delay: 100}">
                        <h2 class="part-3" v-animate="{value: 'bounceInLeft'}">Working On Tablets</h2>
                        <h3 class="" v-animate="{value: 'bounceIn'}">Designed to fit different screen sizes as well as tablet and mobile devices. </h3>
                        <p class="part-3" v-animate="{value: 'bounceInLeft',}">vue-fullpage</p>
                        <p class="part-3" v-animate="{value: 'bounceInRight', delay: 300}">vue-fullpage</p>
                        <p class="part-3" v-animate="{value: 'bounceInDown', delay: 600}">vue-fullpage</p>
                        <p class="part-3" v-animate="{value: 'zoomInDown', delay: 900}">vue-fullpage</p>
                    </div>
                    <div class="page-2 page" v-for="page in pageNum" :key="page">
                        <h2 class="part-2" v-animate="{value: 'bounceInRight'}">page {{page}}</h2>
                    </div>
                </div>
                
                </div>
            </div>
</template>
<script>
    export default {        
		data() {
            var that = this;
            return {
                index: 0,
                pageNum: 0,
                disabled: false,
                opts: {
                    start: 0,
                    dir: 'v',
                    loop: false,
                    duration: 300,
                    beforeChange: function(ele, current, next) {
                        console.log('before', current, next)
                        that.index = next;
                    },
                    afterChange: function(ele, current) {
                        that.index = current;
                        console.log('after', current)
                    }
                },
                horizontalOpts:{
                    start:0,
                    dir:'h',
                    loop: false,
                    afterChange: function(ele, current) {
                        that.active2 = current;
                    }
                },
                active2:0
            };
        },
		methods: {
            scroll(){
                console.log('scroll.')
                console.log('Width'+this.$vssWidth)
				console.log('Height'+this.$vssHeight)
                window.scrollTo(0,this.$vssHeight)
			},
			moveTo: function(index) {
                this.$refs.fullpage.$fullpage.moveTo(index, true, true);
            },
            showPage: function() {
                this.pageNum++;
                this.$refs.fullpage.$fullpage.$update();
            },
            setDisabled(){
                this.disabled = !this.disabled
                this.$refs.fullpage.$fullpage.setDisabled(this.disabled)
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
