<script setup>
import Icon from '@/components/icon/LucideIcon.vue'
import { useRouter, RouterView } from 'vue-router' 
</script>

<template>
    <div class="tfhb-meeting-create">
        <div class="tfhb-meeting-create-notice tfhb-mb-32">
            <div class="tfhb-meeting-heading tfhb-flexbox">
                <Icon name="ArrowLeft" size="20px" /> 
                <h3>Create One-to-One booking type</h3>
            </div>
            <div class="tfhb-meeting-subtitle">
                Create and manage booking/appointment form
            </div>
        </div>
        <nav class="tfhb-booking-tabs tfhb-mb-32"> 
            <ul>
                <!-- to route example like meetings/create/13/details -->
                
                <li><router-link :to="'/meetings/create/details'" exact :class="{ 'active': $route.path === '/meetings/create/details' }"> Details</router-link></li> 
                <li><router-link :to="'/meetings/create/availability'" :class="{ 'active': $route.path === '/meetings/create/availability' }"> Availability</router-link></li>  
                <li><router-link :to="'/meetings/create/limits'" :class="{ 'active': $route.path === '/meetings/create/limits' }"> Limits</router-link></li>  
                <li><router-link :to="'/meetings/create/questions'" :class="{ 'active': $route.path === '/meetings/create/questions' }"> Questions</router-link></li>  
                <li><router-link :to="'/meetings/create/notifications'" :class="{ 'active': $route.path === '/meetings/create/notifications' }"> Notifications</router-link></li>  
                <li><router-link :to="'/meetings/create/questions'" :class="{ 'active': $route.path === '/meetings/create/questions' }"> Payment</router-link></li>  

            </ul>  
        </nav>

        <div class="tfhb-hydra-dasboard-content"> 
            <router-view />
            
        </div> 
    </div>
</template>

<style scoped>

</style>