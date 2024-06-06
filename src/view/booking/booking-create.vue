<script setup>
import { ref, reactive, onBeforeMount } from 'vue';
import axios from 'axios' 
import HbText from '@/components/form-fields/HbText.vue'
import HbDropdown from '@/components/form-fields/HbDropdown.vue'

// Fetch Pre booking Data
const timeZone = reactive({});
const meetings = reactive({});
const fetchPreBookingData = async () => {
    try { 
        const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/booking/pre');
        if (response.data.status) { 
            timeZone.value = response.data.time_zone; 
            meetings.value = response.data.meetings; 
        }
    } catch (error) {
        console.log(error);
    } 
}

onBeforeMount(() => { 
    fetchPreBookingData();
});
</script>

<template>

    <div class="tfhb-booking-create">
        <div class="tfhb-booking-box tfhb-flexbox">
            <HbText  
                required= "true"  
                :label="$tfhb_trans['Customer name']"  
                name="name"
                selected = "1"
                :placeholder="$tfhb_trans['Jhon Deo']" 
            /> 
            <HbText  
                required= "true"  
                :label="$tfhb_trans['Customer email']"  
                name="email"
                selected = "1"
                :placeholder="$tfhb_trans['name@yourmail.com']" 
            /> 

            <HbDropdown
                required= "true"  
                :label="$tfhb_trans['Client Time zone']" 
                :filter="true"
                selected = "1"
                placeholder="Select Time Zone"  
                :option = "timeZone.value" 
            />  

            <HbDropdown
                required= "true"  
                :label="$tfhb_trans['Select Meeting']" 
                :filter="true"
                selected = "1"
                placeholder="Select Your Meeting"  
                :option = "meetings.value" 
            />  

            <HbDropdown  
                :label="$tfhb_trans['Status']" 
                required= "true" 
                :selected = "1"
                placeholder="Select Booking status"   
                :option = "[
                    {'name': 'Pending', 'value': 'pending'},  
                    {'name': 'Confirmed', 'value': 'confirmed'},   
                    {'name': 'Canceled', 'value': 'canceled'}
                ]" 
            />  
        </div>
    </div>
</template>

<style scoped>

</style>