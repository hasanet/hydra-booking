<script setup>
    import { ref, reactive, onBeforeMount, } from 'vue'; 
    import { useRouter, RouterView,} from 'vue-router' 
    import Icon from '@/components/icon/LucideIcon.vue'

    // import Form Field 
    import HbSelect from '@/components/form-fields/HbSelect.vue' 
    import HbText from '@/components/form-fields/HbText.vue'
    import HbSwitch from '@/components/form-fields/HbSwitch.vue';
    import HbPopup from '@/components/widgets/HbPopup.vue';  
    
    const zoomPopup = ref(false);

    const zoom_integration = reactive(  { 
        status: 0, 
        connection_status: 0,   
    });
</script>

<template>
      <!-- Zoom Integrations  -->
      <div class="tfhb-integrations-single-block tfhb-admin-card-box ">
        <span class="tfhb-integrations-single-block-icon">
            <img :src="$tfhb_url+'/assets/images/Zoom.png'" alt="">
        </span> 

        <h3>Zoom</h3>
        <p>New standard in online payment</p>

        <div class="tfhb-integrations-single-block-btn tfhb-flexbox">
            <button @click="zoomPopup = true" class="tfhb-btn tfhb-flexbox tfhb-gap-8">{{ zoom_integration.connection_status == 1 ? 'connected' : 'connect'  }} <Icon name="ChevronRight" size="15px" /></button>
                <!-- Checkbox swicher -->

                <HbSwitch v-if="zoom_integration.connection_status"  v-model="zoom_integration.status"    />
            <!-- Swicher --> 
        </div>

        <HbPopup :isOpen="zoomPopup" @modal-close="zoomPopup = false" max_width="600px" name="first-modal">
            <template #header> 
                <h2>Add New Zoom User Account</h2>
                
            </template>

            <template #content>  
                <p>
                    Please read the documentation here for step by step guide to know how you can get api credentials from Zoom Account
                </p>
                <HbText  
                    v-model="account_id"  
                    required= "true"  
                    :label="$tfhb_trans['Zoom Account ID']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Enter Your Account ID']"  
                /> 
                <HbText  
                    v-model="app_client_id"  
                    required= "true"  
                    :label="$tfhb_trans['Zoom App Client ID']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Enter Your App Client ID']"  
                /> 
                <HbText  
                    v-model="app_secret_key"  
                    required= "true"  
                    :label="$tfhb_trans['Zoom App Secret Key']"  
                    selected = "1"
                    type = "password"
                    :placeholder="$tfhb_trans['Enter Your App Secret Key']"  
                /> 
                <button class="tfhb-btn boxed-btn" @click="UpdateHostsInformation">{{ $tfhb_trans['Save & Validate'] }}</button>
            </template> 
        </HbPopup>

    </div>  
    <!-- Single Integrations  -->

</template>

<style scoped>
</style> 