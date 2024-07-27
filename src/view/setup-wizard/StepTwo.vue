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
 
     <!-- Step Two -->
     <div class="tfhb-setup-wizard-content-wrap tfhb-s-w-step-two tfhb-flexbox">
        <div class="tfhb-step-wizard-steper tfhb-flexbox tfhb-gap-16" >
            <span class="tfhb-step-bar step-1 active"></span>
            <span class="tfhb-step-bar step-1 active"></span>
            <span class="tfhb-step-bar step-1 "></span>
            <span class="tfhb-step-bar step-1 "></span>
        </div>
        <div class="tfhb-s-w-icon-text">
           
            <h2>Take control of your schedule</h2>
            <p>You can streamline your appointment booking process by using your availability.</p>
        </div>
        <div class="tfhb-s-w-getting-email">

           
             <HbDropdown 
                    
                    v-model="business_type"  
                    required= "true"  
                    :label="'Your business type?'"   
                    width="50"
                    selected = "1"
                    placeholder="Select Time Format"  
                    :option = "[
                        {'name': 'Select One', 'value': 'Select one'}, 
                        {'name': 'Select Two', 'value': 'Select two'}, 
                        {'name': 'Select Three', 'value': 'Select three'}
                    ]"
                    
                />
        </div>
        <div class="tfhb-submission-btn tfhb-flexbox">
            <button class="tfhb-btn boxed-btn tfhb-flexbox" > <Icon name="ChevronLeft" size="20" /> Back </button>
            <button class="tfhb-btn boxed-btn tfhb-flexbox" >Next<Icon name="ChevronRight" size="20" />  </button>
            <button class="tfhb-btn boxed-btn tfhb-flexbox" >Skip<Icon name="ChevronRight" size="20" />  </button>
        </div>
     </div>
     <!-- Step Two -->

</template>
 