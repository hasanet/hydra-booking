<script setup>

import { ref, reactive, onBeforeMount, } from 'vue'; 
import Icon from '@/components/icon/LucideIcon.vue'

// import Form Field 
import HbText from '@/components/form-fields/HbText.vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue';
import HbPopup from '@/components/widgets/HbPopup.vue';  

const props = defineProps([
    'outlook_calendar', 
    'class', 
    'display', 
    'ispopup'
])
const emit = defineEmits([ "update-integrations", 'popup-open-control', 'popup-close-control' ]); 

const closePopup = () => { 
    emit('popup-close-control', false)
}
</script>
 
<template>
      <!-- Zoom Integrations  -->
      <div :class="props.class" class="tfhb-integrations-single-block tfhb-admin-card-box ">
         <div :class="display =='list' ? 'tfhb-flexbox' : '' " class="tfhb-admin-cartbox-cotent">
            <span class="tfhb-integrations-single-block-icon">
                <img :src="$tfhb_url+'/assets/images/outlook-calendar.png'" alt="" >
            </span> 

            <div class="cartbox-text">
                <h3>{{ $tfhb_trans['Outlook Calendar'] }}</h3> 
                <p>{{ $tfhb_trans['New standard in online payment'] }}</p>

            </div>
        </div>
        <div class="tfhb-integrations-single-block-btn tfhb-flexbox">
            <button @click="emit('popup-open-control')" class="tfhb-btn tfhb-flexbox tfhb-gap-8">{{ outlook_calendar.connection_status == 1 ? 'Connected' : 'Connect'  }} <Icon name="ChevronRight" size="18" /></button>
                <!-- Checkbox swicher -->

                <HbSwitch v-if="outlook_calendar.connection_status" @change="emit('update-integrations', 'outlook_calendar', outlook_calendar)" v-model="outlook_calendar.status"    />
            <!-- Swicher --> 
        </div>

        <HbPopup :isOpen="ispopup" @modal-close="closePopup" max_width="600px" name="first-modal">
            <template #header> 
                <!-- {{ outlook_calendar }} -->
                <h2>{{ $tfhb_trans['Add Outlook Calendar'] }}</h2>
                
            </template>

            <template #content>  
                <p>
                    {{ $tfhb_trans['Please read the documentation here for step by step guide to know how you can get api credentials from Outlook Calendar'] }}
                    
                </p>
                <HbText  
                    v-model="outlook_calendar.client_id"  
                    required= "true"  
                    :label="$tfhb_trans['Client ID']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Enter Client ID']"  
                /> 
                <HbText  
                    v-model="outlook_calendar.secret_key"  
                    required= "true"  
                    :label="$tfhb_trans['Secret Key']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Enter Secret Key']"  
                /> 
                <HbText  
                    v-model="outlook_calendar.redirect_url"  
                    required= "true"  
                    :label="$tfhb_trans['Redirect Url']"  
                    selected = "1" 
                    :placeholder="$tfhb_trans['Enter Redirect Url']"  
                /> 
                <button class="tfhb-btn boxed-btn" @click.stop="emit('update-integrations', 'outlook_calendar', outlook_calendar)">{{ $tfhb_trans['Save & Validate'] }}</button>
            </template> 
        </HbPopup>

    </div>  
    <!-- Single Integrations  -->

</template>
 

<style scoped>
</style> 