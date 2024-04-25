<script setup>
import { reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue'
import HbSelect from '@/components/form-fields/HbSelect.vue'
import HbText from '@/components/form-fields/HbText.vue'
import HbImageBox from '@/components/form-fields/HbImageBox.vue'
import { Upload } from 'lucide-vue-next';
const emit = defineEmits(["save-host-info"]); 

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

const imageChange = (attachment) => {  
    const image = document.querySelector('.'+props.name+'_display'); 
    image.src = attachment.url; 
    // props.modelValue = attachment.url; 
}
const UploadImage = () => {   
    wp.media.editor.send.attachment = (props, attachment) => { 
    // set the image url to the input field
    imageChange(attachment);
    };  
    wp.media.editor.open(); 
}

</script>

<template>
    <div class="tfhb-admin-card-box">   

        <div class="tfhb-single-form-field-wrap tfhb-flexbox">
            <div class="tfhb-field-image" > 
                <img  :class="name+'_display'"  :src="host.avatar">
                <button class="tfhb-image-btn tfhb-btn" @click="UploadImage">Change</button> 
                <input  type="text"  :v-model="host.avatar"   /> 

            </div>
            <div class="tfhb-image-box-content">  
            <h4 v-if="label !=''" :for="name">{{ $tfhb_trans['Profile image'] }} <span  v-if="required == 'true'"> *</span> </h4>
            <p v-if="description !=''">{{ $tfhb_trans['Recommended Image Size: 400x400px'] }}</p>
            </div>
        </div> 
    </div>
    <div class="tfhb-admin-title" >
        <h2>{{ $tfhb_trans['Information Builder'] }}    </h2> 
        <p>{{ $tfhb_trans['Date and Time Settings'] }}</p>
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
        <!-- Time Zone -->
        <HbSelect 
            
            v-model="host.time_zone"  
            required= "true"  
            :label="$tfhb_trans['Time zone']"  
            selected = "1"
            placeholder="Select Time Zone"  
            :option = "time_zone" 
            width="50" 
        /> 
        <HbText  
            v-model="host.phone_number"  
            required= "true"  
            :label="$tfhb_trans['Mobile']"  
            selected = "1"
            :placeholder="$tfhb_trans['Type your mobile no']" 
            width="50" 
        />  
         
    <!-- Time Zone -->
    </div>  


    <!--  Update Hosts Information -->
    <button class="tfhb-btn boxed-btn" @click="emit('save-host-info')">{{ $tfhb_trans['Save'] }}</button>
</template>


 