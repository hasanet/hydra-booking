<script setup>
import { ref, reactive, onBeforeMount } from 'vue';
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue'
import AvailabilityPopupSingle from '@/components/availability/AvailabilityPopupSingle.vue';
import AvailabilitySingle from '@/components/availability/AvailabilitySingle.vue';
import { toast } from "vue3-toastify"; 
import { hostInfo, fetchHost } from '@/store/availability';

const props = defineProps({
    hostId: {
        type: Number,
        required: true
    },
    host: {
        type: Object,
        required: true
    },
});


const isModalOpened = ref(false);
const timeZone = reactive({}); 
const AvailabilityGet = reactive({
  data: [],
});
const availabilityDataSingle = reactive({}) 
const skeleton = ref(true);


const openModal = () => {
  availabilityDataSingle.value = {
    host: props.hostId,
    user_id: props.host.user_id,
    key: 0,
    id: '',
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
        {
            start: '2022-01-01',
            end: '2022-01-01',
        }
    ]
  };
  isModalOpened.value = true;
};

const closeModal = () => { 
  isModalOpened.value = false;
};

const fetchAvailabilitySettings = async () => {

    let data = {
        id: hostInfo.value
    };  

    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/availability', data, {
            headers: {
                'X-WP-Nonce': tfhb_core_apps.rest_nonce
            } 
        } );
        
        if (response.data.status) {    
            AvailabilityGet.data = response.data.availability; 
            timeZone.value = response.data.time_zone;     
        }else{
            toast.error(response.data.message, {
                position: 'bottom-right', // Set the desired position
            });
        }

    } catch (error) {
        console.log(error);
    } 
}

onBeforeMount(() => { 
    fetchHost(props.hostId).then(() => {
        fetchAvailabilitySettings();
    });
});

// Edit availability
const EditAvailabilitySettings = async (key, id, availability ) => { 
  availabilityDataSingle.value = availability;
  availabilityDataSingle.value.id = key;
  availabilityDataSingle.value.host = props.hostId;
  isModalOpened.value = true;
}

// Fetch generalSettings pass value update avaulability
const fetchAvailabilitySettingsUpdate = async (data) => {
  AvailabilityGet.data = data; 
}

// Fetch generalSettings pass value update avaulability
const deleteAvailabilitySettings = async (key, id, user_id ) => { 
  const deleteAvailability = {
    key: key,
    id: id,
    user_id: user_id
  }
  try { 
      const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/availability/delete', deleteAvailability, {
             
      } );
      if (response.data.status) { 
        AvailabilityGet.data = response.data.availability; 
          toast.success(response.data.message); 
      }
  } catch (error) {
      console.log(error);
  }
}

</script>

<template>
<div class="tfhb-host-availability">
    <div class="tfhb-dashboard-heading">
        <div class="tfhb-admin-title"> 
            <h1 >{{ $tfhb_trans['Availability'] }}</h1> 
            <p>{{ $tfhb_trans['Set up booking times when you are available'] }}</p>
        </div>
        <div class="thb-admin-btn right"> 
            <button class="tfhb-btn boxed-btn flex-btn" @click="openModal"><Icon name="PlusCircle" size="15px" /> {{ $tfhb_trans[' Add New Availability'] }}</button> 
        </div> 
    </div>

    <div class="tfhb-content-wrap tfhb-flexbox">

        <AvailabilitySingle  v-for="(availability, key) in AvailabilityGet.data" :availability="availability" :key="key"  @edit-availability="EditAvailabilitySettings(key, availability.id, availability)" @delete-availability="deleteAvailabilitySettings(key, availability.id, host.user_id)" />

        <AvailabilityPopupSingle v-if="isModalOpened" :timeZone="timeZone.value" :availabilityDataSingle="availabilityDataSingle.value" :isOpen="isModalOpened" @modal-close="closeModal" :is_host="true" @update-availability="fetchAvailabilitySettingsUpdate" />
    </div>
</div>
</template>


 