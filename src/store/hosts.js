import { reactive } from 'vue'
const Host = reactive({
    hosts: [],
    async fetchHosts() {
        const apiUrl = tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/lists';

        try {
            const response = await fetch(apiUrl, {
                method: 'GET'
            });
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const hostsData = await response.json();
            this.hosts = hostsData.hosts.map(host => ({
                id: host.id,
                first_name: host.first_name,
                last_name: host.last_name
            }));
        } catch (error) {
            console.error('Error fetching Hosts:', error);
        }
    }
})

export { Host }