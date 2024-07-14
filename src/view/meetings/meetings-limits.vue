<script setup> 
import HbDropdown from '@/components/form-fields/HbDropdown.vue'
import HbCounter from '@/components/meetings/HbCounter.vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue';
import HbCheckbox from '@/components/form-fields/HbCheckbox.vue';
import HbText from '@/components/form-fields/HbText.vue';
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
        <div class="tfhb-admin-title tfhb-full-width tfhb-m-0" >
            <h2>Meeting Limits</h2> 
            <p>How often attendee can be book</p>
        </div>
        <div class="tfhb-admin-card-box tfhb-meeting-limits tfhb-flexbox tfhb-align-baseline tfhb-m-0 tfhb-full-width">  

            <!-- Buffer time before meeting -->
            <HbDropdown 
                v-model="meeting.buffer_time_before"
                required= "true" 
                :label="$tfhb_trans['Buffer time before meeting']"  
                width="50"
                :selected = "1"
                placeholder="No buffer time"  
                :option = "[
                    {name: '5 Minutes', value: '5'},  
                    {name: '10 Minutes', value: '10'},  
                    {name: '15 Minutes', value: '15'},  
                    {name: '20 Minutes', value: '20'},  
                    {name: '30 Minutes', value: '30'},  
                    {name: '45 Minutes', value: '45'},  
                    {name: '60 Minutes', value: '60'},  
                    {name: '90 Minutes', value: '90'},  
                    {name: '120 Minutes', value: '120'},   
                ]" 
            />
            <!-- Buffer time before meeting --> 
            <!-- Buffer time after meeting -->
            <HbDropdown 
                v-model="meeting.buffer_time_after"
                required= "true"  
                :label="$tfhb_trans['Buffer time after meeting']"   
                width="50"
                selected = "1"
                placeholder="No buffer time"  
                :option = "[
                    {name: '5 Minutes', value: '5'},  
                    {name: '10 Minutes', value: '10'},  
                    {name: '15 Minutes', value: '15'},  
                    {name: '20 Minutes', value: '20'},  
                    {name: '30 Minutes', value: '30'},  
                    {name: '45 Minutes', value: '45'},  
                    {name: '60 Minutes', value: '60'},  
                    {name: '90 Minutes', value: '90'},  
                    {name: '120 Minutes', value: '120'},   
                ]" 
            />
            <!-- Buffer time after meeting -->

            <!-- Booking Frequency -->
           
            <HbCounter
                :label="$tfhb_trans['Booking frequency']"
                width="50"
                :description="$tfhb_trans['Limit how many times this meeting can be booked']"
                :repater="true" 
                counterLabel="Bookings"
                :counter_value="meeting.booking_frequency"
                @limits-frequency-add="addExtraFrequency"
                @limits-frequency-remove="removeExtraFrequency"
            />

            <!-- Meeting interval -->
            <HbDropdown 
                v-model="meeting.meeting_interval"
                required= "true"  
                :label="$tfhb_trans['Meeting interval']"   
                width="50"
                selected = "1"
                :placeholder="$tfhb_trans['Use meeting length (default)']"
                :option = "[
                    {name: '5 Minutes', value: '5'},  
                    {name: '10 Minutes', value: '10'},  
                    {name: '15 Minutes', value: '15'},  
                    {name: '20 Minutes', value: '20'},  
                    {name: '30 Minutes', value: '30'},  
                    {name: '45 Minutes', value: '45'},  
                    {name: '60 Minutes', value: '60'},  
                    {name: '90 Minutes', value: '90'},  
                    {name: '120 Minutes', value: '120'},   
                ]"
            />
        
        </div>  

        <div class="tfhb-admin-title tfhb-full-width tfhb-m-0">
            <h2 class="tfhb-flexbox tfhb-gap-8 tfhb-justify-normal">
                Recurring Event
                <HbSwitch 
                    v-model="meeting.recurring_status"
                />
            </h2> 
            <p>Set up a repeating schedule</p>
        </div>
        <div class="tfhb-admin-card-box tfhb-meeting-limits tfhb-flexbox tfhb-m-0 tfhb-full-width" v-if="meeting.recurring_status">  

            <!-- Meeting interval -->

            <HbCounter
                :label="$tfhb_trans['Repeats every']"
                width="50"
                :repater="false"
                :counter_value="meeting.recurring_repeat"
            />
            
            <!-- For a maximum of --> 
            <HbText  
                    v-model="meeting.recurring_maximum"   
                    type="number"
                    :label="$tfhb_trans['Maximum number of bookings']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Use meeting length (default)']" 
                    width="50"  
                    limit="1"
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