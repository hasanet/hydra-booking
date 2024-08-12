<script setup>

import { ref, reactive, onBeforeMount, } from 'vue'; 
import Icon from '@/components/icon/LucideIcon.vue'

// import Form Field 
import HbText from '@/components/form-fields/HbText.vue' 
import HbPopup from '@/components/widgets/HbPopup.vue';  
import HbSwitch from '@/components/form-fields/HbSwitch.vue'; 

const props = defineProps([
    'class', 
    'display', 
    'cf7_data', 
    'ispopup'
])
const emit = defineEmits([ "update-integrations", 'popup-open-control', 'popup-close-control' ]); 

const closePopup = () => { 
    emit('popup-close-control', false)
}

</script>

<template>
      <!-- Contact Form 7 Integrations  -->
      <div :class="props.class" class="tfhb-integrations-single-block tfhb-admin-card-box "> 
         <div :class="display =='list' ? 'tfhb-flexbox' : '' " class="tfhb-admin-cartbox-cotent">
            <span class="tfhb-integrations-single-block-icon">
                <img :src="$tfhb_url+'/assets/images/cf7.png'" alt="">
            </span> 


            <div class="cartbox-text">
                <h3>{{ $tfhb_trans['Contact Form 7'] }}</h3>
                <p>{{ $tfhb_trans['New standard in online payment'] }}</p>
            </div>
        </div>
        <div class="tfhb-integrations-single-block-btn tfhb-flexbox">
            <HbSwitch  @change="emit('update-integrations', 'cf7', cf7_data)" v-model="cf7_data.status"    />
        </div>

 
    </div>  
    <!-- Single Integrations  -->

</template>

<style scoped>
</style> 