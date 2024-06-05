<script setup>
import { reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import Icon from '@/components/icon/LucideIcon.vue'

</script>

<template>
    <!-- {{ tfhbClass }} -->
    <div class="tfhb-hydra-dasboard">
        <div class="tfhb-hydra-wrap ">    
            <nav class="tfhb-hydra-admin-tabs tfhb-hydra-settings"> 
                <ul>
                    <li><router-link to="/settings/general" exact :class="{ 'active': $route.path === '/settings/general' }"> <Icon name="SlidersHorizontal" /> {{ $tfhb_trans['General'] }}</router-link></li> 
                    <li><router-link to="/settings/hosts-settings" exact :class="{ 'active': $route.path.startsWith('/settings/hosts-settings') }"> <Icon name="UserCog" /> {{ $tfhb_trans['Host Settings'] }}</router-link></li> 
                    <li><router-link to="/settings/availability" :class="{ 'active': $route.path === '/settings/availability' }"> <Icon name="Clock" /> {{ $tfhb_trans['Availability'] }}</router-link></li> 
                    <li><router-link to="/settings/notifications" :class="{ 'active': $route.path === '/settings/notifications' }"> <Icon name="BellDot" /> {{ $tfhb_trans['Notifications'] }}</router-link></li>
                    <li><router-link to="/settings/integrations" :class="{ 'active': $route.path === '/settings/integrations' }"> <Icon name="Unplug" /> {{ $tfhb_trans['Integrations'] }}</router-link>
                        <!-- <ul class="dropdown">
                            <li><a href="#"> <Icon name="sidebarMenu" /> All </a></li>

                        </ul> -->
                    </li>
                    <li><router-link to="/settings/appearance" :class="{ 'active': $route.path === '/settings/appearance' }"> <Icon name="SwatchBook" /> {{ $tfhb_trans['Appearance'] }}</router-link></li>

                    <li><router-link to="/settings/category" :class="{ 'active': $route.path === '/settings/category' }"> <Icon name="ClipboardList" /> {{ $tfhb_trans['Meeting Category'] }}</router-link></li>

                </ul>  
            </nav>  
            <div class="tfhb-hydra-dasboard-content"> 
                <router-view />
                
            </div> 
            
          
            </div>
    </div>
</template>
 