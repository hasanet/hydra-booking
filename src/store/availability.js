import { ref } from 'vue';
const hostInfo = ref([]);

const fetchHost = async (HostId) => {
    const apiUrl = tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/hosts/' + HostId;
    try {
        const response = await fetch(apiUrl, {
            method: 'GET'
        });
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const hostsData = await response.json();
        hostInfo.value = hostsData.host.user_id;
        
    } catch (error) {
        console.error('Error fetching Hosts:', error);
    }
}

export { hostInfo, fetchHost };