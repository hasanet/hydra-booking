<script setup>
import { reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue'
import HbSelect from '@/components/form-fields/HbSelect.vue'
import HbText from '@/components/form-fields/HbText.vue'
import HbImageBox from '@/components/form-fields/HbImageBox.vue'
import { toast } from "vue3-toastify";
import { Upload } from 'lucide-vue-next';
// Get Current Route url 
const props = defineProps({
    hostId: {
        type: Number,
        required: true
    },
    host: {
        type: Object,
        required: true
    },
    time_zone:{}

});
const UpdateHostsInformation = async () => {
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/information/update', props.host);
        if (response.data.status == true) { 
            toast.success(response.data.message); 
        }else{
            toast.error(response.data.message); 
        }
    } catch (error) {
        console.log(error);
    } 
}
 

</script>

<template>
    <div class="tfhb-admin-card-box">  
        <!-- Avater --> 
        <!-- <HbImageBox  
            v-model="host.avatar"  
            required= "true"  
            name= "host_avatar"  
            type= "file"  
            :label="$tfhb_trans['Profile image']"  
            selected = "1" 
            width="50"
            :description = "$tfhb_trans['Recommended Image Size: 400x400px']"
        />   -->

    </div>
    <div class="tfhb-admin-card-box tfhb-flexbox">  
        <HbText  
            v-model="host.first_name"  
            required= "true"  
            :label="$tfhb_trans['First name']"  
            selected = "1"
            :placeholder="$tfhb_trans['Type your first name']" 
            width="50"
        /> 
        <HbText  
            v-model="host.last_name"  
            required= "true"  
            :label="$tfhb_trans['Last name']"  
            selected = "1"
            :placeholder="$tfhb_trans['Type your last name']" 
            width="50"
        />  
        <HbText  
            v-model="host.email"  
            required= "true"  
            :label="$tfhb_trans['Email']"  
            selected = "1"
             :placeholder="$tfhb_trans['Type your email']" 
            width="50"
            disabled="true"
        /> 
        <HbText  
            v-model="host.mobile"  
            required= "true"  
            :label="$tfhb_trans['Mobile']"  
            selected = "1"
            :placeholder="$tfhb_trans['Type your mobile no']" 
            width="50" 
        />  
         <!-- Time Zone -->
         <HbSelect 
            
            v-model="host.time_zone"  
            required= "true"  
            :label="$tfhb_trans['Time zone']"  
            selected = "1"
            placeholder="Select Time Zone"  
            :option = "props.time_zone" 
        /> 
    <!-- Time Zone -->
        <!--  Update Hosts Information -->
        <button class="tfhb-btn boxed-btn" @click="UpdateHostsInformation">{{ $tfhb_trans['Save'] }}</button>
    </div>  
</template>


 