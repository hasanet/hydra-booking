<script setup> 
// Use children routes for the tabs 
import { useRouter, RouterView,} from 'vue-router'  
import Icon from '@/components/icon/LucideIcon.vue'
const router = useRouter();


</script>
<template>
    <div class="thb-event-dashboard">
        <div class="thb-dashboard-heading">
            <div class="thb-admin-title">
                <h1>Appearance</h1> 
                <p>Manage your time zone settings and bookings</p>
            </div>
            <div class="thb-admin-btn right"> 
                <a href="#" target="_blank" class="tfhb-btn">View Documentation <Icon name="ArrowUpRight" size="15px" /></a>
            </div> 
        </div>
        <div class="thb-content-wrap">
            <div class="no-content">
                <p>There are no events yet.</p>
            </div>
        </div>
    </div>
 
</template>