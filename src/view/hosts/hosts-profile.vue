<script setup>
import { reactive, onBeforeMount, ref } from 'vue';
import { useRouter, useRoute, RouterView } from 'vue-router' 
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue'

// Get Current Route url 
const route = useRoute();
const skeleton = ref(true);
const router = useRouter();
const hostData = reactive({
    id: 0,
    user_id: 0,
    first_name: '',
    first_name: '',
    email: '',
    phone_number: '',
    phone_number: '',
    about: '',
    avatar: '',
    featured_image: '',
    time_zone: '',
    status: '',

});
const time_zone = reactive({});

const hostId = route.params.id;
 // Fetch generalSettings
 const fetchHost = async () => {

    try { 
        const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/'+hostId);
        if (response.data.status == true) { 
            // console.log(response.data.host)
            hostData.id = response.data.host.id;
            hostData.user_id = response.data.host.user_id;
            hostData.first_name = response.data.host.first_name;
            hostData.last_name = response.data.host.last_name;
            hostData.email = response.data.host.email;
            hostData.phone_number = response.data.host.phone_number;
            hostData.about = response.data.host.about;
            hostData.avatar = response.data.host.avatar;
            hostData.featured_image = response.data.host.featured_image;
            hostData.status = response.data.host.status;
            skeleton.value = false;
            time_zone.data = response.data.time_zone; 
        }else{ 
            // return to redirect back route 
            router.push({ name: 'HostsLists' });
        }
    } catch (error) {
        
        console.log(error);
    } 
} 
onBeforeMount(() => { 
    fetchHost();
});
 
</script>

<template>
    <!-- {{ tfhbClass }} --> 
    <div :class="{ 'tfhb-skeleton': skeleton }" class="tfhb-hydra-wrap tfhbb-host-profile-page ">    
        <div  class="tfhb-dashboard-heading ">
            <div class="tfhb-admin-title"> 
                <h3>{{hostData.first_name}} {{ hostData.last_name }} Profile  </h3>   
            </div> 
        </div>
        <nav class="tfhb-booking-tabs"> 
            <ul>
                <!-- to route example like hosts/profile/13/information -->
                
                <li><router-link :to="'/hosts/profile/'+ $route.params.id +'/information'" exact :class="{ 'active': $route.path === '/hosts/profile/'+ $route.params.id +'/information' }"> <Icon name="UserRound" /> Information</router-link></li> 
                <li><router-link :to="'/hosts/profile/'+ $route.params.id +'/meeting'" :class="{ 'active': $route.path === '/hosts/profile/'+ $route.params.id +'/meeting' }"> <Icon name="Clock" /> Meeting</router-link></li>  
                <li><router-link :to="'/hosts/profile/'+ $route.params.id +'/integrations'" :class="{ 'active': $route.path === '/hosts/profile/'+ $route.params.id +'/integrations' }"> <Icon name="Unplug" /> Integrations</router-link></li>  

            </ul>  
        </nav>
        <div class="tfhb-hydra-dasboard-content">      
            <router-view :hostId ="hostId" :host="hostData" :time_zone="time_zone.data"/>
            
        </div> 
    </div> 
</template>



<style scoped lang="scss">
/* Your component styles go here */

.tfhb-hydra-dasboard {

    width: 100%;

    .tfhb-hydra-wrap {
        display: flex;
        // gap: 16px;
    }

    .tfhb-hydra-settings {

        width: 272px;

        ul {
            display: inline-block;
        }
    }

    .tfhb-hydra-dasboard-content {
        width: calc(100% - 272px);
        padding: 24px;
    }

    .tfhb-hydra-wrap {
        width: auto;
        margin-right: 10px;
    }
}
</style>
