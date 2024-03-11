<script setup>
import { ref, reactive, onBeforeMount, } from 'vue'; 
import { useRouter, RouterView,} from 'vue-router' 
import Icon from '@/components/icon/LucideIcon.vue'
const props = defineProps([
    'availability', 
]) 
</script>

<template>
  <div class="tfhb-availability-single-box">
    <div class="tfhb-availability-single-box-wrap">
        <div  class="tfhb-dashboard-heading ">
            <div class="tfhb-admin-title"> 
                <h2 >{{availability.title}} </h2>  
            </div>
            <div class="thb-admin-btn right"> 
                <span><Icon name="ListCollapse" size="20px" /> </span>
            </div> 
        </div>
        <div class="tfhb-availability-single-box-info  tfhb-flexbox">
            <Icon name="Clock" size="20px" /> 
            <span>Mon(04:50 am-05:00 pm),  (09:00 pm - 10:00pm)</span>
        </div>
        <div class="tfhb-availability-single-box-info tfhb-flexbox">
            <Icon name="MapPin" size="20px" /> 
            <span>{{availability.time_zone}}</span>
        </div>
    </div>
  </div>
</template>

<style scoped>
</style> 