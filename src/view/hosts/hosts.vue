<script setup>
import { reactive, onBeforeMount, ref } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue'
import Header from '@/components/Header.vue';
import CreateHostPopup from '@/components/hosts/CreateHostPopup.vue';
// Import for redirect route  
 

import { toast } from "vue3-toastify"; 
const router = useRouter();
const isModalOpened = ref(false);
const skeleton = ref(true);
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
            hosts.data = response.data.hosts; 
            skeleton.value = false;
        }
    } catch (error) {
        console.log(error);
    } 
} 
// Hosts
const CreateHosts = async (host) => {    
    // redirect with router argument
   
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
            closeModal(); 
            hosts.data = response.data.hosts;  
            router.push({ name: 'HostsProfile', params: { id: response.data.id} });
            // Redirecto to Other Route
            // router.push({ name: 'HostsProfile' });
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

// Delete Hosts 
const deleteHost = async ($id, $user_id) => { 
    let deleteHost = {
        id: $id,
        user_id: $user_id
    }
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/delete', deleteHost, {
               
        } );
        if (response.data.status) { 
            hosts.data = response.data.hosts;  
            toast.success(response.data.message, {
                position: 'bottom-right', // Set the desired position
                "autoClose": 1500,
            }); 
        }
    } catch (error) {
        console.log(error);
    }
}

// Delete Hosts 
const updateHostStatus = async ($id, $user_id, $status) => { 
    let HostData = {
        id: $id,
        user_id: $user_id,
        status: $status
    }
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/update-status', HostData, {
               
        } );
        if (response.data.status) { 
            hosts.data = response.data.hosts;  
            toast.success(response.data.message, {
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

// Filtering
const filterData = reactive({
    name: '',
})
const Tfhb_Host_Filter = async (e) =>{
    filterData.name=e.target.value;
    skeleton.value = true;
    try {
        const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/filter', {
            params: {
                filterData
            },
        });
        
        if (response.data.status) { 
            hosts.data = response.data.hosts;  
            skeleton.value = false;
        }

    } catch (error) {
        console.error('Error:', error);
    }
}

</script>

<template>

    <!-- {{ tfhbClass }} -->
    <div :class="{ 'tfhb-skeleton': skeleton }"  class="tfhb-admin-hosts">
        <Header title="Hosts" />
        <div class="tfhb-dashboard-heading tfhb-flexbox">
           <div class="tfhb-header-filters">
                <input type="text" @keyup="Tfhb_Host_Filter" placeholder="Search by host name" /> 
                <span><Icon name="Search" size="20" /></span>
           </div>
            <div class="thb-admin-btn right">
               <button class="tfhb-btn boxed-btn flex-btn" @click="openModal"><Icon name="PlusCircle" size="15" /> {{ $tfhb_trans['Add New Host'] }}</button> 
            </div> 
        </div>
        <div class="tfhb-hosts-content">  
            <CreateHostPopup v-if="isModalOpened" :isOpen="isModalOpened" :usersData="usersData.data" @modal-close="closeModal" @hosts-create="CreateHosts"   />
            <router-view :host_list="hosts.data" @update-host-status="updateHostStatus" @delete-host="deleteHost" :host_skeleton="skeleton" /> 
        </div> 
    </div>
</template>



<style scoped lang="scss">
/* Your component styles go here */

 
</style>
