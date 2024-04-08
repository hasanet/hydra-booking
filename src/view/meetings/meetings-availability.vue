<script setup>
import {ref} from 'vue'
import axios from 'axios'  
import HbSelect from '@/components/form-fields/HbSelect.vue'
import HbDateTime from '@/components/form-fields/HbDateTime.vue';
import Icon from '@/components/icon/LucideIcon.vue'
import HbText from '@/components/form-fields/HbText.vue';
import useValidators from '@/store/validator'
const { errors, isEmpty } = useValidators();

const emit = defineEmits(["availability-time", "availability-time-del", "availability-date", "availability-date-del", "availability-tabs", "update-meeting"]); 
const props = defineProps({
    meetingId: {
        type: Number,
        required: true
    },
    meeting: {
        type: Object,
        required: true
    },

});
const transformedHostData = {};
if ( props.meeting.hosts) {
    props.meeting.hosts.forEach(host => {
        const userId = host.user_id;
        const firstName = host.first_name;
        transformedHostData[userId] = firstName;
    });
} else {
    console.error("Error: props.meeting.hosts is not defined or is undefined");
}

const Availability_Id = {};
if ( props.meeting.availability_seetings ) {
    props.meeting.availability_seetings.forEach((availability, key) => {
        const Title = availability.title;
        Availability_Id[key] = Title;
    });
} else {
    console.error("Error: props.meeting.availability_seetings is not defined or is undefined");
}

const Settings_avalibility = ref();


// Fetch generalSettings
const fetchAvailabilitySettings = async (setting) => {
    try { 
        const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/availability/'+setting); 
        if (response.data.status) { 
            Settings_avalibility.value = response.data;
        }
    } catch (error) {
        console.log(error);
    } 
}

const Settings_Avalibility_Callback = (e) => {
    if(e.target.value){
        fetchAvailabilitySettings(e.target.value);
    }
}

const validateSelect = (fieldName) => {
    const fieldValueKey = fieldName;
    isEmpty(fieldName, props.meeting[fieldValueKey]);
};

</script>

<template>

    <div class="meeting-create-details tfhb-gap-24">
        <!-- Select Host -->
        <HbSelect 
            v-model="meeting.host_id"
            required= "true" 
            :label="$tfhb_trans['Select Host']"  
            name="host_id"
            :placeholder="$tfhb_trans['Select Host']"  
            :option = "transformedHostData" 
            @change="() => validateSelect('host_id')"
            @click="() => validateSelect('host_id')"
            :errors="errors.host_id"
        />

        <div class="tfhb-availaility-tabs">
            <ul class="tfhb-flexbox tfhb-gap-16">
                <li class="tfhb-flexbox tfhb-gap-8" :class="'settings'==meeting.availability_type ? 'active' : ''" @click="emit('availability-tabs', 'settings')"><Icon name="Heart" :width="20" /> Use existing availability</li>
                <li class="tfhb-flexbox tfhb-gap-8" :class="'custom'==meeting.availability_type ? 'active' : ''" @click="emit('availability-tabs', 'custom')"><Icon name="PencilLine" :width="20" /> Custom availability</li>
            </ul>
        </div>
        <!-- Choose Schedule -->
        <HbSelect 
            v-model="meeting.availability_id"
            required= "true" 
            :label="$tfhb_trans['Choose Schedule']"  
            :selected = "1"
            :placeholder="$tfhb_trans['Choose Schedule']"   
            :option = "Availability_Id" 
            v-if="'settings'==meeting.availability_type"
            @tfhb-onchange="Settings_Avalibility_Callback"
        />

        <HbText 
            v-model="meeting.availability_custom.title"
            required= "true" 
            :label="$tfhb_trans['Choose Schedule']"  
            :placeholder="$tfhb_trans['Availability title']"   
            v-if="'custom'==meeting.availability_type"
        />
        <!-- Settings Data -->
        <div class="tfhb-admin-card-box tfhb-gap-24" v-if="Settings_avalibility && 'settings'==meeting.availability_type">  
            <div  class="tfhb-availability-schedule-single tfhb-schedule-heading tfhb-flexbox">
                <div class="tfhb-admin-title"> 
                    <h3 >Weekly hours </h3>  
                </div>
                <div class="thb-admin-btn right"> 
                    <span>{{ Settings_avalibility.availability.time_zone }}</span> 
                </div> 
            </div>
            
            <div v-for="(time_slot, key) in Settings_avalibility.availability.time_slots" :key="key" class="tfhb-availability-schedule-single tfhb-flexbox">
                <div class="tfhb-swicher-wrap  tfhb-flexbox">
                    <!-- Checkbox swicher -->
                    <label class="switch">
                        <input id="swicher" true-value="1" type="checkbox" v-bind:checked="time_slot.status =='1'">
                        <span class="slider"></span>
                    </label>
                    <label class="tfhb-schedule-swicher" for="swicher"> {{time_slot.day}}</label>
                    <!-- Swicher -->
                </div>
                <div v-if="time_slot.status == 1" class="tfhb-availability-schedule-wrap"> 
                    <div v-for="(time, tkey) in time_slot.times" :key="tkey" class="tfhb-availability-schedule-inner tfhb-flexbox">
                        <div class="tfhb-availability-schedule-time tfhb-flexbox">
                            
                            <div class="tfhb-single-form-field" style="width: calc(45% - 12px);" selected="1">
                                <div class="tfhb-single-form-field-wrap tfhb-field-date">
                                    <input type="text" data-input="true" class="flatpickr-input" :value="time.start" readonly="readonly">
                                    <span class="tfhb-flat-icon"><!--v-if-->
                                    </span>
                                </div>
                            </div>

                            <Icon name="MoveRight" size="20px" /> 

                            <div class="tfhb-single-form-field" style="width: calc(45% - 12px);" selected="1">
                                <div class="tfhb-single-form-field-wrap tfhb-field-date">
                                    <input type="text" data-input="true" class="flatpickr-input" :value="time.end" readonly="readonly">
                                    <span class="tfhb-flat-icon"><!--v-if-->
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                </div>
            </div>

            <div  class="tfhb-availability-schedule-single tfhb-schedule-heading tfhb-flexbox">
                <div class="tfhb-admin-title"> 
                    <h3 >Yearly dates </h3>  
                </div>
                <div class="tfhb-availability-schedule-single tfhb-flexbox">
                    <div class="tfhb-swicher-wrap  tfhb-flexbox">
                        <!-- Checkbox swicher -->
                        <label class="switch">
                            <input id="swicher" true-value="1" type="checkbox" v-bind:checked="Settings_avalibility.availability.date_status =='1'">
                            <span class="slider"></span>
                        </label>
                        <label class="tfhb-schedule-swicher"  for="swicher"> Dates</label>
                        <!-- Swicher -->
                    </div>
                    <div class="tfhb-availability-schedule-wrap ">
                        <div v-for="(date_slot, key) in Settings_avalibility.availability.date_slots" :key="key" class="tfhb-availability-schedule-inner tfhb-flexbox">
                            <div  class="tfhb-availability-schedule-time tfhb-flexbox">
                                <div class="tfhb-single-form-field" style="width: calc(45% - 12px);" selected="1">
                                    <div class="tfhb-single-form-field-wrap tfhb-field-date">
                                        <input type="text" data-input="true" class="flatpickr-input" :value="date_slot.start" readonly="readonly">
                                        <span class="tfhb-flat-icon"><!--v-if-->
                                        </span>
                                    </div>
                                </div>

                                <Icon name="MoveRight" size="20px" /> 

                                <div class="tfhb-single-form-field" style="width: calc(45% - 12px);" selected="1">
                                    <div class="tfhb-single-form-field-wrap tfhb-field-date">
                                        <input type="text" data-input="true" class="flatpickr-input" :value="date_slot.end" readonly="readonly">
                                        <span class="tfhb-flat-icon"><!--v-if-->
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>  

        <!-- Custom Data -->
        <div class="tfhb-admin-card-box tfhb-gap-24" v-if="'custom'==meeting.availability_type">  
            <div  class="tfhb-availability-schedule-single tfhb-schedule-heading tfhb-flexbox">
                <div class="tfhb-admin-title"> 
                    <h3 >Weekly hours </h3>  
                </div>
                <div class="thb-admin-btn right"> 
                    <span>Asia/Dhaka</span> 
                </div> 
            </div>
            
            <div v-for="(time_slot, key) in meeting.availability_custom.time_slots" :key="key" class="tfhb-availability-schedule-single tfhb-flexbox">
                <div class="tfhb-swicher-wrap  tfhb-flexbox">
                    <!-- Checkbox swicher -->
                    <label class="switch">
                        <input id="swicher" v-model="time_slot.status" true-value="1" type="checkbox">
                        <span class="slider"></span>
                    </label>
                    <label class="tfhb-schedule-swicher" for="swicher"> {{time_slot.day}}</label>
                    <!-- Swicher -->
                </div>
                <div v-if="time_slot.status == 1" class="tfhb-availability-schedule-wrap"> 
                    <div v-for="(time, tkey) in time_slot.times" :key="tkey" class="tfhb-availability-schedule-inner tfhb-flexbox">
                        <div class="tfhb-availability-schedule-time tfhb-flexbox">
                            <HbDateTime   
                                v-model="time.start" 
                                selected = "1" 
                                :config="{
                                    enableTime: true,
                                    noCalendar: true,
                                    dateFormat: 'H:i',
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
                                }"
                                width="45"
                                placeholder="Type your schedule title"   
                            /> 

                        </div>
                        <div v-if="tkey == 0" class="tfhb-availability-schedule-clone-single">
                            <button class="tfhb-availability-schedule-btn" @click="emit('availability-time',key)"><Icon name="Plus" size="20px" /> </button> 
                        </div>
                        <div v-else class="tfhb-availability-schedule-clone-single">
                            <button class="tfhb-availability-schedule-btn" @click="emit('availability-time-del',key, tkey)"><Icon name="X" size="20px" /> </button> 
                        </div>
                    </div>
                    
                </div>
            </div>

            <div  class="tfhb-availability-schedule-single tfhb-schedule-heading tfhb-flexbox">
                <div class="tfhb-admin-title"> 
                    <h3 >Yearly dates </h3>  
                </div>
                <div class="tfhb-availability-schedule-single tfhb-flexbox">
                    <div class="tfhb-swicher-wrap  tfhb-flexbox">
                        <!-- Checkbox swicher -->
                        <label class="switch">
                            <input id="swicher" true-value="1" v-model="meeting.availability_custom.date_status" type="checkbox">
                            <span class="slider"></span>
                        </label>
                        <label class="tfhb-schedule-swicher"  for="swicher"> Dates</label>
                        <!-- Swicher -->
                    </div>
                    <div class="tfhb-availability-schedule-wrap ">
                        <div v-for="(date_slot, key) in meeting.availability_custom.date_slots" :key="key" class="tfhb-availability-schedule-inner tfhb-flexbox">
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
                                <button class="tfhb-availability-schedule-btn" @click="emit('availability-date',key)"><Icon name="Plus" size="20" /> </button> 
                            </div>
                            <div v-else class="tfhb-availability-schedule-clone-single">
                                <button class="tfhb-availability-schedule-btn" @click="emit('availability-date-del',key)"><Icon name="X" size="20" />   </button> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
        </div>  
        <div class="tfhb-submission-btn">
            <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="emit('update-meeting', ['host_id'])">{{ $tfhb_trans['Save & Continue'] }} </button>
        </div>
        <!--Bookings -->
    </div>
</template>