import Vue from 'vue'
import App from './App.vue'
import axios from 'axios'
import router from './router.js'
import BootstrapVue from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

axios.defaults.withCredentials = true
Vue.prototype.$axios = axios

Vue.config.productionTip = false
Vue.use(BootstrapVue)

new Vue({
  router,
  render: h => h(App),
}).$mount('#app')
