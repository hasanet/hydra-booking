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
        <ZoomIntregration display="list" class="tfhb-flexbox tfhb-host-integrations" :zoom_meeting="Integration.zoom_meeting" @update-integrations="UpdateIntegration" />

        <!-- Host Integration -->
        <GoogleCalendarIntegrations display="list" class="tfhb-flexbox tfhb-host-integrations" :google_calendar="Integration.google_calendar" @update-integrations="UpdateIntegration" />

        <div class="tfhb-admin-card-box tfhb-flexbox">   
            <div class="tfhb-admin-cartbox-cotent tfhb-flexbox">
                <svg width="56" height="32" viewBox="0 0 56 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_809_950)">
                    <path d="M21.9658 16.0003L25.7522 20.3282L30.8436 23.582L31.7314 16.0269L30.8436 8.64062L25.6545 11.4993L21.9658 16.0003Z" fill="#00832D"/>
                    <path d="M-0.00683594 22.8805V29.3169C-0.00683594 30.7884 1.18501 31.9802 2.6565 31.9802H9.0929L10.4246 27.1152L9.0929 22.8805L4.6762 21.5488L-0.00683594 22.8805Z" fill="#0066DA"/>
                    <path d="M9.0929 0.0195312L-0.00683594 9.11927L4.6762 10.4509L9.0929 9.11927L10.4024 4.94227L9.0929 0.0195312Z" fill="#E94235"/>
                    <path d="M9.0929 9.12012H-0.00683594V22.8807H9.0929V9.12012Z" fill="#2684FC"/>
                    <path d="M36.6588 3.87287L30.8438 8.64024V23.5816L36.6854 28.3711C37.5599 29.0547 38.8383 28.4311 38.8383 27.3191V4.90269C38.8383 3.77743 37.531 3.16042 36.6588 3.87287ZM21.9661 15.9999V22.8802H9.09326V31.98H28.1805C29.652 31.98 30.8438 30.7881 30.8438 29.3166V23.5816L21.9661 15.9999Z" fill="#00AC47"/>
                    <path d="M28.1805 0.0195312H9.09326V9.11927H21.9661V15.9996L30.8438 8.64431V2.68287C30.8438 1.21137 29.652 0.0195312 28.1805 0.0195312Z" fill="#FFBA00"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_809_950">
                    <rect width="56" height="32" fill="white"/>
                    </clipPath>
                    </defs>
                </svg> 
                <div class="cartbox-text">
                    <h3>Google Meet</h3>
                    <p>Configure Google Calendar / Meet to sync your events</p>
                </div>
            </div>
            <div class="tfhb-admin-cartbox-action tfhb-flexbox">
                <a href="" class="tfhb-btn tfhb-flexbox" >Connected <Icon name="ChevronRight" size="20" /> </a> 
                <div class="tfhb-swicher-wrap">
                    <!-- Checkbox swicher -->
                    <label class="switch">
                        <input id="swicher" v-model="status" true-value="1"  type="checkbox">
                        <span class="slider"></span>
                    </label> 
                    <!-- Swicher -->
                </div>
            </div>
         </div>

        <div class="tfhb-admin-card-box tfhb-flexbox">   
            <div class="tfhb-admin-cartbox-cotent tfhb-flexbox">
                <svg width="56" height="32" viewBox="0 0 56 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_809_950)">
                    <path d="M21.9658 16.0003L25.7522 20.3282L30.8436 23.582L31.7314 16.0269L30.8436 8.64062L25.6545 11.4993L21.9658 16.0003Z" fill="#00832D"/>
                    <path d="M-0.00683594 22.8805V29.3169C-0.00683594 30.7884 1.18501 31.9802 2.6565 31.9802H9.0929L10.4246 27.1152L9.0929 22.8805L4.6762 21.5488L-0.00683594 22.8805Z" fill="#0066DA"/>
                    <path d="M9.0929 0.0195312L-0.00683594 9.11927L4.6762 10.4509L9.0929 9.11927L10.4024 4.94227L9.0929 0.0195312Z" fill="#E94235"/>
                    <path d="M9.0929 9.12012H-0.00683594V22.8807H9.0929V9.12012Z" fill="#2684FC"/>
                    <path d="M36.6588 3.87287L30.8438 8.64024V23.5816L36.6854 28.3711C37.5599 29.0547 38.8383 28.4311 38.8383 27.3191V4.90269C38.8383 3.77743 37.531 3.16042 36.6588 3.87287ZM21.9661 15.9999V22.8802H9.09326V31.98H28.1805C29.652 31.98 30.8438 30.7881 30.8438 29.3166V23.5816L21.9661 15.9999Z" fill="#00AC47"/>
                    <path d="M28.1805 0.0195312H9.09326V9.11927H21.9661V15.9996L30.8438 8.64431V2.68287C30.8438 1.21137 29.652 0.0195312 28.1805 0.0195312Z" fill="#FFBA00"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_809_950">
                    <rect width="56" height="32" fill="white"/>
                    </clipPath>
                    </defs>
                </svg> 
                <div class="cartbox-text">
                    <h3>Microsoft Outlook</h3>
                    <p>Configure Google Calendar / Meet to sync your events</p>
                </div>
            </div>
            <div class="tfhb-admin-cartbox-action tfhb-flexbox">
                <a href="" class="tfhb-btn tfhb-flexbox" >Connect <Icon name="ChevronRight" size="20" /> </a> 
                 
            </div>
         </div> 

        <div class="tfhb-admin-card-box tfhb-flexbox">   
            <div class="tfhb-admin-cartbox-cotent tfhb-flexbox">
                <svg width="56" height="32" viewBox="0 0 56 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_809_950)">
                    <path d="M21.9658 16.0003L25.7522 20.3282L30.8436 23.582L31.7314 16.0269L30.8436 8.64062L25.6545 11.4993L21.9658 16.0003Z" fill="#00832D"/>
                    <path d="M-0.00683594 22.8805V29.3169C-0.00683594 30.7884 1.18501 31.9802 2.6565 31.9802H9.0929L10.4246 27.1152L9.0929 22.8805L4.6762 21.5488L-0.00683594 22.8805Z" fill="#0066DA"/>
                    <path d="M9.0929 0.0195312L-0.00683594 9.11927L4.6762 10.4509L9.0929 9.11927L10.4024 4.94227L9.0929 0.0195312Z" fill="#E94235"/>
                    <path d="M9.0929 9.12012H-0.00683594V22.8807H9.0929V9.12012Z" fill="#2684FC"/>
                    <path d="M36.6588 3.87287L30.8438 8.64024V23.5816L36.6854 28.3711C37.5599 29.0547 38.8383 28.4311 38.8383 27.3191V4.90269C38.8383 3.77743 37.531 3.16042 36.6588 3.87287ZM21.9661 15.9999V22.8802H9.09326V31.98H28.1805C29.652 31.98 30.8438 30.7881 30.8438 29.3166V23.5816L21.9661 15.9999Z" fill="#00AC47"/>
                    <path d="M28.1805 0.0195312H9.09326V9.11927H21.9661V15.9996L30.8438 8.64431V2.68287C30.8438 1.21137 29.652 0.0195312 28.1805 0.0195312Z" fill="#FFBA00"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_809_950">
                    <rect width="56" height="32" fill="white"/>
                    </clipPath>
                    </defs>
                </svg> 
                <div class="cartbox-text">
                    <h3>Apple Calendar</h3>
                    <p>Configure Google Calendar / Meet to sync your events</p>
                </div>
            </div>
            <div class="tfhb-admin-cartbox-action tfhb-flexbox">
                <a href="" class="tfhb-btn tfhb-flexbox" >Connect <Icon name="ChevronRight" size="20" /> </a> 
                 
            </div>
         </div> 
        
        <div class="tfhb-admin-card-box tfhb-flexbox tfhb-m-0">   
            <div class="tfhb-admin-cartbox-cotent tfhb-flexbox">
                <svg width="56" height="32" viewBox="0 0 56 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_809_950)">
                    <path d="M21.9658 16.0003L25.7522 20.3282L30.8436 23.582L31.7314 16.0269L30.8436 8.64062L25.6545 11.4993L21.9658 16.0003Z" fill="#00832D"/>
                    <path d="M-0.00683594 22.8805V29.3169C-0.00683594 30.7884 1.18501 31.9802 2.6565 31.9802H9.0929L10.4246 27.1152L9.0929 22.8805L4.6762 21.5488L-0.00683594 22.8805Z" fill="#0066DA"/>
                    <path d="M9.0929 0.0195312L-0.00683594 9.11927L4.6762 10.4509L9.0929 9.11927L10.4024 4.94227L9.0929 0.0195312Z" fill="#E94235"/>
                    <path d="M9.0929 9.12012H-0.00683594V22.8807H9.0929V9.12012Z" fill="#2684FC"/>
                    <path d="M36.6588 3.87287L30.8438 8.64024V23.5816L36.6854 28.3711C37.5599 29.0547 38.8383 28.4311 38.8383 27.3191V4.90269C38.8383 3.77743 37.531 3.16042 36.6588 3.87287ZM21.9661 15.9999V22.8802H9.09326V31.98H28.1805C29.652 31.98 30.8438 30.7881 30.8438 29.3166V23.5816L21.9661 15.9999Z" fill="#00AC47"/>
                    <path d="M28.1805 0.0195312H9.09326V9.11927H21.9661V15.9996L30.8438 8.64431V2.68287C30.8438 1.21137 29.652 0.0195312 28.1805 0.0195312Z" fill="#FFBA00"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_809_950">
                    <rect width="56" height="32" fill="white"/>
                    </clipPath>
                    </defs>
                </svg> 
                <div class="cartbox-text">
                    <h3>Nextcloud Calendar</h3>
                    <p>Configure Google Calendar / Meet to sync your events</p>
                </div>
            </div>
            <div class="tfhb-admin-cartbox-action tfhb-flexbox">
                <a href="" class="tfhb-btn tfhb-flexbox" >Connect <Icon name="ChevronRight" size="20" /> </a> 
                 
            </div>
         </div> 

    </div> 
</template>


 