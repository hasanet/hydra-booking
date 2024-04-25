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
 
}
</script>

<template>
    <!-- {{ tfhbClass }} -->
    <div class="tfhb-hydra-dasboard">
        <div class="tfhb-hydra-wrap ">    
            <nav class="tfhb-hydra-admin-tabs tfhb-hydra-settings"> 
                <ul>
                    <li><router-link to="/settings/general" exact :class="{ 'active': $route.path === '/settings/general' }"> <Icon name="SlidersHorizontal" /> General</router-link></li> 
                    <li><router-link to="/settings/hosts-settings" exact :class="{ 'active': $route.path.startsWith('/settings/hosts-settings') }"> <Icon name="SlidersHorizontal" /> Host Settings</router-link></li> 
                    <li><router-link to="/settings/availability" :class="{ 'active': $route.path === '/settings/availability' }"> <Icon name="Clock" /> Availability</router-link></li> 
                    <li><router-link to="/settings/notifications" :class="{ 'active': $route.path === '/settings/notifications' }"> <Icon name="BellDot" /> Notifications</router-link></li>
                    <li><router-link to="/settings/integrations" :class="{ 'active': $route.path === '/settings/integrations' }"> <Icon name="Unplug" /> Integrations</router-link></li>
                    <li><router-link to="/settings/appearance" :class="{ 'active': $route.path === '/settings/appearance' }"> <Icon name="SwatchBook" /> Appearance</router-link></li>

                </ul>  
            </nav>  
            <div class="tfhb-hydra-dasboard-content"> 
                <router-view />
                
            </div> 
            
          
            </div>
    </div>
</template>
 