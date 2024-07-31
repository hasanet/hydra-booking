<script setup>
import { ref, reactive, onBeforeMount } from 'vue'; 
import { setupWizard } from '@/store/setupWizard';

// Component
import GettingStart from './GetingStart.vue';
import StepOne from './StepOne.vue';
import StepTwo from './StepTwo.vue';
import StepThree from './StepThree.vue';
import StepFour from './StepFour.vue';
import StepEnd from './StepEnd.vue';


// add class into body
document.body.classList.add('tfhb-setup-wizard-body');

onBeforeMount(() => { 
    setupWizard.fetchSetupWizard(); 
});

 
</script>

<template> 
   <div class="tfhb-setup-wizard tfhb-flexbox tfhb-hydra-wrap" 
        :style="'background-image: url(' + $tfhb_url+'/assets/images/setup-wizard.png' + ')'" 
        alt=""
    >
    
     <div class="tfhb-setup-wizard-header tfhb-flexbox">
        <div class="tfhb-s-w-logo">
            <img :src="$tfhb_url+'/assets/images/hydra-booking-logo.png'" alt="">
        </div>
        <div class="tfhb-s-w-cta tfhb-flexbox tfhb-gap-16">
            <a href="#">Need Help</a>
            <span></span>
            <a href="#">Faq</a>
        </div>
     </div>

     <!-- Getting Start Component -->
     <GettingStart 
        v-if="setupWizard.currentStep  == 'getting-start'"
        :setupWizard="setupWizard"

     />

     <!-- Step One -->
     <StepOne 
        v-if="setupWizard.currentStep  == 'step-one'"
        :setupWizard="setupWizard"
    />

    <!-- // Step two -->
    <StepTwo 
        v-if="setupWizard.currentStep  == 'step-two'"
        :setupWizard="setupWizard"
     />

     <!-- Step Three -->
    <StepThree 
        v-if="setupWizard.currentStep  == 'step-three'"
        :setupWizard="setupWizard"
    />

    <!-- Step Four -->
    <StepFour 
        v-if="setupWizard.currentStep  == 'step-four'"
        :setupWizard="setupWizard"
    />

    <!-- Step End -->
    <StepEnd 
        v-if="setupWizard.currentStep  == 'step-end'"
        :setupWizard="setupWizard"
    />
   </div>
</template>
 