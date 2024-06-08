<script setup> 
// Use children routes for the tabs 
import { ref, reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView,} from 'vue-router' 
import axios from 'axios' 
import { toast } from "vue3-toastify"; 

// component
import ZoomIntregration from '@/components/integrations/ZoomIntegrations.vue';
import WooIntegrations from '@/components/integrations/WooIntegrations.vue';
import GoogleCalendarIntegrations from '@/components/integrations/GoogleCalendarIntegrations.vue'; 
import OutlookCalendarIntegrations from '@/components/integrations/OutlookCalendarIntegrations.vue'; 
import StripeIntegrations from '@/components/integrations/StripeIntegrations.vue'; 

// import Form Field 
import Icon from '@/components/icon/LucideIcon.vue' 

//  Load Time Zone 
const skeleton = ref(true);
 
 

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
        client_id: '',
        secret_key: '',
        redirect_url: '',

    },
    outlook_calendar : {
        type: 'meeting', 
        status: 0, 
        connection_status: 0,
        client_id: '',
        secret_key: '',
        redirect_url: '',

    },
    stripe : {
        type: 'stripe', 
        status: 0, 
        secret_key: '',

    },
});

//  update Integration

// Fetch generalSettings
const fetchIntegration = async () => {

    try { 
        const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/integration');
        if (response.data.status) { 
            
            // console.log(response.data.integration_settings);
            Integration.zoom_meeting= response.data.integration_settings.zoom_meeting ? response.data.integration_settings.zoom_meeting : Integration.zoom_meeting;
            Integration.woo_payment= response.data.integration_settings.woo_payment ? response.data.integration_settings.woo_payment : Integration.woo_payment;
            Integration.google_calendar= response.data.integration_settings.google_calendar ? response.data.integration_settings.google_calendar : Integration.google_calendar;
            Integration.outlook_calendar= response.data.integration_settings.outlook_calendar ? response.data.integration_settings.outlook_calendar : Integration.outlook_calendar;

            Integration.stripe= response.data.integration_settings.stripe ? response.data.integration_settings.stripe : Integration.stripe;

            skeleton.value = false;
        }
    } catch (error) {
        console.log(error);
    } 
}
const UpdateIntegration = async (key, value) => { 
    let data = {
        key: key,
        value: value
    }; 
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/integration/update', data, {
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
            gpopup.value = false;
            spopup.value = false;
            spopup.value = false;
            
        }else{
            toast.error(response.data.message, {
                position: 'bottom-right', // Set the desired position
            });

            popup.value = false;
            gpopup.value = false;
            outlookpopup.value = false;
        }
    } catch (error) {
        toast.error('Action successful', {
            position: 'bottom-right', // Set the desired position
        });
    }
}
onBeforeMount(() => {  
    fetchIntegration();
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
                <a href="#" target="_blank" class="tfhb-btn tfhb-flexbox tfhb-gap-8"> {{ $tfhb_trans['View Documentation'] }}<Icon name="ArrowUpRight" size="15" /></a>
            </div> 
        </div>
        <div class="tfhb-content-wrap"> 
            <!-- {{ Integration }} -->
            <div class="tfhb-integrations-wrap tfhb-flexbox">

                <!-- Woo  Integrations  -->
                
                <WooIntegrations :woo_payment="Integration.woo_payment" @update-integrations="UpdateIntegration" />

                <!-- Woo Integrations  -->

                <!-- zoom intrigation -->
                <ZoomIntregration 
                :zoom_meeting="Integration.zoom_meeting" 
                @update-integrations="UpdateIntegration" 
                :ispopup="popup"
                @popup-open-control="isPopupOpen"
                @popup-close-control="isPopupClose"
                />
                <!-- zoom intrigation -->

                <!-- zoom intrigation -->
                <GoogleCalendarIntegrations 
                :google_calendar="Integration.google_calendar" 
                @update-integrations="UpdateIntegration"
                :ispopup="gpopup"
                @popup-open-control="isgPopupOpen"
                @popup-close-control="isgPopupClose" 
                />
                <!-- zoom intrigation -->
                 
                <!-- zoom intrigation -->
                <OutlookCalendarIntegrations 
                :outlook_calendar="Integration.outlook_calendar" 
                @update-integrations="UpdateIntegration"
                :ispopup="outlookpopup"
                @popup-open-control="isOutlookPopupOpen"
                @popup-close-control="isOutlookPopupClose" 
                />
                <!-- zoom intrigation -->

                <!-- stripe intrigation -->
                <StripeIntegrations 
                :stripe_data="Integration.stripe" 
                @update-integrations="UpdateIntegration" 
                :ispopup="spopup"
                @popup-open-control="isstripePopupOpen"
                @popup-close-control="isstripePopupClose" 
                />
                <!-- stripe intrigation -->
          

            </div> 


        </div>
    </div>
 
</template>