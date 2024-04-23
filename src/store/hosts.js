import { reactive } from 'vue';

const Host = reactive({
    hosts: {},
    hostInfo: "",
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
            // Create an object where each key-value pair corresponds to a host's user_id and their combined name
            this.hosts = hostsData.hosts.reduce((acc, host) => {
                acc[host.user_id] = host.first_name + ' ' + host.last_name;
                return acc;
            }, {});
        } catch (error) {
            console.error('Error fetching Hosts:', error);
        }
    },

    async fetchHost(HostId){
        const apiUrl = tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/' + HostId;
        try {
            const response = await fetch(apiUrl, {
                method: 'GET'
            });
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const hostsData = await response.json();
            this.hostInfo = hostsData.host.user_id;
            
        } catch (error) {
            console.error('Error fetching Hosts:', error);
        }
    }
});

export { Host };