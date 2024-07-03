<script setup> 
import { ref, reactive, onBeforeMount } from 'vue';
import axios from 'axios' 
import Icon from '@/components/icon/LucideIcon.vue'
import AvailabilityPopupSingle from '@/components/availability/AvailabilityPopupSingle.vue';
import AvailabilitySingle from '@/components/availability/AvailabilitySingle.vue';
import { toast } from "vue3-toastify"; 
import { Availability } from '@/store/availability';
const isModalOpened = ref(false);
const timeZone = reactive({}); 
const AvailabilityGet = reactive({
  data: [],
});
const GeneralSettings = reactive({});
const availabilityDataSingle = reactive({}) 
const skeleton = ref(true);
// 


const openModal = () => {
  availabilityDataSingle.value = {
    key: 0,
    id: 0,
    title: '',
    time_zone: '',
    date_status: 0,
    time_slots: [
        { 
            day: 'Monday',
            status: 1,
            times: [
                {
                    start: '09:00',
                    end: '17:00',
                },   
            ]
        },
        { 
            day: 'Tuesday', 
            status: 1,
            times: [
                {
                    start: '09:00',
                    end: '17:00',
                }
            ]
        },
        { 
            day: 'Wednesday', 
            status: 1,
            times: [
                {
                    start: '09:00',
                    end: '17:00',
                }
            ]
        },
        { 
            day: 'Thursday', 
            status: 1,
            times: [
                {
                    start: '09:00',
                    end: '17:00',
                }
            ]
        },
        { 
            day: 'Friday', 
            status: 1,
            times: [
                {
                    start: '09:00',
                    end: '17:00',
                }
            ]
        },
        { 
            day: 'Saturday', 
            status: 1,
            times: [
                {
                    start: '09:00',
                    end: '17:00',
                }
            ]
        },
        { 
            day: 'Sunday', 
            status: 1,
            times: [
                {
                    start: '09:00',
                    end: '17:00',
                }
            ]
        }
    ],
    date_slots: [
    ]
  }; 
    availabilityDataSingle.value.time_zone = GeneralSettings.value.time_zone ? GeneralSettings.value.time_zone : '';
  
    availabilityDataSingle.value.time_slots = GeneralSettings.value.week_start_from ?  Availability.RearraingeWeekStart(GeneralSettings.value.week_start_from, availabilityDataSingle.value.time_slots) : availabilityDataSingle.value.time_slots;
 
    isModalOpened.value = true;
};

// Edit availability
const EditAvailabilitySettings = async (key, id, availability ) => { 
    availabilityDataSingle.value.time_slots = GeneralSettings.value.week_start_from ?  Availability.RearraingeWeekStart(GeneralSettings.value.week_start_from, availabilityDataSingle.value.time_slots) : availabilityDataSingle.value.time_slots;
  // availabilityDataSingle.value.key = key;
  availabilityDataSingle.value = availability;
  isModalOpened.value = true;
}

const closeModal = () => { 
  isModalOpened.value = false;
};


// Fetch generalSettings
const fetchAvailabilitySettings = async () => {

  try { 
      const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/availability'); 
      if (response.data.status) { 
          timeZone.value = response.data.time_zone;     
          AvailabilityGet.data = response.data.availability; 
          GeneralSettings.value = response.data.general_settings; 


          skeleton.value = false;
      }
  } catch (error) {
      console.log(error);
  } 
}

// Fetch generalSettings pass value update avaulability
const fetchAvailabilitySettingsUpdate = async (data) => {
  AvailabilityGet.data = data; 
}

// Fetch generalSettings pass value update avaulability
const deleteAvailabilitySettings = async (key, id ) => { 
  const deleteAvailability = {
    key: key,
    id: id
  }
  try { 
      // const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/availability/'+key); 
      const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/availability/delete', deleteAvailability, {
             
      } );
      if (response.data.status) { 
        AvailabilityGet.data = response.data.availability;
         
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
  fetchAvailabilitySettings();
});

 

</script>
<template>
     <div :class="{ 'tfhb-skeleton': skeleton }" class="thb-event-dashboard ">
    <div  class="tfhb-dashboard-heading">
        <div class="tfhb-admin-title tfhb-m-0"> 
            <h1 >{{ $tfhb_trans['Availability'] }}</h1> 
            <p>{{ $tfhb_trans['Set up booking times when you are available'] }}</p>
        </div>
        <div class="thb-admin-btn right"> 
            <button class="tfhb-btn boxed-btn flex-btn" @click="openModal"><Icon name="PlusCircle" size="15" /> {{ $tfhb_trans[' Add New Availability'] }}</button> 
        </div> 
    </div>
    <div class="tfhb-content-wrap tfhb-flexbox tfhb-gap-tb-24">
         <AvailabilitySingle  v-for="(availability, key) in AvailabilityGet.data" :availability="availability" :key="key" @delete-availability="deleteAvailabilitySettings(key, availability.id)" @edit-availability="EditAvailabilitySettings(key, availability.id, availability)"  />

         <AvailabilityPopupSingle v-if="isModalOpened" :timeZone="timeZone.value" :availabilityDataSingle="availabilityDataSingle.value" :isOpen="isModalOpened" @modal-close="closeModal"  @update-availability="fetchAvailabilitySettingsUpdate" />
    </div>
</div>
 
</template>