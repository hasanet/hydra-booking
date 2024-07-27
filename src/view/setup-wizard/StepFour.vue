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
 

     <!-- Step Four -->
     <div class="tfhb-setup-wizard-content-wrap tfhb-s-w-step-four tfhb-flexbox">
        <div class="tfhb-step-wizard-steper tfhb-flexbox tfhb-gap-16" >
            <span class="tfhb-step-bar step-1 active"></span>
            <span class="tfhb-step-bar step-1 active"></span>
            <span class="tfhb-step-bar step-1 active"></span>
            <span class="tfhb-step-bar step-1 active"></span>
        </div>
        <div class="tfhb-s-w-icon-text">
           
            <h2>Your Meeting is ready!</h2>
            <p>All set! Your Hydrabooking meeting is good to go.   Click "Preview" to peek at your booking page or "Share" to send the link to your attendees</p>
        </div>
        <div class="tfhb-s-w-getting-email">

            <button class="tfhb-btn secondary-btn tfhb-flexbox" >Import demo data<Icon name="ChevronRight" size="20" />  </button>
             
        </div>
        <div class="tfhb-submission-btn tfhb-flexbox">
            <button class="tfhb-btn secondary-btn tfhb-flexbox" > <Icon name="ChevronLeft" size="20" /> Back </button>
            <button class="tfhb-btn boxed-btn tfhb-flexbox" >Complete setup<Icon name="ChevronRight" size="20" />  </button>
        </div>
     </div>
     <!-- Step Four -->

</template>
 