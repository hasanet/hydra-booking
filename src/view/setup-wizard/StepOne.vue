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

const StepOne = () => {
    if(props.setupWizard.data.business_type == ''){
        toast.error('Please select your business type');
        return;

    }
    
    props.setupWizard.currentStep = 'step-two';
}


</script>

<template>
    <!-- Step One -->
    <div class="tfhb-setup-wizard-content-wrap tfhb-s-w-step-one tfhb-flexbox">
       
        <div class="tfhb-s-w-icon-text">
            <div class="tfhb-step-wizard-steper tfhb-flexbox tfhb-gap-16" >
                <span class="tfhb-step-bar step-1 active"></span>
                <span class="tfhb-step-bar step-1 "></span>
                <span class="tfhb-step-bar step-1 "></span>
                <span class="tfhb-step-bar step-1 "></span>
            </div>
            <h2>Simplify Your Work with Hydrabooking</h2>
            <p>Our intuitive setup process makes Hydrabooking a breeze to use, even for non-technical users.</p>
        </div>
        <div class="tfhb-s-w-getting-email">

           
             <HbDropdown 
                    
                    v-model="props.setupWizard.data.business_type"  
                    required= "true"  
                    :label="'Your business type?'"   
                    selected = "1"
                    placeholder="Select Your Business Type"  
                    :option = "[
                        {'name': 'Consultent', 'value': 'consultent'}, 
                        {'name': 'Doctor', 'value': 'doctor'}, 
                        {'name': 'Teacher', 'value': 'teacher'}
                    ]"
                    
                />
        </div>
        <div class="tfhb-submission-btn tfhb-flexbox">
            <button @click="props.setupWizard.currentStep = 'getting-start'" class="tfhb-btn secondary-btn tfhb-flexbox tfhb-gap-8" > <Icon name="ChevronLeft" size="20" /> Back </button>

            <button @click="StepOne" class="tfhb-btn boxed-btn tfhb-flexbox tfhb-gap-8" >Next<Icon name="ChevronRight" size="20" />  </button>
        </div>
     </div>
     <!-- Step One -->
</template>
 