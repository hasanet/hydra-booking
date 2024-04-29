import { reactive } from 'vue'
import { toast } from "vue3-toastify"; 
import { useRouter, useRoute, RouterView } from 'vue-router' 
import axios from 'axios'  

const Meeting = reactive({
    meetings: [],
    meetingCategory: [],
    
    // Meeting List
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
            this.meetings = meetingsData.meetings;
        } catch (error) {
            console.error('Error fetching Meetings:', error);
        }
    },

    // Delete Meeting
    async deleteMeeting ($id, $post_id){ 
        let deleteMeeting = {
            id: $id,
            post_id: $post_id
        }
        try { 
            const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/delete', deleteMeeting, {
                   
            } );
            console.log(response);
            if (response.data.status) { 
                this.meetings = response.data.meetings;  
                toast.success(response.data.message); 
            }
        } catch (error) {
            console.log(error);
        }
    },

    // Popup Meeting Creation
    async CreatePopupMeeting (type, routes){   
        console.log(routes);
        let TypeData = {
            data: type
        }
        try { 
             // axisos sent dataHeader Nonce Data
             const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/create', TypeData, {
                 headers: {
                     'X-WP-Nonce': tfhb_core_apps.rest_nonce
                 } 
             } );
     
             if (response.data.status) {  
                 toast.success(response.data.message, {
                     position: 'bottom-right', // Set the desired position
                     "autoClose": 1500,
                 });  
                //  alert(router);
                routes.push({ name: 'MeetingsCreate', params: { id: response.data.id} });
             }else{
                 toast.error(response.data.message, {
                     position: 'bottom-right', // Set the desired position
                     "autoClose": 1500,
                 });
             }
         } catch (error) {
             console.log(error);
         }   
    },
    
    // Filter By Meeting Title
    async Tfhb_Meeting_Filter (filterData){
        try {
            const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/filter', {
                params: {
                    filterData
                },
            });
            
            if (response.data.status) { 
                this.meetings = response.data.meetings;  
            }
    
        } catch (error) {
            console.error('Error:', error);
        }
    },

    async Tfhb_Meeting_Select_Filter (filterData){
        try {
            const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/filter', {
                params: {
                    filterData
                },
            });
            
            if (response.data.status) { 
                this.meetings = response.data.meetings;  
            }
    
        } catch (error) {
            console.error('Error:', error);
        }
    },

    // Meeting Category

    async fetchMeetingCategory (){
        try { 
            const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/categories');
            if (response.data.status) { 
                this.meetingCategory.data = response.data.category;  
            }
        } catch (error) {
            console.log(error);
        } 
    } 
    
})

export { Meeting }