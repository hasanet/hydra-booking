import './assets/main.scss'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router/router.js'
import ElementPlus from 'element-plus' 
import 'element-plus/dist/index.css'

createApp(App).use(router).use(ElementPlus).mount('#thb-admin-app')

// const app = createApp(App)
// app.use(router)
// app.use(ElementPlus)
// app.mount('#thb-admin-app')