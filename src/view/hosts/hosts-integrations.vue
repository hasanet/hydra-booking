<script setup>
import { ref, reactive, onBeforeMount } from 'vue';
import { useRouter, useRoute, RouterView } from 'vue-router' 
import axios from 'axios'  
import { toast } from "vue3-toastify"; 

// Get Current Route url
const currentRoute = useRouter().currentRoute.value.path;
import ZoomIntregration from '@/components/integrations/ZoomIntegrations.vue';
import ZohoIntegrations from '@/components/hosts/ZohoIntegrations.vue';
import StripeIntegrations from '@/components/integrations/StripeIntegrations.vue';
import MailchimpIntegrations from '@/components/integrations/MailchimpIntegrations.vue'; 
import PaypalIntegrations from '@/components/integrations/PaypalIntegrations.vue'; 

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
const paypalpopup = ref(false);
const spopup = ref(false);
const zohopopup = ref(false);
const mailpopup = ref(false);
const isPopupOpen = () => {
    popup.value = true;
}
const isPopupClose = (data) => {
    popup.value = false;
}
const ispaypalPopupOpen = () => {
    paypalpopup.value = true;
}
const ispaypalPopupClose = (data) => {
    paypalpopup.value = false;
}
const isZohoPopupOpen = () => {
    zohopopup.value = true;
}
const isZohoPopupClose = (data) => {
    zohopopup.value = false;
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
        connection_status: 0, 
        public_key: '',
        secret_key: ''
    },
    paypal : {
        type: 'paypal', 
        environment: '',
        status: 0, 
        connection_status: 0, 
        client_id: '',
        secret_key: '',
    },
    mailchimp : {
        type: 'mailchimp', 
        status: 0, 
        connection_status: 0, 
        key: ''
    },
    zoho : {
        type: 'zoho', 
        status: 0, 
        client_id: '',
        client_secret: '',
        redirect_url: '',
        access_token: '',
        refresh_token: '',
        modules: ''
    },
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
            Integration.zoho = response.data.zoho  ? response.data.zoho  : Integration.zoho ;  
            Integration.paypal = response.data.paypal  ? response.data.paypal  : Integration.paypal ;  
            Integration.zoho = response.data.zoho  ? response.data.zoho  : Integration.zoho ;  
            

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
            paypalpopup.value = false;
            spopup.value = false;
            mailpopup.value = false;
            zohopopup.value = false;
            
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
        <ZoomIntregration display="list" class="tfhb-flexbox tfhb-host-integrations" 
        :zoom_meeting="Integration.zoom_meeting" 
        v-if="Integration.zoom_meeting.connection_status == 1"
        @update-integrations="UpdateIntegration"
        :ispopup="popup"
        @popup-open-control="isPopupOpen"
        @popup-close-control="isPopupClose" 
        />


        <StripeIntegrations display="list" class="tfhb-flexbox tfhb-host-integrations"  
        v-if="Integration.stripe.connection_status == 1"
        :stripe_data="Integration.stripe" 
        @update-integrations="UpdateIntegration"
        :ispopup="spopup"
        @popup-open-control="isstripePopupOpen"
        @popup-close-control="isstripePopupClose"
        />

        <!-- paypal intrigation -->
        <PaypalIntegrations display="list" class="tfhb-flexbox tfhb-host-integrations"
        v-if="Integration.paypal.connection_status == 1"
        :paypal_data="Integration.paypal" 
        @update-integrations="UpdateIntegration" 
        :ispopup="paypalpopup"
        @popup-open-control="ispaypalPopupOpen"
        @popup-close-control="ispaypalPopupClose" 
        />
        <!-- paypal intrigation -->

        <!-- Mailchimp intrigation -->
        <MailchimpIntegrations display="list" class="tfhb-flexbox tfhb-host-integrations" 
        v-if="Integration.mailchimp.connection_status == 1"
        :mail_data="Integration.mailchimp" 
        @update-integrations="UpdateIntegration" 
        :ispopup="mailpopup"
        @popup-open-control="ismailchimpPopupOpen"
        @popup-close-control="ismailchimpPopupClose" 
        />
        <!-- Mailchimp intrigation -->

        <!-- Zoho intrigation -->
        <ZohoIntegrations display="list" class="tfhb-flexbox tfhb-host-integrations"  
        :zoho_data="Integration.zoho" 
        v-if="Integration.zoho.connection_status == 1"
        @update-integrations="UpdateIntegration" 
        :ispopup="zohopopup"
        :host_id="props.hostId"
        @popup-open-control="isZohoPopupOpen"
        @popup-close-control="isZohoPopupClose" 
        />
        <!-- Zoho intrigation -->

    </div> 
</template>


 