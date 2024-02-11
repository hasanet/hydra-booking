<script setup>
import { reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue'


 

const event = reactive({
    data : {
        title: 'Hello World',
        description: 'This is a First Event',
        duration: '20',
        location: 'online',
        host: 'sydur',
    }
});  



const updateEvent = async () => {

 console.log(event.data); 
}
</script>

<template>
    <!-- {{ tfhbClass }} -->
     <div class="tfhb-hydra-wrap tfhb-hydra-dasboard"    > 
 
        
        <nav class="tfhb-hydra-admin-tabs tfhb-hydra-settings"> 
            <ul>
                <li><router-link to="/settings/general" exact :class="{ 'active': $route.path === '/settings/general' }"> <Icon name="SlidersHorizontal" /> General</router-link></li> 
                <li><router-link to="/settings/availability" :class="{ 'active': $route.path === '/settings/availability' }"> <Icon name="Clock" /> Availability</router-link></li>
                <li><router-link to="/settings/questions" :class="{ 'active': $route.path === '/settings/questions' }"> <Icon name="MessageCircleQuestion" /> Booking questions</router-link></li>
                <li><router-link to="/settings/notifications" :class="{ 'active': $route.path === '/settings/notifications' }"> <Icon name="BellDot" /> Notifications</router-link></li>
                <li><router-link to="/settings/integrations" :class="{ 'active': $route.path === '/settings/integrations' }"> <Icon name="Blocks" /> Integrations</router-link></li>
                <li><router-link to="/settings/appearance" :class="{ 'active': $route.path === '/settings/appearance' }"> <Icon name="SwatchBook" /> Appearance</router-link></li>

            </ul>  
        </nav>  
        <div class="tfhb-hydra-dasboard-content"> 
            <router-view :events="event"/>
        </div> 
        
        <button class="thb-btn" @click="updateEvent">Save</button>

     </div>
</template>



<style scoped>
/* Your component styles go here */

.thb-event-dashboard .thb-dashboard-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    height: 60px;  
    border-radius: 4px; 
    margin-right: 16px;
}
.thb-event-dashboard .thb-admin-title h1 {
    font-size: 24px;
    font-weight: 600;
    color: #000;
    text-decoration: none;
    outline: none;
    box-shadow: none;
    margin-left: 5px;
}
.thb-event-dashboard .thb-admin-btn.right {
    margin-top: 10px;
}
.thb-event-dashboard .thb-admin-btn.right .thb-btn {
    background-color: #F62681;
    color: #fff;
    border-radius: 4px;
    padding: 8px 16px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    outline: none;
    box-shadow: none;
    margin-left: 5px;
}

.thb-content-wrap .no-content {
    padding: 20px;
    background-color: #fff;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    margin-right: 16px;
    margin-top: 32px;
}
</style>
