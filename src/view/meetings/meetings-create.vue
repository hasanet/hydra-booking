<script setup>
import { reactive, onBeforeMount, ref } from 'vue';
import Icon from '@/components/icon/LucideIcon.vue'
import { useRouter, useRoute, RouterView } from 'vue-router' 
import axios from 'axios'  
import { toast } from "vue3-toastify"; 
import useValidators from '@/store/validator'
import { Availability } from '@/store/availability';
const { errors } = useValidators();
const error = reactive({})

const route = useRoute();
const router = useRouter();
const skeleton = ref(true);
const timeZone = reactive({});
const meetingCategory = reactive({});
const wcProduct = reactive({});

const meetingData = reactive({
    id: 0,
    user_id: 0,
    host_id: '',
    post_id: '',
    title: '',
    description: '',
    meeting_type: '',
    duration: '',
    custom_duration: 5,
    meeting_locations: [
        {
        location:'',
        address:''
        }
    ],
    meeting_category: '',
    availability_range_type: 'indefinitely',
    availability_range: {
        start: '',
        end: ''
    },
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
    recurring_maximum: '',
    attendee_can_cancel: 1,
    attendee_can_reschedule: 1,
    questions_status: 1,
    questions: [
        {
            label: 'name',
            type:'Text',
            placeholder:'Name',
            options: [],
            required: 1
        },
        {
            label: 'email',
            type:'Email',
            options: [],
            placeholder:'Email',
            options: [],
            required: 1
        },
        {
            label: 'address',
            type:'Text', 
            placeholder:'Address',
            options: [],
            required: 1
        }
    ],
    notification: {
        host: {
            booking_confirmation: {
                status : 0,
                template : 'default',
                form : '',
                subject : '',
                body : '',

            },
            booking_cancel: {
                status : 0,
                template : 'default',
                form : '',
                subject : '',
                body : '',

            },
            booking_reschedule: {
                status : 0,
                template : 'default',
                form : '',
                subject : '',
                body : '',

            },
            booking_reminder: {
                status : 0,
                template : 'default',
                form : '',
                subject : '',
                body : '',

            },
        },
        attendee : {
            booking_confirmation: {
                status : 0,
                template : 'default',
                form : '',
                subject : '',
                body : '',
            },
            booking_cancel: {
                status : 0,
                template : 'default',
                form : '',
                subject : '',
                body : '',

            },
            booking_reschedule: {
                status : 0,
                template : 'default',
                form : '',
                subject : '',
                body : '',

            },
            booking_reminder: {
                status : 0,
                template : 'default',
                form : '',
                subject : '',
                body : '',

            },
        }
    },
    payment_status: 1,
    meeting_price: '',
    payment_currency: '',
    payment_method: '',
    payment_meta: {
        product_id: '',
    }
});

// Add more Location
function addMoreLocations(){
    meetingData.meeting_locations.push({
    location:'',
    address:'',
  })
}
// Remove Location
const removeLocations = (key) => {
    meetingData.meeting_locations.splice(key, 1);
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
        date: '',
        available: '',
        times: [
            {
                start: '09:00',
                end: '17:00',
            }
        ]
    });
}

// Remove date slot 
const removeAvailabilityTDate = (key) => {
    meetingData.availability_custom.date_slots.splice(key, 1);
}

//add Overrides Time
const addOverridesTime = (key) => {
    meetingData.availability_custom.date_slots[key].times.push({
        start: '09:00',
        end: '17:00',
    });
}

// Remove Overrides time slot
const removeOverridesTime = (key, tkey = null) => {
    meetingData.availability_custom.date_slots[key].times.splice(tkey, 1);
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
            // Time Zone = 
            timeZone.value = response.data.time_zone;  

            wcProduct.value = response.data.wc_product;  
            meetingCategory.value = response.data.meeting_category;

            meetingData.id = response.data.meeting.id
            meetingData.user_id = response.data.meeting.user_id
            meetingData.host_id = response.data.meeting.host_id && response.data.meeting.host_id!=0 ? response.data.meeting.host_id : '';
            meetingData.post_id = response.data.meeting.post_id
            meetingData.title = response.data.meeting.title
            meetingData.description = response.data.meeting.description
            meetingData.meeting_type = response.data.meeting.meeting_type
            meetingData.duration = response.data.meeting.duration
            meetingData.custom_duration = response.data.meeting.custom_duration
            meetingData.meeting_category = response.data.meeting.meeting_category
            meetingData.payment_method = response.data.meeting.payment_method

            if(response.data.meeting.meeting_locations){
                meetingData.meeting_locations = JSON.parse(response.data.meeting.meeting_locations)
            }

            meetingData.availability_range_type = response.data.meeting.availability_range_type ? response.data.meeting.availability_range_type : 'indefinitely'

            meetingData.availability_range = response.data.meeting.availability_range ? JSON.parse(response.data.meeting.availability_range) : {}
           
            if(response.data.meeting.availability_custom){
                 
                meetingData.availability_custom = JSON.parse(response.data.meeting.availability_custom)
             
                
            } 
            meetingData.availability_custom.time_zone = Availability.GeneralSettings.time_zone ? Availability.GeneralSettings.time_zone : '';

            meetingData.availability_custom.time_slots = Availability.GeneralSettings.week_start_from ?  Availability.RearraingeWeekStart(Availability.GeneralSettings.week_start_from, meetingData.availability_custom.time_slots) : meetingData.availability_custom.time_slots;
            
            if(response.data.meeting.availability_type){
                meetingData.availability_type = response.data.meeting.availability_type
            }
            meetingData.availability_id = response.data.meeting.availability_id
            meetingData.buffer_time_before = response.data.meeting.buffer_time_before
            meetingData.buffer_time_after = response.data.meeting.buffer_time_after
            meetingData.meeting_interval = response.data.meeting.meeting_interval
            if(response.data.meeting.recurring_status){
                meetingData.recurring_status = response.data.meeting.recurring_status
            }
            meetingData.recurring_maximum = response.data.meeting.recurring_maximum

            if(response.data.meeting.recurring_repeat){
                meetingData.recurring_repeat = JSON.parse(response.data.meeting.recurring_repeat)
            }
            if(response.data.meeting.booking_frequency){
                meetingData.booking_frequency = JSON.parse(response.data.meeting.booking_frequency)
            }

            meetingData.attendee_can_cancel = response.data.meeting.attendee_can_cancel
            meetingData.attendee_can_reschedule = response.data.meeting.attendee_can_reschedule

            if(response.data.meeting.questions_status){
                meetingData.questions_status = response.data.meeting.questions_status
            }

            if(response.data.meeting.questions){
                meetingData.questions = JSON.parse(response.data.meeting.questions)
            }
            if(response.data.meeting.notification && "string" == typeof response.data.meeting.notification){
                meetingData.notification = JSON.parse(response.data.meeting.notification)
            }
            if(response.data.meeting.notification && "object" == typeof response.data.meeting.notification){
                meetingData.notification = response.data.meeting.notification
            }
            if(response.data.meeting.payment_status){
                meetingData.payment_status = response.data.meeting.payment_status
            }
            meetingData.meeting_price = response.data.meeting.meeting_price
            meetingData.payment_currency = response.data.meeting.payment_currency

            if(response.data.meeting.payment_meta && "string" == typeof response.data.meeting.payment_meta){
                meetingData.payment_meta = JSON.parse(response.data.meeting.payment_meta)
            }
            if(response.data.meeting.payment_meta && "object" == typeof response.data.meeting.payment_meta){
                meetingData.payment_meta = response.data.meeting.payment_meta
            }

            skeleton.value = false
        }else{ 
            router.push({ name: 'MeetingsLists' });
        }
    } catch (error) {
        console.log(error);
    } 
} 

onBeforeMount(() => { 

    fetchMeeting();
    Availability.getGeneralSettings();
});


const UpdateMeetingData = async (validator_field) => {
    
    // Errors Added
    if(validator_field){
        validator_field.forEach(field => {

        const fieldParts = field.split('___'); // Split the field into parts
        if(fieldParts[0] && !fieldParts[1]){
            if(!meetingData[fieldParts[0]]){
                errors[fieldParts[0]] = 'Required this field';
            }
        }
        if(fieldParts[0] && fieldParts[1]){
            if(!meetingData[fieldParts[0]][fieldParts[1]]){
                errors[fieldParts[0]+'___'+[fieldParts[1]]] = 'Required this field';
            }
        }
            
        });
    }

    // Errors Checked
    const isEmpty = Object.keys(errors).length === 0;
    if(!isEmpty){
        toast.error('Fill Up The Required Fields'); 
        return
    }

    // Api Submission
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
            if("MeetingsCreateLimits"==route.name){
                router.push({ name: 'MeetingsCreateQuestions' });
            }
            if("MeetingsCreateQuestions"==route.name){
                router.push({ name: 'MeetingsCreateNotifications' });
            }
            if("MeetingsCreateNotifications"==route.name){
                router.push({ name: 'MeetingsCreatePayment' });
            }
            if("MeetingsCreatePayment"==route.name){
                router.push({ name: 'MeetingsLists' });
            }
        }else{
            toast.error(response.data.message); 
        }
    } catch (error) {
        console.log(error);
    } 
}

const TfhbPrevNavigator = () => {
    if("MeetingsCreateDetails"==route.name){
        router.push({ name: 'MeetingsLists' });
    }
    if("MeetingsCreateAvailability"==route.name){
        router.push({ name: 'MeetingsCreateDetails' });
    }
    if("MeetingsCreateLimits"==route.name){
        router.push({ name: 'MeetingsCreateAvailability' });
    }
    if("MeetingsCreateQuestions"==route.name){
        router.push({ name: 'MeetingsCreateLimits' });
    }
    if("MeetingsCreateNotifications"==route.name){
        router.push({ name: 'MeetingsCreateQuestions' });
    }
    if("MeetingsCreatePayment"==route.name){
        router.push({ name: 'MeetingsCreateNotifications' });
    }
}
</script>

<template>
    <div class="tfhb-meeting-create" :class="{ 'tfhb-skeleton': skeleton }">
        <div class="tfhb-meeting-create-notice tfhb-mb-32">
            <div class="tfhb-meeting-heading tfhb-flexbox">
                <div class="prev-navigator" @click="TfhbPrevNavigator()">
                    <Icon name="ArrowLeft" size="20" /> 
                </div>
                <h3>{{ $tfhb_trans['Create One-to-One booking type'] }}</h3>
            </div>
            <div class="tfhb-meeting-subtitle">
                {{ $tfhb_trans['Create and manage booking/appointment form'] }}
            </div>
        </div>
        <nav class="tfhb-booking-tabs tfhb-meeting-tabs tfhb-mb-32"> 
            <ul>
                <!-- to route example like meetings/create/13/details -->
                
                <li><router-link :to="'/meetings/single/'+ $route.params.id +'/details'" exact :class="{ 'active': $route.path === '/meetings/single/'+ $route.params.id +'/details' }">{{ $tfhb_trans['Details'] }}</router-link></li> 
                <li><router-link :to="'/meetings/single/'+ $route.params.id +'/availability'" :class="{ 'active': $route.path === '/meetings/single/'+ $route.params.id +'/availability' }">{{ $tfhb_trans['Availability'] }}</router-link></li>  
                <li><router-link :to="'/meetings/single/'+ $route.params.id +'/limits'" :class="{ 'active': $route.path === '/meetings/single/'+ $route.params.id +'/limits' }">{{ $tfhb_trans['Limits'] }}</router-link></li>  
                <li><router-link :to="'/meetings/single/'+ $route.params.id +'/questions'" :class="{ 'active': $route.path === '/meetings/single/'+ $route.params.id +'/questions' }"> {{ $tfhb_trans['Questions'] }}</router-link></li>  
                <li><router-link :to="'/meetings/single/'+ $route.params.id +'/notifications'" :class="{ 'active': $route.path === '/meetings/single/'+ $route.params.id +'/notifications' }"> {{ $tfhb_trans['Notifications'] }}</router-link></li>  
                <li><router-link :to="'/meetings/single/'+ $route.params.id +'/payment'" :class="{ 'active': $route.path === '/meetings/single/'+ $route.params.id +'/payment' }">{{ $tfhb_trans['Payment'] }}</router-link></li>  

            </ul>  
        </nav>

        <div class="tfhb-hydra-dasboard-content"> 
            <router-view 
            :meetingId ="meetingId" 
            :meeting="meetingData" 
            :timeZone="timeZone.value" 
            :wcProduct="wcProduct.value" 
            :meetingCategory="meetingCategory.value" 
            @add-more-location="addMoreLocations" 
            @remove-meeting-location="removeLocations" 
            @update-meeting="UpdateMeetingData" 
            @availability-time="addAvailabilityTime"
            @availability-time-del="removeAvailabilityTime"
            @availability-date="addAvailabilityDate"
            @availability-date-del="removeAvailabilityTDate"
            @availability-tabs="AvailabilityTabs"
            @add-overrides-time="addOverridesTime"
            @remove-overrides-time="removeOverridesTime"
            />
        </div> 
    </div>
</template>

<style scoped>

</style>