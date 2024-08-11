import { reactive } from 'vue';
import axios from 'axios'; 
import { toast } from "vue3-toastify"; 
import Papa from 'papaparse';
const importExport = reactive({
    skeleton: false,
    booking: {
        column: {},  
        import_column: {},  
        import_file: null,
        import_data: {},
        rearrange_column: {},
        import_status: false,
        import_progress: 0, // shuld be 0 to 100 dynamically
    }, 
    async GetImportExportData() { 
        try {  
            const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/import-export', {
                headers: {
                    'X-WP-Nonce': tfhb_core_apps.rest_nonce
                } 
            } );
    
            if (response.data.status) {  
                this.booking.column = response.data.booking_column;
                
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

    // export Booking Data
    async exportBooking() { 
        try {  
            const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/import-export/export-meeting-csv', {
                headers: {
                    'X-WP-Nonce': tfhb_core_apps.rest_nonce
                } 
            } );
    
            if (response.data.status) {  
 
                // export csv file data
                const url = window.URL.createObjectURL(new Blob([response.data.data]));
                const link = document.createElement('a');
                const file_name = response.data.file_name;
    
                link.href = url;
    
                link.setAttribute('download', file_name);
    
                // Append to the DOM
                document.body.appendChild(link);
                link.click();
    
                // Clean up
                link.remove();
                window.URL.revokeObjectURL(url);
                toast.success(response.data.message, {
                    position: 'bottom-right', // Set the desired position
                    "autoClose": 1500,
                });   
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
    // Other Information 
    async readBookingImportData(event) {   
        const file = event.target.files[0];
        Papa.parse(file, { 
            header: false,
            json: true,
            complete: function(results) { 
                importExport.booking.import_data = results.data;
                // get first row as column
                if (results.data.length > 0) {
                    importExport.booking.import_column = results.data[0];
                }
                 // make loop for column get value 
                for (let i = 0; i < importExport.booking.import_column.length; i++) { 
                    let element = importExport.booking.import_column[i];
                    importExport.booking.rearrange_column[element] = importExport.booking.import_column[i];
                } 
                 
            }
        });    
    },
    // Run The booking import
    async importBooking() { 
        try {  
            const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/import-export/import-booking', {
                data: this.booking.import_data,
                column: this.booking.rearrange_column
            }, {
                headers: {
                    'X-WP-Nonce': tfhb_core_apps.rest_nonce
                } 
            } );
    
            if (response.data.status) {  
                toast.success(response.data.message, {
                    position: 'bottom-right', // Set the desired position
                    "autoClose": 1500,
                });   
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
    async exportBookingAsCSV() { 
 
    },
})

export { importExport }