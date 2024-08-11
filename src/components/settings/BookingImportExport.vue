<script setup>

import { ref, reactive, onBeforeMount, } from 'vue'; 
import { useRouter, RouterView,} from 'vue-router'  
import HbCheckbox from '@/components/form-fields/HbCheckbox.vue';
import HbText from '@/components/form-fields/HbText.vue';
import HbDropdown from '@/components/form-fields/HbDropdown.vue';
 
// const informationPopup = ref(false);
// const emit = defineEmits(["update-meeting", "limits-frequency-add"]); 
const props = defineProps({
    importExport : {
        type: Object,
        required: true
    }
}); 
 
 

// Extra Qestion Data
const questions_data =  reactive({});

// Import Data Function 
const readImportDdata = (event) => { 
   props.importExport.readBookingImportData(event);
   
}
 
</script>

<template>  
    <div class="tfhb-admin-card-box"  >   
        <div class="tfhb-dashboard-heading tfhb-flexbox tfhb-mb-16">
            <div class="tfhb-admin-title "> 
                <h3 >{{ $tfhb_trans['Import bookings from a CSV file'] }}</h3> 
                <p>{{ $tfhb_trans['This tool allows you to import or merge booking data to your store from a CSV file.'] }}</p>
            </div> 
            <button @click="ExportAsCSV = true" class="tfhb-btn boxed-btn flex-btn">
                <!-- <Icon name="PlusCircle " size="20" />   -->
                {{ $tfhb_trans['Export as CSV'] }}
            </button>
        </div>

        <div class="tfhb-content-wrap "> 
            {{ props.importExport.booking.import_data }}
           <div class="tfhb-upload-csv tfhb-flexbox tfhb-gap-16">
                <div class="tfhb-hydra-content-wrap">      
                    <HbText  
                        v-model="props.importExport.booking.import_file"
                        type="file"
                        required= "true"  
                        :label="$tfhb_trans['Customer name']"  
                        :width="100"
                        name="name"
                        selected = "1"
                         @change="readImportDdata" 
                        :placeholder="$tfhb_trans['Jhon Deo']" 
                    /> 
                    <br>
                   
                </div>  
           </div>
           <!-- Export Column -->
            <div v-if="props.importExport.booking.import_column.length > 0"  class="tfhb-import-column-data tfhb-admin-card-box">
                <div class="tfhb-admin-title "> 
                    <h3 >{{ $tfhb_trans['Map CSV fields to Booking'] }}</h3> 
                    <p>{{ $tfhb_trans['Select fields from your CSV file to map against booking fields, or to ignore during import.'] }}</p>
                </div> 
                <!-- Time format -->
                <div class="tfhb-import-export-column-wrap tfhb-flexbox tfhb-gap-8" v-for="(item, index) in props.importExport.booking.import_column">
                   
                    <HbDropdown 
                   
                    v-model="props.importExport.booking.rearrange_column[item]"   
                    :label="item"
                    width="100"
                    :selected = "item"
                    placeholder="Select Time Format"   
                    :option = "props.importExport.booking.column"  
                />
                 
                   
                </div>
                <br>
                <button @click="props.importExport.importBooking" class="tfhb-btn boxed-btn flex-btn"><Icon name="Download" size="20" /> {{ $tfhb_trans['Run The Import'] }}</button> 
                <!-- Time format --> 
            </div>
              
        </div>
    </div> 
</template>

<style scoped>
</style> 