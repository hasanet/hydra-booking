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
const gCalPopup = ref(false);

 

const props = defineProps([
    'google_calendar', 
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
                <img :src="$tfhb_url+'/assets/images/google-calendar.png'" alt="">
            </span> 

            <div class="cartbox-text">
                <h3>{{ $tfhb_trans['Google Calendar'] }}</h3> 
                <p>{{ $tfhb_trans['New standard in online payment'] }}</p>

            </div>
        </div> 
        <div class="tfhb-integrations-single-block-btn tfhb-flexbox">
            <!-- Checke -->
            <!-- <button @click="gCalPopup = true" class="tfhb-btn tfhb-flexbox tfhb-gap-8">{{ google_calendar.connection_status == 1 ? 'Connected' : 'Connect'  }} <Icon name="ChevronRight" size="18" /></button> -->
             <!-- a tag for get access token  -->
            <!-- <a   :href="'https://accounts.google.com/o/oauth2/auth?scope=https://www.googleapis.com/auth/calendar&redirect_uri='+google_calendar.redirect_url+'&response_type=code&client_id='+google_calendar.client_id+'&access_type=online'" target="_blank"class="tfhb-btn tfhb-flexbox tfhb-gap-8">Get Access Token</a> -->
            <button   v-if="google_calendar.connection_status == 1 && google_calendar.tfhb_google_calendar !== undefined "  @click="gCalPopup = true" class="tfhb-btn tfhb-flexbox tfhb-gap-8">{{ $tfhb_trans['Settings'] }}  <Icon name="ChevronRight" size="18" /></button>
             
            <a v-else :href="google_calendar.access_url" target="_blank"class="tfhb-btn tfhb-flexbox tfhb-gap-8">{{ $tfhb_trans['Get Access Token'] }}</a>

        </div>

        <HbPopup v-if="google_calendar.tfhb_google_calendar !== undefined " :isOpen="gCalPopup" @modal-close="gCalPopup = false" max_width="800px" name="first-modal">
            <template #header> 
                <!-- {{ google_calendar }} -->
                <h3>{{ $tfhb_trans['Google Calendar'] }}</h3>
                <p v-if="google_calendar.tfhb_google_calendar.email">{{ google_calendar.tfhb_google_calendar.email }}</p>
                
            </template>

            <template #content>  
                <p>
                    {{ $tfhb_trans['Enable the calendars you want to check for conflicts to prevent double bookings.'] }}
                </p> 
                <div class="tfhb-admin-card-box tfhb-flexbox  tfhb-gap-16  tfhb-m-0"  >   
                  
                    <HbRadio 
                        v-model="google_calendar.selected_calendar_id" 
                        :groups="true" 
                        :object="true"  
                        :name="'tfhb_calendar_items_'+index"
                        :options="storedOptionData(google_calendar.tfhb_google_calendar.items)"
                    />  
                </div>
                <div class="tfhb-submission-btn tfhb-mt-8 tfhb-mb-8">
                    <button class="tfhb-btn boxed-btn tfhb-flexbox"   @click.stop="emit('update-integrations', 'google_calendar', google_calendar)">{{ $tfhb_trans['Update Host Settings'] }} </button>
                </div> 
            </template> 
        </HbPopup>

    </div>  
    <!-- Single Integrations  -->

</template>
 

<style scoped>
</style> 