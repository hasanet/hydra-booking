<script setup>
import { reactive, ref } from 'vue';
import axios from 'axios'  
import { toast } from "vue3-toastify"; 

import Icon from '@/components/icon/LucideIcon.vue';
import HbDropdown from '@/components/form-fields/HbDropdown.vue';
import HbText from '@/components/form-fields/HbText.vue';
import HbSwitch from '@/components/form-fields/HbSwitch.vue'; 
import HbCheckbox from '@/components/form-fields/HbCheckbox.vue';

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

const integrationsList = ref(true);
const integrationsListopen = ref(false);
const integrationscreate = ref(false);
const integrationsData = reactive({
    'meeting_id' : props.meetingId,
    'title': '',
    'events': '',
    'audience' : '',
    'lists' : '',
    'modules' : '',
    'tags' : '',
    'fields': '',
    'bodys': [
        {
            'name': '',
            'type': 'Settings',
            'value': ''
        }
    ],
    'status': '',
});

const dataFields = ref('');

const updateIntegrations = async () => {
    // Api Submission
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/integration/update', integrationsData);
        if (response.data.status == true) { 
            toast.success(response.data.message); 
            props.meeting.integrations = response.data.integrations ? JSON.parse(response.data.integrations) : '';

            integrationscreate.value = false;
            integrationsList.value = true;
        }else{
            toast.error(response.data.message); 
        }
    } catch (error) {
        console.log(error);
    } 
}

const deleteIntegrations = async (key) => {
    const data = {
        key: key,
        meeting_id: props.meetingId
    };

    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/integration/delete', data, {
               
        } );
        if (response.data.status) { 
            toast.success(response.data.message, {
                position: 'bottom-right', // Set the desired position
                "autoClose": 1500,
            }); 

            props.meeting.integrations = response.data.integrations ? JSON.parse(response.data.integrations) : '';
        }
    } catch (error) {
        console.log(error);
    }
}

const addNewIntegrations = (integration) => {
    integrationsList.value = false;
    integrationscreate.value = true;
    integrationsListopen.value = false;

    integrationsData.key = '';
    integrationsData.title = '';
    integrationsData.webhook = integration;
    integrationsData.events = '';
    integrationsData.audience = '';
    integrationsData.tags = '';
    integrationsData.lists = '';
    integrationsData.modules = '';
    integrationsData.fields = '';
    integrationsData.status = '';
    integrationsData.bodys = [
        {
            'name': '',
            'type': 'Settings',
            'value': ''
        }
    ];
}

const backtointegrationsList = () => {
  
    integrationscreate.value = false;
    integrationsList.value = true;
}

// edit webhook
const editIntegrations = (data, key) => {
    integrationsData.key = key;
    integrationsData.meeting_id = props.meetingId;
    integrationsData.webhook = data.webhook;
    integrationsData.title = data.title;
    integrationsData.audience = data.audience;
    integrationsData.tags = data.tags;
    integrationsData.lists = data.lists;
    integrationsData.modules = data.modules;
    integrationsData.fields = data.fields;
    integrationsData.events = data.events;
    integrationsData.status = data.status;
    integrationsData.bodys = data.bodys;

    integrationsList.value = false;
    integrationsListopen.value = false;
    integrationscreate.value = true;
}

// update webhook status
const updateHookStatus = (e, data, key) => {

    integrationsData.key = key;
    integrationsData.meeting_id = props.meetingId;
    integrationsData.webhook = data.webhook;
    integrationsData.title = data.title;
    integrationsData.events = data.events;
    integrationsData.audience = data.audience;
    integrationsData.tags = data.tags;
    integrationsData.lists = data.lists;
    integrationsData.modules = data.modules;
    integrationsData.fields = data.fields;
    integrationsData.status = e.target.checked ? 1 : 0;
    integrationsData.bodys = data.bodys;

    updateIntegrations();
}

// Add body
const addBodyField = () => {
    integrationsData.bodys.push({
        name: '',
        type: 'Settings',
        value: '',
    });
}
// Delete body
const deleteBodyField = (key) => {
    integrationsData.bodys.splice(key, 1)
}

const BodyValues = (key, value) => {
    if(value!='tfhb_ct'){
        integrationsData.bodys[key].value = value
    }
}

// modules callback
const moduleFields = async (e) => {
    if(e.value){
        let data = {
            host_id: props.meeting.host_id,
            webhook: integrationsData.webhook,
            module: e.value
        };  
        try { 
            const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/integration/fields', data, {
                headers: {
                    'X-WP-Nonce': tfhb_core_apps.rest_nonce
                } 
            } );
            if (response.data.status) { 
                integrationsData.fields = response.data.fields;
            }
        } catch (error) {
            console.log(error);
        } 
    }
}

</script>

<template>

<!-- {{ integrationsData.bodys  }} -->
<div class="meeting-create-details tfhb-gap-24">
    <div class="tfhb-webhook-title tfhb-flexbox tfhb-full-width">
        <div class="tfhb-admin-title tfhb-m-0">
            <h2>{{ $tfhb_trans['Availability Range for this Booking'] }}</h2> 
            <p>{{ $tfhb_trans['How many days can the invitee schedule?'] }}</p>
        </div>
        <div class="tfhb-integration-box">
            <button class="tfhb-btn boxed-btn tfhb-flexbox tfhb-gap-8" v-if="integrationsList" @click="integrationsListopen=!integrationsListopen">
                <Icon name="PlusCircle" :width="20"/>
                {{ $tfhb_trans['Add New Integrations'] }}
            </button>
            <button class="tfhb-btn boxed-btn tfhb-flexbox tfhb-gap-8" v-if="integrationscreate" @click="backtointegrationsList">
                <Icon name="ArrowLeft" :width="20"/>
                {{ $tfhb_trans['Back'] }}
            </button>

            <div class="tfhb-integrations-lists" v-if="integrationsListopen">
                <ul>
                    <li @click="addNewIntegrations('Mailchimp')" v-if="meeting.mailchimp.status">{{ $tfhb_trans['Mailchimp'] }}</li>
                    <li @click="addNewIntegrations('FluentCRM')" v-if="meeting.fluentcrm.status">{{ $tfhb_trans['FluentCRM'] }}</li>
                    <li @click="addNewIntegrations('ZohoCRM')" v-if="meeting.zohocrm.status">{{ $tfhb_trans['ZohoCRM'] }}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="tfhb-webhook-content tfhb-full-width" v-if="meeting.integrations && integrationsList">
        <div class="tfhb-admin-card-box tfhb-full-width tfhb-justify-between tfhb-mb-16" v-for="(hook, key)  in meeting.integrations" :key="key">
            <div class="tfhb-webhook-info">
                <h4>{{ hook.webhook }}</h4>
                <p>{{ hook.title }}</p>
                <ul class="webhook-event" v-if="hook.events">
                    <li v-for="event in hook.events">
                        {{ event }}
                    </li>
                </ul>
            </div>
            <div class="tfhb-webhook-action tfhb-flexbox tfhb-gap-8">

                <HbSwitch 
                v-model="hook.status"
                @change="(e) => updateHookStatus(e, hook, key)"
                />
                <button class="question-edit-btn" >
                    <Icon name="PencilLine" :width="16" @click="editIntegrations(hook, key)" />
                </button>
                <button class="question-edit-btn">
                    <Icon name="X" :width="16" @click="deleteIntegrations(key)" />
                </button>
            </div>
        </div>
    </div>

    <div class="tfhb-admin-card-box tfhb-webhook-box tfhb-full-width tfhb-gap-24" v-if="integrationscreate">

        <HbText  
            v-model="integrationsData.title"
            required= "true"  
            :label="$tfhb_trans['Integrations Title']"  
            selected = "1"
            :placeholder="$tfhb_trans['Type your Integrations Title']" 
            width="50"
        /> 

        <HbDropdown  
            v-if="integrationsData.webhook=='Mailchimp'"
            v-model="integrationsData.audience"
            required= "true"  
            :label="$tfhb_trans['Select Audience']"   
            width="50"
            selected = "1"
            placeholder="Select Audience"  
            :option = "meeting.mailchimp.audience"
            @tfhb-onchange="moduleFields"
        />

        <HbDropdown  
            v-if="integrationsData.webhook=='FluentCRM'"
            v-model="integrationsData.lists"
            required= "true"  
            :label="$tfhb_trans['FluentCRM Lists']"   
            width="50"
            selected = "1"
            :placeholder="$tfhb_trans['Select FluentCRM List']"  
            :option = "meeting.fluentcrm.lists"
            @tfhb-onchange="moduleFields"
        />

        <HbDropdown  
            v-if="integrationsData.webhook=='FluentCRM'"
            v-model="integrationsData.tags"
            required= "true"  
            :label="$tfhb_trans['Contact Tags']"   
            width="50"
            selected = "1"
            :placeholder="$tfhb_trans['Select Contact Tag']" 
            :option = "meeting.fluentcrm.tags"
        />

        <HbDropdown  
            v-if="integrationsData.webhook=='ZohoCRM'"
            v-model="integrationsData.modules"
            required= "true"  
            :label="$tfhb_trans['Modules']"   
            width="50"
            selected = "1"
            :placeholder="$tfhb_trans['Select Modules']" 
            :option = "meeting.zohocrm.modules"
            @tfhb-onchange="moduleFields"
        />

        <HbCheckbox 
            required= "true"
            v-model="integrationsData.events"
            name="webhook_events"
            :label="$tfhb_trans['Event Triggers']"
            :groups="true"
            :options="['Booking Confirmed', 'Booking Canceled', 'Booking Completed']" 
        />

        <div class="tfhb-headers tfhb-full-width">
            <p>{{ $tfhb_trans['Other Fields'] }}</p>
            <div class="tfhb-flexbox" v-for="(body, key) in integrationsData.bodys">
                <div class="tfhb-request-header-fields tfhb-flexbox">
                    <HbDropdown  
                        v-model="body.name"
                        required= "true"    
                        width="50"
                        selected = "1"
                        placeholder="Select Tag"  
                        :option = "integrationsData.fields"
                    />
                    <HbDropdown  
                        v-show="body.type!='tfhb_ct'"
                        v-model="body.type"
                        required= "true"  
                        width="50"
                        selected = "1"
                        :placeholder="$tfhb_trans['Enter Value']" 
                        :option = "[
                            {'name': '{{attendee.full_name}}', 'value': '{{attendee.full_name}}'}, 
                            {'name': '{{attendee.email}}', 'value': '{{attendee.email}}'},
                            {'name': '{{attendee.phone}}', 'value': '{{attendee.phone}}'},
                            {'name': '{{attendee.timezone}}', 'value': '{{attendee.timezone}}'},
                            {'name': '{{attendee.address}}', 'value': '{{attendee.address}}'},
                            {'name': '{{booking.meeting_date}}', 'value': '{{booking.meeting_date}}'},
                            {'name': '{{booking.start_time}}', 'value': '{{booking.start_time}}'},
                            {'name': '{{booking.end_time}}', 'value': '{{booking.end_time}}'},
                            {'name': '{{booking.duration}}', 'value': '{{booking.duration}}'},
                            {'name': '{{booking.hash}}', 'value': '{{booking.hash}}'},
                            {'name': '{{host.name}}', 'value': '{{host.name}}'},
                            {'name': '{{host.email}}', 'value': '{{host.email}}'},
                            {'name': '{{host.timezone}}', 'value': '{{host.timezone}}'},
                            {'name': 'Custom', 'value': 'tfhb_ct'},
                        ]"
                        @tfhb_body_value_change="BodyValues"
                        :single_key = "key"
                    />
                    <HbText  
                        v-show="body.type=='tfhb_ct'"
                        v-model="body.value"
                        required= "true"   
                        selected = "1"
                        :placeholder="$tfhb_trans['Enter Value']" 
                        width="50"
                    /> 
                </div>
                <div class="request-actions">
                    <button class="tfhb-availability-schedule-btn" @click="addBodyField" v-if="key == 0">
                        <Icon name="Plus" size="20px" /> 
                    </button> 
                    <button class="tfhb-availability-schedule-btn" @click="deleteBodyField(key)" v-else>
                        <Icon name="X" size="20px" /> 
                    </button> 
                </div>
            </div>
        </div>

        <HbCheckbox 
            v-model="integrationsData.status"
            :label="$tfhb_trans['Enable this Webhook']"
            name="enable_webhook"
        />

        <div class="tfhb-submission-btn">
            <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="updateIntegrations">{{ $tfhb_trans['Save Webhook'] }} </button>
        </div>
    </div>
</div>
</template>

<style scoped>

</style>