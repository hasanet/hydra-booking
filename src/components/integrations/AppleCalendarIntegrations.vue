<script setup>

import { ref, reactive, onBeforeMount, } from 'vue'; 
import Icon from '@/components/icon/LucideIcon.vue'

// import Form Field 
import HbText from '@/components/form-fields/HbText.vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue';
import HbPopup from '@/components/widgets/HbPopup.vue';  

const props = defineProps([
    'apple_calendar', 
    'class', 
    'display', 
    'ispopup'
])
const emit = defineEmits([ "update-integrations", ]); 

const closePopup = () => { 
    emit('popup-close-control', false)
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
        <div class="tfhb-integrations-single-block-btn tfhb-flexbox"   >
            <!-- <button  class="tfhb-btn tfhb-flexbox tfhb-gap-8">Connected  </button> -->
               
                <HbSwitch   @change="emit('update-integrations', 'apple_calendar', apple_calendar)" v-model="apple_calendar.connection_status"    />
            <!-- Swicher --> 
        </div>
 
    </div>  
    <!-- Single Integrations  -->

</template>
 

<style scoped>
</style> 