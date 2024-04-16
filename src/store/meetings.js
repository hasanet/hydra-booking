import { reactive } from 'vue'
const Meeting = reactive({
    meetings: [],
    async fetchMeetings() {
        const apiUrl = tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/lists';

        try {
            const response = await fetch(apiUrl, {
                method: 'GET'
            });
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const meetingsData = await response.json();
            this.meetings = meetingsData.meetings.map(meeting => ({
                [meeting.id]: meeting.title
            }));
        } catch (error) {
            console.error('Error fetching Meetings:', error);
        }
    }
})

export { Meeting }