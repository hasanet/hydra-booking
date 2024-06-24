<script setup>

import { ref, reactive, onBeforeMount, } from 'vue'; 
import { useRouter, RouterView,} from 'vue-router' 
import Icon from '@/components/icon/LucideIcon.vue'

// import Form Field 
import HbSelect from '@/components/form-fields/HbSelect.vue' 
import HbText from '@/components/form-fields/HbText.vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue';
import HbPopup from '@/components/widgets/HbPopup.vue';  
import HbRadio from '@/components/form-fields/HbRadio.vue';
const outlookCalPopup = ref(false);

 

const props = defineProps([
    'apple_calendar', 
    'class', 
    'display', 
])
const emit = defineEmits([ "update-integrations", ]);  

const storedOptionData = (data) => {
    let options = [];
    // data suild be array single array
    data.forEach((item, index) => {  
        options.push({
            value: item.id,
            label: item.title,
        });
    }); 
    return options;
}
</script>
 
<template>  
      <!-- Zoom Integrations  -->
      <div :class="props.class" class="tfhb-integrations-single-block tfhb-admin-card-box ">
         <div :class="display =='list' ? 'tfhb-flexbox' : '' " class="tfhb-admin-cartbox-cotent">
            <span class="tfhb-integrations-single-block-icon">
                <img :src="$tfhb_url+'/assets/images/ical.png'" alt="" >
            </span> 

            <div class="cartbox-text">
                <h3>{{ $tfhb_trans['Apple Calendar'] }}</h3> 
                <p>{{ $tfhb_trans['New standard in online payment'] }}</p>

            </div>
        </div> 
        <div class="tfhb-integrations-single-block-btn tfhb-flexbox">
            <!-- Checke -->
            <!-- <button @click="gCalPopup = true" class="tfhb-btn tfhb-flexbox tfhb-gap-8">{{ outlook_calendar.connection_status == 1 ? 'Connected' : 'Connect'  }} <Icon name="ChevronRight" size="18" /></button> -->
             <!-- a tag for get access token  -->
            <!-- <a   :href="'https://accounts.google.com/o/oauth2/auth?scope=https://www.googleapis.com/auth/calendar&redirect_uri='+outlook_calendar.redirect_url+'&response_type=code&client_id='+outlook_calendar.client_id+'&access_type=online'" target="_blank"class="tfhb-btn tfhb-flexbox tfhb-gap-8">Get Access Token</a> -->
            <button v-if="apple_calendar.connection_status == 1 "  @click="outlookCalPopup = true" class="tfhb-btn tfhb-flexbox tfhb-gap-8">{{ $tfhb_trans['Settings'] }}  <Icon name="ChevronRight" size="18" /></button>
             
      

        </div>

        <HbPopup  :isOpen="outlookCalPopup" @modal-close="outlookCalPopup = false" max_width="800px" name="first-modal">
            <template #header> 
                <!-- {{ outlook_calendar }} -->
                <h3>{{ $tfhb_trans['Apple Calendar'] }}</h3> 
                
            </template>

            <template #content>  
                <p>
                    {{ $tfhb_trans['Enable the calendars you want to check for conflicts to prevent double bookings.'] }}
                </p> 
                <HbText  
                    v-model="apple_calendar.apple_id"  
                    required= "true"  
                    :label="$tfhb_trans['Apple ID (Email)']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Enter Apple ID (Email)']"  
                /> 
                <HbText  
                    v-model="apple_calendar.app_password"  
                    required= "true"  
                    :label="$tfhb_trans['App Specific Password']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Enter App Specific Password']"  
                />
                <div class="tfhb-submission-btn tfhb-mt-8 tfhb-mb-8">
                    <button class="tfhb-btn boxed-btn tfhb-flexbox"   @click.stop="emit('update-integrations', 'apple_calendar', apple_calendar)">{{ $tfhb_trans['Update Host Settings'] }} </button>
                </div> 
            </template> 
        </HbPopup>

    </div>  
    <!-- Single Integrations  -->

</template>
 

<style scoped>
</style> 