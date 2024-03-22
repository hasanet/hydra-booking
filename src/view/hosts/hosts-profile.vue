<script setup>
import { reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue'
// Get Current Route url
const currentRoute = useRouter().currentRoute.value.path;

 
</script>

<template>
    <!-- {{ tfhbClass }} --> 
    <div class="tfhb-hydra-wrap tfhbb-host-profile-page ">    
        <div  class="tfhb-dashboard-heading ">
            <div class="tfhb-admin-title"> 
                <h3>Jhons Profile  </h3>   
            </div> 
        </div>
        <nav class="tfhb-booking-tabs"> 
            <ul>
                <!-- to route example like hosts/profile/13/information -->
                
                <li><router-link :to="'/hosts/profile/'+ $route.params.id +'/information'" exact :class="{ 'active': $route.path === '/hosts/profile/'+ $route.params.id +'/information' }"> <Icon name="UserRound" /> Information</router-link></li> 
                <li><router-link :to="'/hosts/profile/'+ $route.params.id +'/meeting'" :class="{ 'active': $route.path === '/hosts/profile/'+ $route.params.id +'/meeting' }"> <Icon name="Clock" /> Meeting</router-link></li>  
                <li><router-link :to="'/hosts/profile/'+ $route.params.id +'/integrations'" :class="{ 'active': $route.path === '/hosts/profile/'+ $route.params.id +'/integrations' }"> <Icon name="Unplug" /> Integrations</router-link></li>  

            </ul>  
        </nav>
        <div class="tfhb-hydra-dasboard-content"> 
            <router-view />
            
        </div> 
    </div> 
</template>



<style scoped lang="scss">
/* Your component styles go here */

.tfhb-hydra-dasboard {

    width: 100%;

    .tfhb-hydra-wrap {
        display: flex;
        // gap: 16px;
    }

    .tfhb-hydra-settings {

        width: 272px;

        ul {
            display: inline-block;
        }
    }

    .tfhb-hydra-dasboard-content {
        width: calc(100% - 272px);
        padding: 24px;
    }

    .tfhb-hydra-wrap {
        width: auto;
        margin-right: 10px;
    }
}
</style>
