<script setup>
import {ref} from 'vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue'

// component
import HbDropdown from '@/components/form-fields/HbDropdown.vue';

const emit = defineEmits(["update-meeting"]); 
const props = defineProps({
    meetingId: {
        type: Number,
        required: true
    },
    meeting: {
        type: Object,
        required: true
    },
    wcProduct: {
        type: Object,
        required: true
    },

});


const host = ref(true);
const attendee = ref(false);

</script>

<template>
    <div class="meeting-create-details tfhb-gap-24">
        <div class="tfhb-notification-wrap tfhb-admin-card-box tfhb-m-0 tfhb-gap-32 tfhb-full-width">
            <div class="tfhb-admin-title tfhb-m-0 tfhb-full-width">

                <h2 class="tfhb-flexbox tfhb-gap-8 tfhb-justify-normal">
                    {{ $tfhb_trans['Payment for this Meeting '] }}
                     
                    <HbSwitch 
                        v-model="meeting.payment_status"
                    />
                </h2> 
                <p>{{ $tfhb_trans['You can enable or disable payment for this meeting by toggle switch'] }}</p>
            </div> 
            <div v-if="meeting.payment_status == 1"  class="tfhb-content-wrap tfhb-full-width"> 
                <div class="tfhb-integrations-wrap tfhb-flexbox">

                    <HbDropdown 
                        v-model="meeting.payment_method" 
                        required= "true" 
                        :label="$tfhb_trans['Payment Method']"  
                        :selected = "1"
                        name="payment_method"
                        placeholder="Select Payment Method"  
                        :option = "[
                            {name: 'Woocommerce', value: 'woo_payment'},  
                            {name: 'Stripe Pay', value: 'stripe_payment'},  
                            {name: 'Paypal', value: 'paypal_payment'},  
                        ]"   
                    /> 
                    <!-- Woo Integrations  -->
                </div> 
            </div>
            <div v-if="meeting.payment_status == 1 && meeting.payment_method=='woo_payment'" class="tfhb-single-form-field" style="width: 100%;" selected="1">
                <HbDropdown 
                    v-model="meeting.payment_meta.product_id" 
                    required= "true" 
                    :filter="true"
                    :label="$tfhb_trans['Selecte Product']"  
                    :selected = "1"
                    name="payment_meta"
                    placeholder="Select Product"  
                    :option = "props.wcProduct"   
                /> 
            </div>
            <div v-if="meeting.payment_status == 1 && meeting.payment_method=='stripe_payment' || meeting.payment_method=='paypal_payment'" class="tfhb-single-form-field" style="width: 100%;" selected="1">
                <div class="tfhb-single-form-field-wrap tfhb-field-input">
                    <label>{{ $tfhb_trans['Price'] }} <span> *</span></label>
                    <div class="tfhb-meeting-currency tfhb-flexbox tfhb-justify-normal tfhb-gap-0">
                        <input v-model="meeting.meeting_price" required="" type="text" placeholder="00.000">
                        <select v-model="meeting.payment_currency" placeholder="USD">
                            <option value="USD">USD</option>
                            <option value="EUR">Euro</option>
                        </select>
                    </div>
                </div>
            </div>
            

        </div> 
        <div class="tfhb-submission-btn">
            <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="emit('update-meeting')">{{ $tfhb_trans['Save & Preview'] }} </button>
        </div>
        <!--Bookings -->
    </div>
</template>