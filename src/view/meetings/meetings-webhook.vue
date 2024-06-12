<script setup>
import { reactive, onBeforeMount, ref } from 'vue';
import axios from 'axios'  
import { toast } from "vue3-toastify"; 

import Icon from '@/components/icon/LucideIcon.vue';
import HbDropdown from '@/components/form-fields/HbDropdown.vue';
import HbText from '@/components/form-fields/HbText.vue';
import HbSwitch from '@/components/form-fields/HbSwitch.vue'; 
import HbCheckbox from '@/components/form-fields/HbCheckbox.vue';
import HbRadio from '@/components/form-fields/HbRadio.vue';

const props = defineProps({
    meetingId: {
        type: Number,
        required: true
    },
    meeting: {
        type: Object,
        required: true
    },

});

const webhookList = ref(true);
const webhookcreate = ref(false);
const webhookData = reactive({
    'meeting_id' : props.meetingId,
    'id': '',
    'webhook': '',
    'url': '',
    'request_method': '',
    'request_format': '',
    'events': '',
    'request_body': 'all',
    'status': '',
});


const updateWebHook = async () => {
    // Api Submission
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/webhook/update', webhookData);
        if (response.data.status == true) { 
            toast.success(response.data.message); 
            props.meeting.webhook = response.data.webhook ? JSON.parse(response.data.webhook) : '';

            webhookcreate.value = false;
            webhookList.value = true;
        }else{
            toast.error(response.data.message); 
        }
    } catch (error) {
        console.log(error);
    } 
}

const addNewWebHook = () => {
    webhookList.value = false;
    webhookcreate.value = true;
}

const backtoWebHookList = () => {
    webhookcreate.value = false;
    webhookList.value = true;
}

</script>

<template>
<div class="meeting-create-details tfhb-gap-24">
    <div class="tfhb-webhook-title tfhb-flexbox tfhb-full-width">
        <div class="tfhb-admin-title tfhb-m-0">
            <h2>{{ $tfhb_trans['Availability Range for this Booking'] }}</h2> 
            <p>{{ $tfhb_trans['How many days can the invitee schedule?'] }}</p>
        </div>
        <button class="tfhb-btn boxed-btn tfhb-flexbox tfhb-gap-8" v-if="webhookList" @click="addNewWebHook">
            <Icon name="PlusCircle" :width="20"/>
            {{ $tfhb_trans['Add New Webhook'] }}
        </button>
        <button class="tfhb-btn boxed-btn tfhb-flexbox tfhb-gap-8" v-if="webhookcreate" @click="backtoWebHookList">
            <Icon name="ArrowLeft" :width="20"/>
            {{ $tfhb_trans['Back'] }}
        </button>
    </div>

    <div class="tfhb-webhook-content tfhb-full-width" v-if="meeting.webhook && webhookList">
        <div class="tfhb-admin-card-box tfhb-full-width tfhb-justify-between tfhb-mb-16" v-for="(hook, key)  in meeting.webhook" :key="key">
            <div class="tfhb-webhook-info">
                <h4>{{ hook.webhook }}</h4>
                <p>{{ hook.url }}</p>
            </div>
            <div class="tfhb-webhook-action tfhb-flexbox tfhb-gap-8">

                <HbSwitch />
                <button class="question-edit-btn" >
                    <Icon name="PencilLine" :width="16" />
                </button>
                <button class="question-edit-btn">
                    <Icon name="X" :width="16"/>
                </button>
            </div>
        </div>
    </div>

    <div class="tfhb-admin-card-box tfhb-webhook-box tfhb-full-width tfhb-gap-24" v-if="webhookcreate">
        <HbDropdown  
            v-model="webhookData.webhook"
            required= "true"  
            :label="$tfhb_trans['Select Webhook']"   
            width="50"
            selected = "1"
            placeholder="Select Webhook"  
            :option = "[
                {'name': 'Pabbly', 'value': 'Pabbly'}, 
                {'name': 'Zapier', 'value': 'Zapier'}
            ]"
        />

        <HbText  
            v-model="webhookData.url"
            required= "true"  
            :label="$tfhb_trans['Webhook URL']"  
            selected = "1"
            :placeholder="$tfhb_trans['Type your Webhook URL']" 
            width="50"
        /> 

        <HbDropdown  
            v-model="webhookData.request_method"
            :label="$tfhb_trans['Request Method']"   
            width="50"
            selected = "1"
            placeholder="Request Method"  
            :option = "[
                {'name': 'GET', 'value': 'GET'}, 
                {'name': 'POST', 'value': 'POST'},
                {'name': 'PUT', 'value': 'PUT'},
                {'name': 'PATCH', 'value': 'PATCH'}
            ]"
        />

        <HbDropdown  
            v-model="webhookData.request_format"
            :label="$tfhb_trans['Request Format']"   
            width="50"
            selected = "1"
            placeholder="Request Format"  
            :option = "[
                {'name': 'FORM', 'value': 'form'}, 
                {'name': 'JSON', 'value': 'json'}
            ]"
        />

        <HbCheckbox 
            v-model="webhookData.events"
            names="webhook_events"
            :label="$tfhb_trans['Event Triggers*']"
            :groups="true"
            :options="['Booking Confirmed', 'Booking Canceled', 'Booking Completed']" 
        />

        <HbRadio 
            v-model="webhookData.request_body"
            name="request_body"
            :label="$tfhb_trans['Request Body']"
            :groups="true"
            :options="[
                {'label': 'All Data', 'value': 'all'}, 
                {'label': 'Selected Data', 'value': 'selected'}
            ]" 
        />

        <HbCheckbox 
            v-model="webhookData.status"
            :label="$tfhb_trans['Enable this Webhook']"
            name="enable_webhook"
        />

        <div class="tfhb-submission-btn">
            <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="updateWebHook">{{ $tfhb_trans['Save Webhook'] }} </button>
        </div>
    </div>
</div>
</template>

<style scoped>

</style>