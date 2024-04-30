<script setup>
import { reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView } from 'vue-router'  
import HbDropdown from '@/components/form-fields/HbDropdown.vue'
import HbText from '@/components/form-fields/HbText.vue'
import HbTextarea from '@/components/form-fields/HbTextarea.vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue';
import Icon from '@/components/icon/LucideIcon.vue'
import useValidators from '@/store/validator'
const { errors, isEmpty } = useValidators();

const emit = defineEmits(["add-more-location", "remove-meeting-location", "update-meeting"]); 
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
            <HbDropdown 
                v-model="meeting.duration" 
                required= "true" 
                :label="$tfhb_trans['Duration']"  
                :selected = "1"
                name="duration"
                placeholder="Select Meetings Duration"  
                :option = "[
                    {name: '15 minutes', value: '15'}, 
                    {name: '30 minutes', value: '30'},
                    {name: '45 minutes', value: '45'},
                    {name: '60 minutes', value: '60'},
                    {name: 'Custom', value: 'custom'} 
                ]" 
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
                <div class="tfhb-meeting-location tfhb-flexbox tfhb-gap-16" :style="meeting.meeting_locations.length<2 ?'width:100%' : '' ">
                    <!-- Location -->
                    <HbDropdown 
                        v-model="slocation.location" 
                        required= "true" 
                        :label="$tfhb_trans['Location']"  
                        :selected = "1"
                        :placeholder="$tfhb_trans['Location']" 
                        :option = "[
                            {name: 'Zoom', value: 'zoom'}, 
                            {name: 'In Person (Attendee Address)', value: 'attendee_address'},
                            {name: 'In Person (Organizer Address)', value: 'organizer_address'},
                            {name: 'Attendee Phone Number', value: 'attendee_phone'},
                            {name: 'Organizer Phone Number', value: 'organizer_phone'},
                            {name: 'Online Meeting', value: 'online_meeting'}
                        ]" 
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
                <div class="tfhb-meeting-location-removed" v-if="meeting.meeting_locations.length>1" @click="emit('remove-meeting-location', index)">
                    <Icon name="Trash" :width="16" />
                </div>
            </div>
            <div class="tfhb-add-new-question">
                <div class="new-location tfhb-flexbox tfhb-gap-8 tfhb-justify-normal" @click="emit('add-more-location')">
                    <Icon name="PlusCircle" :width="20"/>
                    {{ $tfhb_trans['Add Another Location'] }}
                </div>
            </div>
        </div>

        <!-- Category -->
        <HbDropdown 
            v-model="meeting.meeting_category" 
            required= "true" 
            :label="$tfhb_trans['Select Category']"  
            :selected = "1"
            placeholder="Select Category"  
            :option = "[
                    {name: 'Design System', value: 'Design System'},  
                ]"  
        />
        <div class="tfhb-submission-btn">
            <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="emit('update-meeting', ['title', 'description', 'duration'])">{{ $tfhb_trans['Save & Continue'] }} </button>
        </div>
        <!--Bookings -->
    </div>
</template>