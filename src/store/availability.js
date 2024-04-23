import { reactive } from 'vue';

const Availability = reactive({
    availabilities: {},

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

            this.availabilities = availabilityData.availability.reduce((acc, available) => {
                acc[available.id] = available.title;
                return acc;
            }, {});
        } catch (error) {
            console.error('Error fetching Availability:', error);
        }
    }

});

export { Availability };