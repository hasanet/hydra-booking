<script setup> 
// Use children routes for the tabs 
import { ref, reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView,} from 'vue-router' 
import axios from 'axios' 
import { toast } from "vue3-toastify"; 

// component
import ZoomIntregration from '@/components/integrations/ZoomIntegrations.vue';

// import Form Field 
import HbSelect from '@/components/form-fields/HbSelect.vue' 
import HbText from '@/components/form-fields/HbText.vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue';
import HbPopup from '@/components/widgets/HbPopup.vue'; 
import Icon from '@/components/icon/LucideIcon.vue' 

//  Load Time Zone 
const skeleton = ref(false);
 
 

// Const for Modal
const wooPopup = ref(false);


const Integration = reactive( {
    woo_payment : {
        type: 'payment', 
        status: 1, 
        connection_status: 1,  
    },
    zoom_meeting : {
        type: 'meeting', 
        status: 0, 
        connection_status: 0,
        account_id: '',
        app_client_id: '',
        app_secret_key: '',

    },
});

//  update Integration

// Fetch generalSettings
const fetchIntegration = async () => {

    try { 
        const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/integration');
        if (response.data.status) { 
            // console.log(response.data.integration_settings);
            Integration.zoom_meeting= response.data.integration_settings.zoom_meeting;
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
            
        }else{
            toast.error(response.data.message, {
                position: 'bottom-right', // Set the desired position
            });
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
{{ Integration }}
        <div  class="tfhb-dashboard-heading ">
            <div class="tfhb-admin-title"> 
                <h1 >{{ $tfhb_trans['Integrations'] }}</h1> 
                <p>{{ $tfhb_trans['One-liner description'] }}</p>
            </div>
            <div class="thb-admin-btn right"> 
                <a href="#" target="_blank" class="tfhb-btn tfhb-flexbox tfhb-gap-8"> {{ $tfhb_trans['View Documentation'] }}<Icon name="ArrowUpRight" size="15px" /></a>
            </div> 
        </div>
        <div class="tfhb-content-wrap"> 
            <div class="tfhb-integrations-wrap tfhb-flexbox">

                <!-- Woo  Integrations  -->
                <div class="tfhb-integrations-single-block tfhb-admin-card-box ">
                    <span class="tfhb-integrations-single-block-icon">
                        <img :src="$tfhb_url+'/assets/images/Woo.png'" alt="">
                    </span> 

                    <h3 >Woo Payment</h3>
                    <p>New standard in online payment</p>


                    <div class="tfhb-integrations-single-block-btn tfhb-flexbox">
                        <button @click="wooPopup = true" class="tfhb-btn tfhb-flexbox tfhb-gap-8">{{ Integration.woo_payment.connection_status == 1 ? 'connected' : 'connect'  }} <Icon name="ChevronRight" size="15px" /></button>
                         <!-- Checkbox swicher -->

                         <HbSwitch v-if="Integration.woo_payment.connection_status"  v-model="Integration.woo_payment.status"    />
                        <!-- Swicher --> 
                    </div>

                    <HbPopup :isOpen="wooPopup" @modal-close="wooPopup = false" max_width="600px" name="first-modal">
                        <template #header> 
                            <h1>Woo Payment Integrations</h1>
                        </template>
                        <template #content>

                        </template> 
                    </HbPopup>

                </div>  
                <!-- Woo Integrations  -->

                <!-- zoom intrigation -->
                <ZoomIntregration :zoom_meeting="Integration.zoom_meeting" @update-integrations="UpdateIntegration" />
                <!-- zoom intrigation -->
          

            </div> 


        </div>
    </div>
 
</template>