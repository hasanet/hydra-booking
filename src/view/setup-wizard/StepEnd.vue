<script setup>
import { ref, reactive, onBeforeMount } from 'vue';
import axios from 'axios' 
import { RouterView } from 'vue-router' 
import HbText from '@/components/form-fields/HbText.vue'
import HbDropdown from '@/components/form-fields/HbDropdown.vue'
import HbDateTime from '@/components/form-fields/HbDateTime.vue';
import Icon from '@/components/icon/LucideIcon.vue'
import HbCheckbox from '@/components/form-fields/HbCheckbox.vue';
import { setupWizard } from '@/store/setupWizard';


// component
import ZoomIntregration from '@/components/integrations/ZoomIntegrations.vue';
import WooIntegrations from '@/components/integrations/WooIntegrations.vue';
import GoogleCalendarIntegrations from '@/components/integrations/GoogleCalendarIntegrations.vue'; 
import OutlookCalendarIntegrations from '@/components/integrations/OutlookCalendarIntegrations.vue'; 
import AppleCalendarIntegrations from '@/components/integrations/AppleCalendarIntegrations.vue'; 
import StripeIntegrations from '@/components/integrations/StripeIntegrations.vue'; 
import MailchimpIntegrations from '@/components/integrations/MailchimpIntegrations.vue'; 
import PaypalIntegrations from '@/components/integrations/PaypalIntegrations.vue'; 

// Toast
import { toast } from "vue3-toastify"; 
//  Load Time Zone 
const skeleton = ref(true);
const props = defineProps({
    setupWizard : {
        type: Object,
        required: true
    }
}); 



const popup = ref(false);
const gpopup = ref(false);
const spopup = ref(false);
const mailpopup = ref(false);
const outlookpopup = ref(false);
const paypalpopup = ref(false);

const currentHash = ref('all'); 
 
const selectedFilterIntegrations = (e) => {
    // remove all display none css 
    document.querySelectorAll('.tfhb-integrations-single-block').forEach((integration) => {
        integration.style.display = '';
    });
    currentHash.value = e.target.getAttribute('data-filter');

    // change the label
    document.querySelector('.tfhb-s-w-integrations-dropdown span').innerText = e.target.innerText;
}

const FilterBySearch = (e) => {
    // show only input thext mathc besd on .tfhb-integrations-single-block h3 text
    let search = e.target.value;
    currentHash.value = 'all';
    document.querySelector('.tfhb-s-w-integrations-dropdown span').innerText = 'All Integrations';
    //
    let integrations = document.querySelectorAll('.tfhb-integrations-single-block');
    integrations.forEach((integration) => {
        let title = integration.querySelector('h3').innerText;
        if (title.toLowerCase().indexOf(search.toLowerCase()) > -1) {
            integration.style.display = '';
        } else {
            integration.style.display = 'none';
        }
    });
    //


    console.log(e.target.value);
}
 
const RedirectToDeshboard = () => {
    // redirect with windows reload 
    // remove body class
    document.querySelector('body').classList.remove('tfhb-setup-wizard-body');

    window.location.href = $tfhb_hydra_admin_url; 
    // w
}

 
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
        environment: '',
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
 

    <!-- Step End -->
    <div  class="tfhb-setup-wizard-content-wrap tfhb-hydra-dasboard-content tfhb-s-w-step-end tfhb-flexbox">
        <div class="tfhb-s-w-icon-text">
            <img :src="$tfhb_url+'/assets/images/hydra-booking-logo.png'" alt="">
            <h2>Congratulations! You're All Set Up!</h2>
            <p>You have successfully installed and activated Hydrabooking, configured your settings, connected your calendar, customized your booking forms, and embedded them on your website.</p>
        </div>
        <div class="tfhb-s-w-step-end tfhb-flexbox">

            <div class="tfhb-s-w-integrations-bar tfhb-flexbox">
                <div class="tfhb-s-w-integrations-dropdown tfhb-dropdown tfhb-flexbox tfhb-gap-8 ">
                    <span>All Integrations </span>  <Icon name="ChevronDown" size="20" /> 
                    <div class="tfhb-dropdown-wrap"> 
                        <span @click="selectedFilterIntegrations" data-filter="all"> All Integrations</span>
                        <span @click="selectedFilterIntegrations"  data-filter="conference"> Conference</span>
                        <span @click="selectedFilterIntegrations"  data-filter="calendars"> Calendars</span>
                        <span @click="selectedFilterIntegrations"  data-filter="payments"> Payments</span>
                    </div>
                </div>
                <div class="tfhb-integrations-searchbar">
                    <input @keyup="FilterBySearch" type="text" placeholder="Search Integrations">
                    <Icon name="ChevronRight" size="20" /> 
                </div>
            </div>

            <div class="tfhb-flexbox tfhb-s-w-integrations-wrap">

                <!-- Woo  Integrations  -->

                <WooIntegrations
                display="list" class="tfhb-flexbox tfhb-host-integrations"
                :woo_payment="Integration.woo_payment" @update-integrations="UpdateIntegration" v-if="currentHash === 'all' || currentHash === 'payments'"/>

                <!-- Woo Integrations  -->

                <!-- zoom intrigation -->
                <ZoomIntregration display="list" class="tfhb-flexbox tfhb-host-integrations"
                :zoom_meeting="Integration.zoom_meeting" 
                @update-integrations="UpdateIntegration" 
                :ispopup="popup"
                @popup-open-control="isPopupOpen"
                @popup-close-control="isPopupClose"
                v-if="currentHash === 'all' || currentHash === 'conference'"
                />
                <!-- zoom intrigation -->

                <!-- zoom intrigation -->
                <GoogleCalendarIntegrations display="list" class="tfhb-flexbox tfhb-host-integrations"
                :google_calendar="Integration.google_calendar" 
                @update-integrations="UpdateIntegration"
                :ispopup="gpopup"
                @popup-open-control="isgPopupOpen"
                @popup-close-control="isgPopupClose" 
                v-if="currentHash === 'all' || currentHash === 'calendars'"
                />
                <!-- zoom intrigation -->
                
                <!-- Outlook intrigation -->
                <OutlookCalendarIntegrations display="list" class="tfhb-flexbox tfhb-host-integrations"
                :outlook_calendar="Integration.outlook_calendar" 
                @update-integrations="UpdateIntegration"
                :ispopup="outlookpopup"
                @popup-open-control="isOutlookPopupOpen"
                @popup-close-control="isOutlookPopupClose" 
                v-if="currentHash === 'all' || currentHash === 'calendars'"
                />
                <!-- Outlook intrigation -->

                <!-- Apple intrigation -->
                <AppleCalendarIntegrations display="list" class="tfhb-flexbox tfhb-host-integrations"
                :apple_calendar="Integration.apple_calendar" 
                @update-integrations="UpdateIntegration"
                :ispopup="outlookpopup" 
                v-if="currentHash === 'all' || currentHash === 'calendars'"
                />
                <!-- Apple intrigation -->

                <!-- stripe intrigation -->
                <StripeIntegrations display="list" class="tfhb-flexbox tfhb-host-integrations"
                :stripe_data="Integration.stripe" 
                @update-integrations="UpdateIntegration" 
                :ispopup="spopup"
                @popup-open-control="isstripePopupOpen"
                @popup-close-control="isstripePopupClose" 
                v-if="currentHash === 'all' || currentHash === 'payments'"
                />
                <!-- stripe intrigation -->

                <!-- Mailchimp intrigation -->
                <MailchimpIntegrations display="list" class="tfhb-flexbox tfhb-host-integrations"
                :mail_data="Integration.mailchimp" 
                @update-integrations="UpdateIntegration" 
                :ispopup="mailpopup"
                @popup-open-control="ismailchimpPopupOpen"
                @popup-close-control="ismailchimpPopupClose" 
                v-if="currentHash === 'all' || currentHash === 'all'"
                />
                <!-- Mailchimp intrigation -->

                <!-- paypal intrigation -->
                <PaypalIntegrations display="list" class="tfhb-flexbox tfhb-host-integrations"
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
        <div class="tfhb-submission-btn tfhb-flexbox">
             <a @click="RedirectToDeshboard" :href="$tfhb_hydra_admin_url + ''"  class="tfhb-btn boxed-btn tfhb-flexbox tfhb-flexbox tfhb-gap-8" > Visit Dashboard <Icon name="ChevronRight" size="20" /> </a>
            
        </div>
     </div>
     <!-- Step End-->


</template>
 