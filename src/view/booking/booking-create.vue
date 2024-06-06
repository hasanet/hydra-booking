<script setup>
import { ref, reactive, onBeforeMount } from 'vue';
import axios from 'axios' 
import HbText from '@/components/form-fields/HbText.vue'
import HbDropdown from '@/components/form-fields/HbDropdown.vue'
import HbDateTime from '@/components/form-fields/HbDateTime.vue';
import Icon from '@/components/icon/LucideIcon.vue'
import { useRouter } from 'vue-router' 
const router = useRouter();

// Fetch Pre booking Data
const booking = reactive({
    'name': '',
    'email': '',
    'time_zone': '',
    'meeting': '',
    'host': '',
    'location': '',
    'date': '',
    'time': '',
    'status': '',
})
const timeZone = reactive({});
const meetings = reactive({});
const meeting_locations = reactive({});
const meeting_hosts = reactive({});
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

// Back to Booking
const TfhbPrevNavigator = () => {
    router.push({ name: 'BookingLists' });
}

// Meeting Change
const MeetingChangeCallback = async (e) => {
    let data = {
        meeting_id: e.value,
    };  
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/booking/meeting', data, {} );
      
        if (response.data.status) {    
            meeting_locations.value = response.data.locations; 
            meeting_hosts.value = response.data.hosts;
        }
    } catch (error) {
        
    }

}

onBeforeMount(() => { 
    fetchPreBookingData();
});
</script>

<template>
{{ booking }}
    <div class="tfhb-booking-create">
        <div class="tfhb-booking-box tfhb-flexbox">
            <div class="tfhb-meeting-heading tfhb-flexbox tfhb-gap-8">
                <div class="prev-navigator tfhb-cursor-pointer" @click="TfhbPrevNavigator()">
                    <Icon name="ArrowLeft" size="20" /> 
                </div>
                <h3>{{ $tfhb_trans['Back to Booking'] }}</h3>
            </div>
            
            <HbText  
                v-model="booking.name"
                required= "true"  
                :label="$tfhb_trans['Customer name']"  
                name="name"
                selected = "1"
                :placeholder="$tfhb_trans['Jhon Deo']" 
            /> 
            <HbText  
                v-model="booking.email"
                required= "true"  
                :label="$tfhb_trans['Customer email']"  
                name="email"
                selected = "1"
                :placeholder="$tfhb_trans['name@yourmail.com']" 
            /> 

            <HbDropdown
                v-model="booking.time_zone"
                required= "true"  
                :label="$tfhb_trans['Client Time zone']" 
                :filter="true"
                selected = "1"
                placeholder="Select Time Zone"  
                :option = "timeZone.value" 
            />  

            <HbDropdown
                v-model="booking.meeting"
                required= "true"  
                :label="$tfhb_trans['Select Meeting']" 
                :filter="true"
                selected = "1"
                placeholder="Select Your Meeting"  
                :option = "meetings.value" 
                @tfhb-onchange="MeetingChangeCallback"
            />  

            <HbDropdown
                v-model="booking.host"
                required= "true"  
                :label="$tfhb_trans['Select Team Member']" 
                :filter="true"
                selected = "1"
                :option = "meeting_hosts.value" 
            /> 

            <HbDropdown
                v-model="booking.location"
                required= "true"  
                :label="$tfhb_trans['Select Location']" 
                :filter="true"
                selected = "1"
                :option = "meeting_locations.value" 
            /> 

            <HbDateTime  
                v-model="booking.date"
                :label="$tfhb_trans['Select Date']" 
                selected = "1" 
                :config="{
                    disable: ['2024-06-12', '2024-06-14']
                }"
                placeholder="Type your schedule title"   
            />

            <HbDropdown  
                v-model="booking.status"
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

            <div class="tfhb-submission-btn">
                <button class="tfhb-btn boxed-btn tfhb-flexbox">{{ $tfhb_trans['Create Booking'] }} </button>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>