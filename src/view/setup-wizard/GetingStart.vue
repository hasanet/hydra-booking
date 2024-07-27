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

const GettingStart = () => {
    if(props.setupWizard.data.email == ''){
        toast.error('Email is required');
        return;

    }
    
    props.setupWizard.currentStep = 'step-one';
}

</script>

<template>
   
    <!-- Getting Start -->
    <div class="tfhb-setup-wizard-content-wrap tfhb-s-w-geting-start tfhb-flexbox">
        <div class="tfhb-s-w-icon-text">
            <img :src="$tfhb_url+'/assets/images/hydra-booking-logo.png'" alt="">
            <h2>Welcome to Hydrabooking!</h2>
            <p>Thank you for choosing Hydrabooking, the ultimate solution for seamless appointment and meeting bookings.</p>
        </div>
        <div class="tfhb-s-w-getting-email">

             <!-- Custom Duration -->
             <HbText  
                v-model="props.setupWizard.data.email"  
                :label="'Email'"  
                name="title"
                type="email"
                selected = "1"
                :placeholder="'Enter your email'"  
            /> 
             <!-- Custom Duration -->
            <HbCheckbox 
                v-model="props.setupWizard.data.enable_recevie_updates"  
                type="checkbox" 
                required= "true" 
                :label="'Receive updates and promotions'" 
            />
        </div>
        <div class="tfhb-submission-btn">
            <button @click="GettingStart" class="tfhb-btn boxed-btn tfhb-flexbox" >Get Started in a Minute <Icon name="ChevronRight" size="20" />  </button>
        </div>
     </div>
     <!-- Getting Start -->
</template>
 