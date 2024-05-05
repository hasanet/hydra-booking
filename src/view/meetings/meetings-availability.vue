<script setup>
import {ref, onBeforeMount, reactive} from 'vue'
import axios from 'axios'  
import HbSelect from '@/components/form-fields/HbSelect.vue'
import HbDateTime from '@/components/form-fields/HbDateTime.vue';
import Icon from '@/components/icon/LucideIcon.vue'
import HbText from '@/components/form-fields/HbText.vue';
import HbCheckbox from '@/components/form-fields/HbCheckbox.vue';
import useValidators from '@/store/validator'
import { Host } from '@/store/hosts';
const { errors, isEmpty } = useValidators();

const emit = defineEmits(["availability-time", "availability-time-del", "availability-date", "availability-date-del", "availability-tabs", "update-meeting", "add-overrides-time", "remove-overrides-time"]); 
const props = defineProps({
    meetingId: {
        type: Number,
        required: true
    },
    meeting: {
        type: Object,
        required: true
    },
    timeZone: {
        type: Object,
        required: true
    },

});

// Fetch Single Availability while Schdeule on change 
const Settings_avalibility = ref();
const fetchAvailabilitySettings = async (availability_id) => {
    let data = {
        user_id: props.meeting.user_id,
        availability_id: availability_id
    };  
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/availability/single', data, {
            headers: {
                'X-WP-Nonce': tfhb_core_apps.rest_nonce
            } 
        } );
        if (response.data.status && response.data.availability.length > 0) { 
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

// Host Wise Availability
const HostAvailabilities = reactive({});
const fetchHostAvailability = async (host) => {
    try { 
        const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/'+host); 
        
        // Clear existing data before updating
        for (const key in HostAvailabilities) {
            delete HostAvailabilities[key];
        }
        
        response.data.host.availability && response.data.host.availability.forEach((available, key) => {
            HostAvailabilities[key] = available.title;
        });
    } catch (error) {
        console.log(error);
    } 
}

const Host_Avalibility_Callback = (e) => {
    if(e.target.value){
        fetchHostAvailability(e.target.value);
    }
}

// Host Default Availability
const fetchSingleAvailabilitySettings = async (user_id, availability_id) => {
    let data = {
        user_id: user_id,
        availability_id: availability_id
    };  
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/availability/single', data, {
            headers: {
                'X-WP-Nonce': tfhb_core_apps.rest_nonce
            } 
        } );
        if (response.data.status && response.data.availability) {    
            Settings_avalibility.value = response.data;
        }
    } catch (error) {
        console.log(error);
    } 
}

// Mount
onBeforeMount(() => { 
    Host.fetchHosts().then(() => {
        if(props.meeting.host_id!=0){
            fetchHostAvailability(props.meeting.host_id);
        }
        fetchSingleAvailabilitySettings(props.meeting.user_id, props.meeting.availability_id);
    });
});

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

// Remove Single Availability
const removeAvailabilityTDate = (key) => {
    props.meeting.availability_custom.date_slots.splice(key, 1);
}

// Open Overrides Form
const openOverridesCalendarDate = () => {
    props.meeting.availability_custom.date_slots.push({
        date: '',
        available: '',
        times: [
            {
                start: '09:00',
                end: '17:00',
            }
        ]
    });

    const lastIndexOfQuestion = props.meeting.availability_custom.date_slots.length - 1;
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

// Store to the reactive
const addAvailabilityDate = (key) => {

    props.meeting.availability_custom.date_slots[OverridesDates.key].date = OverridesDates.date
    props.meeting.availability_custom.date_slots[OverridesDates.key].available = OverridesDates.available
    props.meeting.availability_custom.date_slots[OverridesDates.key].times = OverridesDates.times

    OverridesOpen.value = false;
}

const editAvailabilityDate = (key) => {
    props.meeting.availability_custom.date_slots.forEach((available, qkey) => {
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

    <div class="meeting-create-details tfhb-gap-24">
        <div class="tfhb-meeting-range tfhb-full-width">
            <div class="tfhb-admin-title" >
                <h2>Availability Range for this Booking</h2> 
                <p>How many days can the invitee schedule?</p>
            </div>

            <div class="tfhb-flexbox tfhb-gap-0 tfhb-align-normal">
                <div class="tfhb-single-meeting-range tfhb-admin-card-box tfhb-border-box tfhb-m-0 tfhb-align-baseline">
                    <label for="tfhb_continuos_date" class="tfhb-m-0 tfhb-flexbox tfhb-gap-16 tfhb-align-normal">
                        <div class="tfhb-range-checkbox">
                            <input id="tfhb_continuos_date" name="tfhb_range_date" value="indefinitely" type="radio" v-model="meeting.availability_range_type" :checked="meeting.availability_range_type == 'indefinitely' ? true : false">
                            <span class="checkmark"></span> 
                        </div>
                        <div class="tfhb-range-title">
                            <h4 class="tfhb-m-0">Indefinitely into the future</h4> 
                            <p class="tfhb-m-0">Meeting will be go for indefinitely into the future</p>
                        </div>
                    </label>
                </div>
                <div class="tfhb-single-meeting-range tfhb-admin-card-box tfhb-border-box tfhb-m-0 tfhb-align-baseline">
                    <label for="tfhb_specific_date" class="tfhb-m-0 tfhb-flexbox tfhb-gap-16 tfhb-align-normal">
                        <div class="tfhb-range-checkbox">
                            <input id="tfhb_specific_date" name="tfhb_range_date" type="radio" value="range" v-model="meeting.availability_range_type" :checked="meeting.availability_range_type == 'range' ? true : false">
                            <span class="checkmark"></span> 
                        </div>
                        <div class="tfhb-range-title">
                            <h4 class="tfhb-m-0">Specific date range</h4> 
                            <p class="tfhb-m-0">Meeting will be only available on specific dates</p>
                        </div>
                    </label>
                    <div class="tfhb-availability-schedule-time tfhb-flexbox" v-if="meeting.availability_range_type == 'range'">
                        <HbDateTime   
                            v-model="meeting.availability_range.start"
                            icon="CalendarDays"
                            selected = "1" 
                            :config="{
                            }"
                            width="45"
                            placeholder="Type your schedule title"   
                        /> 
                        <Icon name="MoveRight" size="20" /> 
                        <HbDateTime  
                            v-model="meeting.availability_range.end"
                            icon="CalendarDays"
                            :label="$tfhb_trans['End']"  
                            selected = "1"
                            :config="{
                            }"
                            width="45"
                            placeholder="Type your schedule title"   
                        /> 

                    </div>
                </div>
            </div>
        </div>
        <!-- Select Host -->
        <HbSelect 
            v-model="meeting.host_id"
            required= "true" 
            :label="$tfhb_trans['Select Host']"  
            name="host_id"
            :placeholder="$tfhb_trans['Select Host']"  
            :option = "Host.hosts" 
            @change="() => validateSelect('host_id')"
            @click="() => validateSelect('host_id')"
            :errors="errors.host_id"
            @tfhb-onchange="Host_Avalibility_Callback"
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
            :option="HostAvailabilities"
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
        <!-- Time Zone -->
        <HbSelect 
            
            v-model="meeting.availability_custom.time_zone"  
            required= "true"  
            :label="$tfhb_trans['Time zone']"  
            selected = "1"
            placeholder="Select Time Zone"  
            :option = "props.timeZone" 
            v-if="'custom'==meeting.availability_type"
        /> 
        <!-- Time Zone --> 
        <!-- Settings Data -->
        
        <div class="tfhb-admin-card-box tfhb-gap-24 tfhb-full-width" v-if="Settings_avalibility && 'settings'==meeting.availability_type">  
            <div  class="tfhb-availability-schedule-single tfhb-schedule-heading tfhb-flexbox">
                <div class="tfhb-admin-title"> 
                    <h3 >Weekly hours </h3>  
                </div>
                <div class="thb-admin-btn right"> 
                    <span>{{ Settings_avalibility.availability.time_zone }}</span> 
                </div> 
            </div>
            
            <div v-for="(time_slot, key) in Settings_avalibility.availability.time_slots" :key="key" class="tfhb-availability-schedule-single tfhb-flexbox tfhb-align-baseline">
                <div class="tfhb-swicher-wrap  tfhb-flexbox">
                    <label class="tfhb-schedule-swicher" for="swicher"> {{time_slot.day}}</label>
                    <!-- Swicher -->
                </div>
                <div v-if="time_slot.status == 1" class="tfhb-availability-schedule-wrap"> 
                    <div v-for="(time, tkey) in time_slot.times" :key="tkey" class="tfhb-availability-schedule-inner tfhb-flexbox">
                        <div class="tfhb-availability-schedule-time tfhb-flexbox">
                            
                            <div class="tfhb-single-form-field" style="width: calc(45% - 12px);" selected="1">
                                <div class="tfhb-single-form-field-wrap tfhb-field-date">
                                    <input type="text" data-input="true" class="flatpickr-input" :value="time.start" readonly="readonly">
                                    <span class="tfhb-flat-icon">
                                        <Icon name="Clock4" size="20px" />
                                    </span>
                                </div>
                            </div>

                            <Icon name="MoveRight" size="20px" /> 

                            <div class="tfhb-single-form-field" style="width: calc(45% - 12px);" selected="1">
                                <div class="tfhb-single-form-field-wrap tfhb-field-date">
                                    <input type="text" data-input="true" class="flatpickr-input" :value="time.end" readonly="readonly">
                                    <span class="tfhb-flat-icon">
                                        <Icon name="Clock4" size="20px" />
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Date Overrides -->
            <div class="tfhb-admin-card-box tfhb-m-0 tfhb-flexbox tfhb-full-width" v-if="Settings_avalibility.availability.date_slots">  
                <div  class="tfhb-dashboard-heading tfhb-full-width" :style="{margin: '0 !important'}">
                    <div class="tfhb-admin-title tfhb-m-0"> 
                        <h3>Add date overrides </h3>  
                        <p>Add dates when your availability changes from your daily hours</p>
                    </div> 
                </div>

                <div class="tfhb-admin-card-box tfhb-m-0 tfhb-full-width" v-for="(date_slot, key) in Settings_avalibility.availability.date_slots" :key="key">
                    <div class="tfhb-flexbox tfhb-full-width">
                        <div class="tfhb-overrides-date">
                            <h4>{{ date_slot.date }}</h4>
                            <p class="tfhb-m-0">{{ date_slot.available!=1 ? formatTimeSlots(date_slot.times) : 'Unavailable' }}</p>
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
                    <span>{{ meeting.availability_custom.time_zone }}</span> 
                </div> 
            </div>
            
            <div v-for="(time_slot, key) in meeting.availability_custom.time_slots" :key="key" class="tfhb-availability-schedule-single tfhb-flexbox tfhb-align-baseline">
                <div class="tfhb-swicher-wrap tfhb-flexbox">
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
                                }"
                                width="45"
                                placeholder="Type your schedule title"   
                                icon="Clock4"
                            /> 

                        </div>
                        <div v-if="tkey == 0" class="tfhb-availability-schedule-clone-single">
                            <button class="tfhb-availability-schedule-btn" @click="emit('availability-time',key)"><Icon name="Plus" size="20" /> </button> 
                        </div>
                        <div v-else class="tfhb-availability-schedule-clone-single">
                            <button class="tfhb-availability-schedule-btn" @click="emit('availability-time-del',key, tkey)"><Icon name="X" size="20" /> </button> 
                        </div>
                    </div>
                    
                </div>
            </div>


            <!-- Date Overrides -->
            <div class="tfhb-admin-card-box tfhb-m-0 tfhb-flexbox tfhb-full-width">  

                <div  class="tfhb-dashboard-heading tfhb-full-width" :style="{margin: '0 !important'}">
                    <div class="tfhb-admin-title"> 
                        <h3>Add date overrides </h3>  
                        <p>Add dates when your availability changes from your daily hours</p>
                    </div> 
                </div>

                <div class="tfhb-admin-card-box tfhb-m-0 tfhb-full-width" v-for="(date_slot, key) in meeting.availability_custom.date_slots" :key="key">
                    <div class="tfhb-flexbox tfhb-full-width">
                        <div class="tfhb-overrides-date">
                            <h4>{{ date_slot.date }}</h4>
                            <p class="tfhb-m-0">{{ date_slot.available!=1 ? formatTimeSlots(date_slot.times) : 'Unavailable' }}</p>
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
                                    mode: 'multiple'
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
                                    v-model="OverridesDates.available"
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
        <div class="tfhb-submission-btn">
            <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="emit('update-meeting', ['host_id'])">{{ $tfhb_trans['Save & Continue'] }} </button>
        </div>
        <!--Bookings -->
    </div>
</template>