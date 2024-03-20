<script setup> 
// Use children routes for the tabs 
import { ref, reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView,} from 'vue-router' 
import axios from 'axios' 
import Icon from '@/components/icon/LucideIcon.vue'
import { toast } from "vue3-toastify"; 


// import Form Field 
import HbSelect from '@/components/form-fields/HbSelect.vue'

 

//  Load Time Zone 
const skeleton = ref(false);
 
onBeforeMount(() => {  
});


</script>
<template>
    
    <div :class="{ 'tfhb-skeleton': skeleton }" class="thb-event-dashboard ">
  
        <div  class="tfhb-dashboard-heading ">
            <div class="tfhb-admin-title"> 
                <h1 >{{ $tfhb_trans['Integrations'] }}</h1> 
                <p>{{ $tfhb_trans['One-liner description'] }}</p>
            </div>
            <div class="thb-admin-btn right"> 
                <a href="#" target="_blank" class="tfhb-btn"> {{ $tfhb_trans['View Documentation'] }}<Icon name="ArrowUpRight" size="15px" /></a>
            </div> 
        </div>
        <div class="tfhb-content-wrap">
            <div class="tfhb-notification-button-tabs tfhb-flexbox">
                <button class="tfhb-btn boxed-btn flex-btn"><Icon name="UserRound" size="15px" /> To host </button>
                <button class="tfhb-btn boxed-btn flex-btn"><Icon name="UsersRound" size="15px" /> To Attendee </button>
            </div>

            <div class="tfhb-notification-wrap tfhb-admin-card-box ">

                <!-- Single Notification  -->
                <div class="tfhb-notification-single tfhb-flexbox">
                    <div class="tfhb-swicher-wrap  tfhb-flexbox">
                        <!-- Checkbox swicher -->
                        <label class="switch">
                            <input id="swicher" v-model="date_status" true-value="1"  type="checkbox">
                            <span class="slider"></span>
                        </label>
                        <label class="tfhb-schedule-swicher"  for="swicher">Booking Confirmation</label>
                        <!-- Checkbox Swicher -->
                    </div>

                    <button class="tfhb-btn boxed-btn flex-btn"><Icon name="PencilLine" size="15px" /> Edit </button>
                </div>
                <!-- Single Integrations  -->

                <!-- Single Notification  -->
                <div class="tfhb-notification-single tfhb-flexbox">
                    <div class="tfhb-swicher-wrap  tfhb-flexbox">
                        <!-- Checkbox swicher -->
                        <label class="switch">
                            <input id="swicher" v-model="date_status" true-value="1"  type="checkbox">
                            <span class="slider"></span>
                        </label>
                        <label class="tfhb-schedule-swicher"  for="swicher">Booking Cancel</label>
                        <!-- Checkbox Swicher -->
                    </div>

                    <button class="tfhb-btn boxed-btn flex-btn"><Icon name="PencilLine" size="15px" /> Edit </button>
                </div>
                <!-- Single Integrations  -->
 
            </div> 


        </div>
    </div>
 
</template>