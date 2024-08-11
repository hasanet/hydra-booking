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
        <span class="tfhb-badge tfhb-badge-pro">{{ $tfhb_trans['Pro'] }}</span>
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
            <a href="#" class="tfhb-btn tfhb-flexbox tfhb-gap-8">{{ $tfhb_trans['Upgrade to Pro'] }}  <Icon name="ChevronRight" size="18" /></a>
 
        </div>
 
    </div>  
    <!-- Single Integrations  -->

</template>
 

<style scoped>
</style> 