<script setup> 
import { ref, reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView,} from 'vue-router' 
import axios from 'axios' 
import Icon from '@/components/icon/LucideIcon.vue'
import AvailabilityPopupSingle from '@/components/availability/AvailabilityPopupSingle.vue';
import { toast } from "vue3-toastify"; 

const isModalOpened = ref(true);

const openModal = () => {
  isModalOpened.value = true;
};
const closeModal = () => {
  isModalOpened.value = false;
};


// import Form Field 
import HbSelect from '@/components/form-fields/HbSelect.vue'



const generalSettings = reactive({
  time_zone: '',
  time_format: '',
  week_start_from: '',
  date_format: '',
  country: '',
  after_booking_completed: '',
  booking_status: '',
  allowed_reschedule_before_meeting_start: '', 
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
    <div class="tfhb-content-wrap">
         <AvailabilityPopupSingle :isOpen="isModalOpened" @modal-close="closeModal" />
    </div>
</div>
 
</template>