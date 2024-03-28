<script setup>
import { ref, reactive, onBeforeMount, } from 'vue';  
import Icon from '@/components/icon/LucideIcon.vue' 
import HbSelect from '../form-fields/HbSelect.vue'; 
import HbText from '../form-fields/HbText.vue';
  
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

                <!-- Select User -->
                <HbSelect    
                    v-model="host.id"  
                    required= "true"  
                    :label="$tfhb_trans['Select User']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Select User']" 
                    :option = "props.usersData" 
                /> 
                <!-- Select User --> 
                <!-- UsernName -->
                <HbText  
                    v-if="host.id == 0"
                    v-model="host.username"  
                    required= "true"  
                    :label="$tfhb_trans['Username']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Type Username']"  
                /> 
                <!-- UsernName -->
                <!-- Email -->
                <HbText  
                v-if="host.id == 0"
                    v-model="host.email"  
                    required= "true"  
                    type= "email"  
                    :label="$tfhb_trans['Email']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Type User Email']"  
                /> 
                <!-- Email -->

                <!-- Password -->
                
                <HbText  
                    v-if="host.id == 0"
                    v-model="host.password"  
                    required= "true"  
                    type= "password"  
                    :label="$tfhb_trans['Password']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Type User Password']"  
                /> 
                <!-- Password -->
                

                  <!-- Create Or Update Availability -->
                 <button class="tfhb-btn boxed-btn" @click="CreateHosts">{{ $tfhb_trans['Create Hosts'] }}</button>
            </div>
          
        </div>
    </div>

</template>

<style scoped>
</style> 