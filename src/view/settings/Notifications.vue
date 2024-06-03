<script setup> 
// Use children routes for the tabs 
import { ref, reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView,} from 'vue-router' 
import axios from 'axios' 
import Icon from '@/components/icon/LucideIcon.vue'
import { toast } from "vue3-toastify"; 


// import Form Field 
import HbSelect from '@/components/form-fields/HbSelect.vue' 
import MailNotifications from '@/components/notifications/MailNotifications.vue'



//  Load Time Zone 
const skeleton = ref(true);   
const host = ref(true);
const attendee = ref(false);
const popup = ref(false);
const isPopupOpen = () => {
    popup.value = true;
}
const isPopupClose = (data) => {
    popup.value = false;
}

const Notification = reactive(  { 
     host : {
        booking_confirmation: {
            status : 0,
            template : 'default',
            form : '',
            subject : '',
            body : '',

        },
        booking_cancel: {
            status : 0,
            template : 'default',
            form : '',
            subject : '',
            body : '',

        },
        booking_reschedule: {
            status : 0,
            template : 'default',
            form : '',
            subject : '',
            body : '',

        },
        booking_reminder: {
            status : 0,
            template : 'default',
            form : '',
            subject : '',
            body : '',

        },
    
     },
     attendee : {
        booking_confirmation: {
            status : 0,
            template : 'default',
            form : '',
            subject : '',
            body : '',

        },
        booking_cancel: {
            status : 0,
            template : 'default',
            form : '',
            subject : '',
            body : '',

        },
        booking_reschedule: {
            status : 0,
            template : 'default',
            form : '',
            subject : '',
            body : '',

        },
        booking_reminder: {
            status : 0,
            template : 'default',
            form : '',
            subject : '',
            body : '',

        },
    
     }
});


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


// Fetch Notification
const fetchNotification = async () => {

    try { 
        const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/notification');
        if (response.data.status) { 
            // console.log(response.data.integration_settings);
            Notification.host = response.data.notification_settings.host ? response.data.notification_settings.host : Notification.host; 
            Notification.attendee = response.data.notification_settings.attendee ? response.data.notification_settings.attendee : Notification.attendee;
            
            
            skeleton.value = false;
        }
    } catch (error) {
        console.log(error);
    } 
}
const UpdateNotification = async () => {   

    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/notification/update', Notification, {
            headers: {
                'X-WP-Nonce': tfhb_core_apps.rest_nonce
            } 
        } );

        if (response.data.status) {    
            toast.success(response.data.message, {
                position: 'bottom-right', // Set the desired position
                "autoClose": 1500,
            }); 

            popup.value = false;
            
        }else{
            toast.error(response.data.message, {
                position: 'bottom-right', // Set the desired position
            });

            popup.value = false;
        }
    } catch (error) {
        toast.error('Action successful', {
            position: 'bottom-right', // Set the desired position
        });
    }
}
onBeforeMount(() => {  
    fetchNotification();
});

</script>
<template> 
    <div :class="{ 'tfhb-skeleton': skeleton }" class="thb-event-dashboard ">
  
        <div  class="tfhb-dashboard-heading ">
            <div class="tfhb-admin-title tfhb-m-0"> 
                <h1 >{{ $tfhb_trans['Integrations'] }}</h1> 
                <p>{{ $tfhb_trans['One-liner description'] }}</p>
            </div>
            <div class="thb-admin-btn right"> 
                <a href="#" target="_blank" class="tfhb-btn"> {{ $tfhb_trans['View Documentation'] }}<Icon name="ArrowUpRight" size="15" /></a>
            </div> 
        </div>
        <div class="tfhb-content-wrap">
            <!-- Gmail -->
            <div class="tfhb-notification-button-tabs tfhb-flexbox tfhb-mb-16">
                <button @click="changeTab" data-tab="host" class="tfhb-btn tfhb-notification-tabs boxed-secondary-btn flex-btn"  :class="host ? 'active' : ''" ><Icon name="UserRound" size="15" /> To Host </button>
                <button @click="changeTab"  data-tab="attendee" class="tfhb-btn tfhb-notification-tabs boxed-secondary-btn flex-btn" :class="attendee ? 'active' : ''"><Icon name="UsersRound" size="15" /> To Attendee </button>
            </div>
 
            <div v-if="host" class="tfhb-notification-wrap tfhb-notification-attendee tfhb-admin-card-box "> 
 
                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Host" 
                    label="Booking Confirmation" 
                    @update-notification="UpdateNotification"
                    :data="Notification.host.booking_confirmation"  
                    :ispopup="popup"
                    @popup-open-control="isPopupOpen"
                    @popup-close-control="isPopupClose"
                /> 
                <!-- Single Integrations  -->


                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Host" 
                    label="Booking Cancel" 
                    @update-notification="UpdateNotification"
                    :data="Notification.host.booking_cancel"  
                    :ispopup="popup"
                    @popup-open-control="isPopupOpen"
                    @popup-close-control="isPopupClose"
                /> 
                <!-- Single Integrations  -->

                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Host" 
                    label="Booking Reschedule" 
                    @update-notification="UpdateNotification"
                    :data="Notification.host.booking_reschedule"  
                    :ispopup="popup"
                    @popup-open-control="isPopupOpen"
                    @popup-close-control="isPopupClose"
                /> 
                <!-- Single Integrations  -->

                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Host" 
                    label="Booking Reminder" 
                    @update-notification="UpdateNotification"
                    :data="Notification.host.booking_reminder"  
                    :ispopup="popup"
                    @popup-open-control="isPopupOpen"
                    @popup-close-control="isPopupClose"
                /> 
                <!-- Single Integrations  -->
 
 
            </div> 
            <div v-if="attendee"  class="tfhb-notification-wrap tfhb-notification-host tfhb-admin-card-box "> 

                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Attendee" 
                    label="Booking Confirmation" 
                    @update-notification="UpdateNotification"
                    :data="Notification.attendee.booking_confirmation"  
                    :ispopup="popup"
                    @popup-open-control="isPopupOpen"
                    @popup-close-control="isPopupClose"
                /> 
                <!-- Single Integrations  -->


                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Attendee" 
                    label="Booking Cancel" 
                    @update-notification="UpdateNotification"
                    :data="Notification.attendee.booking_cancel"  
                    :ispopup="popup"
                    @popup-open-control="isPopupOpen"
                    @popup-close-control="isPopupClose"
                /> 
                <!-- Single Integrations  -->

                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Attendee" 
                    label="Booking Reschedule" 
                    :data="Notification.attendee.booking_reschedule"  
                    :ispopup="popup"
                    @popup-open-control="isPopupOpen"
                    @popup-close-control="isPopupClose"
                /> 
                <!-- Single Integrations  -->

                <!-- Single Notification  -->
                <MailNotifications 
                    title="Send Email to Attendee" 
                    label="Booking Reminder" 
                    @update-notification="UpdateNotification"
                    :data="Notification.attendee.booking_reminder"  
                    :ispopup="popup"
                    @popup-open-control="isPopupOpen"
                    @popup-close-control="isPopupClose"
                /> 
                <!-- Single Integrations  -->
 
 
            </div> 


        </div>
    </div>
 
</template>