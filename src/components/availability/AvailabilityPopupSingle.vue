<script setup>
import { ref, reactive, onBeforeMount, defineProps, defineEmits } from 'vue'; 
import { useRouter, RouterView,} from 'vue-router' 
import Icon from '@/components/icon/LucideIcon.vue'
import HbText from '../form-fields/HbText.vue';
import HbSelect from '../form-fields/HbSelect.vue';
import HbDateTime from '../form-fields/HbDateTime.vue';


const timeZone = reactive({});
const props = defineProps({
  isOpen: Boolean,
});
const emit = defineEmits(["modal-close"]); 
</script>
<style scoped>
</style> 
<template> 
   <div v-if="isOpen" class="tfhb-availability-popup">
        <div  class="tfhb-dashboard-heading ">
            <div class="tfhb-admin-title"> 
                <h2 >Add New Availability </h2>  
            </div>
            <div class="thb-admin-btn right"> 
                <button class="tfhb-availability-close" @click.stop="emit('modal-close')"><Icon name="X" size="20px" /> </button> 
            </div> 
        </div>
        <div class="tfhb-content-wrap tfhb-flexbox"> 
            <!-- Title -->
            <HbText  
                v-model="title"  
                required= "true"  
                :label="$tfhb_trans['Title']"  
                selected = "1"
                placeholder="Type your schedule title"   
            /> 
            <!-- Title -->
            <!-- Time Zone -->
            <HbSelect 
                    
                v-model="time_zone"  
                required= "true"  
                :label="$tfhb_trans['Time zone']"  
                selected = "1"
                placeholder="Select Time Zone"  
                :option = "timeZone.value" 
            /> 
            <!-- Time Zone --> 
        </div>
        <div class="tfhb-content-wrap tfhb-availability-content-wrap">  

            <div class="tfhb-admin-card-box ">  
                <div  class="tfhb-dashboard-heading ">
                    <div class="tfhb-admin-title"> 
                        <h2 >Weekly hours </h2>  
                    </div>
                    <div class="thb-admin-btn right"> 
                        <span>Asia/Dhaka</span> 
                    </div> 
                </div>
                <div class="tfhb-availability-schedule-single tfhb-flexbox">
                    <div class="tfhb-availability-schedule-swicher">
                         <!-- Checkbox swicher -->
                         <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                        <label> Monday</label>
                        <!-- Swicher -->
                    </div>
                    <div class="tfhb-availability-schedule-wrap tfhb-flexbox">
                        <div class="tfhb-availability-schedule-time tfhb-flexbox">
                            <HbDateTime  
                                v-model="title"   
                                :label="$tfhb_trans['start']"  
                                selected = "1"
                                width="50"
                                placeholder="Type your schedule title"   
                            /> 
                            <HbDateTime  
                                v-model="title"   
                                :label="$tfhb_trans['start']"  
                                selected = "1"
                                width="50"
                                placeholder="Type your schedule title"   
                            /> 

                         </div>
                         <div class="tfhb-availability-schedule-clone-single">
                            <button class="tfhb-availability-schedule-clone-btn"><Icon name="PlusCircle" size="15px" /> </button> 
                         </div>
                    </div>
                </div>
            </div>  
        </div>
   </div>
   
</template> 
<style scoped>
.switch {
    position: relative;
    display: inline-block;
    width: 32px;
    height: 20px; 
    margin-top: 0;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #E3CFD7;
    -webkit-transition: .4s;
    transition: .4s;
    border-radius: 10px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 14px;
    width: 14px;
    left: 4px;
    bottom: 3px;
    background-color: #FFFFFF;
    -webkit-transition: .4s;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #F62881;
}

input:focus + .slider {
    box-shadow: none;
}

input:checked + .slider:before {
    -webkit-transform: translateX(10px);
    -ms-transform: translateX(10px);
    transform: translateX(10px);
}

/* Rounded sliders */
.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}
</style> 
