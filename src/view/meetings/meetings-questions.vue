<script setup>
import HbQuestion from '@/components/meetings/HbQuestion.vue'
import Icon from '@/components/icon/LucideIcon.vue'
</script>

<template>
    <div class="meeting-create-details tfhb-gap-24">

        <div class="tfhb-admin-card-box tfhb-gap-24">  

            <HbQuestion 
                required= "true" 
                :label="$tfhb_trans['Name']"  
                :selected = "1"
                :placeholder="$tfhb_trans['Type your name']"   
                :option = "{'12_hours': '30 minutes', '24_hours': '10 minutes'}" 
            />
            <HbQuestion 
                required= "true" 
                :label="$tfhb_trans['Email']"  
                :selected = "1"
                :placeholder="$tfhb_trans['Type your email']"   
                :option = "{'12_hours': '30 minutes', '24_hours': '10 minutes'}" 
            />

            <div class="tfhb-add-new-question tfhb-flexbox tfhb-gap-8">
                <Icon name="PlusCircle" :width="20"/>
                Add more questions
            </div>
        </div>
        <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="UpdateGeneralSettings">{{ $tfhb_trans['Save & Continue'] }} </button>
        <!--Bookings -->
    </div>
</template>