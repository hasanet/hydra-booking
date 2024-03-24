<script setup>
import { reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue'
import HbSelect from '@/components/form-fields/HbSelect.vue'
import HbText from '@/components/form-fields/HbText.vue'
import { toast } from "vue3-toastify";
// Get Current Route url 
</script>

<template>
    <div class="tfhb-admin-card-box">

    </div>
    <div class="tfhb-admin-card-box tfhb-flexbox">  
        <HbText  
            v-model="first_name"  
            required= "true"  
            :label="$tfhb_trans['First name']"  
            selected = "1"
            :placeholder="$tfhb_trans['Type your first name']" 
            width="50"
        /> 
        <HbText  
            v-model="last_name"  
            required= "true"  
            :label="$tfhb_trans['Last name']"  
            selected = "1"
            :placeholder="$tfhb_trans['Type your last name']" 
            width="50"
        />  
        <HbText  
            v-model="email"  
            required= "true"  
            :label="$tfhb_trans['Email']"  
            selected = "1"
             :placeholder="$tfhb_trans['Type your email']" 
            width="50"
            disabled="true"
        /> 
        <HbText  
            v-model="mobile"  
            required= "true"  
            :label="$tfhb_trans['Mobile']"  
            selected = "1"
            :placeholder="$tfhb_trans['Type your mobile no']" 
            width="50" 
        /> 
        <!--  Update Hosts Information -->
        <button class="tfhb-btn boxed-btn" @click="UpdateAvailabilitySettings">{{ $tfhb_trans['Save'] }}</button>
    </div>  
</template>


 