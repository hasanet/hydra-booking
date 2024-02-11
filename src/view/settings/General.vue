<script setup> 
// Use children routes for the tabs 
import { useRouter, RouterView,} from 'vue-router'  
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