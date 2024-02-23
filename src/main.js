import './assets/main.scss'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router/router.js' 

const tfhb_trans = tfhb_core_apps.trans || {}; 
console.log(tfhb_trans);

const tfhbApps = createApp(App).use(router);
tfhbApps.config.globalProperties.$tfhb_trans = tfhb_trans;

tfhbApps.mount('#tfhb-admin-app')

// const app = createApp(App)
// app.use(router)
// app.use(ElementPlus)
// app.mount('#thb-admin-app')