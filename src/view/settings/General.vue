<script setup> 
// Use children routes for the tabs 
import { ref, reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView,} from 'vue-router' 
import axios from 'axios' 
import Icon from '@/components/icon/LucideIcon.vue'


// import Form Field 
import HbSelect from '@/components/form-fields/HbSelect.vue'

const generalSettings = reactive({
  time_zone: '',
  time_format: '',
  week_start_from: '',
  date_format: '',
  country: '',
  after_booking_completed: '',
  booking_status: '',
  allowed_reschedule_before_meeting_start: '', 
});
//  Load Time Zone
const timeZone = reactive({});
const  countryList = reactive({});
const router = useRouter(); 
const skeleton = ref(true);

// Fetch generalSettings
const fetchGeneralSettings = async () => {

    const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/general');

    try {
        if (response.data.status) {

            timeZone.value = response.data.time_zone; 
            countryList.value = response.data.country_list; 
            console.log(response.data); 
            skeleton.value = false;
        }
    } catch (error) {
        console.log(error);
    }
 
 
  
}

onBeforeMount(() => { 
    fetchGeneralSettings();
});


</script>
<template>
    
    <div :class="{ 'tfhb-skeleton': skeleton }" class="thb-event-dashboard ">
  
        <div  class="tfhb-dashboard-heading ">
            <div class="tfhb-admin-title"> 
                <h1 >{{ $tfhb_trans['General Settings'] }}</h1> 
                <p>{{ $tfhb_trans['Manage your time zone settings and bookings'] }}</p>
            </div>
            <div class="thb-admin-btn right"> 
                <a href="#" target="_blank" class="tfhb-btn"> {{ $tfhb_trans['View Documentation'] }}<Icon name="ArrowUpRight" size="15px" /></a>
            </div> 
        </div>
        <div class="tfhb-content-wrap">
          
            <!-- Date And Time --> 
            <div class="tfhb-admin-title" >
                <h2>{{ $tfhb_trans['Date and Time'] }}</h2> 
                <p>{{ $tfhb_trans['Date and Time Settings'] }}</p>
            </div>
            <div class="tfhb-admin-card-box tfhb-flexbox">  
                <!-- Time Zone -->
                <HbSelect 
                    
                    v-model="generalSettings.time_zone"  
                    required= "true"  
                    :label="$tfhb_trans['Time zone']" 
                    width="50"
                    selected = "1"
                    placeholder="Select Time Zone"  
                    :option = "timeZone.value" 
                /> 
                <!-- Time Zone -->

                <!-- Time format -->
                <HbSelect 
                    
                    v-model="generalSettings.time_format"  
                    required= "true" 
                    :label="$tfhb_trans['Time format']"  
                    width="50"
                    :selected = "1"
                    placeholder="Select Time Format"  
                    :option = "{'12_hours': '12 hours', '24_hours': '24 hours'}" 
                />
                <!-- Time format --> 
                <!-- Week start from -->
                <HbSelect 
                    
                    v-model="generalSettings.week_start_from"  
                    required= "true"  
                    :label="$tfhb_trans['Week start from']"   
                    width="50"
                    selected = "1"
                    placeholder="Select Time Format"  
                    :option = "['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']"
                    
                />
                <!-- Week start from -->
                
                <!-- Date Format -->
                <HbSelect 
                    
                    v-model="generalSettings.date_format"  
                    required= "true" 
                    :label="$tfhb_trans['Date format']"   
                    width="50"
                    selected = "1"
                    placeholder="Select Date Format"  
                    :option = "{
                        'g:i a': 'g:i a', 
                        'g:i a': 'g:i a'
                    }" 
                />
                <!-- Date Format -->

                <!-- Select countr -->
                <HbSelect 
                    
                    v-model="generalSettings.country"  
                    required= "true" 
                    :label="$tfhb_trans['Select country for phone code']"   
                    selected = "1"
                    placeholder="Select Country"  
                    :option = "countryList.value"
                />
                <!-- Select countr --> 
            </div>  
            <!-- Date And Time -->

             <!--Bookings --> 
             <div  class="tfhb-admin-title">
                <h2>{{ $tfhb_trans['Bookings'] }}</h2> 
                <p>{{ $tfhb_trans['Manage your bookings and reservations'] }}</p>
            </div>
            <div class="tfhb-admin-card-box tfhb-flexbox">  
                <!-- Bookings will be completed automatically after -->
                <HbSelect 
                    
                    v-model="generalSettings.after_booking_completed"  
                    required= "true" 
                    :label="$tfhb_trans['Bookings will be completed automatically after']"    
                    width="50"
                    selected = "1"
                    placeholder="Select Time"  
                    :option = "{
                        '5': '5 Minutes',  
                        '10': '10 Minutes',   
                        '20': '20 Minutes',  
                        '30': '30 Minutes',  
                        '40': '40 Minutes',
                        '50': '50 Minutes',
                        '60': '1 Hour',
                    }"  
                /> 
                <!-- Bookings will be completed automatically after -->

                <!-- Default status of bookings -->
                <HbSelect 
                    
                    v-model="generalSettings.booking_status"  
                    required= "true"  
                    :label="$tfhb_trans['Default status of bookings']" 
                    width="50"
                    selected = "1"
                    placeholder="Select status"  
                    :option = "{
                        'pending': 'Pending',  
                        'published': 'Published',   
                        'draft': 'Draft',   
                    }"  
                />
                <!-- Default status of bookings --> 
                
                <!-- Minimum time required before Booking/Cancel/Reschedule -->
                <HbSelect 
                    
                    v-model="generalSettings.allowed_reschedule_before_meeting_start"  
                    required= "true" 
                    :label="$tfhb_trans['Minimum time required before Booking/Cancel/Reschedule']"  
                    selected = "1"
                    placeholder="Select Time"  
                    :option = "{
                        '5': '5 Minutes',  
                        '10': '10 Minutes',   
                        '20': '20 Minutes',  
                        '30': '30 Minutes',  
                        '40': '40 Minutes',
                        '50': '50 Minutes',
                        '60': '1 Hour',
                    }" 
                />
                <!-- Minimum time required before Booking/Cancel/Reschedule -->
                 
            </div>  
            <!--Bookings -->


        </div>
    </div>
 
</template>