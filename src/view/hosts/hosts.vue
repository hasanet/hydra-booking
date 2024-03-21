<script setup>
import { reactive, onBeforeMount, ref } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue'
import Header from '@/components/Header.vue';
import CreateHostPopup from '@/components/hosts/CreateHostPopup.vue';

import { toast } from "vue3-toastify"; 

const isModalOpened = ref(false);
const closeModal = () => { 
  isModalOpened.value = false;
};
const openModal = () => {
  isModalOpened.value = true;
};
const hosts = reactive({});
const usersData = reactive({});
 // Fetch generalSettings
const fetchHosts = async () => {

    try { 
        const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/lists');
        if (response.data.status) { 
            usersData.data = response.data.users; 
            skeleton.value = false;
        }
    } catch (error) {
        console.log(error);
    } 
} 
// Hosts
const CreateHosts = async (host) => {   

    try { 
        // axisos sent dataHeader Nonce Data
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/create', host, {
            headers: {
                'X-WP-Nonce': tfhb_core_apps.rest_nonce
            } 
        } );

        if (response.data.status) {  
            // skeleton.value = false;
            toast.success(response.data.message, {
                position: 'bottom-right', // Set the desired position
                "autoClose": 1500,
            });
        }else{
            toast.error(response.data.message, {
                position: 'bottom-right', // Set the desired position
                "autoClose": 1500,
            });
        }
    } catch (error) {
        console.log(error);
    }   
}
onBeforeMount(() => { 
    fetchHosts();
});
</script>

<template>

    <!-- {{ tfhbClass }} -->
    <div class="tfhb-admin-hosts">
        <Header title="Hosts" />
        <div class="tfhb-dashboard-heading tfhb-flexbox">
           <div class="tfhb-header-filters">
                <input type="text" placeholder="Search Hosts" /> 
           </div>
            <div class="thb-admin-btn right">
               <button class="tfhb-btn boxed-btn flex-btn" @click="openModal"><Icon name="PlusCircle" size="15px" /> {{ $tfhb_trans['Add New Host'] }}</button> 
            </div> 
        </div>
        <div class="tfhb-hydra-hosts-content">  
            <CreateHostPopup v-if="isModalOpened" :isOpen="isModalOpened" :usersData="usersData.data" @modal-close="closeModal" @hosts-create="CreateHosts"   />
            <router-view :hosts="hosts.data"/> 
        </div> 
    </div>
</template>



<style scoped lang="scss">
/* Your component styles go here */

 
</style>
