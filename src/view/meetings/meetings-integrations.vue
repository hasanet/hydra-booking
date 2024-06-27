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

const integrationsList = ref(true);
const integrationsListopen = ref(false);
const integrationscreate = ref(false);
const integrationsData = reactive({
    'meeting_id' : props.meetingId,
    'title': '',
    'events': '',
    'audience' : '',
    'lists' : '',
    'tags' : '',
    'bodys': [
        {
            'name': '',
            'value': ''
        }
    ],
    'status': '',
});


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
    integrationsData.status = '';
    integrationsData.bodys = [
        {
            'name': '',
            'value': ''
        }
    ];
}

const openIntegrations =  () => {
    integrationsListopen.value = true;
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
    integrationsData.status = e.target.checked ? 1 : 0;
    integrationsData.bodys = data.bodys;

    updateIntegrations();
}

// Add body
const addBodyField = () => {
    integrationsData.bodys.push({
        name: '',
        value: '',
    });
}
// Delete body
const deleteBodyField = (key) => {
    integrationsData.bodys.splice(key, 1)
}

const attendeeShortcode = ref([
    '{{attendee.full_name}}',
    '{{attendee.email}}',
    '{{attendee.phone}}',
    '{{attendee.timezone}}',
    '{{attendee.address}}',
]);

const hostShortcode = ref([
    '{{host.name}}',
    '{{host.email}}',
    '{{host.timezone}}'
]);

const bookingShortcode = ref([
    '{{booking.meeting_date}}',
    '{{booking.start_time}}',
    '{{booking.end_time}}',
    '{{booking.duration}}',
    '{{booking.hash}}',
    '{{booking.meeting_location}}'
])

const copyShortcode = (value) => { 
    //  copy to clipboard without navigator 
    const textarea = document.createElement('textarea');
    textarea.value = value;
    textarea.setAttribute('readonly', '');
    textarea.style.position = 'absolute';
    textarea.style.left = '-9999px';
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);
    
    // Show a toast notification or perform any other action
    toast.success(value + ' is Copied');
}

</script>

<template>

<!-- {{ integrationsData  }} -->
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
                    <li @click="addNewIntegrations('Mailchimp')">Mailchimp</li>
                    <li @click="addNewIntegrations('FluentCRM')">FluentCRM</li>
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
                        :option = "[
                            {'name': 'Attendee Time_zone', 'value': 'attendee_time_zone'},
                            {'name': 'Meeting Dates', 'value': 'meeting_dates'}, 
                        ]"
                    />
                    <HbText  
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

        <div class="processing-data-shortcode" v-if="'FluentCRM'==integrationsData.webhook">
            <p>{{ $tfhb_trans['Attendee Data'] }}</p>
            <div class="tfhb-mail-shortcode tfhb-flexbox tfhb-gap-8 tfhb-justify-normal"> 
                <span class="tfhb-mail-shortcode-badge"  v-for="(value, key) in attendeeShortcode" :key="key" @click="copyShortcode(value)">{{ value}}</span>
            </div>
        </div>

        <div class="processing-data-shortcode" v-if="'FluentCRM'==integrationsData.webhook">
            <p>{{ $tfhb_trans['Booking Data'] }}</p>
            <div class="tfhb-mail-shortcode tfhb-flexbox tfhb-gap-8 tfhb-justify-normal"> 
                <span class="tfhb-mail-shortcode-badge"  v-for="(value, key) in bookingShortcode" :key="key" @click="copyShortcode(value)">{{ value}}</span>
            </div>
        </div>

        <div class="processing-data-shortcode" v-if="'FluentCRM'==integrationsData.webhook">
            <p>{{ $tfhb_trans['Host Data'] }}</p>
            <div class="tfhb-mail-shortcode tfhb-flexbox tfhb-gap-8 tfhb-justify-normal"> 
                <span class="tfhb-mail-shortcode-badge"  v-for="(value, key) in hostShortcode" :key="key" @click="copyShortcode(value)">{{ value}}</span>
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