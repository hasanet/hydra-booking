<script setup>

import { ref, reactive, onBeforeMount, } from 'vue'; 
import { useRouter, RouterView,} from 'vue-router' 
import Icon from '@/components/icon/LucideIcon.vue'

// import Form Field 
import HbSelect from '@/components/form-fields/HbSelect.vue' 
import HbText from '@/components/form-fields/HbText.vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue';
import HbPopup from '@/components/widgets/HbPopup.vue';  

const gCalPopup = ref(false);

 

const props = defineProps([
    'google_calendar', 
])
const emit = defineEmits([ "update-integrations", ]); 


</script>

<template>
      <!-- Zoom Integrations  -->
      <div class="tfhb-integrations-single-block tfhb-admin-card-box ">
        <span class="tfhb-integrations-single-block-icon">
            <img :src="$tfhb_url+'/assets/images/google-calendar.png'" alt="">
        </span> 

        <h3>Google Calender</h3> 
        <p>New standard in online payment</p>

        <div class="tfhb-integrations-single-block-btn tfhb-flexbox">
            <button @click="gCalPopup = true" class="tfhb-btn tfhb-flexbox tfhb-gap-8">{{ google_calendar.connection_status == 1 ? 'Connected' : 'Connect'  }} <Icon name="ChevronRight" size="18" /></button>
                <!-- Checkbox swicher -->

                <HbSwitch v-if="google_calendar.connection_status" @change="emit('update-integrations', 'google_calendar', google_calendar)" v-model="google_calendar.status"    />
            <!-- Swicher --> 
        </div>

        <HbPopup :isOpen="gCalPopup" @modal-close="gCalPopup = false" max_width="600px" name="first-modal">
            <template #header> 
                <!-- {{ google_calendar }} -->
                <h2>Add Google Calendar</h2>
                
            </template>

            <template #content>  
                <p>
                    Please read the documentation here for step by step guide to know how you can get api credentials from Google Calendar
                </p>
                <HbText  
                    v-model="google_calendar.client_id"  
                    required= "true"  
                    :label="$tfhb_trans['Client ID']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Enter Client ID']"  
                /> 
                <HbText  
                    v-model="google_calendar.secret_key"  
                    required= "true"  
                    :label="$tfhb_trans['Secret Key']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Enter Secret Key']"  
                /> 
                <HbText  
                    v-model="google_calendar.redirect_url"  
                    required= "true"  
                    :label="$tfhb_trans['Redirect Url']"  
                    selected = "1" 
                    :placeholder="$tfhb_trans['Enter Redirect Url']"  
                /> 
                <button class="tfhb-btn boxed-btn" @click.stop="emit('update-integrations', 'google_calendar', google_calendar)">{{ $tfhb_trans['Save & Validate'] }}</button>
            </template> 
        </HbPopup>

    </div>  
    <!-- Single Integrations  -->

</template>

<style scoped>
</style> 