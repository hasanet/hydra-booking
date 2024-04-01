<script setup>
import { reactive, onBeforeMount, ref } from 'vue';
import Icon from '@/components/icon/LucideIcon.vue'
import { useRouter, useRoute, RouterView } from 'vue-router' 
import axios from 'axios'  
import { toast } from "vue3-toastify"; 

const route = useRoute();
const router = useRouter();

const meetingData = reactive({
    id: 0,
    user_id: 0,
    host_id: '',
    title: '',
    description: '',
    meeting_type: '',
    duration: '',
    meeting_locations: [
        {
        location:'',
        address:''
        }
    ],
    meeting_category: '',
    availability_type: 'settings',
    availability_id : '',
    availability_custom: 
        {
        title: '',
        time_zone: '',
        date_status: 0,
        time_slots: [
            { 
                day: 'Monday',
                status: 1,
                times: [
                    {
                        start: '09:00',
                        end: '17:00',
                    },   
                ]
            },
            { 
                day: 'Tuesday', 
                status: 1,
                times: [
                    {
                        start: '09:00',
                        end: '17:00',
                    }
                ]
            },
            { 
                day: 'Wednesday', 
                status: 1,
                times: [
                    {
                        start: '09:00',
                        end: '17:00',
                    }
                ]
            },
            { 
                day: 'Thursday', 
                status: 1,
                times: [
                    {
                        start: '09:00',
                        end: '17:00',
                    }
                ]
            },
            { 
                day: 'Friday', 
                status: 1,
                times: [
                    {
                        start: '09:00',
                        end: '17:00',
                    }
                ]
            },
            { 
                day: 'Saturday', 
                status: 1,
                times: [
                    {
                        start: '09:00',
                        end: '17:00',
                    }
                ]
            },
            { 
                day: 'Sunday', 
                status: 1,
                times: [
                    {
                        start: '09:00',
                        end: '17:00',
                    }
                ]
            }
        ],
        date_slots: [
            {
                start: '2022-01-01',
                end: '2022-01-01',
            }
        ]
    },
    buffer_time_before: '',
    buffer_time_after: '',
    booking_frequency: [
        {
            limit: 1,
            times:'Year'
        }
    ],
    meeting_interval: '',
    recurring_status: 1,
    recurring_repeat:[
        {
            limit: 1,
            times:'Year'
        }
    ],
    recurring_maximum: ''
    
});

// Add more Location
function addMoreLocations(){
    meetingData.meeting_locations.push({
    location:'',
    address:'',
  })
}

// Add new time slot
const addAvailabilityTime = (key) => {
    meetingData.availability_custom.time_slots[key].times.push({
        start: '09:00',
        end: '17:00',
    });
}

// Remove time slot
const removeAvailabilityTime = (key, tkey = null) => {
    meetingData.availability_custom.time_slots[key].times.splice(tkey, 1);
}

// Add new date slot
const addAvailabilityDate = (key) => {
    meetingData.availability_custom.date_slots.push({
        start: '2022-01-01',
        end: '2022-01-01',
    });
}

// Remove date slot 
const removeAvailabilityTDate = (key) => {
    meetingData.availability_custom.date_slots.splice(key, 1);
}

// Meeting Type
const AvailabilityTabs = (type) => {
    meetingData.availability_type = type
}

const meetingId = route.params.id;

 const fetchMeeting = async () => {
    try { 
        const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/'+meetingId);
        if (response.data.status == true) { 
            meetingData.id = response.data.meeting.id
            meetingData.user_id = response.data.meeting.user_id
            meetingData.title = response.data.meeting.title
            meetingData.description = response.data.meeting.description
            meetingData.meeting_type = response.data.meeting.meeting_type
            meetingData.duration = response.data.meeting.duration
            meetingData.meeting_category = response.data.meeting.meeting_category
            if(response.data.meeting.meeting_locations){
                meetingData.meeting_locations = JSON.parse(response.data.meeting.meeting_locations)
            }
            if(response.data.meeting.hosts){
                meetingData.hosts = JSON.parse(response.data.meeting.hosts)
            }
            if(response.data.meeting.availability_seetings){
                meetingData.availability_seetings = response.data.meeting.availability_seetings
            }
            if(response.data.meeting.availability_custom){
                meetingData.availability_custom = JSON.parse(response.data.meeting.availability_custom)
            }
            meetingData.availability_type = response.data.meeting.availability_type
            meetingData.availability_id = response.data.meeting.availability_id

        }else{ 
            router.push({ name: 'MeetingsLists' });
        }
    } catch (error) {
        console.log(error);
    } 
} 

onBeforeMount(() => { 
    fetchMeeting();
});


const UpdateMeetingData = async () => {
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/details/update', meetingData);
        if (response.data.status == true) { 
            toast.success(response.data.message); 
            if("MeetingsCreateDetails"==route.name){
                router.push({ name: 'MeetingsCreateAvailability' });
            }
            if("MeetingsCreateAvailability"==route.name){
                router.push({ name: 'MeetingsCreateLimits' });
            }
        }else{
            toast.error(response.data.message); 
        }
    } catch (error) {
        console.log(error);
    } 
}
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
                
                <li><router-link :to="'/meetings/single/'+ $route.params.id +'/details'" exact :class="{ 'active': $route.path === '/meetings/single/'+ $route.params.id +'/details' }"> Details</router-link></li> 
                <li><router-link :to="'/meetings/single/'+ $route.params.id +'/availability'" :class="{ 'active': $route.path === '/meetings/single/'+ $route.params.id +'/availability' }"> Availability</router-link></li>  
                <li><router-link :to="'/meetings/single/'+ $route.params.id +'/limits'" :class="{ 'active': $route.path === '/meetings/single/'+ $route.params.id +'/limits' }"> Limits</router-link></li>  
                <li><router-link :to="'/meetings/single/'+ $route.params.id +'/questions'" :class="{ 'active': $route.path === '/meetings/single/'+ $route.params.id +'/questions' }"> Questions</router-link></li>  
                <li><router-link :to="'/meetings/single/'+ $route.params.id +'/notifications'" :class="{ 'active': $route.path === '/meetings/single/'+ $route.params.id +'/notifications' }"> Notifications</router-link></li>  
                <li><router-link :to="'/meetings/single/'+ $route.params.id +'/questions'" :class="{ 'active': $route.path === '/meetings/single/'+ $route.params.id +'/questions' }"> Payment</router-link></li>  

            </ul>  
        </nav>

        <div class="tfhb-hydra-dasboard-content"> 
            <router-view 
            :meetingId ="meetingId" 
            :meeting="meetingData" 
            @add-more-location="addMoreLocations" 
            @update-meeting="UpdateMeetingData" 
            @availability-time="addAvailabilityTime"
            @availability-time-del="removeAvailabilityTime"
            @availability-date="addAvailabilityDate"
            @availability-date-del="removeAvailabilityTDate"
            @availability-tabs="AvailabilityTabs"
            />
        </div> 
    </div>
</template>

<style scoped>

</style>