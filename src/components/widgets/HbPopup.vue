<script setup>
import { ref, reactive, onBeforeMount, } from 'vue'; 
import { useRouter, RouterView,} from 'vue-router' 
import axios from 'axios' 
import Icon from '@/components/icon/LucideIcon.vue'
import HbText from '../form-fields/HbText.vue';
import HbSelect from '../form-fields/HbSelect.vue';
import HbDateTime from '../form-fields/HbDateTime.vue';
import { toast } from "vue3-toastify"; 
 

const props = defineProps({
  isOpen: Boolean,
  availabilityDataSingle: {},
  timeZone: {}, 
  max_width: {
    type: String,
    default: '600px'
  },
  gap: {
    type: String,
    default: '24px'
  },
});
const emit = defineEmits([ "modal-close" ]); 
 
</script>
 

<template>
    <div v-if="isOpen" class="tfhb-popup">
        <div class="tfhb-popup-wrap" :style="{ 'max-width': max_width }">
            <div  class="tfhb-dashboard-heading tfhb-flexbox tfhb-m-0">
                <div class="tfhb-admin-title"> 
                    <slot name="header"> default header </slot>
                </div>
                <div class="thb-admin-btn"> 
                    <span class="tfhb-popup-close tfhb-cursor-pointer" @click.stop="emit('modal-close')"><Icon name="X" size="20px" /> </span> 
                </div> 
            </div>
            <div class="tfhb-content-wrap tfhb-flexbox" :style="{ 'gap': gap }">  
                <slot name="content"> default content </slot>
            </div> 
        </div> 
    </div>
</template>
  