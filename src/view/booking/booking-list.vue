<script setup>
import { ref, reactive, onBeforeMount, onMounted, computed } from 'vue';
import axios from 'axios'  
import 'primevue/resources/themes/aura-light-green/theme.css'
import Icon from '@/components/icon/LucideIcon.vue'
import HbSelect from '@/components/form-fields/HbSelect.vue';
import HbRadio from '@/components/form-fields/HbRadio.vue';
import HbPopup from '@/components/widgets/HbPopup.vue'; 
import HbDropdown from '@/components/form-fields/HbDropdown.vue'
import HbDateTime from '@/components/form-fields/HbDateTime.vue';
import { toast } from "vue3-toastify"; 
import useDateFormat from '@/store/dateformat'
const { Tfhb_Date, Tfhb_Time } = useDateFormat();
import { Meeting } from '@/store/meetings'
import { Booking } from '@/store/booking'

import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'

const BookingDetailsPopup = ref(false);
const BookingEditPopup = ref(false);
const ExportAsCSV = ref(false);
const itemsPerPage = ref(10);
const currentPage = ref(1);
const bookingView = ref('calendar');
const exportData = reactive({
    date_range: 'days',
    start_date: '',
    end_dates: ''
});


// Export CSV
const ExportBookingAsCSV = async () => {
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/booking/export-csv', exportData, {
            headers: {
                'X-WP-Nonce': tfhb_core_apps.rest_nonce
            } 
        } );

        if (response.data.status) {  

            // Booking.bookings = response.data.booking; 
            // Booking.calendarbooking.events = response.data.booking_calendar;
            // BookingEditPopup.value = false;
            // export csv file data
            const url = window.URL.createObjectURL(new Blob([response.data.data]));
            const link = document.createElement('a');
            const file_name = response.data.file_name;

            link.href = url;

            link.setAttribute('download', file_name);

            // Append to the DOM
            document.body.appendChild(link);
            link.click();

            // Clean up
            link.remove();
            window.URL.revokeObjectURL(url);
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

const TfhbFormatMeetingLocation = (address) => {
    const meeting_address = JSON.parse(address)
    return meeting_address.map(loc => loc.location).join(', ');
}

onBeforeMount(() => { 
    Booking.fetchBookings();
    Meeting.fetchMeetings();
});

// Booking Status Changed
const meeting_status = reactive({});
const UpdateMeetingStatus = async (id, host, status) => {    
    meeting_status.id = id
    meeting_status.host = host
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
            Booking.calendarbooking.events = response.data.booking_calendar;
            BookingEditPopup.value = false;

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


const singleCalendarBookingData = reactive({
    title: '',
    booking_id: '',
    status: '',
    booking_date: '',
    booking_time: '',
    host_id: '',
});
const bookingCalendarPopup = (data) => {
    singleCalendarBookingData.title = data.title;
    singleCalendarBookingData.booking_id = data.extendedProps.booking_id;
    singleCalendarBookingData.status = data.extendedProps.status;
    singleCalendarBookingData.booking_date = data.extendedProps.booking_date;
    singleCalendarBookingData.booking_time = data.extendedProps.booking_time;
    singleCalendarBookingData.host_id = data.extendedProps.host_id;
    BookingEditPopup.value = true;
}

const deleteBooking = async ($id, $host) => { 
    let deleteBooking = {
        id: $id,
        host: $host
    }
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/booking/delete', deleteBooking);

        if (response.data.status) { 
            Booking.bookings = response.data.bookings; 
            Booking.calendarbooking.events = response.data.booking_calendar;  
            BookingEditPopup.value = false;
            toast.success(response.data.message); 
        }
    } catch (error) {
        console.log(error);
    }
}
const Booking_Status_Callback = (e) => {
    UpdateMeetingStatus(singleCalendarBookingData.booking_id, singleCalendarBookingData.host_id, e.value);
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
<!-- :class="{ 'tfhb-skeleton': Booking.skeleton }" -->
<div class="tfhb-dashboard-heading tfhb-flexbox">
    <div class="tfhb-filter-box tfhb-flexbox">
        <div class="tfhb-booking-view">
            <div class="tfhb-list-calendar">
                <ul class="tfhb-flexbox tfhb-gap-8">
                    <li :class="'list'==bookingView ? 'active' : ''" @click="bookingView='list'">
                        <Icon name="List" size="20" />
                    </li>
                    <li :class="'calendar'==bookingView ? 'active' : ''" @click="bookingView='calendar'">
                        <Icon name="CalendarDays" size="20" />
                    </li>
                </ul>
            </div>
        </div>
        <div class="tfhb-header-filters">
            <input type="text" placeholder="Host name or meeting title" /> 
            <span><Icon name="Search" size="20" /></span>
        </div>
    </div>
    <div class="thb-admin-btn right tfhb-flexbox tfhb-action-filter-button"> 
        <HbDropdown  
            v-model="status" 
            :selected = "1"
            placeholder="Status"   
            :option = "[
                {'name': 'Pending', 'value': 'pending'},  
                {'name': 'Confirmed', 'value': 'confirmed'},   
                {'name': 'Canceled', 'value': 'canceled'}
            ]"
            @tfhb-onchange="Booking_Status_Callback" 
        />  
        <button @click="ExportAsCSV = true" class="tfhb-btn boxed-secondary-btn flex-btn">
            <!-- <Icon name="PlusCircle " size="20" />   -->
            {{ $tfhb_trans['Export as CSV'] }}
        </button>
        <router-link :to="{ name: 'BookingCreate' }" class="tfhb-btn boxed-btn flex-btn"><Icon name="PlusCircle" size="20" /> {{ $tfhb_trans['Add New Booking'] }}</router-link>
    </div> 
</div>

<!-- Export CSV POPup -->
<HbPopup  :isOpen="ExportAsCSV" @modal-close="ExportAsCSV = false" max_width="500px" name="first-modal" gap="32px">
    <template #header>  
        <h3>Export Bookings as CSV</h3>
    </template>

    <template #content> 
        
        <HbRadio  
            required= "true"
            v-model="exportData.date_range"
            name="request_header"
            :label="$tfhb_trans['Date Range']"
            :groups="true" 
            :options="[
                {'label': 'Today', 'value': 'days'},  
                {'label': 'Last 7 Days', 'value': 'weeks'},
                {'label': 'Current Month', 'value': 'months'},
                {'label': 'Last Year', 'value': 'years'}, 
                {'label': 'All', 'value': 'all'}, 
                {'label': 'Custom', 'value': 'custom'} 
            ]" 
        />
      <div v-if="exportData.date_range == 'custom'" class="custom-date-range" >
        <label for="">{{ $tfhb_trans['Select Date Range'] }}</label>
        <div class="tfhb-filter-dates tfhb-flexbox">
            
            <div class="tfhb-filter-start-date">
                <HbDateTime 
                    v-model="exportData.start_date"
                    :label="$tfhb_trans['start Date']"
                    width="45"
                    enableTime='true'
                    placeholder="From"   
                /> 
                <Icon name="CalendarDays" size="20" /> 
            </div>
            <div class="tfhb-calender-move-icon">
                <Icon name="MoveRight" size="20px" /> 
            </div>
            <div class="tfhb-filter-end-date">
                <HbDateTime 
                    v-model="exportData.end_date"
                    width="45"
                    enableTime='true'
                    placeholder="To"   
                /> 
                <Icon name="CalendarDays" size="20" /> 
            </div>
        </div> 
      </div>

      <div class="tfhb-popup-actions tfhb-flexbox tfhb-full-width"> 
        <button @click="ExportBookingAsCSV" class="tfhb-btn boxed-btn flex-btn"><Icon name="Download" size="20" /> {{ $tfhb_trans['Export Meeting'] }}</button> 
      </div>
    </template> 
</HbPopup>
<!-- Export CSV POPup -->

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

<!-- Booking Calendar View -->
<div class="tfhb-booking-calendar tfhb-mt-72" v-if="bookingView=='calendar'"> 
    <FullCalendar class='demo-app-calendar' :options='Booking.calendarbooking'>
        <template v-slot:eventContent='arg'>
            <!-- {{ arg.event.booking_date }}
            <b>{{ arg.timeText }}</b>
            {{ arg.event.extendedProps.booking_time }} -->
            <b class="tfhb-calendar-popup" :class="arg.event.extendedProps.status" @click="bookingCalendarPopup(arg.event)">{{ arg.event.title }}  ( {{  arg.event.extendedProps.booking_time }} )</b>
        </template>
    </FullCalendar>
</div>
<!-- Booking Calendar View -->

<!-- Booking Calendar Edit -->

<HbPopup :isOpen="BookingEditPopup" @modal-close="BookingEditPopup = false" max_width="300px" name="first-modal" gap="24px" class="tfhb-booking-calendar-popup">
    <template #header> 
        <h3>{{ singleCalendarBookingData.title }}</h3>
    </template>

    <template #content> 

        <HbDropdown  
            v-model="singleCalendarBookingData.status"
            :label="$tfhb_trans['Status']" 
            :selected = "1"
            placeholder="Select Booking status"   
            :option = "[
                {'name': 'Pending', 'value': 'pending'},  
                {'name': 'Confirmed', 'value': 'confirmed'},   
                {'name': 'Canceled', 'value': 'canceled'}
            ]"
            @tfhb-onchange="Booking_Status_Callback" 
        />  

        <div class="tfhb-single-form-field" style="width: 100%;">
            <div class="tfhb-single-form-field-wrap tfhb-field-input">
                <label>Date</label>
                <div class="tfhb-time-date-view tfhb-flexbox">
                    <Icon name="CalendarDays" size="20" />
                    <input type="text" readonly :value="singleCalendarBookingData.booking_date">
                </div>
            </div>
        </div>

        <div class="tfhb-single-form-field" style="width: 100%;">
            <div class="tfhb-single-form-field-wrap tfhb-field-input">
                <label>Time</label>
                <div class="tfhb-time-date-view tfhb-flexbox">
                    <Icon name="Clock4" size="20" />
                    <input type="text" readonly :value="singleCalendarBookingData.booking_time">
                </div>
            </div>
        </div>

        <div class="tfhb-popup-actions tfhb-flexbox tfhb-full-width">
            <a href="#" class="tfhb-btn boxed-btn flex-btn"><Icon name="Video" size="20" /> {{ $tfhb_trans['Join Meet'] }}</a>

            <button class="tfhb-btn boxed-btn flex-btn tfhb-warning" @click="deleteBooking(singleCalendarBookingData.booking_id, singleCalendarBookingData.host_id)">{{ $tfhb_trans['Delete'] }}</button>
        </div>
    </template> 
</HbPopup>

<!-- Booking Calendar Edit -->

<div :class="{ 'tfhb-skeleton': Booking.skeleton }"  class="tfhb-booking-details tfhb-mt-32" v-if="bookingView=='list'">
    <table class="table" cellpadding="0" :cellspacing="0">
        <thead>
            <tr>
                <th> 
                </th>
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
                    {{ book.attendee_name }}
                    <span>{{ book.attendee_email }}</span>
                </td>
                <td>
                    {{ book.duration }} {{ $tfhb_trans['minutes'] }}
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
                                    <li @click="UpdateMeetingStatus(book.id, book.host_id, 'approved')">{{ $tfhb_trans['Approved'] }}</li>
                                    <li class="pending" @click="UpdateMeetingStatus(book.id, book.host_id, 'pending')">{{ $tfhb_trans['Pending'] }}</li>
                                    <li class="schedule" @click="UpdateMeetingStatus(book.id, book.host_id, 'schedule')">{{ $tfhb_trans['Re-schedule'] }}</li>
                                    <li class="canceled" @click="UpdateMeetingStatus(book.id, book.host_id, 'canceled')">{{ $tfhb_trans['Canceled'] }}</li>
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
                        <router-link :to="{ name: 'bookingUpdate', params: { id: book.id } }" class="tfhb-dropdown-single">
                            <Icon name="Settings" width="20" />
                        </router-link>
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