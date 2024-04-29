import { reactive } from 'vue';
import axios from 'axios';
const AuthData = reactive({
    Auth: [], 

    async fetchAuth() { 

        try {  
            const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/user/auth', {
                headers: {
                    'X-WP-Nonce': tfhb_core_apps.rest_nonce
                } 
            } );
    
            if (response.data.status) { 
                this.Auth = response.data.user;
            }
        } catch (error) {

            console.log(error);

        } 

        // try {
        //     const response = await fetch(apiUrl, {
        //         method: 'GET',
        //         credentials: 'include', // Include cookies for authentication
        //     });
        //     if (!response.ok) {
        //         throw new Error('Network response was not ok');
        //     }
        //     const data = await response.json(); 
        //     this.Auth =  data;
        // } catch (error) {
        //     console.error('Error fetching Hosts:', error);
        // }
    },
    Capabilities(cap) {  
        if( cap == ''){
            return true;
        }
        if( this.Auth.caps === undefined ){
            return false;
        }
        if(this.Auth.caps[cap] !== undefined && true === this.Auth.caps[cap] ){
            return true;
        }else{
            return false;
        }
    }
})

export { AuthData }