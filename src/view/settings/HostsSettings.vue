<script setup> 
// Use children routes for the tabs 
import { onBeforeMount } from 'vue';
import { RouterView } from 'vue-router'  
import Icon from '@/components/icon/LucideIcon.vue'

// Store 
import { hostsSettings } from '@/store/settings/hostsSettings';

 
onBeforeMount(() => {   
     hostsSettings.fetchHostsSettings();
});
const updateHosts = async () => {
     hostsSettings.updateHostsSettings();
}
</script>
<template> 
    <div :class="{ 'tfhb-skeleton': hostsSettings.skeleton }" class="thb-host-dashboard "> 
        <div  class="tfhb-dashboard-heading tfhb-mb-16">
            <div class="tfhb-admin-title "> 
                <h1 >{{ $tfhb_trans['Host Settings'] }}</h1> 
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
                    <li><router-link to="/settings/hosts-settings/information-builder" exact :class="{ 'active': $route.path === '/settings/hosts-settings/information-builder' }"> <Icon name="UserRound" /> {{ $tfhb_trans['Information Builder'] }}</router-link></li> 
                    <li><router-link to="/settings/hosts-settings/permission" exact :class="{ 'active': $route.path === '/settings/hosts-settings/permission' }"> <Icon name="KeyRound" /> {{ $tfhb_trans['Permission'] }}</router-link></li> 
                    
                </ul>  
            </nav>
            <div class="tfhb-hydra-content-wrap">      
             
                <router-view :hostsSettings="hostsSettings.settings" />

                <div class="tfhb-submission-btn tfhb-mt-16">
                    <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="updateHosts">{{ $tfhb_trans['Update Host Settings'] }} </button>
                </div> 

            </div> 

        </div>
    </div>
 
</template>