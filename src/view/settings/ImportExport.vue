<script setup> 
// Use children routes for the tabs 
import { onBeforeMount } from 'vue';
import { RouterView } from 'vue-router'  
import Icon from '@/components/icon/LucideIcon.vue'

// Store 
import { importExport } from '@/store/settings/importExport';

 
onBeforeMount(() => {    
});
const updateHosts = async () => { 
}
</script>
<template> 
    <div :class="{ 'tfhb-skeleton': importExport.skeleton }" class="thb-host-dashboard "> 
        {{ importExport }}
        <div  class="tfhb-dashboard-heading tfhb-mb-16">
            <div class="tfhb-admin-title "> 
                <h1 >{{ $tfhb_trans['Import/Export'] }}</h1> 
                <p>{{ $tfhb_trans['One-liner description'] }}</p>
            </div>
            <div class="thb-admin-btn right"> 
                <a href="#" target="_blank" class="tfhb-btn"> {{ $tfhb_trans['View Documentation'] }}<Icon name="ArrowUpRight" size="15" /></a>
            </div> 
        </div>
        <div class="tfhb-content-wrap">
             <!-- {{ tfhbClass }} --> 
             <nav class="tfhb-booking-tabs"> 
                <ul>
                    <!-- to route example like hosts/profile/13/information -->
                    <li><router-link to="/settings/import-export/booking" exact :class="{ 'active': $route.path === '/settings/import-export/booking' }"> <Icon name="UserRound" /> {{ $tfhb_trans['Booking'] }}</router-link></li>  
                    
                </ul>  
            </nav>
            <div class="tfhb-hydra-content-wrap">      
             
                <router-view :importExport="importExport" /> 

            </div> 

        </div>
    </div>
 
</template>