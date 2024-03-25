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
    }

});
const UpdateHostsInformation = async () => {
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/information/update', props.host);
        if (response.data.status) { 
            toast.success(response.data.message); 
        }
    } catch (error) {
        console.log(error);
    } 
}
const UploadImage = () => { 
    // on clicked upload image button
    // click to input imagte file
    document.querySelector('input[type="file"]#upload_file').click();


}
const imageChange = (e) => {
    // console.log(e.target.files[0]);
    // on change get the image and display it into image tag 
    const file = e.target.files[0]; 
    // selected image need to display .display
    const img = document.querySelector('img.display');
    img.src = URL.createObjectURL(file);
}


</script>

<template>
    <div class="tfhb-admin-card-box"> 
        <HbImageBox  
            v-model="host.avatar"  
            required= "true"  
            name= "host_avatar"  
            type= "file"  
            :label="$tfhb_trans['Profile image ']"  
            selected = "1" 
            width="50"
            description = "Recommended Image Size: 400x400px"
        /> 
        <div class="tfhb-single-form-field-wrap tfhb-flexbox">
            <div class="tfhb-field-image" > 
                <img class="display" src="https://via.placeholder.com/100" alt="Hosts Avatar">
                <button class="tfhb-image-btn tfhb-btn" @click="UploadImage">Change</button>
                <input type="file" id="upload_file" name="upload_file" @change="imageChange"  >

            </div>
            <div class="tfhb-image-box-content">  
                <h4>Profile image </h4>
                <p>Recommended Image Size: 400x400px</p>
            </div>
        </div>
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
        <!--  Update Hosts Information -->
        <button class="tfhb-btn boxed-btn" @click="UpdateHostsInformation">{{ $tfhb_trans['Save'] }}</button>
    </div>  
</template>


 