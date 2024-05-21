import { reactive } from 'vue'

const Booking = reactive({
    bookings: [],

    // booking List
    async fetchBookings() {
        const apiUrl = tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/booking/lists';
        try {
            const response = await fetch(apiUrl, {
                method: 'GET'
            });
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const bookingsData = await response.json();
            this.bookings = bookingsData.bookings;
        } catch (error) {
            console.error('Error fetching Bookings:', error);
        }
    }
    
})

export { Booking }