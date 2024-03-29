<script setup> 
// Use children routes for the tabs 
import { ref, reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView,} from 'vue-router' 
import axios from 'axios' 
import { toast } from "vue3-toastify"; 

// component
import ZoomIntregration from '@/components/Integrations/ZoomIntegrations.vue';

// import Form Field 
import HbSelect from '@/components/form-fields/HbSelect.vue' 
import HbText from '@/components/form-fields/HbText.vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue';
import HbPopup from '@/components/widgets/HbPopup.vue'; 
import Icon from '@/components/icon/LucideIcon.vue' 

//  Load Time Zone 
const skeleton = ref(false);
 
onBeforeMount(() => {  
});

// Const for Modal
const wooPopup = ref(false);


const Integration = reactive( {
    woo_payment : {
        type: 'payment', 
        status: 1, 
        connection_status: 1,  
    }
});


</script>
<template>
    
    <div :class="{ 'tfhb-skeleton': skeleton }" class="thb-event-dashboard ">
{{ Integration }}
        <div  class="tfhb-dashboard-heading ">
            <div class="tfhb-admin-title"> 
                <h1 >{{ $tfhb_trans['Integrations'] }}</h1> 
                <p>{{ $tfhb_trans['One-liner description'] }}</p>
            </div>
            <div class="thb-admin-btn right"> 
                <a href="#" target="_blank" class="tfhb-btn tfhb-flexbox tfhb-gap-8"> {{ $tfhb_trans['View Documentation'] }}<Icon name="ArrowUpRight" size="15px" /></a>
            </div> 
        </div>
        <div class="tfhb-content-wrap"> 
            <div class="tfhb-integrations-wrap tfhb-flexbox">

                <!-- Woo  Integrations  -->
                <div class="tfhb-integrations-single-block tfhb-admin-card-box ">
                    <span class="tfhb-integrations-single-block-icon">
                        <img :src="$tfhb_url+'/assets/images/Woo.png'" alt="">
                    </span> 

                    <h3 >Woo Payment</h3>
                    <p>New standard in online payment</p>


                    <div class="tfhb-integrations-single-block-btn tfhb-flexbox">
                        <button @click="wooPopup = true" class="tfhb-btn tfhb-flexbox tfhb-gap-8">{{ Integration.woo_payment.connection_status == 1 ? 'connected' : 'connect'  }} <Icon name="ChevronRight" size="15px" /></button>
                         <!-- Checkbox swicher -->

                         <HbSwitch v-if="Integration.woo_payment.connection_status"  v-model="Integration.woo_payment.status"    />
                        <!-- Swicher --> 
                    </div>

                    <HbPopup :isOpen="wooPopup" @modal-close="wooPopup = false" max_width="600px" name="first-modal">
                        <template #header> 
                            <h1>Woo Payment Integrations</h1>
                        </template>
                        <template #content>

                        </template> 
                    </HbPopup>

                </div>  
                <!-- Woo Integrations  -->
                <!-- zoom intrigation -->
                <ZoomIntregration />
                <!-- zoom intrigation -->
          

            </div> 


        </div>
    </div>
 
</template>