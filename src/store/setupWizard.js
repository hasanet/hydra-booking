import { reactive } from 'vue';
import axios from 'axios'; 
import { toast } from "vue3-toastify"; 

const setupWizard = reactive({
    skeleton: true,
    currentStep: 'getting-start',
    data: {
        email: '',
        enable_recevie_updates: 1,
        business_type : '',
    }, 

    // Other Information 
    async fetchHostsSettings() { 

        try {  
            const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/hosts-settings', {
                headers: {
                    'X-WP-Nonce': tfhb_core_apps.rest_nonce
                } 
            } );
    
            if (response.data.status) { 
                if(response.data.hosts_settings){

                    this.settings.others_information.enable_others_information = response.data.hosts_settings.others_information.enable_others_information;
                    this.settings.others_information.fields = response.data.hosts_settings.others_information.fields ? response.data.hosts_settings.others_information.fields :   this.settings.others_information.fields;
                    this.settings.permission = response.data.hosts_settings.permission;
                   
                }
                this.skeleton = false;
                 
            }
        } catch (error) {

            console.log(error);

        }  
    }
})

export { setupWizard }