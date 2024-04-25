<script setup>
import { ref, reactive, onBeforeMount, } from 'vue'; 
import { useRouter, RouterView,} from 'vue-router' 
import axios from 'axios' 
import Icon from '@/components/icon/LucideIcon.vue'
import HbText from '../form-fields/HbText.vue';
import HbSelect from '../form-fields/HbSelect.vue';
import HbDateTime from '../form-fields/HbDateTime.vue';
import HbCheckbox from '@/components/form-fields/HbCheckbox.vue';
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
        toast.error('Action successful', {
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

// Remove date slot 
const removeAvailabilityTDate = (key) => {
    props.availabilityDataSingle.date_slots.splice(key, 1);
}

// Add new date slot
const addAvailabilityDate = (key) => {
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
}

//add Overrides Time
const addOverridesTime = (key) => {
    props.availabilityDataSingle.date_slots[key].times.push({
        start: '09:00',
        end: '17:00',
    });
}

// Remove Overrides time slot
const removeOverridesTime = (key, tkey = null) => {
    props.availabilityDataSingle.date_slots[key].times.splice(tkey, 1);
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
                <HbSelect 
                        
                    v-model="props.availabilityDataSingle.time_zone"  
                    required= "true"  
                    :label="$tfhb_trans['Time zone']"  
                    selected = "1"
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
                                    <button class="tfhb-availability-schedule-btn" @click="addAvailabilityTime(key)"><Icon name="Plus" size="20px" /> </button> 
                                </div>
                                <div v-else class="tfhb-availability-schedule-clone-single">
                                    <button class="tfhb-availability-schedule-btn" @click="removeAvailabilityTime(key, tkey)"><Icon name="X" size="20px" /> </button> 
                                </div>
                            </div>
                            
                        </div>
                    </div>
                
                </div>  
                <div class="tfhb-admin-card-box ">  
                    <!-- Yearly dates -->
                    <div  class="tfhb-dashboard-heading ">
                        <div class="tfhb-admin-title tfhb-flexbox"> 
                            <h3>Add date overrides </h3>  
                            <div class="tfhb-availability-schedule-clone-single">
                                <button class="tfhb-availability-schedule-btn" @click="addAvailabilityDate(key)"><Icon name="Plus" size="20" /> </button> 
                            </div>
                        </div> 
                    </div>
                    <div class="tfhb-availability-schedule-single tfhb-flexbox tfhb-align-baseline">
                        <div class="tfhb-availability-schedule-wrap tfhb-full-width">
                            <div v-for="(date_slot, key) in props.availabilityDataSingle.date_slots" :key="key" class="tfhb-availability-schedule-inner tfhb-admin-card-box tfhb-flexbox">
                                <div class="tfhb-dates tfhb-full-width">
                                    <p>What dates are you available / unavailable?</p>
                                    <div class="tfhb-flexbox">
                                        <div class="tfhb-availability-schedule-time tfhb-flexbox" style="width:calc(100% - 52px)">
                                            <HbDateTime  
                                                v-model="date_slot.date"    
                                                selected = "1"
                                                enableTime='true'
                                                placeholder="Type your schedule title"   
                                                :config="{
                                                    mode: 'multiple',
                                                }"
                                            /> 
                                        </div>
                                        
                                        <div class="tfhb-availability-schedule-clone-single">
                                            <button class="tfhb-availability-schedule-btn" @click="removeAvailabilityTDate(key)"><Icon name="X" size="20" />   </button> 
                                        </div>
                                    </div>
                                </div>

                                <div class="tfhb-availability-schedule-wrap tfhb-full-width" v-if="!date_slot.available"> 
                                    <p>What hours are you available?</p>
                                    <div v-for="(time, tkey) in date_slot.times" :key="tkey"  class="tfhb-availability-schedule-inner tfhb-flexbox">
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
                                            <button class="tfhb-availability-schedule-btn" @click="addOverridesTime(key)"><Icon name="Plus" size="20px" /> </button> 
                                        </div>
                                        <div v-else class="tfhb-availability-schedule-clone-single">
                                            <button class="tfhb-availability-schedule-btn" @click="removeOverridesTime(key, tkey)"><Icon name="X" size="20px" /> </button> 
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="tfhb-mark-unavailable tfhb-full-width">
                                    <HbCheckbox 
                                        v-model="date_slot.available"
                                        :label="$tfhb_trans['Mark to Unavailable']"
                                        :name="'mark_unavailable'+key"
                                    />
                                </div>

                            </div>
                        </div>
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
