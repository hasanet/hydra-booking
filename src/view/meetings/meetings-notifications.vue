<script setup>
import {reactive, ref} from 'vue'
import Icon from '@/components/icon/LucideIcon.vue'
import MailNotifications from '@/components/notifications/MailNotifications.vue'

const emit = defineEmits(["update-meeting"]); 
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

const popup = ref(false);
const isPopupOpen = () => {
    popup.value = true;
}
const isPopupClose = (data) => {
    popup.value = false;
}

const UpdateNotification = async () => {  
    popup.value = false;
}
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
        <div class="tfhb-notification-wrap tfhb-admin-card-box tfhb-m-0 tfhb-gap-0 tfhb-full-width">

            <!-- Gmail -->
            <div class="tfhb-notification-button-tabs tfhb-flexbox tfhb-mb-16">
                <button @click="changeTab" data-tab="host" class="tfhb-btn tfhb-notification-tabs boxed-secondary-btn flex-btn"  :class="host ? 'active' : ''" ><Icon name="UserRound" size="15" /> {{ $tfhb_trans['To Host'] }}</button>
                <button @click="changeTab"  data-tab="attendee" class="tfhb-btn tfhb-notification-tabs boxed-secondary-btn flex-btn" :class="attendee ? 'active' : ''"><Icon name="UsersRound" size="15" /> {{ $tfhb_trans['To Attendee'] }} </button>
            </div>
 
            <div v-if="host" class="tfhb-notification-wrap tfhb-notification-attendee tfhb-admin-card-box tfhb-m-0 tfhb-full-width"> 
 
                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Host" 
                    :label="$tfhb_trans['Booking Confirmation']" 
                    @update-notification="UpdateNotification"
                    :data="meeting.notification.host.booking_confirmation"  
                    :ispopup="popup"
                    @popup-open-control="isPopupOpen"
                    @popup-close-control="isPopupClose"
                /> 
                <!-- Single Integrations  -->


                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Host" 
                    :label="$tfhb_trans['Booking Cancel']" 
                    @update-notification="UpdateNotification"
                    :data="meeting.notification.host.booking_cancel"  
                    :ispopup="popup"
                    @popup-open-control="isPopupOpen"
                    @popup-close-control="isPopupClose"
                /> 
                <!-- Single Integrations  -->

                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Host" 
                    :label="$tfhb_trans['Booking Reschedule']" 
                    @update-notification="UpdateNotification"
                    :data="meeting.notification.host.booking_reschedule" 
                    :ispopup="popup"
                    @popup-open-control="isPopupOpen"
                    @popup-close-control="isPopupClose"
                /> 
                <!-- Single Integrations  -->

                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Host" 
                    :label="$tfhb_trans['Booking Reminder']"
                    @update-notification="UpdateNotification"
                    :data="meeting.notification.host.booking_reminder"  
                    :ispopup="popup"
                    @popup-open-control="isPopupOpen"
                    @popup-close-control="isPopupClose"
                /> 
                <!-- Single Integrations  -->
 
 
            </div> 
            <div v-if="attendee"  class="tfhb-notification-wrap tfhb-notification-host tfhb-admin-card-box tfhb-m-0 tfhb-full-width"> 

                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Attendee" 
                    :label="$tfhb_trans['Booking Confirmation']"
                    @update-notification="UpdateNotification"
                    :data="meeting.notification.attendee.booking_confirmation"  
                    :ispopup="popup"
                    @popup-open-control="isPopupOpen"
                    @popup-close-control="isPopupClose"
                /> 
                <!-- Single Integrations  -->


                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Attendee" 
                    :label="$tfhb_trans['Booking Cancel']"
                    @update-notification="UpdateNotification"
                    :data="meeting.notification.attendee.booking_cancel"  
                    :ispopup="popup"
                    @popup-open-control="isPopupOpen"
                    @popup-close-control="isPopupClose"
                /> 
                <!-- Single Integrations  -->

                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Attendee" 
                    :label="$tfhb_trans['Booking Reschedule']"
                    :data="meeting.notification.attendee.booking_reschedule"  
                    :ispopup="popup"
                    @popup-open-control="isPopupOpen"
                    @popup-close-control="isPopupClose"
                /> 
                <!-- Single Integrations  -->

                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Attendee" 
                    :label="$tfhb_trans['Booking Reminder']"
                    @update-notification="UpdateNotification"
                    :data="meeting.notification.attendee.booking_reminder"  
                    :ispopup="popup"
                    @popup-open-control="isPopupOpen"
                    @popup-close-control="isPopupClose"
                /> 
                <!-- Single Integrations  -->
 
 
            </div> 

        </div> 

        <div class="tfhb-submission-btn">
            <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="emit('update-meeting')">{{ $tfhb_trans['Save & Continue'] }} </button>
        </div>
        <!--Bookings -->
    </div>
</template>