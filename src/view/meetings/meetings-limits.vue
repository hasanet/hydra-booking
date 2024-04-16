<script setup>
import HbSelect from '@/components/form-fields/HbSelect.vue'
import HbCounter from '@/components/meetings/HbCounter.vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue';
import HbCheckbox from '@/components/form-fields/HbCheckbox.vue';
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
        <div class="tfhb-admin-card-box tfhb-flexbox tfhb-align-baseline tfhb-m-0">  

            <!-- Buffer time before meeting -->
            <HbSelect 
                v-model="meeting.buffer_time_before"
                required= "true" 
                :label="$tfhb_trans['Buffer time before meeting']"  
                width="50"
                :selected = "1"
                placeholder="No buffer time"  
                :option = "{
                    '5': '5 Minutes',  
                    '10': '10 Minutes',  
                    '15': '15 Minutes',  
                    '20': '20 Minutes',  
                    '30': '30 Minutes',  
                    '45': '45 Minutes',  
                    '60': '60 Minutes',  
                    '90': '90 Minutes',  
                    '120': '120 Minutes',   
                }" 
            />
            <!-- Buffer time before meeting --> 
            <!-- Buffer time after meeting -->
            <HbSelect 
                v-model="meeting.buffer_time_after"
                required= "true"  
                :label="$tfhb_trans['Buffer time after meeting']"   
                width="50"
                selected = "1"
                placeholder="No buffer time"  
                :option = "{
                    '5': '5 Minutes',  
                    '10': '10 Minutes',  
                    '15': '15 Minutes',  
                    '20': '20 Minutes',  
                    '30': '30 Minutes',  
                    '45': '45 Minutes',  
                    '60': '60 Minutes',  
                    '90': '90 Minutes',  
                    '120': '120 Minutes',   
                }" 
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
                :option = "{
                    '5': '5 Minutes',  
                    '10': '10 Minutes',  
                    '15': '15 Minutes',  
                    '20': '20 Minutes',  
                    '30': '30 Minutes',  
                    '45': '45 Minutes',  
                    '60': '60 Minutes',  
                    '90': '90 Minutes',  
                    '120': '120 Minutes',   
                }" 
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
        <div class="tfhb-admin-card-box tfhb-flexbox tfhb-m-0 tfhb-full-width" v-if="meeting.recurring_status">  

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

        <div class="tfhb-meeting-schedule tfhb-full-width tfhb-flexbox tfhb-gap-16">
            <HbCheckbox 
                v-model="meeting.attendee_can_cancel"
                :label="$tfhb_trans['Attendee can cancel this meeting']"
                name="attendee_can_cancel"
            />
            <HbCheckbox 
                v-model="meeting.attendee_can_reschedule"
                :label="$tfhb_trans['Attendee can reschedule this meeting']"
                name="attendee_can_reschedule"
            />
        </div>
        
        <div class="tfhb-submission-btn">
            <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="emit('update-meeting')">{{ $tfhb_trans['Save & Continue'] }} </button>
        </div>
        <!--Bookings -->
    </div>
</template>