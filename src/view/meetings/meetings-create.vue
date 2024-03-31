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
});

function addMoreLocations(){
    meetingData.meeting_locations.push({
    location:'',
    address:'',
  })
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
            <router-view :meetingId ="meetingId" :meeting="meetingData" @add-more-location="addMoreLocations" @update-meeting="UpdateMeetingData" />
        </div> 
    </div>
</template>

<style scoped>

</style>