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
                <div class="fullpage-vertical">
                    <div class="" v-fullpage="opts" ref="fullpage">
                    <div class="page-1 page">
                        <h1 class="part-1" v-animate="{value: 'bounceInLeft'}">vue-fullpage.js</h1>
                        <h3 class="" v-animate="{value: 'bounceInLeft'}">A sigle-page scroll plugin based on vue@2.x,support for mobile and PC .</h3>
                        <div>
                            <p class="part-1" v-animate="{value: 'bounceInRight'}">vue-fullpage</p>
                        </div>
                    </div>
                    <div class="page-2 page" >
                        <div class="fullpage-horizontal">
                            <div v-fullpage="horizontalOpts" ref="fullpageHorizontal">
                                <div class="page-4 page">
                                    <h2 class="part-2" v-animate="{value: 'bounceInRight'}">Easy to use plugin</h2>
                                    <p v-animate="{value: 'bounceInRight'}">nesting</p>    
                                    <p v-animate="{value: 'bounceInRight'}">
                                        horizontal 1
                                    </p>
                                </div>
                                <div class="page-5 page">
                                    <p v-animate="{value: 'bounceInDown'}">horizontal 2</p>
                                </div>
                            </div>
                            <div class="fullpage-pagination">
                                <div class="fullpage-pagination-bullet" 
                                    v-for="(i,index) in [0,1]" :key="i"
                                    :class="{'fullpage-pagination-bullet__active':active2==index}"></div>
                            </div>
                        </div>
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
                <div class="fullpage-pagination">
                    <div class="fullpage-pagination-bullet" 
                        v-for="(i,indx) in [0,1,2]" :key="i"
                        :class="{'fullpage-pagination-bullet__active':index==indx}"></div>
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
