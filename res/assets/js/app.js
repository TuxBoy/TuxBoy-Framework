window.Vue = require('vue');

import Example from './componants/ExampleComponant.vue'

const app = new Vue({
	el: '#app',
	components: {
		Example
	}
});
