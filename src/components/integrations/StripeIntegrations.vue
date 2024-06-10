<script setup>

import { ref, reactive, onBeforeMount, } from 'vue'; 
import Icon from '@/components/icon/LucideIcon.vue'

// import Form Field 
import HbText from '@/components/form-fields/HbText.vue' 
import HbPopup from '@/components/widgets/HbPopup.vue';  

const zoomPopup = ref(false);

const zoom_integration = reactive(  { 
    status: 0, 
    connection_status: 0,   
});

const props = defineProps([
    'class', 
    'display', 
    'stripe_data', 
    'ispopup'
])
const emit = defineEmits([ "update-integrations", 'popup-open-control', 'popup-close-control' ]); 

const closePopup = () => { 
    emit('popup-close-control', false)
}

</script>

<template>
      <!-- Stripe Integrations  -->
      <div :class="props.class" class="tfhb-integrations-single-block tfhb-admin-card-box ">
         <div :class="display =='list' ? 'tfhb-flexbox' : '' " class="tfhb-admin-cartbox-cotent">
            <span class="tfhb-integrations-single-block-icon">
                <img :src="$tfhb_url+'/assets/images/stripe.png'" alt="">
            </span> 

            <div class="cartbox-text">
                <h3>{{ $tfhb_trans['Stripe'] }}</h3>
                <p>{{ $tfhb_trans['New standard in online payment'] }}</p>
            </div>
        </div>
        <div class="tfhb-integrations-single-block-btn tfhb-flexbox">
            <button @click="emit('popup-open-control')" class="tfhb-btn tfhb-flexbox tfhb-gap-8">{{ stripe_data.secret_key ? 'Settings' : 'Connect'  }} <Icon name="ChevronRight" size="18" /></button>
                <!-- Checkbox swicher -->

                <HbSwitch v-if="stripe_data.connection_status" @change="emit('update-integrations', 'stripe', stripe_data)" v-model="stripe_data.status"    />
            <!-- Swicher --> 
        </div>

        <HbPopup :isOpen="ispopup" @modal-close="closePopup" max_width="600px" name="first-modal">
            <template #header> 
                <h2>{{ $tfhb_trans['Connect Your Stripe Account'] }}</h2>
                
            </template>

            <template #content>  
                <p>
                    {{ $tfhb_trans['Please read the documentation here for step by step guide to know how you can get api credentials from Stripe Account'] }}
                </p>
                <HbText  
                    v-model="stripe_data.public_key"  
                    required= "true"  
                    :label="$tfhb_trans['Stripe Public Key']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Enter Your Public Key']"  
                /> 
                <HbText  
                    v-model="stripe_data.secret_key"  
                    required= "true"  
                    :label="$tfhb_trans['Stripe Secret Key']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Enter Your Stripe Secret']"  
                />
                <button class="tfhb-btn boxed-btn" @click.stop="emit('update-integrations', 'stripe', stripe_data)">{{ $tfhb_trans['Save & Validate'] }}</button>
            </template> 
        </HbPopup>

    </div>  
    <!-- Single Integrations  -->

</template>

<style scoped>
</style> 