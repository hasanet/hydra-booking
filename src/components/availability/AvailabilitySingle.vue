<script setup>
import { ref, reactive, onBeforeMount, } from 'vue'; 
import { useRouter, RouterView,} from 'vue-router' 
import Icon from '@/components/icon/LucideIcon.vue'
const props = defineProps([
    'availability', 
]) 
const emit = defineEmits(["delete-availability", "edit-availability"]); 

const deleteAvailability = () => {
    emit('delete-availability');
}
const editAvailability = () => { 
    emit('edit-availability');
}

</script>

<template>
  <div class="tfhb-availability-single-box">
    <div class="tfhb-availability-single-box-wrap">
        <div  class="tfhb-dashboard-heading ">
            <div class="tfhb-admin-title"> 
                <h2 >{{availability.title}} </h2>  
            </div>
            <div class="thb-admin-btn right"> 
                <div class="tfhb-availability-action tfhb-dropdown">
                    <Icon name="ListCollapse" size="20px" /> 
                    <div class="tfhb-dropdown-wrap">
                        <span class="tfhb-dropdown-single" @click="editAvailability">Edit</span>
                        <!-- <span class="tfhb-dropdown-single">Duplicate</span> -->
                        <span class="tfhb-dropdown-single" @click="deleteAvailability">Delete</span>
                    </div>
                </div>
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