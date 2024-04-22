<script setup>
import { ref, reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue'
import AvailabilityPopupSingle from '@/components/availability/AvailabilityPopupSingle.vue';


const isModalOpened = ref(false);
const timeZone = reactive({}); 
const AvailabilityGet = reactive({
  data: [],
});
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
        <AvailabilityPopupSingle v-if="isModalOpened" :timeZone="timeZone.value" :availabilityDataSingle="availabilityDataSingle.value" :isOpen="isModalOpened" @modal-close="closeModal"  @update-availability="fetchAvailabilitySettingsUpdate" />
    </div>
</div>
</template>


 