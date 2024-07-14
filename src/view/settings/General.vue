<script setup> 
// Use children routes for the tabs 
import { ref, reactive, onBeforeMount } from 'vue';
import { useRouter } from 'vue-router' 
import axios from 'axios' 
import Icon from '@/components/icon/LucideIcon.vue'
import { toast } from "vue3-toastify";
import useValidators from '@/store/validator'
const { errors, isEmpty } = useValidators();


// import Form Field  
import HbDropdown from '@/components/form-fields/HbDropdown.vue'
import HbText from '@/components/form-fields/HbText.vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue'; 

const generalSettings = reactive({
  admin_email: '{{wp.admin_email}}',
  time_zone: '',
  time_format: '',
  week_start_from: '',
  date_format: '',
  country: '',
  after_booking_completed: '',
  booking_status: '',
  reschedule_status: '',
  allowed_reschedule_before_meeting_start: '', 
});

// Field Validator

const tfhbValidateInput = (fieldName) => {
    // Clear the errors object
    Object.keys(errors).forEach(key => {
        delete errors[key];
    });

    const fieldParts = fieldName.split('.');
    if(fieldParts[0] && !fieldParts[1]){
        isEmpty(fieldParts[0], generalSettings[fieldParts[0]]);
    }
    if(fieldParts[0] && fieldParts[1]){
        isEmpty(fieldParts[0]+'___'+[fieldParts[1]], generalSettings[fieldParts[0]][fieldParts[1]]);
    }
};

//  Load Time Zone
const timeZone = reactive({});
const  countryList = reactive({});
const router = useRouter(); 
const skeleton = ref(true);

// Fetch generalSettings
const fetchGeneralSettings = async () => {

    try { 
        const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/general');
        if (response.data.status) { 
            timeZone.value = response.data.time_zone; 
            countryList.value = response.data.country_list;  
            // Set General Settings
            generalSettings.time_zone = response.data.general_settings.time_zone;
            generalSettings.time_format = response.data.general_settings.time_format;
            generalSettings.week_start_from = response.data.general_settings.week_start_from;
            generalSettings.date_format = response.data.general_settings.date_format;
            generalSettings.country = response.data.general_settings.country;
            generalSettings.after_booking_completed = response.data.general_settings.after_booking_completed;
            generalSettings.booking_status = response.data.general_settings.booking_status;
            generalSettings.reschedule_status = response.data.general_settings.reschedule_status;
            generalSettings.allowed_reschedule_before_meeting_start = response.data.general_settings.allowed_reschedule_before_meeting_start;


            skeleton.value = false;
        }
    } catch (error) {
        console.log(error);
    } 
}
const UpdateGeneralSettings = async () => { 

    // Clear the errors object
    Object.keys(errors).forEach(key => {
        delete errors[key];
    });
    
    // Errors Added
    let validator_field = ['admin_email', 'time_zone', 'time_format', 'week_start_from', 'country']
    if(validator_field){
        validator_field.forEach(field => {

        const fieldParts = field.split('___'); // Split the field into parts
        if(fieldParts[0] && !fieldParts[1]){
            if(!generalSettings[fieldParts[0]]){
                errors[fieldParts[0]] = 'Required this field';
            }
        }
        if(fieldParts[0] && fieldParts[1]){
            if(!generalSettings[fieldParts[0]][fieldParts[1]]){
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

    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/general/update', generalSettings, {
             
        } );
      
        if (response.data.status) {    
            toast.success(response.data.message, {
                position: 'bottom-right', // Set the desired position
                "autoClose": 1500,
            }); 
            
        }
    } catch (error) {
        toast.error('Action successful', {
            position: 'bottom-right', // Set the desired position
        });
    }
}
onBeforeMount(() => { 
    fetchGeneralSettings();
});


</script>
<template>
    
    <div :class="{ 'tfhb-skeleton': skeleton }" class="thb-event-dashboard">
  
        <div  class="tfhb-dashboard-heading ">
            <div class="tfhb-admin-title tfhb-m-0"> 
                <h1 >{{ $tfhb_trans['General Settings'] }}</h1> 
                <p>{{ $tfhb_trans['Manage your time zone settings and bookings'] }}</p>
            </div>
            <div class="thb-admin-btn right"> 
                <a href="#" target="_blank" class="tfhb-btn"> {{ $tfhb_trans['View Documentation'] }}<Icon name="ArrowUpRight" size="15" /></a>
            </div> 
        </div>
        <div class="tfhb-content-wrap">
          
            <!-- Date And Time --> 
            <div class="tfhb-admin-title" >
                <h2>{{ $tfhb_trans['Date and Time'] }}</h2> 
                <p>{{ $tfhb_trans['Date and Time Settings'] }}</p>
            </div>
            <div class="tfhb-admin-card-box tfhb-general-card tfhb-flexbox tfhb-gap-tb-24">  

                <!-- Time Zone -->
                <HbText  
                    v-model="generalSettings.admin_email"  
                    required= "true"  
                    :label="$tfhb_trans['Admin Email']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Type your Admin Email']" 
                    width="50"
                    @keyup="() => tfhbValidateInput('admin_email')"
                    @click="() => tfhbValidateInput('admin_email')"
                    :errors="errors.admin_email"
                /> 

                <!-- Time Zone --> 
                <HbDropdown
                    
                    v-model="generalSettings.time_zone"  
                    required= "true"  
                    :label="$tfhb_trans['Time zone']" 
                    width="50" 
                    :filter="true"
                    selected = "1"
                    placeholder="Select Time Zone"  
                    :option = "timeZone.value" 
                    @add-change="tfhbValidateInput('time_zone')" 
                    @add-click="tfhbValidateInput('time_zone')" 
                    :errors="errors.time_zone"
                />  
                <!-- Time Zone -->

                <!-- Time format -->
                <HbDropdown 
                    
                    v-model="generalSettings.time_format"  
                    required= "true" 
                    :label="$tfhb_trans['Time format']"  
                    width="50"
                    :selected = "1"
                    placeholder="Select Time Format"   
                    :option = "[
                        {'name': '12 Hours', 'value': '12'}, 
                        {'name': '24 Hours', 'value': '24'}
                    ]"
                    @add-change="tfhbValidateInput('time_format')" 
                    @add-click="tfhbValidateInput('time_format')" 
                    :errors="errors.time_format"
                />
                <!-- Time format --> 
                <!-- Week start from -->
                <HbDropdown 
                    
                    v-model="generalSettings.week_start_from"  
                    required= "true"  
                    :label="$tfhb_trans['Week start from']"   
                    width="50"
                    selected = "1"
                    placeholder="Select Time Format"  
                    :option = "[
                        {'name': 'Sunday', 'value': 'Sunday'}, 
                        {'name': 'Monday', 'value': 'Monday'},
                        {'name': 'Tuesday', 'value': 'Tuesday'},
                        {'name': 'Wednesday', 'value': 'Wednesday'},
                        {'name': 'Thursday', 'value': 'Thursday'},
                        {'name': 'Friday', 'value': 'Friday'},
                        {'name': 'Saturday', 'value': 'Saturday'}
                    ]"
                    @add-change="tfhbValidateInput('week_start_from')" 
                    @add-click="tfhbValidateInput('week_start_from')" 
                    :errors="errors.week_start_from"
                    
                />
                <!-- Week start from -->
                
                <!-- Date Format -->
                <!-- <HbDropdown 
                    
                    v-model="generalSettings.date_format"  
                    required= "true" 
                    :label="$tfhb_trans['Date format']"   
                    width="50"
                    selected = "1"
                    placeholder="Select Date Format"   
                    :option = "[
                        {'name': 'g:i a', 'value': 'g:i a'},  
                    ]"
                    @add-change="tfhbValidateInput('date_format')" 
                    @add-click="tfhbValidateInput('date_format')" 
                    :errors="errors.date_format"
                /> -->
                <!-- Date Format -->

                <!-- Select countr -->
                <HbDropdown 
                    
                    v-model="generalSettings.country" 
                    required= "true"
                    width="50"
                    :filter="true" 
                    :label="$tfhb_trans['Select country for phone code']"   
                    selected = "1"
                    placeholder="Select Country"  
                    :option = "countryList.value"
                    @add-change="tfhbValidateInput('country')" 
                    @add-click="tfhbValidateInput('country')" 
                    :errors="errors.country"
                />
                <!-- Select countr --> 
            </div>  
            <!-- Date And Time -->

             <!--Bookings --> 
             <div  class="tfhb-admin-title">
                <h2>{{ $tfhb_trans['Bookings'] }}</h2> 
                <p>{{ $tfhb_trans['Manage your bookings and reservations'] }}</p>
            </div>
            <div class="tfhb-admin-card-box tfhb-general-card tfhb-flexbox tfhb-gap-tb-24">  
                <!-- Bookings will be completed automatically after -->
                <HbDropdown 
                    
                    v-model="generalSettings.after_booking_completed"  
                    required= "true" 
                    :label="$tfhb_trans['Bookings will be completed automatically after']"    
                    width="50"
                    selected = "1"
                    placeholder="Select Time"  
                    :option = "[
                        {'name': '5 Minutes', 'value': '5'},  
                        {'name': '10 Minutes', 'value': '10'},   
                        {'name': '20 Minutes', 'value': '20'},  
                        {'name': '30 Minutes', 'value': '30'},  
                        {'name': '40 Minutes', 'value': '40'},
                        {'name': '50 Minutes', 'value': '50'},
                        {'name': '1 Hour', 'value': '60'}
                    ]" 
                /> 
                <!-- Bookings will be completed automatically after -->

               
                
                <!-- Minimum time required before Booking/Cancel/Reschedule -->
                <HbDropdown 
                    
                    v-model="generalSettings.allowed_reschedule_before_meeting_start"  
                    required= "true" 
                    :label="$tfhb_trans['Minimum time required before Booking/Cancel/Reschedule']"  
                    selected = "1"
                    width="50"
                    placeholder="Select Time"  
                    :option = "[
                        {'name': '5 Minutes', 'value': '5'},  
                        {'name': '10 Minutes', 'value': '10'},   
                        {'name': '20 Minutes', 'value': '20'},  
                        {'name': '30 Minutes', 'value': '30'},  
                        {'name': '40 Minutes', 'value': '40'},
                        {'name': '50 Minutes', 'value': '50'},
                        {'name': '1 Hour', 'value': '60'}
                    ]" 
                />
                <!-- Minimum time required before Booking/Cancel/Reschedule -->

                 <!-- Default status of bookings Approved if checkbox is checked --> 

                <HbSwitch 
                    v-model="generalSettings.booking_status"
                    width="100"
                    :label="$tfhb_trans['Confirmed bookings by default.']"  
                />
               
                <!-- Default status of bookings --> 

                 <!-- Default status of bookings -->
                 <HbSwitch 
                    v-model="generalSettings.reschedule_status"
                    width="100"
                    :label="$tfhb_trans[ 'Confirmed reschedule by default.']"  
                />
                <!-- Default status of bookings --> 
                 
            </div>  

            <button class="tfhb-btn boxed-btn" @click="UpdateGeneralSettings">{{ $tfhb_trans['Update General Settings'] }}</button>
            <!--Bookings -->


        </div>
    </div>
 
</template>