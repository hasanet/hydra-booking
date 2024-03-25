<script setup>
import HbSelect from '@/components/form-fields/HbSelect.vue'
import HbText from '@/components/form-fields/HbText.vue'
import HbTextarea from '@/components/form-fields/HbTextarea.vue'
import HBSwitch from '@/components/form-fields/HBSwitch.vue';
import Icon from '@/components/icon/LucideIcon.vue'
</script>

<template>
    <div class="meeting-create-details tfhb-gap-24">
        <HbText  
            required= "true"  
            :label="$tfhb_trans['Meeting title']"  
            selected = "1"
            :placeholder="$tfhb_trans['Type meeting title']" 
        /> 
        <HbTextarea  
            required= "true"  
            :label="$tfhb_trans['Description']"  
            selected = "1"
            :placeholder="$tfhb_trans['Describe about meeting']" 
        /> 

        <div class="tfhb-admin-card-box tfhb-flexbox tfhb-gap-16 tfhb-m-0"> 
            <!-- Duration -->
            <HbSelect 
                required= "true" 
                :label="$tfhb_trans['Duration']"  
                :selected = "1"
                placeholder="Select Time Format"  
                :option = "{'12_hours': '30 minutes', '24_hours': '10 minutes'}" 
            />
            <HBSwitch 
                type="checkbox" 
                required= "true" 
                :label="$tfhb_trans['Allow attendee to select duration']" 
            />
        </div>

        <div class="tfhb-admin-card-box tfhb-flexbox tfhb-gap-16 tfhb-m-0"> 
            <!-- Location -->
            <HbSelect 
                required= "true" 
                :label="$tfhb_trans['Location']"  
                :selected = "1"
                placeholder="Select Time Format"  
                :option = "{'12_hours': '30 minutes', '24_hours': '10 minutes'}" 
                :width= "50"
            />
            <!-- Address -->
            <HbText  
                required= "true"  
                :label="$tfhb_trans['Address']"  
                selected = "1"
                :placeholder="$tfhb_trans['Type Address']" 
                :width= "50"
            /> 
        </div>

        <!-- Category -->
        <HbSelect 
            required= "true" 
            :label="$tfhb_trans['Select Category']"  
            :selected = "1"
            placeholder="Select Category"  
            :option = "{'12_hours': 'Design System', '24_hours': '10 minutes'}" 
        />

        <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="UpdateGeneralSettings">{{ $tfhb_trans['Save & Continue'] }} </button>
        <!--Bookings -->
    </div>
</template>