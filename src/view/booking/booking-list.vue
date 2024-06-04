<script setup>
import { ref, reactive, onBeforeMount, computed } from 'vue';
import axios from 'axios'  
import 'primevue/resources/themes/aura-light-green/theme.css'
import Icon from '@/components/icon/LucideIcon.vue'
import HbText from '@/components/form-fields/HbText.vue';
import HbSelect from '@/components/form-fields/HbSelect.vue';
import HbPopup from '@/components/widgets/HbPopup.vue'; 
import AutoComplete from 'primevue/autocomplete';
import { toast } from "vue3-toastify"; 
import useDateFormat from '@/store/dateformat'
const { Tfhb_Date, Tfhb_Time } = useDateFormat();
import { Meeting } from '@/store/meetings'
import { Booking } from '@/store/booking'

const booking_data = reactive({
    meeting: '',
    name: '',
    phone: '',
    email: '',
    address: '',
});

const BookingDetailsPopup = ref(false);
const BackendBooking = ref(false);
const itemsPerPage = ref(10);
const currentPage = ref(1);

// Add New Booking
const Tfhb_BackendBooking = async () => {
    // Api Submission
    try { 

        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/booking/create', booking_data, {
            headers: {
                'X-WP-Nonce': tfhb_core_apps.rest_nonce
            } 
        } );

        // Api Response
        if (response.data.status) {  
            Booking.bookings = response.data.booking;  
            toast.success(response.data.message, {
                position: 'bottom-right', // Set the desired position
                "autoClose": 1500,
            });  
            BackendBooking.value = false;
            booking_data.meeting = '';
            booking_data.name = '';
            booking_data.phone = '';
            booking_data.email = '';
            booking_data.address = '';
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

const TfhbFormatMeetingLocation = (address) => {
    const meeting_address = JSON.parse(address)
    return meeting_address.map(loc => loc.location).join(', ');
}

onBeforeMount(() => { 
    Booking.fetchBookings();
    Meeting.fetchMeetings();
});

// Auto Suggetions Meetings
const defaultItems = [...Array(10).keys()].map(item => 'default-' + item);
const meeting_items = ref(defaultItems);
const search = (event) => {
    meeting_items.value = event.query ? defaultItems.filter(item => item.includes(event.query)) : defaultItems;
}


// Booking Status Changed
const meeting_status = reactive({});
const UpdateMeetingStatus = async (id, status) => {    
    meeting_status.id = id
    meeting_status.status = status
   try { 
        // axisos sent dataHeader Nonce Data
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/booking/update', meeting_status, {
            headers: {
                'X-WP-Nonce': tfhb_core_apps.rest_nonce
            } 
        } );

        if (response.data.status) {  
            Booking.bookings = response.data.booking; 

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

const singleBookingData = ref('');
const Tfhb_Booking_View = async (data) => {   
    singleBookingData.value = data;
    BookingDetailsPopup.value = true;
}

// Pagination
const totalPages = computed(() => {
  return Math.ceil(Booking.bookings.length / itemsPerPage.value);
});

const paginatedBooking = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return Booking.bookings.slice(start, end);
});

const changePage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value += 1;
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value -= 1;
  }
};

</script>
<template>

<!-- {{ tfhbClass }} -->
<div :class="{ 'tfhb-skeleton': Booking.skeleton }" class="tfhb-dashboard-heading tfhb-flexbox">
    <div class="tfhb-filter-box tfhb-flexbox">
        <div class="tfhb-header-filters">
            <input type="text" placeholder="Host name or meeting title" /> 
            <span><Icon name="Search" size="20" /></span>
        </div>
    </div>
    <div class="thb-admin-btn right tfhb-flexbox tfhb-action-filter-button">
        <HbSelect 
            :selected = "1"
            placeholder="Status"  
            :option = "{'12_hours': '30 minutes', '24_hours': '10 minutes'}" 
        />
        <button class="tfhb-btn boxed-btn flex-btn" @click="BackendBooking = true"><Icon name="PlusCircle" size="20" /> {{ $tfhb_trans['Add New Booking'] }}</button>
    </div> 
</div>

<!-- Booking Quick View Start -->

<HbPopup :isOpen="BookingDetailsPopup" @modal-close="BookingDetailsPopup = false" max_width="750px" name="first-modal" gap="32px">
    <template #header> 
        <h3>{{ singleBookingData.title }}</h3>
    </template>

    <template #content> 

        <div class="tfhb-attendee-info tfhb-full-width tfhb-flexbox tfhb-gap-16">
            <h3 class="tfhb-m-0 tfhb-full-width">{{ $tfhb_trans['Attendee'] }}</h3>
            <div class="tfhb-attendee-box tfhb-p-24 tfhb-pt-0 tfhb-flexbox tfhb-align-baseline tfhb-full-width">
                <div class="tfhb-attendee-name" v-if="singleBookingData.attendee_name">
                    <h4>{{ $tfhb_trans['Name'] }}</h4>
                    <p>{{ singleBookingData.attendee_name }}</p>
                </div>
                <div class="tfhb-attendee-name" v-if="singleBookingData.attendee_email">
                    <h4>{{ $tfhb_trans['E-mail'] }}</h4>
                    <p>{{ singleBookingData.attendee_email }}</p>
                </div>
                <div class="tfhb-attendee-name" v-if="singleBookingData.attendee_phone">
                    <h4>{{ $tfhb_trans['Phone'] }}</h4>
                    <p>{{ singleBookingData.attendee_phone }}</p>
                </div>
                <div class="tfhb-attendee-name" v-if="singleBookingData.address">
                    <h4>{{ $tfhb_trans['Address'] }}</h4>
                    <p>{{ singleBookingData.address }}</p>
                </div>
                <div class="tfhb-attendee-name" style="width: calc(66% - 4px);" v-if="singleBookingData.message">
                    <h4>{{ $tfhb_trans['Notes'] }}</h4>
                    <p>{{ singleBookingData.message }}</p>
                </div>
            </div>
        </div>

        <div class="tfhb-attendee-info tfhb-full-width tfhb-flexbox tfhb-gap-16">
            <h3 class="tfhb-m-0 tfhb-full-width">Host</h3>
            <div class="tfhb-attendee-box tfhb-p-24 tfhb-pt-0 tfhb-flexbox tfhb-align-baseline tfhb-full-width">
                <div class="tfhb-attendee-name" v-if="singleBookingData.host_first_name">
                    <h4>{{ $tfhb_trans['Name'] }}</h4>
                    <p>{{ singleBookingData.host_first_name }} {{ singleBookingData.host_last_name }}</p>
                </div>
                <div class="tfhb-attendee-name" v-if="singleBookingData.host_email">
                    <h4>{{ $tfhb_trans['E-mail'] }}</h4>
                    <p>{{ singleBookingData.host_email }}</p>
                </div>
                <div class="tfhb-attendee-name" v-if="singleBookingData.host_time_zone">
                    <h4>{{ $tfhb_trans['Time zone'] }}</h4>
                    <p>{{ singleBookingData.host_time_zone }}</p>
                </div>
            </div>
        </div>

        <div class="tfhb-attendee-info tfhb-full-width tfhb-flexbox tfhb-gap-16 tfhb-border-none">
            <h3 class="tfhb-m-0 tfhb-full-width">{{ $tfhb_trans['Meeting'] }}</h3>
            <div class="tfhb-attendee-box tfhb-p-24 tfhb-pt-0 tfhb-pb-0 tfhb-flexbox tfhb-align-baseline tfhb-full-width">
                <div class="tfhb-attendee-name">
                    <h4>{{ $tfhb_trans['Time'] }}</h4>
                    <p>{{singleBookingData.start_time}} - {{singleBookingData.end_time}}</p>
                </div>
                <div class="tfhb-attendee-name">
                    <h4>{{ $tfhb_trans['Date'] }}</h4>
                    <p>{{ Tfhb_Date(singleBookingData.meeting_dates) }}</p>
                </div>
                <div class="tfhb-attendee-name">
                    <h4>{{ $tfhb_trans['Location'] }}</h4>
                    <p>{{ TfhbFormatMeetingLocation(singleBookingData.meeting_locations) }}</p>
                </div>
            </div>
        </div>

    </template> 
</HbPopup>

<!-- Booking Quick View End -->

<!-- Backend Booking Popup Start -->

<HbPopup :isOpen="BackendBooking" @modal-close="BackendBooking = false" max_width="400px" name="first-modal" gap="24px">
    <template #header> 
        <h3>{{ $tfhb_trans['Add New Booking'] }}</h3>
    </template>

    <template #content> 
        
        <div class="tfhb-meeting-title">
            <label>{{ $tfhb_trans['Meeting Name'] }} *</label>
            <AutoComplete v-model="booking_data.meeting" :suggestions="meeting_items" @complete="search" placeholder="Search by Meeting title..." />
        </div>

        <HbText  
            v-model="booking_data.name"
            required= "true"  
            :label="$tfhb_trans['Attendee']"  
        /> 
        <HbText  
            v-model="booking_data.email"
            required= "true"  
            :label="$tfhb_trans['Attendee Email']"  
        /> 
        <HbText  
            v-model="booking_data.phone"
            required= "true"  
            :label="$tfhb_trans['Attendee Phone']"  
        />
        <HbText  
            v-model="booking_data.address"
            required= "true"  
            :label="$tfhb_trans['Attendee Address']"  
        /> 

        <div class="tfhb-button-group tfhb-flexbox tfhb-gap-16">
            <button class="tfhb-btn boxed-btn secondary-btn tfhb-flexbox" @click="BackendBooking = false">
                {{ $tfhb_trans['Cancel'] }}
            </button>
            <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="Tfhb_BackendBooking()">
                {{ $tfhb_trans['Save'] }}
            </button>
        </div>
    </template> 

</HbPopup>

<!-- Backend Booking Popup End -->

<div :class="{ 'tfhb-skeleton': Booking.skeleton }"  class="tfhb-booking-details tfhb-mt-32">
    <table class="table" cellpadding="0" :cellspacing="0">
        <thead>
            <tr>
                <th></th>
                <th>{{ $tfhb_trans['Date & Time'] }}</th>
                <th>{{ $tfhb_trans['Title'] }}</th>
                <th>{{ $tfhb_trans['Host'] }}</th>
                <th>{{ $tfhb_trans['Attendee'] }}</th>
                <th>{{ $tfhb_trans['Duration'] }}</th>
                <th>{{ $tfhb_trans['Status'] }}</th>
                <th>{{ $tfhb_trans['Action'] }}</th>
            </tr>
        </thead>

        <tbody v-if="paginatedBooking">
            <tr v-for="(book, key) in paginatedBooking" :key="key">
                <td>
                    <div class="checkbox-lists">
                        <label>
                            <input type="checkbox" :value="book.id">   
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </td>
                <td>
                    {{ Tfhb_Date(book.meeting_dates) }}
                    <span>{{ book.start_time }} - {{ book.end_time }}</span>
                </td>
                <td>
                    {{ book.title }}
                </td>
                <td>
                    {{ book.host_first_name }} {{ book.host_last_name }}
                    <span>{{ book.host_email }}</span>
                </td>
                <td>
                    {{ book.attendee_first_name }} {{ book.attendee_last_name }}
                    <span>{{ book.attendee_email }}</span>
                </td>
                <td>
                    {{ book.duration }}
                </td>
                <td>
                    <div class="tfhb-details-status tfhb-flexbox tfhb-justify-normal tfhb-gap-0">
                        <div class="status" :class="book.booking_status">
                            {{ book.booking_status }}
                        </div>
                        <div class="tfhb-status-bar">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 13.334L5 8.33398H15L10 13.334Z" fill="#765664"/>
                            </svg>
                            <div class="tfhb-status-popup">
                                <ul class="tfhb-flexbox tfhb-gap-2">
                                    <li @click="UpdateMeetingStatus(book.id, 'approved')">{{ $tfhb_trans['Approved'] }}</li>
                                    <li class="pending" @click="UpdateMeetingStatus(book.id, 'pending')">{{ $tfhb_trans['Pending'] }}</li>
                                    <li class="schedule" @click="UpdateMeetingStatus(book.id, 'schedule')">{{ $tfhb_trans['Re-schedule'] }}</li>
                                    <li class="canceled" @click="UpdateMeetingStatus(book.id, 'canceled')">{{ $tfhb_trans['Canceled'] }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="tfhb-details-action tfhb-flexbox tfhb-justify-normal tfhb-gap-16">
                        <span @click.stop="Tfhb_Booking_View(book)">
                            <Icon name="Eye" width="20" />
                        </span>
                        <a href="">
                            <Icon name="Settings" width="20" />
                        </a>
                    </div>
                </td>
            </tr>
            
        </tbody>
    </table>

    <div class="tfhb-booking-details-pagination tfhb-flexbox tfhb-mt-32" v-if="totalPages > 1">
        <div class="tfhb-prev-next-button">
            <a href="#" @click.prevent="prevPage" class="tfhb-flexbox tfhb-gap-8 tfhb-justify-normal" :disabled="currentPage === 1"><Icon name="ArrowLeft" width="20" />Previous</a>
        </div>
        <div class="tfhb-pagination">
            <ul class="tfhb-flexbox tfhb-gap-0 tfhb-justify-normal">
                <li v-for="page in totalPages" :key="page" :class="{ active: page === currentPage }">
                    <a href="#" @click.prevent="changePage(page)" :class="{ 'active-link': page === currentPage }">{{ page }}</a>
                </li>
            </ul>
        </div>
        <div class="tfhb-prev-next-button">
            <a href="#" @click.prevent="nextPage" class="tfhb-flexbox tfhb-gap-8 tfhb-justify-normal" :disabled="currentPage === totalPages">Next<Icon name="ArrowRight" width="20" /></a>
        </div>
    </div>
</div>
</template>

<style scoped>

</style>