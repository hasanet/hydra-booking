<script setup>
import { reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import HbSelect from '@/components/form-fields/HbSelect.vue'
import HbText from '@/components/form-fields/HbText.vue'
import HbTextarea from '@/components/form-fields/HbTextarea.vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue';
import Icon from '@/components/icon/LucideIcon.vue'
import useValidators from '@/store/validator'
const { errors, isEmpty } = useValidators();

const emit = defineEmits(["add-more-location", "update-meeting"]); 
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

const validateInput = (fieldName) => {
    const fieldValueKey = fieldName;
    isEmpty(fieldName, props.meeting[fieldValueKey]);
};

const validateSelect = (fieldName) => {
    const fieldValueKey = fieldName;
    isEmpty(fieldName, props.meeting[fieldValueKey]);
};

</script>

<template>
    <div class="meeting-create-details tfhb-gap-24">
        <HbText  
            v-model="meeting.title" 
            required= "true"  
            :label="$tfhb_trans['Meeting title']"  
            name="title"
            selected = "1"
            :placeholder="$tfhb_trans['Type meeting title']" 
            @keyup="() => validateInput('title')"
            @click="() => validateInput('title')"
            :errors="errors.title"
        /> 
        <HbTextarea  
            v-model="meeting.description" 
            required= "true"  
            name="description"
            :label="$tfhb_trans['Description']"  
            :placeholder="$tfhb_trans['Describe about meeting']"
            @keyup="() => validateInput('description')"
            @click="() => validateInput('description')"
            :errors="errors.description"
        /> 

        <div class="tfhb-admin-card-box tfhb-flexbox tfhb-gap-16 tfhb-m-0 tfhb-full-width"> 
            <!-- Duration -->
            <HbSelect 
                v-model="meeting.duration" 
                required= "true" 
                :label="$tfhb_trans['Duration']"  
                :selected = "1"
                name="duration"
                placeholder="Select Meetings Duration"  
                :option = "{
                    '15': '15 minutes', 
                    '30': '30 minutes',
                    '45': '45 minutes',
                    '60': '60 minutes',
                    'custom': 'Custom'
                    }" 
                @change="() => validateSelect('duration')"
                @click="() => validateSelect('duration')"
                :errors="errors.duration"
            />
            <!-- Duration -->
            <!-- Custom Duration -->
            <HbText  
                v-model="meeting.custom_duration"  
                :label="$tfhb_trans['Custom Duration']"  
                name="title"
                type="number"
                selected = "1"
                :placeholder="$tfhb_trans['Type Custom Duration']"  
                v-if="'custom'==meeting.duration"
            /> 
             <!-- Custom Duration -->
            <HbSwitch 
                type="checkbox" 
                required= "true" 
                :label="$tfhb_trans['Allow attendee to select duration']" 
            />
        </div>

        <div class="tfhb-admin-card-box tfhb-no-flexbox tfhb-m-0 tfhb-full-width"> 
            <div class="tfhb-flexbox tfhb-gap-16 tfhb-mb-24" v-for="(slocation, index) in meeting.meeting_locations" :key="index">
                <!-- Location -->
                <HbSelect 
                    v-model="slocation.location" 
                    required= "true" 
                    :label="$tfhb_trans['Location']"  
                    :selected = "1"
                    :placeholder="$tfhb_trans['Location']" 
                    :option = "{
                        'zoom': 'Zoom', 
                        'attendee_address': 'In Person (Attendee Address)',
                        'organizer_address': 'In Person (Organizer Address)',
                        'attendee_phone': 'Attendee Phone Number',
                        'organizer_phone': 'Organizer Phone Number',
                        'online_meeting': 'Online Meeting',
                        }" 
                    :width= "50"
                />
                <!-- Address -->
                <HbText  
                    v-model="slocation.address" 
                    required= "true"  
                    :label="$tfhb_trans['Address']"  
                    selected = "1"
                    :placeholder="'Type Location Address'" 
                    :width= "50"
                    v-if="'organizer_address'==slocation.location || 'organizer_phone'==slocation.location || 'online_meeting'==slocation.location"
                /> 
 
            </div>
            <div class="tfhb-add-new-question">
                <div class="new-location tfhb-flexbox tfhb-gap-8 tfhb-justify-normal" @click="emit('add-more-location')">
                    <Icon name="PlusCircle" :width="20"/>
                    {{ $tfhb_trans['Add Another Location'] }}
                </div>
            </div>
        </div>

        <!-- Category -->
        <HbSelect 
            v-model="meeting.meeting_category" 
            required= "true" 
            :label="$tfhb_trans['Select Category']"  
            :selected = "1"
            placeholder="Select Category"  
            :option = "{'12_hours': 'Design System', '24_hours': '10 minutes'}" 
        />
        <div class="tfhb-submission-btn">
            <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="emit('update-meeting', ['title', 'description', 'duration'])">{{ $tfhb_trans['Save & Continue'] }} </button>
        </div>
        <!--Bookings -->
    </div>
</template>