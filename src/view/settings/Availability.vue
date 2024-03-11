<script setup> 
import { ref, reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView,} from 'vue-router' 
import axios from 'axios' 
import Icon from '@/components/icon/LucideIcon.vue'
import AvailabilityPopupSingle from '@/components/availability/AvailabilityPopupSingle.vue';
import AvailabilitySingle from '@/components/availability/AvailabilitySingle.vue';
import { toast } from "vue3-toastify"; 

const isModalOpened = ref(false);

const openModal = () => {
  isModalOpened.value = true;
};
const closeModal = () => { 
  isModalOpened.value = false;
};
const timeZone = reactive({}); 
const AvailabilityGet = reactive({
  data: [],
});

// Fetch generalSettings
const fetchAvailabilitySettings = async () => {

  try { 
      const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/availability'); 
      if (response.data.status) { 
          timeZone.value = response.data.time_zone;     
          AvailabilityGet.data = response.data.availability; 
      }
  } catch (error) {
      console.log(error);
  } 
}

onBeforeMount(() => { 
  fetchAvailabilitySettings();
});

 
const skeleton = ref(false);

</script>
<template>
     <div :class="{ 'tfhb-skeleton': skeleton }" class="thb-event-dashboard ">
  
    <div  class="tfhb-dashboard-heading ">
        <div class="tfhb-admin-title"> 
            <h1 >{{ $tfhb_trans['Availability'] }}</h1> 
            <p>{{ $tfhb_trans['Set up booking times when you are available'] }}</p>
        </div>
        <div class="thb-admin-btn right"> 
            <button class="tfhb-btn boxed-btn flex-btn" @click="openModal"><Icon name="PlusCircle" size="15px" /> {{ $tfhb_trans[' Add New Availability'] }}</button> 
        </div> 
    </div>
    <div class="tfhb-content-wrap tfhb-flexbox">
         <AvailabilityPopupSingle :timeZone="timeZone.value" :isOpen="isModalOpened" @modal-close="closeModal"  @update-availability="fetchAvailabilitySettings" />
         <AvailabilitySingle  v-for="(availability, key) in AvailabilityGet.data" :availability="availability" :key="key" />
    </div>
</div>
 
</template>