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
import AppleCalendarIntegrations from '@/components/integrations/AppleCalendarIntegrations.vue'; 
import StripeIntegrations from '@/components/integrations/StripeIntegrations.vue'; 
import MailchimpIntegrations from '@/components/integrations/MailchimpIntegrations.vue'; 
import PaypalIntegrations from '@/components/integrations/PaypalIntegrations.vue'; 

// import Form Field 
import Icon from '@/components/icon/LucideIcon.vue' 

//  Load Time Zone 
const skeleton = ref(true);
 
 

const popup = ref(false);
const gpopup = ref(false);
const spopup = ref(false);
const mailpopup = ref(false);
const outlookpopup = ref(false);
const paypalpopup = ref(false);

const currentHash = ref('all'); 
 
// tfhb-hydra-admin-tabs a clicked using javascript event
document.addEventListener('click', function (event) {
    if (event.target.matches('.integrations-submenu')) {
        // .tfhb-integrations-settings-menu add class expand
        document.querySelector('.tfhb-integrations-settings-menu').classList.add('expand');

        currentHash.value = event.target.getAttribute('data-filter');
        // this add class active to the clicked element
        document.querySelectorAll('.dropdown a').forEach(function (el) {
            el.classList.remove('active');
            // 
        });
        event.target.classList.add('active');
    }
}, false);

 

 
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

const ispaypalPopupOpen = () => {
    paypalpopup.value = true;
}
const ispaypalPopupClose = (data) => {
    paypalpopup.value = false;
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
        type: 'calendar', 
        status: 0, 
        connection_status: 0,
        client_id: '',
        secret_key: '',
        redirect_url: '',

    },
    outlook_calendar : {
        type: 'calendar', 
        status: 0, 
        connection_status: 0,
        client_id: '',
        secret_key: '',
        redirect_url: '',

    },
    apple_calendar : {
        type: 'calendar', 
        status: 0,
        connection_status: 0,
    },
    stripe : {
        type: 'stripe', 
        status: 0, 
        public_key: '',
        secret_key: '',
    },
    mailchimp : {
        type: 'mailchimp', 
        status: 0, 
        key: ''
    },
    paypal : {
        type: 'paypal', 
        status: 0, 
        client_id: '',
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
            Integration.apple_calendar= response.data.integration_settings.apple_calendar ? response.data.integration_settings.apple_calendar : Integration.apple_calendar;

            Integration.stripe= response.data.integration_settings.stripe ? response.data.integration_settings.stripe : Integration.stripe;
            Integration.mailchimp= response.data.integration_settings.mailchimp ? response.data.integration_settings.mailchimp : Integration.mailchimp;
            Integration.paypal= response.data.integration_settings.paypal ? response.data.integration_settings.paypal : Integration.paypal;

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
            mailpopup.value = false;
            paypalpopup.value = false;
            
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
    // if currentHash == all 
   
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
                
                <WooIntegrations :woo_payment="Integration.woo_payment" @update-integrations="UpdateIntegration" v-if="currentHash === 'all' || currentHash === 'payments'"/>

                <!-- Woo Integrations  -->

                <!-- zoom intrigation -->
                <ZoomIntregration 
                :zoom_meeting="Integration.zoom_meeting" 
                @update-integrations="UpdateIntegration" 
                :ispopup="popup"
                @popup-open-control="isPopupOpen"
                @popup-close-control="isPopupClose"
                v-if="currentHash === 'all' || currentHash === 'conference'"
                />
                <!-- zoom intrigation -->

                <!-- zoom intrigation -->
                <GoogleCalendarIntegrations 
                :google_calendar="Integration.google_calendar" 
                @update-integrations="UpdateIntegration"
                :ispopup="gpopup"
                @popup-open-control="isgPopupOpen"
                @popup-close-control="isgPopupClose" 
                v-if="currentHash === 'all' || currentHash === 'calendars'"
                />
                <!-- zoom intrigation -->
                 
                <!-- Outlook intrigation -->
                <OutlookCalendarIntegrations 
                :outlook_calendar="Integration.outlook_calendar" 
                @update-integrations="UpdateIntegration"
                :ispopup="outlookpopup"
                @popup-open-control="isOutlookPopupOpen"
                @popup-close-control="isOutlookPopupClose" 
                v-if="currentHash === 'all' || currentHash === 'calendars'"
                />
                <!-- Outlook intrigation -->

                <!-- Apple intrigation -->
                <AppleCalendarIntegrations 
                :apple_calendar="Integration.apple_calendar" 
                @update-integrations="UpdateIntegration"
                :ispopup="outlookpopup" 
                v-if="currentHash === 'all' || currentHash === 'calendars'"
                />
                <!-- Apple intrigation -->

                <!-- stripe intrigation -->
                <StripeIntegrations 
                :stripe_data="Integration.stripe" 
                @update-integrations="UpdateIntegration" 
                :ispopup="spopup"
                @popup-open-control="isstripePopupOpen"
                @popup-close-control="isstripePopupClose" 
                v-if="currentHash === 'all' || currentHash === 'payments'"
                />
                <!-- stripe intrigation -->

                <!-- Mailchimp intrigation -->
                <MailchimpIntegrations 
                :mail_data="Integration.mailchimp" 
                @update-integrations="UpdateIntegration" 
                :ispopup="mailpopup"
                @popup-open-control="ismailchimpPopupOpen"
                @popup-close-control="ismailchimpPopupClose" 
                v-if="currentHash === 'all' || currentHash === 'all'"
                />
                <!-- Mailchimp intrigation -->

                <!-- paypal intrigation -->
                <PaypalIntegrations 
                :paypal_data="Integration.paypal" 
                @update-integrations="UpdateIntegration" 
                :ispopup="paypalpopup"
                @popup-open-control="ispaypalPopupOpen"
                @popup-close-control="ispaypalPopupClose" 
                v-if="currentHash === 'all' || currentHash === 'payments'"
                />
                <!-- paypal intrigation -->
          

            </div> 


        </div>
    </div>
 
</template>