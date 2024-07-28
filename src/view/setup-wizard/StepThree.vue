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
 

     <!-- Step Three -->
     <div class="tfhb-setup-wizard-content-wrap tfhb-s-w-step-three tfhb-flexbox">
        <div class="tfhb-step-wizard-steper tfhb-flexbox tfhb-gap-16" >
            <span class="tfhb-step-bar step-1 active"></span>
            <span class="tfhb-step-bar step-1 active"></span>
            <span class="tfhb-step-bar step-1 active"></span>
            <span class="tfhb-step-bar step-1 "></span>
        </div>
        <div class="tfhb-s-w-icon-text">
           
            <h2>Experience Hydrabooking in Action (Instantly!)</h2>
            <p>Hydrabooking allows you to tailor the booking experience to perfectly suit your workflow.</p>
        </div>
        <div class="tfhb-s-w-getting-email">

            <button class="tfhb-btn boxed-btn tfhb-flexbox tfhb-gap-8" @click="props.setupWizard.importDemoMeeting" >Import demo data<Icon name="ChevronRight" size="20" />  </button>
             
        </div>
        <div class="tfhb-submission-btn tfhb-flexbox">
            <button class="tfhb-btn secondary-btn tfhb-flexbox tfhb-gap-8" > <Icon name="ChevronLeft" size="20" /> Back </button>
            <!-- <button class="tfhb-btn boxed-btn tfhb-flexbox tfhb-gap-8" >Next<Icon name="ChevronRight" size="20" />  </button> -->
        </div>
     </div>
     <!-- Step Three -->

</template>
 