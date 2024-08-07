import { reactive } from 'vue';
import axios from 'axios'; 
import { toast } from "vue3-toastify"; 

const importExport = reactive({
    skeleton: false,
    booking: {
        column: {}, 
        export_column: {},
        import_file: null,
        import_data: {},
    }, 
    // Other Information 
    async readBookingImportData() {  
        const file = this.booking.import_file;
        const reader = new FileReader();
        reader.onload = function(e) {
            const lines = e.target.result.split('\n');
            const headers = lines[0].split(',');
            const data = [];
            for (let i = 1; i < lines.length; i++) {
                const obj = {};
                const currentline = lines[i].split(',');
                for (let j = 0; j < headers.length; j++) {
                    obj[headers[j]] = currentline[j];
                }
                data.push(obj);
            }
            console.log(data);
            importExport.booking.import_data = data;
        };
    },
    async exportBookingAsCSV() { 
 
    },
})

export { importExport }