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
    'forminator_data', 
    'ispopup'
])
const emit = defineEmits([ "update-integrations", 'popup-open-control', 'popup-close-control' ]); 

const closePopup = () => { 
    emit('popup-close-control', false)
}

</script>

<template>
      <!-- Forminator Integrations  -->
      <div :class="props.class" class="tfhb-integrations-single-block tfhb-admin-card-box "> 
         <div :class="display =='list' ? 'tfhb-flexbox' : '' " class="tfhb-admin-cartbox-cotent">
            <span class="tfhb-integrations-single-block-icon">
                <img :src="$tfhb_url+'/assets/images/forminator.png'" alt="">
            </span> 


            <div class="cartbox-text">
                <h3>{{ $tfhb_trans['Forminator'] }}</h3>
                <p>{{ $tfhb_trans['New standard in online payment'] }}</p>
            </div>
        </div>
        <div class="tfhb-integrations-single-block-btn tfhb-flexbox">
            <HbSwitch  @change="emit('update-integrations', 'forminator', forminator_data)" v-model="forminator_data.status"    />
        </div>

 
    </div>  
    <!-- Single Integrations  -->

</template>

<style scoped>
</style> 