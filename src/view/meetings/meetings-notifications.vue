<script setup>
import {reactive, ref} from 'vue'
import Icon from '@/components/icon/LucideIcon.vue'
import MailNotifications from '@/components/notifications/MailNotifications.vue'

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


const host = ref(true);
const attendee = ref(false);

// Update Notification 

const changeTab = (e) => {  
    // get data-tab attribute value of clicked button
    const tab = e.target.getAttribute('data-tab'); 
    if(tab == 'host') {  
        host.value = true;
        attendee.value = false;  
    } else { 
        host.value = false;
        attendee.value = true; 
    }

}

</script>

<template>
    <div class="meeting-create-details tfhb-gap-24">
        {{ meeting.notification }}
        <div class="tfhb-notification-wrap tfhb-admin-card-box tfhb-m-0 tfhb-gap-32">

            <!-- Gmail -->
            <div class="tfhb-notification-button-tabs tfhb-flexbox tfhb-mb-16">
                <button @click="changeTab" data-tab="host" class="tfhb-btn tfhb-notification-tabs boxed-secondary-btn flex-btn"  :class="host ? 'active' : ''" ><Icon name="UserRound" size="15px" /> To Host </button>
                <button @click="changeTab"  data-tab="attendee" class="tfhb-btn tfhb-notification-tabs boxed-secondary-btn flex-btn" :class="attendee ? 'active' : ''"><Icon name="UsersRound" size="15px" /> To Attendee </button>
            </div>
 
            <div v-if="host" class="tfhb-notification-wrap tfhb-notification-attendee tfhb-admin-card-box "> 
 
                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Host" 
                    label="Booking Confirmation" 
                    @update-notification="UpdateNotification"
                    :data="meeting.notification.host.booking_confirmation"  
                /> 
                <!-- Single Integrations  -->


                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Host" 
                    label="Booking Cancel" 
                    @update-notification="UpdateNotification"
                    :data="meeting.notification.host.booking_cancel"  
                /> 
                <!-- Single Integrations  -->

                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Host" 
                    label="Booking Reschedule" 
                    @update-notification="UpdateNotification"
                    :data="meeting.notification.host.booking_reschedule"  
                /> 
                <!-- Single Integrations  -->

                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Host" 
                    label="Booking Reminder" 
                    @update-notification="UpdateNotification"
                    :data="meeting.notification.host.booking_reminder"  
                /> 
                <!-- Single Integrations  -->
 
 
            </div> 
            <div v-if="attendee"  class="tfhb-notification-wrap tfhb-notification-host tfhb-admin-card-box "> 

                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Attendee" 
                    label="Booking Confirmation" 
                    @update-notification="UpdateNotification"
                    :data="meeting.notification.attendee.booking_confirmation"  
                /> 
                <!-- Single Integrations  -->


                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Attendee" 
                    label="Booking Cancel" 
                    @update-notification="UpdateNotification"
                    :data="meeting.notification.attendee.booking_cancel"  
                /> 
                <!-- Single Integrations  -->

                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Attendee" 
                    label="Booking Reschedule" 
                    :data="meeting.notification.attendee.booking_reschedule"  
                /> 
                <!-- Single Integrations  -->

                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Attendee" 
                    label="Booking Reminder" 
                    @update-notification="UpdateNotification"
                    :data="meeting.notification.attendee.booking_reminder"  
                /> 
                <!-- Single Integrations  -->
 
 
            </div> 

        </div> 

        <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="UpdateGeneralSettings">{{ $tfhb_trans['Save & Continue'] }} </button>
        <!--Bookings -->
    </div>
</template>