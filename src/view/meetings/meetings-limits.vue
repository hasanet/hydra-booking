<script setup>
import HbSelect from '@/components/form-fields/HbSelect.vue'
import HbCounter from '@/components/meetings/HbCounter.vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue';
import Icon from '@/components/icon/LucideIcon.vue'
const emit = defineEmits(["update-meeting", "limits-frequency-add"]); 
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


// Add more addExtraFrequency
function addExtraFrequency(){
    props.meeting.booking_frequency.push({
    limit: 1,
    times:'Month',
  })
}

// Remove removeExtraFrequency
const removeExtraFrequency = (key) => {
    props.meeting.booking_frequency.splice(key, 1);
}

</script>

<template>
    <div class="meeting-create-details tfhb-gap-24">

        <!-- Date And Time --> 
        <div class="tfhb-admin-title" >
            <h2>Meeting Limits</h2> 
            <p>How often attendee can be book</p>
        </div>
        <div class="tfhb-admin-card-box tfhb-flexbox tfhb-align-baseline">  

            <!-- Buffer time before meeting -->
            <HbSelect 
                v-model="meeting.buffer_time_before"
                required= "true" 
                :label="$tfhb_trans['Buffer time before meeting']"  
                width="50"
                :selected = "1"
                placeholder="Buffer time after meeting"  
                :option = "{'12_hours': '12 hours', '24_hours': '24 hours'}" 
            />
            <!-- Buffer time before meeting --> 
            <!-- Buffer time after meeting -->
            <HbSelect 
                v-model="meeting.buffer_time_after"
                required= "true"  
                :label="$tfhb_trans['Buffer time after meeting']"   
                width="50"
                selected = "1"
                placeholder="Buffer time after meeting"  
                :option = "['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']"
                
            />
            <!-- Buffer time after meeting -->

            <!-- Booking Frequency -->

            <HbCounter
                :label="$tfhb_trans['Booking frequency']"
                width="50"
                :description="$tfhb_trans['Limit how many times this meeting can be booked']"
                :repater="true"
                :counter_value="meeting.booking_frequency"
                @limits-frequency-add="addExtraFrequency"
                @limits-frequency-remove="removeExtraFrequency"
            />

            <!-- Meeting interval -->
            <HbSelect 
                v-model="meeting.meeting_interval"
                required= "true"  
                :label="$tfhb_trans['Meeting interval']"   
                width="50"
                selected = "1"
                :placeholder="$tfhb_trans['Use meeting length (default)']"
                :option = "['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']"
                
            />
        
        </div>  

        <div class="tfhb-admin-title" >
            <h2 class="tfhb-flexbox tfhb-gap-8">
                Recurring Event
                <HbSwitch 
                    v-model="meeting.recurring_status"
                    width="50"
                />
            </h2> 
            <p>Set up a repeating schedule</p>
        </div>
        <div class="tfhb-admin-card-box tfhb-flexbox" v-if="meeting.recurring_status">  

            <!-- Meeting interval -->

            <HbCounter
                :label="$tfhb_trans['Repeats every']"
                width="50"
                :repater="false"
                :counter_value="meeting.recurring_repeat"
            />
            
            <!-- For a maximum of -->
            <HbSelect 
                v-model="meeting.recurring_maximum"
                required= "true"  
                :label="$tfhb_trans['For a maximum of']"   
                width="50"
                selected = "1"
                :placeholder="$tfhb_trans['Use meeting length (default)']"
                :option = "['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']"
                
            />

        </div>  

        <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="emit('update-meeting')">{{ $tfhb_trans['Save & Continue'] }} </button>
        <!--Bookings -->
    </div>
</template>