<script setup>
import { ref, reactive, onBeforeMount } from 'vue';
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue'
import HbSelect from '@/components/form-fields/HbSelect.vue'
import AvailabilityPopupSingle from '@/components/availability/AvailabilityPopupSingle.vue';
import AvailabilitySingle from '@/components/availability/AvailabilitySingle.vue';
import { toast } from "vue3-toastify"; 
import { Host } from '@/store/hosts';
import { Availability } from '@/store/availability';

const emit = defineEmits(["availability-tabs", "save-host-info"]); 
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

const HostAvailability = reactive({
    availability_type: 'settings'
})


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
    ]
  };
  isModalOpened.value = true;
};

const closeModal = () => { 
  isModalOpened.value = false;
};

const fetchAvailabilitySettings = async () => {

    let data = {
        id: Host.hostInfo
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
    Host.fetchHost(props.hostId).then(() => {
        fetchAvailabilitySettings();
    });
    Availability.fetchAvailability();
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
  props.host.availability = data; 
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
        props.host.availability = response.data.availability; 
        toast.success(response.data.message); 
      }
  } catch (error) {
      console.log(error);
  }
}

// Fetch generalSettings
const Settings_avalibility = ref();
const fetchAvailabilitySingle = async (setting) => {
    try { 
        const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/availability/'+setting); 
        if (response.data.status) { 
            Settings_avalibility.value = response.data;
        }
    } catch (error) {
        console.log(error);
    } 
}

const Settings_Avalibility_Callback = (e) => {
    if(e.target.value){
        fetchAvailabilitySingle(e.target.value);
    }
}

</script>

<template>
<div class="tfhb-host-availability">

    <div class="tfhb-availaility-tabs tfhb-mb-24">
        <ul class="tfhb-flexbox tfhb-gap-16 tfhb-justify-normal">
            <li class="tfhb-flexbox tfhb-gap-8" :class="'settings'==host.availability_type ? 'active' : ''" @click="emit('availability-tabs', 'settings')"><Icon name="Heart" :width="20" /> Use existing availability</li>
            <li class="tfhb-flexbox tfhb-gap-8" :class="'custom'==host.availability_type ? 'active' : ''" @click="emit('availability-tabs', 'custom')"><Icon name="PencilLine" :width="20" /> Custom availability</li>
        </ul>
    </div>

    <HbSelect 
        v-model="host.availability_id"
        required= "true" 
        :label="$tfhb_trans['Choose Schedule']"  
        :selected = "1"
        :placeholder="$tfhb_trans['Choose Schedule']"   
        :option = "Availability.availabilities" 
        v-if="'settings'==host.availability_type"
        @tfhb-onchange="Settings_Avalibility_Callback"
    />

    <div class="tfhb-dashboard-heading" v-if="'custom'==host.availability_type">
        <div class="tfhb-admin-title"> 
            <h3 >{{ $tfhb_trans['Availability'] }}</h3> 
            <p>{{ $tfhb_trans['Set up booking times when you are available'] }}</p>
        </div>
        <div class="thb-admin-btn right"> 
            <button class="tfhb-btn boxed-btn flex-btn" @click="openModal"><Icon name="PlusCircle" size="15px" /> {{ $tfhb_trans[' Add New Availability'] }}</button> 
        </div> 
    </div>

    <div class="tfhb-content-wrap tfhb-flexbox" v-if="'custom'==host.availability_type">
        <AvailabilitySingle  v-for="(availability, key) in AvailabilityGet.data" :availability="availability" :key="key"  @edit-availability="EditAvailabilitySettings(key, availability.id, availability)" @delete-availability="deleteAvailabilitySettings(key, availability.id, host.user_id)" />

        <AvailabilityPopupSingle v-if="isModalOpened" :timeZone="timeZone.value" :availabilityDataSingle="availabilityDataSingle.value" :isOpen="isModalOpened" @modal-close="closeModal" :is_host="true" @update-availability="fetchAvailabilitySettingsUpdate" />
    </div>
    <div class="tfhb-submission-btn tfhb-mt-24">
        <button class="tfhb-btn boxed-btn" @click="emit('save-host-info')">{{ $tfhb_trans['Save'] }}</button>
    </div>
</div>
</template>