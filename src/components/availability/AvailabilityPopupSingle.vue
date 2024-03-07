<script setup>
import { ref, reactive, onBeforeMount, } from 'vue'; 
import { useRouter, RouterView,} from 'vue-router' 
import Icon from '@/components/icon/LucideIcon.vue'
import HbText from '../form-fields/HbText.vue';
import HbSelect from '../form-fields/HbSelect.vue';
import HbDateTime from '../form-fields/HbDateTime.vue';


const timeZone = reactive({}); 
const availabilityData = reactive({
    id: 1,
    title: 'Title',
    time_zone: 'timezone',
    time_slots: [
        {
            id: 'monday',
            day: 'Monday', 
            status: true,
            times: [
                {
                    start: '09:00',
                    end: '17:00',
                },   
            ]
        },
        {
            id: 'tuesday',
            day: 'Tuesday', 
            status: true,
            times: [
                {
                    start: '09:00',
                    end: '17:00',
                }
            ]
        },
        {
            id: 'wednesday',
            day: 'Wednesday', 
            status: true,
            times: [
                {
                    start: '09:00',
                    end: '17:00',
                }
            ]
        },
        {
            id: 'thursday',
            day: 'Thursday', 
            status: true,
            times: [
                {
                    start: '09:00',
                    end: '17:00',
                }
            ]
        },
        {
            id: 'friday',
            day: 'Friday', 
            status: true,
            times: [
                {
                    start: '09:00',
                    end: '17:00',
                }
            ]
        },
        {
            id: 'saturday',
            day: 'Saturday', 
            status: false,
            times: [
                {
                    start: '09:00',
                    end: '17:00',
                }
            ]
        },
        {
            id: 'sunday',
            day: 'Sunday', 
            status: false,
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
});

const handleCheckboxChange = (e) => {
    console.log(availabilityData);
}
// Remove time slot
const removeAvailabilityTime = (key, tkey = null) => {
    availabilityData.time_slots[key].times.splice(tkey, 1);
}
// Add new time slot
const addAvailabilityTime = (key) => {
    availabilityData.time_slots[key].times.push({
        start: '09:00',
        end: '17:00',
    });
}

// Remove date slot 
const removeAvailabilityTDate = (key) => {
    availabilityData.date_slots.splice(tkey, 1);
}

// Add new date slot
const addAvailabilityDate = (key) => {
    availabilityData.date_slots.push({
        start: '2022-01-01',
        end: '2022-01-01',
    });
}
const props = defineProps({
  isOpen: Boolean,
});
const emit = defineEmits(["modal-close"]); 

onBeforeMount(() => {
    timeZone.value = [
        { value: 'timezone', label: 'Select Time Zone' },
        { value: 'timezone1', label: 'Select Time Zone 1' },
        { value: 'timezone2', label: 'Select Time Zone 2' },
        { value: 'timezone3', label: 'Select Time Zone 3' },
    ];
});


</script>
 

<template> 
   <div v-if="isOpen" class="tfhb-availability-popup">
        <div  class="tfhb-dashboard-heading ">
            <div class="tfhb-admin-title"> 
                <h2 >Add New Availability </h2>  
            </div>
            <div class="thb-admin-btn right"> 
                <button class="tfhb-availability-close" @click.stop="emit('modal-close')"><Icon name="X" size="20px" /> </button> 
            </div> 
        </div>
        <div class="tfhb-content-wrap tfhb-flexbox"> 
            <!-- Title -->
            <HbText  
                v-model="availabilityData.title"  
                required= "true"  
                :label="$tfhb_trans['Title']"  
                selected = "1"
                placeholder="Type your schedule title"   
            /> 
            <!-- Title -->
            <!-- Time Zone -->
            <HbSelect 
                    
                v-model="time_zone"  
                required= "true"  
                :label="$tfhb_trans['Time zone']"  
                selected = "1"
                placeholder="Select Time Zone"  
                :option = "timeZone.value" 
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
                        <span>Asia/Dhaka</span> 
                    </div> 
                </div>
                <div v-for="(time_slot, key) in availabilityData.time_slots" :key="key" class="tfhb-availability-schedule-single tfhb-flexbox">
                    <div class="tfhb-availability-schedule-swicher">
                         <!-- Checkbox swicher -->
                         <label class="switch">
                            <input id="swicher" v-model="time_slot.status" @change="handleCheckboxChange"   type="checkbox">
                            <span class="slider"></span>
                        </label>
                        <label class="tfhb-schedule-swicher" for="swicher"> {{time_slot.day}}</label>
                        <!-- Swicher -->
                    </div>
                    <div v-if="time_slot.status == true" class="tfhb-availability-schedule-wrap ">
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
                                /> 
                                <Icon name="MoveRight" size="20px" /> 
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
                                /> 

                            </div>
                            <div v-if="tkey == 0" class="tfhb-availability-schedule-clone-single">
                                <button class="tfhb-availability-schedule-clone-btn" @click="addAvailabilityTime(key)"><Icon name="Plus" size="20px" /> </button> 
                            </div>
                            <div v-else class="tfhb-availability-schedule-clone-single">
                                <button class="tfhb-availability-schedule-clone-btn" @click="removeAvailabilityTime(key, tkey)"><Icon name="X" size="20px" /> </button> 
                            </div>
                        </div>
                        
                    </div>
                </div>
               
            </div>  
            <div class="tfhb-admin-card-box ">  
                 <!-- Yearly dates -->
                 <div  class="tfhb-dashboard-heading ">
                    <div class="tfhb-admin-title"> 
                        <h3 >Yearly dates </h3>  
                    </div> 
                </div>
                <div class="tfhb-availability-schedule-single tfhb-flexbox">
                    <div class="tfhb-availability-schedule-swicher">
                         <!-- Checkbox swicher -->
                         <label class="switch">
                            <input id="swicher" type="checkbox">
                            <span class="slider"></span>
                        </label>
                        <label class="tfhb-schedule-swicher" for="swicher"> Dates</label>
                        <!-- Swicher -->
                    </div>
                    <div class="tfhb-availability-schedule-wrap ">
                        <div v-for="(date_slot, key) in availabilityData.date_slots" :key="key" class="tfhb-availability-schedule-inner tfhb-flexbox">
                            <div  class="tfhb-availability-schedule-time tfhb-flexbox">
                                <HbDateTime  
                                    v-model="date_slot.start"    
                                    selected = "1"
                                    width="45"
                                    enableTime='true'
                                    placeholder="Type your schedule title"   
                                /> 
                                <Icon name="MoveRight" size="20px" /> 
                                <HbDateTime  
                                    v-model="date_slot.end"    
                                    selected = "1"
                                    width="45"
                                    placeholder="Type your schedule title"   
                                /> 

                            </div>
                            <div v-if="key == 0" class="tfhb-availability-schedule-clone-single">
                                <button class="tfhb-availability-schedule-clone-btn" @click="addAvailabilityDate(key)"><Icon name="Plus" size="20px" /> </button> 
                            </div>
                            <div v-else class="tfhb-availability-schedule-clone-single">
                                <button class="tfhb-availability-schedule-clone-btn" @click="removeAvailabilityTDate(key, tkey)"><Icon name="X" size="20px" /> {{ key }} </button> 
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>  
        </div>
   </div>
   
</template> 
<style scoped>
.switch {
    position: relative;
    display: inline-block;
    width: 32px;
    height: 20px; 
    margin-top: 0;
    
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #E3CFD7;
    -webkit-transition: .4s;
    transition: .4s;
    border-radius: 10px;
    margin: 0 !important;
    
}

.slider:before {
    position: absolute;
    content: "";
    height: 14px;
    width: 14px;
    left: 4px;
    bottom: 3px;
    background-color: #FFFFFF;
    -webkit-transition: .4s;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #F62881;
}

input:focus + .slider {
    box-shadow: none;
}

input:checked + .slider:before {
    -webkit-transform: translateX(10px);
    -ms-transform: translateX(10px);
    transform: translateX(10px);
}

/* Rounded sliders */
.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}
.tfhb-schedule-swicher{
    margin-left: 10px !important; 
}
</style> 
