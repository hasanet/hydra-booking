<script setup>
import HbSelect from '@/components/form-fields/HbSelect.vue'
import Icon from '@/components/icon/LucideIcon.vue'
</script>

<template>
    <div class="meeting-create-details tfhb-gap-24">

        <!-- Date And Time --> 
        <div class="tfhb-admin-title" >
                <h2>Meeting Limits</h2> 
                <p>How often attendee can be book</p>
            </div>
            <div class="tfhb-admin-card-box tfhb-flexbox">  

                <!-- Buffer time before meeting -->
                <HbSelect 
                    required= "true" 
                    :label="$tfhb_trans['Buffer time before meeting']"  
                    width="50"
                    :selected = "1"
                    placeholder="Buffer time after meeting"  
                    :option = "{'12_hours': '12 hours', '24_hours': '24 hours'}" 
                />
                <!-- Buffer time before meeting --> 
                <!-- Buffer time after meeting -->
                <HbSelect 
                    
                    required= "true"  
                    :label="$tfhb_trans['Buffer time after meeting']"   
                    width="50"
                    selected = "1"
                    placeholder="Buffer time after meeting"  
                    :option = "['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']"
                    
                />
                <!-- Buffer time after meeting -->

                <!-- Meeting interval -->
                <HbSelect 
                    
                    required= "true"  
                    :label="$tfhb_trans['Meeting interval']"   
                    width="50"
                    selected = "1"
                    :placeholder="$tfhb_trans['Use meeting length (default)']"
                    :option = "['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']"
                    
                />
                <!-- Meeting interval -->
            
            </div>  

        <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="UpdateGeneralSettings">{{ $tfhb_trans['Save & Continue'] }} </button>
        <!--Bookings -->
    </div>
</template>