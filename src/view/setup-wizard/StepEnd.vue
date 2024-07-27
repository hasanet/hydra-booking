<script setup>
import { RouterView } from 'vue-router' 
import HbText from '@/components/form-fields/HbText.vue'
import HbDropdown from '@/components/form-fields/HbDropdown.vue'
import HbDateTime from '@/components/form-fields/HbDateTime.vue';
import Icon from '@/components/icon/LucideIcon.vue'
import HbCheckbox from '@/components/form-fields/HbCheckbox.vue';
import { setupWizard } from '@/store/setupWizard';

// Toast
import { toast } from "vue3-toastify"; 

const props = defineProps({
    setupWizard : {
        type: Object,
        required: true
    }
}); 


</script>

<template>
 

    <!-- Step End -->
    <div class="tfhb-setup-wizard-content-wrap tfhb-s-w-geting-start tfhb-flexbox">
        <div class="tfhb-s-w-icon-text">
            <img :src="$tfhb_url+'/assets/images/hydra-booking-logo.png'" alt="">
            <h2>Congratulations! You're All Set Up!</h2>
            <p>You have successfully installed and activated Hydrabooking, configured your settings, connected your calendar, customized your booking forms, and embedded them on your website.</p>
        </div>
        <div class="tfhb-s-w-getting-email">

             <!-- Custom Duration -->
             <HbText  
                v-model="email"  
                :label="'Email'"  
                name="title"
                type="email"
                selected = "1"
                :placeholder="'Enter your email'"  
            /> 
             <!-- Custom Duration -->
            <HbCheckbox 
                v-model="swich_status"  
                type="checkbox" 
                required= "true" 
                :label="'Receive updates and promotions'" 
            />
        </div>
        <div class="tfhb-submission-btn">
            <button class="tfhb-btn boxed-btn tfhb-flexbox" >Visit Dashboard<Icon name="ChevronRight" size="20" />  </button>
        </div>
     </div>
     <!-- Step End-->


</template>
 