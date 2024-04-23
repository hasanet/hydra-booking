import { reactive } from 'vue'
import { useRouter, useRoute, RouterView } from 'vue-router' 

const Availability = reactive({
    host_info: [],
    async fetchHost() {
        const apiUrl = tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/1';

        try {
            const response = await fetch(apiUrl, {
                method: 'GET'
            });
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const hostsData = await response.json();
            this.host_info = hostsData.host.user_id;
            
        } catch (error) {
            console.error('Error fetching Hosts:', error);
        }
    }
})

export { Availability }