<script setup>
import { ref, reactive, onBeforeMount, } from 'vue';  
import Icon from '@/components/icon/LucideIcon.vue' 
import HbSelect from '../form-fields/HbSelect.vue'; 
  
const props = defineProps({
  isOpen: Boolean, 
  usersData: {}, 
});
const host = reactive({});
const emit = defineEmits(["modal-close", "hosts-create"]); 

const CreateHosts = () => {   
    emit('hosts-create', host);
      
}


</script>

<template> 
    <div class="tfhb-popup"  v-if="isOpen" >
        <div class="tfhb-popup-wrap">
            <div  class="tfhb-dashboard-heading tfhb-flexbox">
                <div class="tfhb-admin-title"> 
                    <h2>{{$tfhb_trans['Add New Host']}}</h2>   
                </div>
                <div class="thb-admin-btn right"> 
                    <button class="tfhb-popup-close" @click.stop="emit('modal-close')"><Icon name="X" size="20px" /> </button> 
                </div> 
            </div>
            <div class="tfhb-content-wrap tfhb-flexbox">  

                <!-- Time Zone -->
                <HbSelect 
                        
                    v-model="host.id"  
                    required= "true"  
                    :label="$tfhb_trans['Select Hosts']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Select Hosts']" 
                    :option = "props.usersData" 
                /> 
                <!-- Time Zone --> 

                  <!-- Create Or Update Availability -->
                 <button class="tfhb-btn boxed-btn" @click="CreateHosts">{{ $tfhb_trans['Create Hosts'] }}</button>
            </div>
          
        </div>
    </div>

</template>

<style scoped>
</style> 