<script setup>

import { ref, reactive, onBeforeMount, } from 'vue'; 
import { useRouter, RouterView,} from 'vue-router'  
import HbCheckbox from '@/components/form-fields/HbCheckbox.vue';


const informationPopup = ref(false);
const emit = defineEmits(["update-meeting", "limits-frequency-add"]); 
const props = defineProps({
    hostsSettings : {
        type: Object,
        required: true
    }
}); 
 
 

// Extra Qestion Data
const questions_data =  reactive({});
 
</script>

<template>  
        <div class="tfhb-admin-card-box tfhb-flexbox  tfhb-gap-16  tfhb-m-0"  >   

            <HbCheckbox 
                v-model="hostsSettings.permission.tfhb_manage_dashboard"
                :label="$tfhb_trans['Host Can Manage Dashboard']"
                name="tfhb_manage_dashboard"
            /> 
            <HbCheckbox 
                v-model="hostsSettings.permission.tfhb_manage_meetings"
                :label="$tfhb_trans['Host Can Manage Meetings']"
                name="tfhb_manage_meetings"
            /> 
            <HbCheckbox 
                v-model="hostsSettings.permission.tfhb_manage_booking"
                :label="$tfhb_trans['Host Can Manage Booking']"
                name="tfhb_manage_booking"
            /> 
            <HbCheckbox 
                v-model="hostsSettings.permission.tfhb_manage_settings"
                :label="$tfhb_trans['Host Can Manage Settings']"
                name="tfhb_manage_settings"
            /> 
            <HbCheckbox 
                v-model="hostsSettings.permission.tfhb_manage_custom_availability"
                :label="$tfhb_trans['Host Can Manage Custom Availability']"
                name="tfhb_manage_custom_availability"
            /> 
            <HbCheckbox 
                v-model="hostsSettings.permission.tfhb_manage_integrations"
                :label="$tfhb_trans['Host Can Manage Integrations']"
                name="tfhb_manage_integrations"
            /> 
        </div>
        
</template>

<style scoped>
</style> 