import './assets/main.scss'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router/router.js' 

createApp(App).use(router).mount('#tfhb-admin-app')

// const app = createApp(App)
// app.use(router)
// app.use(ElementPlus)
// app.mount('#thb-admin-app')