<script setup>
import { reactive, onBeforeMount } from 'vue';
import axios from 'axios' 
 

const event = reactive({
  details: [],
  availability: [],
  questions: '',
  notifications: [],
  Payment: [],
});  
</script>

<template>
    <!-- {{ tfhbClass }} -->
     <div class="tfhb-hydra-wrap tfhb-hydra-event"    >
        <div class="tfhb-hydra-admin-heading">
            <div class="tfhb-hydra-admin-title">
                <h1>Create One-to-One booking type</h1>
                <span>Create and manage booking/appointment form</span>
            </div>
            <div class="tfhb-hydra-admin-btn right">
                <button>Share</button>
            </div> 
        </div>
        <nav class="tfhb-hydra-admin-tabs">
            <ul>
                <li><router-link to="/event/details" exact :class="{ 'active': $route.path === 'event/details' }">Details</router-link></li>
                <li><router-link to="/event/availability" :class="{ 'active': $route.path === '/event/availability' }">Availability</router-link></li>
                <li><router-link to="/event/questions" :class="{ 'active': $route.path === '/event/questions' }">Questions</router-link></li>
                <li><router-link to="/event/notifications" :class="{ 'active': $route.path === '/event/notifications' }">Notifications</router-link></li>
                <li><router-link to="/event/payment" :class="{ 'active': $route.path === '/event/payment' }">Payment</router-link></li>
            </ul>
        </nav>

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
