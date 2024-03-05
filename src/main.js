import './assets/main.scss' 
import { createApp } from 'vue'
import App from './App.vue'
import router from './router/router.js' 
import { SkeletonLoader } from "vue3-loading-skeleton";

const tfhb_trans = tfhb_core_apps.trans || {};  

const tfhbApps = createApp(App).use(router);
tfhbApps.component("SkeletonLoader", SkeletonLoader);
tfhbApps.config.globalProperties.$tfhb_trans = tfhb_trans;

tfhbApps.mount('#tfhb-admin-app')

// const app = createApp(App)
// app.use(router)
// app.use(ElementPlus)
// app.mount('#thb-admin-app')