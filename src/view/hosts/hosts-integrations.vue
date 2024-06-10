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
import StripeIntegrations from '@/components/integrations/StripeIntegrations.vue';

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
    stripe : {
        type: 'stripe', 
        status: 0, 
        public_key: '',
        secret_key: '',

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
    console.log(data);
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
            spopup.value = false;
            
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
        <!-- Woo  Integrations  --> 
        <ZoomIntregration display="list" class="tfhb-flexbox tfhb-host-integrations" :zoom_meeting="Integration.zoom_meeting" 
        @update-integrations="UpdateIntegration"
        :ispopup="popup"
        @popup-open-control="isPopupOpen"
        @popup-close-control="isPopupClose" 
        />

        <!-- Host Integration -->
        <GoogleCalendarIntegrations display="list" class="tfhb-flexbox tfhb-host-integrations" :google_calendar="Integration.google_calendar" @update-integrations="UpdateIntegration" />
        <OutlookCalendarIntegrations display="list" class="tfhb-flexbox tfhb-host-integrations" :outlook_calendar="Integration.outlook_calendar" @update-integrations="UpdateIntegration" />

        <StripeIntegrations display="list" class="tfhb-flexbox tfhb-host-integrations" 
        :stripe_data="Integration.stripe" 
        @update-integrations="UpdateIntegration"
        :ispopup="spopup"
        @popup-open-control="isstripePopupOpen"
        @popup-close-control="isstripePopupClose"
        />


    </div> 
</template>


 