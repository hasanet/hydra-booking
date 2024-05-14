<script setup>
import { ref, reactive, onBeforeMount, } from 'vue'; 
import { useRouter, RouterView,} from 'vue-router' 
import axios from 'axios' 
import Icon from '@/components/icon/LucideIcon.vue'
import HbText from '../form-fields/HbText.vue'; 
import HbDateTime from '../form-fields/HbDateTime.vue';
import HbCheckbox from '@/components/form-fields/HbCheckbox.vue';
import HbDropdown from '@/components/form-fields/HbDropdown.vue'
import { toast } from "vue3-toastify"; 
 

const props = defineProps({
  isOpen: Boolean,
  availabilityDataSingle: {},
  timeZone: {}, 
  is_host: Boolean,
});
const emit = defineEmits(["update:availabilityData", "modal-close", "update-availability"]); 

 
 
// Update Availability Settings
const UpdateAvailabilitySettings = async () => {   
    try { 
        if(props.is_host){
            const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/availability/update', props.availabilityDataSingle, {
                headers: {
                    'X-WP-Nonce': tfhb_core_apps.rest_nonce,
                    'capability': 'tfhb_manage_custom_availability'
                } 
            } );
            if (response.data.status) {    
            
                // close the popup
                emit('modal-close');
                emit('update-availability', response.data.availability);
                toast.success(response.data.message, {
                    position: 'bottom-right', // Set the desired position
                    "autoClose": 1500,
                }); 
            }
        }else{
            const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/availability/update', props.availabilityDataSingle, {
                headers: {
                    'X-WP-Nonce': tfhb_core_apps.rest_nonce
                } 
            } );
            if (response.data.status) {    
            
                // close the popup
                emit('modal-close');
                emit('update-availability', response.data.availability);
                toast.success(response.data.message, {
                    position: 'bottom-right', // Set the desired position
                    "autoClose": 1500,
                }); 
            }
        }
        
    } catch (error) {
        toast.error(error.message, {
            position: 'bottom-right', // Set the desired position
        });
    }
}

 
// Remove time slot
const removeAvailabilityTime = (key, tkey = null) => {
    props.availabilityDataSingle.time_slots[key].times.splice(tkey, 1);
}
// Add new time slot
const addAvailabilityTime = (key) => {
    props.availabilityDataSingle.time_slots[key].times.push({
        start: '09:00',
        end: '17:00',
    });
}

// Overrides Calander Open
const OverridesOpen = ref(false);
const OverridesDates = reactive({
    
})

//add Overrides Time
const addOverridesTime = (key) => {
    OverridesDates.times.push({
        start: '09:00',
        end: '17:00',
    });
}

// Remove Overrides time slot
const removeOverridesTime = (key, tkey = null) => {
    OverridesDates.times.splice(tkey, 1);
}

const openOverridesCalendarDate = () => {
    props.availabilityDataSingle.date_slots.push({
        date: '',
        available: '',
        times: [
            {
                start: '09:00',
                end: '17:00',
            }
        ]
    });

    const lastIndexOfQuestion = props.availabilityDataSingle.date_slots.length - 1;
    OverridesDates.key = lastIndexOfQuestion;
    OverridesDates.date = '';
    OverridesDates.available = '';
    OverridesDates.times = [
        {
            start: '09:00',
            end: '17:00',
        }
    ];

    OverridesOpen.value = true;
}

// Remove date slot 
const removeAvailabilityTDate = (key) => {
    props.availabilityDataSingle.date_slots.splice(key, 1);
}

// Store to the reactive
const addAvailabilityDate = (key) => {

    props.availabilityDataSingle.date_slots[OverridesDates.key].date = OverridesDates.date
    props.availabilityDataSingle.date_slots[OverridesDates.key].available = OverridesDates.available
    props.availabilityDataSingle.date_slots[OverridesDates.key].times = OverridesDates.times

    OverridesOpen.value = false;
}

const editAvailabilityDate = (key) => {
    props.availabilityDataSingle.date_slots.forEach((available, qkey) => {
        if (qkey === key) {
            OverridesDates.key = key;
            OverridesDates.date = available.date;
            OverridesDates.available = available.available;
            OverridesDates.times = available.times;
            
            OverridesOpen.value = true;
        }
    });
}
// Date & Time Format
function formatTimeSlots(timeSlots) {
    return timeSlots.map(slot => {
    return `${this.formatTime(slot.start)} - ${this.formatTime(slot.end)}`
    }).join(', ');
}

function formatTime(time) {
    const [hour, minute] = time.split(':');
    const formattedHour = (parseInt(hour) % 12 || 12);
    const period = parseInt(hour) < 12 ? 'AM' : 'PM';
    return `${formattedHour}:${minute} ${period}`;
}
  
</script>
 

<template> 
   <div v-if="isOpen" class="tfhb-popup tfhb-availability-popup">
        <div class="tfhb-popup-wrap tfhb-availability-popup-wrap">
            <div  class="tfhb-dashboard-heading ">
                <div class="tfhb-admin-title"> 
                    <h2 >Add New Availability  </h2>    
                </div>
                <div class="thb-admin-btn"> 
                    <button class="tfhb-popup-close" @click.stop="emit('modal-close')"><Icon name="X" size="20px" /> </button> 
                </div> 
            </div>
            <div class="tfhb-content-wrap tfhb-flexbox"> 
                <!-- Title -->
                <HbText  
                    v-model="props.availabilityDataSingle.title"  
                    required= "true"  
                    :label="$tfhb_trans['Title']"  
                    selected = "1"
                    placeholder="Type your schedule title"   
                /> 
                <!-- Title -->
                <!-- Time Zone -->
                <HbDropdown 
                        
                    v-model="props.availabilityDataSingle.time_zone"  
                    required= "true"  
                    :label="$tfhb_trans['Time zone']"  
                    selected = "1"
                    :filter="true"
                    placeholder="Select Time Zone"  
                    :option = "props.timeZone" 
                /> 
                <!-- Time Zone --> 
            </div>
            <div class="tfhb-content-wrap tfhb-availability-content-wrap">  

                <div class="tfhb-admin-card-box ">  
                    <div  class="tfhb-dashboard-heading ">
                        <div class="tfhb-admin-title"> 
                            <h3 >Weekly hours </h3>  
                        </div>
                        <div class="thb-admin-btn right"> 
                            <span>{{props.availabilityDataSingle.time_zone}}</span> 
                        </div> 
                    </div>
                    <div v-for="(time_slot, key) in props.availabilityDataSingle.time_slots" :key="key" class="tfhb-availability-schedule-single tfhb-flexbox tfhb-align-baseline">
                        <div class="tfhb-swicher-wrap  tfhb-flexbox">
                            <!-- Checkbox swicher -->
                            <label class="switch">
                                <input id="swicher" v-model="time_slot.status" true-value="1"    type="checkbox">
                                <span class="slider"></span>
                            </label>
                            <label class="tfhb-schedule-swicher" for="swicher"> {{time_slot.day}}</label>
                            <!-- Swicher -->
                        </div>
                        <div v-if="time_slot.status == 1" class="tfhb-availability-schedule-wrap "> 
                            <div v-for="(time, tkey) in time_slot.times" :key="tkey"  class="tfhb-availability-schedule-inner tfhb-flexbox">
                                <div class="tfhb-availability-schedule-time tfhb-flexbox">
                                    <HbDateTime  
                                        v-model="time.start"    
                                        selected = "1" 
                                        :config="{
                                            enableTime: true,
                                            noCalendar: true,
                                            dateFormat: 'H:i',
                                            defaultDate: time.start
                                        }"
                                        width="45"
                                        placeholder="Type your schedule title"   
                                        icon="Clock4"
                                    /> 
                                    <Icon name="MoveRight" size="20" /> 
                                    <HbDateTime  
                                        v-model="time.end"   
                                        :label="$tfhb_trans['End']"  
                                        selected = "1"
                                        :config="{
                                            enableTime: true,
                                            noCalendar: true,
                                            dateFormat: 'H:i',
                                            defaultDate: time.end
                                        }"
                                        width="45"
                                        placeholder="Type your schedule title"   
                                        icon="Clock4"
                                    /> 

                                </div>
                                <div v-if="tkey == 0" class="tfhb-availability-schedule-clone-single">
                                    <button class="tfhb-availability-schedule-btn" @click="addAvailabilityTime(key)"><Icon name="Plus" size="20px" /> </button> 
                                </div>
                                <div v-else class="tfhb-availability-schedule-clone-single">
                                    <button class="tfhb-availability-schedule-btn" @click="removeAvailabilityTime(key, tkey)"><Icon name="X" size="20px" /> </button> 
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    

                    <!-- Date Overrides -->
                    <div class="tfhb-admin-card-box tfhb-m-0 tfhb-flexbox">  

                        <div  class="tfhb-dashboard-heading tfhb-full-width" :style="{margin: '0 !important'}">
                            <div class="tfhb-admin-title"> 
                                <h3>Add date overrides </h3>  
                                <p>Add dates when your availability changes from your daily hours</p>
                            </div> 
                        </div>

                        <div class="tfhb-admin-card-box tfhb-m-0 tfhb-full-width" v-for="(date_slot, key) in props.availabilityDataSingle.date_slots" :key="key">
                            <div class="tfhb-flexbox">
                                <div class="tfhb-overrides-date">
                                    <h4>{{ date_slot.date }}</h4>
                                    <p class="tfhb-m-0">{{ date_slot.unavailable!=1 ? formatTimeSlots(date_slot.times) : 'Unavailable' }}</p>
                                </div>
                                <div class="tfhb-overrides-action tfhb-flexbox tfhb-gap-16 tfhb-justify-normal">
                                    <button class="question-edit-btn" @click="editAvailabilityDate(key)">
                                        <Icon name="PencilLine" :width="16" />
                                    </button>
                                    <button class="question-edit-btn" @click="removeAvailabilityTDate(key)">
                                        <Icon name="Trash" :width="16"/>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Overrides Calendar Form -->

                        <div class="tfhb-overrides-add-form tfhb-flexbox tfhb-full-width" v-if="OverridesOpen">
                            <div class="tfhb-flexbox tfhb-align-normal">
                                <div class="tfhb-override-calendar">
                                    <HbDateTime  
                                        v-model="OverridesDates.date"
                                        selected = "1" 
                                        :config="{
                                            inline: true,
                                            monthSelectorType: 'static',
                                            yearSelectorType: 'static',
                                            mode: 'multiple',
                                            nextArrow: `<svg width='19' height='20' viewBox='0 0 19 20' fill='none' xmlns='http://www.w3.org/2000/svg'><g id='chevron-right'><path id='Vector' d='M7.5 15L12.5 10L7.5 5' stroke='#F62881' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/></g></svg>`,
                                            prevArrow: `<svg width='19' height='20' viewBox='0 0 19 20' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M11.5 15L6.5 10L11.5 5' stroke='#F62881' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/></svg>`
                                        }"
                                        placeholder="Type your schedule title"   
                                    /> 
                                </div>
                                <div class="tfhb-override-times">
                                    <h3>Which hours are you free?</h3>

                                    <div class="tfhb-availability-schedule-inner tfhb-flexbox tfhb-gap-16 tfhb-mt-16" v-for="(time, tkey) in OverridesDates.times" :key="tkey" v-if="OverridesDates.available!=1">
                                        <div class="tfhb-availability-schedule-time tfhb-flexbox tfhb-gap-16">
                                            <HbDateTime  
                                                v-model="time.start"
                                                selected = "1" 
                                                :config="{
                                                    enableTime: true,
                                                    noCalendar: true,
                                                    dateFormat: 'H:i'
                                                }"
                                                width="45"
                                                placeholder="Type your schedule title"   
                                                icon="Clock"
                                            /> 
                                            <Icon name="MoveRight" size="20" /> 
                                            <HbDateTime  
                                                v-model="time.end"
                                                :label="$tfhb_trans['End']"  
                                                selected = "1"
                                                :config="{
                                                    enableTime: true,
                                                    noCalendar: true,
                                                    dateFormat: 'H:i'
                                                }"
                                                width="45"
                                                placeholder="Type your schedule title"   
                                                icon="Clock"
                                            /> 

                                        </div>
                                        
                                        <div v-if="tkey == 0" class="tfhb-availability-schedule-clone-single">
                                            <button class="tfhb-availability-schedule-btn" @click="addOverridesTime(key)"><Icon name="Plus" size="20px" /> </button> 
                                        </div>
                                        <div v-else class="tfhb-availability-schedule-clone-single">
                                            <button class="tfhb-availability-schedule-btn" @click="removeOverridesTime(key, tkey)"><Icon name="X" size="20px" /> </button> 
                                        </div>
                                    </div>

                                    <div class="tfhb-mark-unavailable tfhb-full-width tfhb-mt-16">
                                        <HbCheckbox 
                                            v-model="OverridesDates.unavailable"
                                            :label="$tfhb_trans['Mark unavailable (All day)']"
                                            :name="'mark_unavailable'+key"
                                        />
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="tfhb-overrides-store tfhb-flexbox tfhb-gap-16 tfhb-justify-end tfhb-full-width">
                                <button class="tfhb-btn secondary-btn" @click="OverridesOpen=false">Cancel</button>
                                <button class="tfhb-btn boxed-btn" @click="addAvailabilityDate(key)">Add override</button>
                            </div>
                        </div>


                        <button class="tfhb-btn tfhb-flexbox tfhb-gap-8 tfhb-p-0 tfhb-height-auto" @click="openOverridesCalendarDate()">
                            <Icon name="PlusCircle" :width="20"/>
                            Add an override
                        </button>
                        
                    </div>  


                </div>  

                


                <!-- Create Or Update Availability -->
                <button class="tfhb-btn boxed-btn" @click="UpdateAvailabilitySettings">{{ is_host ? 'Save Availability' : $tfhb_trans['Update General Settings'] }}</button>
            </div>
        </div>
   </div>
   
</template> 
<style scoped>

</style> 
