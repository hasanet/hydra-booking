<script setup>
import { reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import HbSelect from '@/components/form-fields/HbSelect.vue'
import HbText from '@/components/form-fields/HbText.vue'
import HbTextarea from '@/components/form-fields/HbTextarea.vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue';
import Icon from '@/components/icon/LucideIcon.vue'

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

</script>

<template>
    {{ meeting }}
    <div class="meeting-create-details tfhb-gap-24">
        <HbText  
            v-model="meeting.title" 
            required= "true"  
            :label="$tfhb_trans['Meeting title']"  
            selected = "1"
            :placeholder="$tfhb_trans['Type meeting title']" 
        /> 
        <HbTextarea  
            v-model="meeting.description" 
            required= "true"  
            :label="$tfhb_trans['Description']"  
            selected = "1"
            :placeholder="$tfhb_trans['Describe about meeting']" 
        /> 

        <div class="tfhb-admin-card-box tfhb-flexbox tfhb-gap-16 tfhb-m-0"> 
            <!-- Duration -->
            <HbSelect 
                v-model="meeting.duration" 
                required= "true" 
                :label="$tfhb_trans['Duration']"  
                :selected = "1"
                placeholder="Select Time Format"  
                :option = "{'12_hours': '30 minutes', '24_hours': '10 minutes'}" 
            />
            <HbSwitch 
                type="checkbox" 
                required= "true" 
                :label="$tfhb_trans['Allow attendee to select duration']" 
            />
        </div>

        <div class="tfhb-admin-card-box tfhb-no-flexbox tfhb-m-0"> 
            <div class="tfhb-flexbox tfhb-gap-16 tfhb-mb-24" v-for="(slocation, index) in meeting.meeting_locations" :key="index">
                <!-- Location -->
                <HbSelect 
                    v-model="slocation.location" 
                    required= "true" 
                    :label="$tfhb_trans['Location']"  
                    :selected = "1"
                    :placeholder="$tfhb_trans['Location']" 
                    :option = "{'12_hours': '30 minutes', '24_hours': '10 minutes'}" 
                    :width= "50"
                />
                <!-- Address -->
                <HbText  
                    v-model="slocation.address" 
                    required= "true"  
                    :label="$tfhb_trans['Address']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Type Address']" 
                    :width= "50"
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

        <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="emit('update-meeting')">{{ $tfhb_trans['Save & Continue'] }} </button>
        <!--Bookings -->
    </div>
</template>