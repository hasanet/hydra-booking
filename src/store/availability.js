import { reactive } from 'vue';

const Availability = reactive({
    availabilities: {},
    GeneralSettings: {},

    async fetchAvailability() {
        const apiUrl = tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/availability';
        try {
            const response = await fetch(apiUrl, {
                method: 'GET'
            });
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const availabilityData = await response.json();
            console.log(availabilityData.general_settings);
 
            this.availabilities = availabilityData.availability.reduce((acc, available) => {
                acc[available.id] = available.title;
                return acc;
            }, {});
            this.GeneralSettings = availabilityData.general_settings;
        } catch (error) {
            console.error('Error fetching Availability:', error);
        }
    },
    getGeneralSettings() {  
        this.fetchAvailability(); 
        return this.GeneralSettings;
    },
    RearraingeWeekStart(week_start_from, time_slots) {  
   
        let week_start_from_index = time_slots.findIndex( x => x.day == week_start_from );
        let week_start_from_data = time_slots.splice(week_start_from_index, time_slots.length);
        time_slots = [...week_start_from_data, ...time_slots];
        return time_slots;
    }

});

export { Availability };