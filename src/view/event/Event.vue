<script setup> 
// Use children routes for the tabs 
import { useRouter, RouterView,  } from 'vue-router'  
const router = useRouter();


</script>
<template>
    <div class="thb-event-dashboard">
        <div class="thb-dashboard-heading">
            <div class="thb-admin-title">
                <h1>Events</h1>
            </div>
            <div class="thb-admin-btn right">
                <router-link to="/event/create" class="thb-btn">Create Event</router-link>
            </div> 
        </div>
        <div class="thb-content-wrap">
            <div class="no-content">
                <p>There are no events yet.</p>
            </div>
        </div>
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
