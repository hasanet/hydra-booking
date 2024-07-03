<script setup>
import { RouterView } from 'vue-router' 
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
                    <li  :class="{ 'expand': $route.path === '/settings/integrations' }" class="tfhb-integrations-settings-menu"><router-link to="/settings/integrations#all" class="integrations-submenu" data-filter="all" :class="{ 'active': $route.path === '/settings/integrations' }"> <Icon name="Unplug" /> {{ $tfhb_trans['Integrations'] }}
                    </router-link>
                        <ul class="dropdown">
                            <li><router-link to="/settings/integrations#all" :class="{ 'active': $route.hash === '#all' }" class="integrations-submenu" data-filter="all"> <Icon name="Unplug" /> {{ $tfhb_trans['All'] }}</router-link></li>
                            
                            <li><router-link to="/settings/integrations#conference" :class="{ 'active': $route.hash === '#conference' }" class="integrations-submenu" data-filter="conference"> <Icon name="Video" /> {{ $tfhb_trans['Conference'] }}</router-link></li>

                            <li><router-link to="/settings/integrations#calendars" :class="{ 'active': $route.hash === '#calendars' }" class="integrations-submenu" data-filter="calendars"> <Icon name="CalendarDays" /> {{ $tfhb_trans['Calendars'] }}</router-link></li>

                            <li><router-link to="/settings/integrations#payments" :class="{ 'active': $route.hash === '#payments' }" class="integrations-submenu" data-filter="payments"> <Icon name="HandCoins" /> {{ $tfhb_trans['Payments'] }}</router-link></li>
                     

                        </ul>
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
 