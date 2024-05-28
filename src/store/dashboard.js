import { reactive } from 'vue';
import axios from 'axios'; 
import { toast } from "vue3-toastify"; 

const Dashboard = reactive({
    skeleton: true,
    data: { 
        total_bookings: {
            total: 0,
            percentage: 100,
            growth: 'increase',
        },
        total_earnings: {
            total: 0,
            percentage: 100,
            growth: 'increase',
        },
        total_completed_bookings: {
            total: 0,
            percentage: 100,
            growth: 'increase',
        },
        total_cancelled_bookings: {
            total: 0,
            percentage: 100,
            growth: 'increase',
        },
        upcoming_bookings: [], 
        statistics: []

    }, 
    data_request: {
        days: 1,
        from_date: '',
        to_date: '',
    },
    // Other Information 
    async fetcDashboard() { 

        try {  
            const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/dashboard', this.data_request,  {
                headers: {
                    'X-WP-Nonce': tfhb_core_apps.rest_nonce
                } 
            } );
    
            if (response.data.status) {  
                this.data.total_bookings =  response.data.total_bookings != null ? response.data.total_bookings : 0;
                this.data.total_completed_bookings =  response.data.total_completed_bookings != null ? response.data.total_completed_bookings : 0;
                this.data.total_cancelled_bookings =  response.data.total_cancelled_bookings != null ? response.data.total_cancelled_bookings : 0;
                this.skeleton = false;
                 
            }
        } catch (error) {

            console.log(error);

        }  
    },
    
    
})

export { Dashboard }