<script setup>
import { ref, reactive, onBeforeMount } from 'vue';
import { useRouter, useRoute, RouterView } from 'vue-router' 
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue' 
import { toast } from "vue3-toastify"; 

// Get Current Route url
const currentRoute = useRouter().currentRoute.value.path;
import ZoomIntregration from '@/components/integrations/ZoomIntegrations.vue';
import GoogleCalendarIntegrations from '@/components/hosts/GoogleCalendarIntegrations.vue';
import OutlookCalendarIntegrations from '@/components/hosts/OutlookCalendarIntegrations.vue';
import AppleCalendarIntegrations from '@/components/hosts/AppleCalendarIntegrations.vue';
import StripeIntegrations from '@/components/integrations/StripeIntegrations.vue';
import MailchimpIntegrations from '@/components/integrations/MailchimpIntegrations.vue'; 

const route = useRoute();
//  Load Time Zone 
const skeleton = ref(true);
const props = defineProps({
    hostId: {
        type: Number,
        required: true
    },
    host: {
        type: Object,
        required: true
    },
    time_zone:{}

});


const popup = ref(false);
const gpopup = ref(false);
const spopup = ref(false);
const outlookpopup = ref(false);
const mailpopup = ref(false);
const isPopupOpen = () => {
    popup.value = true;
}
const isPopupClose = (data) => {
    popup.value = false;
}
const isgPopupOpen = () => {
    gpopup.value = true;
}
const isgPopupClose = (data) => {
    gpopup.value = false;
}
const isOutlookPopupOpen = () => {
    outlookpopup.value = true;
}
const isOutlookPopupClose = (data) => {
    outlookpopup.value = false;
}
const isstripePopupOpen = () => {
    spopup.value = true;
}
const isstripePopupClose = (data) => {
    spopup.value = false;
}
const ismailchimpPopupOpen = () => {
    mailpopup.value = true;
}
const ismailchimpPopupClose = (data) => {
    mailpopup.value = false;
}


const Integration = reactive( {
    woo_payment : {
        type: 'payment', 
        status: 0, 
        connection_status: 0,  
    },
    zoom_meeting : {
        type: 'meeting', 
        status: 0, 
        connection_status: 0,
        account_id: '',
        app_client_id: '',
        app_secret_key: '',

    },
    google_calendar : {
        type: 'meeting', 
        status: 0, 
        connection_status: 0, 
        selected_calendar_id: '', 
        tfhb_google_calendar: {},

    },
    outlook_calendar : {
        type: 'meeting', 
        status: 0, 
        connection_status: 0, 
        selected_calendar_id: '', 
        tfhb_outlook_calendar: {},

    },
    apple_calendar : {
        type: 'calendar', 
        status: 0,
        connection_status: 0, 
        apple_id: '',
        app_password: '',

    },
    stripe : {
        type: 'stripe', 
        status: 0, 
        public_key: '',
        secret_key: ''
    },
    mailchimp : {
        type: 'mailchimp', 
        status: 0, 
        key: ''
    }
});
 

 // Fetch generalSettings
const fetchIntegration = async () => { 
    let data = {
        id: route.params.id,
        user_id: props.host.user_id,
    };  
    try { 

        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/integration', data, {
            headers: {
                'X-WP-Nonce': tfhb_core_apps.rest_nonce,
                'capability': 'tfhb_manage_integrations'
            } 
        } );

        if (response.data.status) {   
            Integration.zoom_meeting= response.data.integration_settings.zoom_meeting ? response.data.integration_settings.zoom_meeting : Integration.zoom_meeting;
            Integration.google_calendar= response.data.google_calendar ? response.data.google_calendar : Integration.google_calendar;  
            Integration.outlook_calendar = response.data.outlook_calendar  ? response.data.outlook_calendar  : Integration.outlook_calendar ;  
            Integration.apple_calendar = response.data.apple_calendar  ? response.data.apple_calendar  : Integration.apple_calendar ;  
            Integration.mailchimp = response.data.mailchimp  ? response.data.mailchimp  : Integration.mailchimp ;  
            Integration.stripe = response.data.stripe  ? response.data.stripe  : Integration.stripe ;  
            

            skeleton.value = false;
        }
    } catch (error) {
        console.log(error);
    } 
}
const UpdateIntegration = async (key, value) => { 
    let data = {
        key: key,
        value: value,
        id: route.params.id,
        user_id: props.host.user_id,
    };   
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/integration/update', data, {
            headers: {
                'X-WP-Nonce': tfhb_core_apps.rest_nonce,
                'capability': 'tfhb_manage_integrations'
            } 
        } );

        if (response.data.status) {     
            toast.success(response.data.message, {
                position: 'bottom-right', // Set the desired position
                "autoClose": 1500,
            }); 
            
            popup.value = false;
            gpopup.value = false;
            spopup.value = false;
            mailpopup.value = false;
            
        }else{
            toast.error(response.data.message, {
                position: 'bottom-right', // Set the desired position
            });
        }
    } catch (error) {
        toast.error(error.message, {
            position: 'bottom-right', // Set the desired position
        });
    }
}
onBeforeMount(() => {  
    fetchIntegration();
});
</script>

<template>
    <div class="tfhb-admin-card-box tfhb-m-0">    
        <!-- Host Integration -->
        <GoogleCalendarIntegrations v-if="Integration.google_calendar.status == '1'" display="list" class="tfhb-flexbox tfhb-host-integrations" :google_calendar="Integration.google_calendar" @update-integrations="UpdateIntegration" />
        <OutlookCalendarIntegrations v-if="Integration.outlook_calendar.status == '1'" display="list" class="tfhb-flexbox tfhb-host-integrations" :outlook_calendar="Integration.outlook_calendar" @update-integrations="UpdateIntegration" />
        <AppleCalendarIntegrations display="list" class="tfhb-flexbox tfhb-host-integrations" :apple_calendar="Integration.apple_calendar" @update-integrations="UpdateIntegration" />
 

    </div> 
</template>


 