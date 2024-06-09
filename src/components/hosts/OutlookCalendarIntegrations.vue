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
    'outlook_calendar', 
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
                <img :src="$tfhb_url+'/assets/images/outlook-calendar.png'" alt="" >
            </span> 

            <div class="cartbox-text">
                <h3>{{ $tfhb_trans['Outlook Calendar'] }}</h3> 
                <p>{{ $tfhb_trans['New standard in online payment'] }}</p>

            </div>
        </div> 
        <div class="tfhb-integrations-single-block-btn tfhb-flexbox">
            <!-- Checke -->
            <!-- <button @click="gCalPopup = true" class="tfhb-btn tfhb-flexbox tfhb-gap-8">{{ outlook_calendar.connection_status == 1 ? 'Connected' : 'Connect'  }} <Icon name="ChevronRight" size="18" /></button> -->
             <!-- a tag for get access token  -->
            <!-- <a   :href="'https://accounts.google.com/o/oauth2/auth?scope=https://www.googleapis.com/auth/calendar&redirect_uri='+outlook_calendar.redirect_url+'&response_type=code&client_id='+outlook_calendar.client_id+'&access_type=online'" target="_blank"class="tfhb-btn tfhb-flexbox tfhb-gap-8">Get Access Token</a> -->
            <button   v-if="outlook_calendar.connection_status == 1 && outlook_calendar.tfhb_outlook_calendar !== undefined "  @click="outlookCalPopup = true" class="tfhb-btn tfhb-flexbox tfhb-gap-8">{{ $tfhb_trans['Settings'] }}  <Icon name="ChevronRight" size="18" /></button>
             
            <a v-else :href="outlook_calendar.access_url" target="_blank"class="tfhb-btn tfhb-flexbox tfhb-gap-8">{{ $tfhb_trans['Get Access Token'] }}</a>

        </div>

        <HbPopup v-if="outlook_calendar.tfhb_outlook_calendar !== undefined " :isOpen="outlookCalPopup" @modal-close="outlookCalPopup = false" max_width="800px" name="first-modal">
            <template #header> 
                <!-- {{ outlook_calendar }} -->
                <h3>{{ $tfhb_trans['Google Calendar'] }}</h3>
                <p v-if="outlook_calendar.tfhb_outlook_calendar.email">{{ outlook_calendar.tfhb_outlook_calendar.email }}</p>
                
            </template>

            <template #content>  
                <p>
                    {{ $tfhb_trans['Enable the calendars you want to check for conflicts to prevent double bookings.'] }}
                </p> 
                <div class="tfhb-admin-card-box tfhb-flexbox  tfhb-gap-16  tfhb-m-0"  >   
                  
                    <HbRadio 
                        v-model="outlook_calendar.selected_calendar_id" 
                        :groups="true" 
                        :object="true"  
                        :name="'tfhb_calendar_items_'+index"
                        :options="storedOptionData(outlook_calendar.tfhb_outlook_calendar.items)"
                    />  
                </div>
                <div class="tfhb-submission-btn tfhb-mt-8 tfhb-mb-8">
                    <button class="tfhb-btn boxed-btn tfhb-flexbox"   @click.stop="emit('update-integrations', 'outlook_calendar', outlook_calendar)">{{ $tfhb_trans['Update Host Settings'] }} </button>
                </div> 
            </template> 
        </HbPopup>

    </div>  
    <!-- Single Integrations  -->

</template>
 

<style scoped>
</style> 