window.Vue = require('vue');

Vue.component('example-componant', require('./componants/ExampleComponant.vue'))

const app = new Vue({
	el: '#app'
});
