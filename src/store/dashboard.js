import { reactive } from 'vue';
import axios from 'axios'; 

const Dashboard = reactive({
    skeleton: true,
    skeleton_chartbox: false,
    skeleton_chart: false,
    chartData: {},
    chartOptions: {},
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
        upcoming_booking: {}, 
        recent_booking: {}, 
        statistics: {
            total_booked: [],
            total_completed: [],
            total_cancelled: [],
            labels: [],
        }

    }, 
    data_request: {
        days: 30,
        from_date: '',
        to_date: '',
        statistics_days: 7,
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
                this.data.upcoming_booking =  response.data.upcoming_booking != null ? response.data.upcoming_booking : {};
                this.data.recent_booking =  response.data.recent_booking != null ? response.data.recent_booking : {};
                this.data_request.days = response.data.days;
                this.skeleton = false;
                this.skeleton_chartbox = false;
                 
            }
        } catch (error) {

            console.log(error);

        }  
    },
    // Other Information 
    async fetcDashboardStatistics() { 

        try {  
            const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/dashboard/statistics', this.data_request,  {
                headers: {
                    'X-WP-Nonce': tfhb_core_apps.rest_nonce
                } 
            } );
    
            if (response.data.status) {     
                this.data.statistics.labels =  response.data.statistics.label != null ? response.data.statistics.label : this.data.statistics.labels;
                this.data.statistics.total_booked =  response.data.statistics.completed_bookings != null ? response.data.statistics.total_bookings : this.data.statistics.total_booked;
                this.data.statistics.total_completed =  response.data.statistics.completed_bookings != null ? response.data.statistics.completed_bookings : this.data.statistics.total_completed;
                this.data.statistics.total_cancelled =  response.data.statistics.cancelled_bookings != null ? response.data.statistics.cancelled_bookings : this.data.statistics.total_cancelled;
                  
                this.chartData = this.setChartData();
                this.chartOptions = this.setChartOptions();

                this.skeleton_chart = false;
                 
            }
        } catch (error) {

            console.log(error);

        }  
    },
    setChartData() {
        const documentStyle = getComputedStyle(document.documentElement);

        return {
            labels:  this.data.statistics.labels,
            datasets: [
                {
                    label: 'Booked',
                    data: this.data.statistics.total_booked,
                    fill: true,
                    tension: 0.4,
                    borderColor: '#F62881', 
                    pointBackgroundColor: '#F62881', 
                    backgroundColor: '#D9568F15'
                },
                {
                    label: 'Canceled',
                    data: this.data.statistics.total_cancelled,
                    fill: true,
                    // borderDash: [5, 5],
                    tension: 0.4,
                    borderColor: '#C40859',
                    pointBackgroundColor: '#C40859', 
                    backgroundColor: '#C4085915'
                },
                {
                    label: 'Completed',
                    data: this.data.statistics.total_completed,
                    fill: true,
                    borderColor: '#5E082D',
                    pointBackgroundColor: '#5E082D', 
                    tension: 0.4,
                    backgroundColor: '#5E082D15'
                }
            ]
        };
    },
    setChartOptions(){
        const documentStyle = getComputedStyle(document.documentElement);
        const textColor = documentStyle.getPropertyValue('--text-color');
        const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
        const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

        return {
            maintainAspectRatio: true,
            aspectRatio: 3,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: textColor,
                        usePointStyle: true, 
                        padding: 20,
                        height: 5,
                        width: 5
                    }
                    
                    
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: textColorSecondary,
                    
                        
                    },
                    grid: {
                        color: surfaceBorder
                    }
                },
                y: {
                    ticks: {
                    
                        color: textColorSecondary
                    },
                    grid: { 
                        color: '#F6EEF2'
                    }
                }
            }
        };
    }
    
    
})

export { Dashboard }