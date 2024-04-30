import './assets/main.scss' 
import { createApp } from 'vue'
import App from './App.vue'
import router from './router/router.js'   
import PrimeVue from "primevue/config"; 
import 'primevue/resources/themes/aura-light-green/theme.css'
const tfhb_trans = tfhb_core_apps.trans || {};  
const tfhb_url =  tfhb_core_apps.tfhb_url || '';      
const user =  tfhb_core_apps.user || '';      
 

const tfhbApps = createApp(App).use(router); 
tfhbApps.use(PrimeVue);
 
// tfhbApps.component("toast", toast);
tfhbApps.config.globalProperties.$tfhb_trans = tfhb_trans;
tfhbApps.config.globalProperties.$tfhb_url = tfhb_url;  
tfhbApps.config.globalProperties.$user = user; 

tfhbApps.mount('#tfhb-admin-app')

// const app = createApp(App)
// app.use(router)
// app.use(ElementPlus)
// app.mount('#thb-admin-app')