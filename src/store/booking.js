import { reactive } from 'vue'
import axios from 'axios'  

const Booking = reactive({
    bookings: [],

    // booking List
    async fetchBookings() {

        const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/booking/lists', {
            headers: {
                'X-WP-Nonce': tfhb_core_apps.rest_nonce
            } 
        } );

        if (response.data.status) { 
            this.bookings = response.data.bookings;
        }

        // const apiUrl = tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/booking/lists';
        // try {
        //     const response = await fetch(apiUrl, {
        //         method: 'GET'
        //     });
        //     if (!response.ok) {
        //         throw new Error('Network response was not ok');
        //     }
        //     const bookingsData = await response.json();
        //     this.bookings = bookingsData.bookings;
        // } catch (error) {
        //     console.error('Error fetching Bookings:', error);
        // }
    }
    
})

export { Booking }