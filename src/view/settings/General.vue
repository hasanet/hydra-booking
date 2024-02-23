<script setup> 
// Use children routes for the tabs 
import { useRouter, RouterView,} from 'vue-router'  
import Icon from '@/components/icon/LucideIcon.vue'

// import Form Field 
import HbSelect from '@/components/form-fields/HbSelect.vue'

const router = useRouter(); 

</script>
<template>
    <div class="thb-event-dashboard">
        <div class="tfhb-dashboard-heading">
            <div class="tfhb-admin-title">
                <h1>{{ $tfhb_trans['General Settings'] }}</h1> 
                <p>{{ $tfhb_trans['Manage your time zone settings and bookings'] }}</p>
            </div>
            <div class="thb-admin-btn right"> 
                <a href="#" target="_blank" class="tfhb-btn"> {{ $tfhb_trans['View Documentation'] }}<Icon name="ArrowUpRight" size="15px" /></a>
            </div> 
        </div>
        <div class="tfhb-content-wrap">
            <!-- Date And Time -->
            <div class="tfhb-admin-title">
                <h2>{{ $tfhb_trans['Date and Time'] }}</h2> 
                <p>{{ $tfhb_trans['Date and Time Settings'] }}</p>
            </div>
            <div class="tfhb-admin-card-box tfhb-flexbox"> 
                <!-- Time Zone -->
                <HbSelect 
                    v-model="time_zone"  
                    required= "true"  
                    :label="$tfhb_trans['Time zone']" 
                    width="50"
                    selected = "1"
                    placeholder="Select Time Zone"  
                    :option = "[
                        {id: 1, name: 'Asia/Dhaka'},
                        {id: 2, name: 'Asia/Dhaka'},
                        {id: 3, name: 'Asia/Dhaka'},
                        {id: 4, name: 'Asia/Dhaka'},
                        {id: 5, name: 'Asia/Dhaka'},
                        {id: 6, name: 'Asia/Dhaka'},
                    ]"
                /> 
                <!-- Time Zone -->

                <!-- Time format -->
                <HbSelect 
                    v-model="time_format"  
                    required= "true" 
                    :label="$tfhb_trans['Time format']"  
                    width="50"
                    selected = "1"
                    placeholder="Select Time Format"  
                    :option = "[
                        {id: 1, name: 'Asia/Dhaka'},
                        {id: 2, name: 'Asia/Dhaka'},
                        {id: 3, name: 'Asia/Dhaka'},
                        {id: 4, name: 'Asia/Dhaka'},
                        {id: 5, name: 'Asia/Dhaka'},
                        {id: 6, name: 'Asia/Dhaka'},
                    ]"
                />
                <!-- Time format --> 
                <!-- Week start from -->
                <HbSelect 
                    v-model="week_start_from"  
                    required= "true"  
                    :label="$tfhb_trans['Week start from']"   
                    width="50"
                    selected = "1"
                    placeholder="Select Time Format"  
                    :option = "[
                        {id: 1, name: 'Sunday'}, 
                        {id: 2, name: 'Monday'},
                        {id: 3, name: 'Tuesday'},
                        {id: 4, name: 'Wednesday'},
                        {id: 5, name: 'Thursday'},
                        {id: 6, name: 'Friday'},
                        {id: 7, name: 'Saturday'},
                    ]"
                />
                <!-- Week start from -->
                
                <!-- Date Format -->
                <HbSelect 
                    v-model="date_format"  
                    required= "true" 
                    :label="$tfhb_trans['Date format']"   
                    width="50"
                    selected = "1"
                    placeholder="Select Date Format"  
                    :option = "[
                        {id: 1, name: 'g:i a'}, 
                        {id: 2, name: 'g:i a'},
                        {id: 3, name: 'g:i a'},
                    ]"
                />
                <!-- Date Format -->

                <!-- Select countr -->
                <HbSelect 
                    v-model="date_format"  
                    required= "true" 
                    :label="$tfhb_trans['Select country for phone code']"   
                    selected = "1"
                    placeholder="Select Country"  
                    :option = "[
                        {id: 1, name: 'Bangladesh'},  
                    ]"
                />
                <!-- Select countr --> 
            </div>  
            <!-- Date And Time -->

             <!--Bookings -->
             <div class="tfhb-admin-title">
                <h2>{{ $tfhb_trans['Bookings'] }}</h2> 
                <p>{{ $tfhb_trans['Manage your bookings and reservations'] }}</p>
            </div>
            <div class="tfhb-admin-card-box tfhb-flexbox"> 
                <!-- Bookings will be completed automatically after -->
                <HbSelect 
                    v-model="time_zone"  
                    required= "true" 
                    :label="$tfhb_trans['Bookings will be completed automatically after']"    
                    width="50"
                    selected = "1"
                    placeholder="Select Time"  
                    :option = "[
                        {id: 1, name: '15 minutes'}, 
                        {id: 1, name: '15 minutes'}, 
                        {id: 1, name: '15 minutes'}, 
                    ]"
                /> 
                <!-- Bookings will be completed automatically after -->

                <!-- Default status of bookings -->
                <HbSelect 
                    v-model="time_format"  
                    required= "true"  
                    :label="$tfhb_trans['Default status of bookings']" 
                    width="50"
                    selected = "1"
                    placeholder="Select status"  
                    :option = "[
                        {id: 1, name: 'Pending'},                                           
                        {id: 1, name: 'Published'},
                        {id: 1, name: 'Draft'},                                           
                    ]"
                />
                <!-- Default status of bookings --> 
                
                <!-- Minimum time required before Booking/Cancel/Reschedule -->
                <HbSelect 
                    v-model="before_cencel_reschedule"  
                    required= "true" 
                    :label="$tfhb_trans['Minimum time required before Booking/Cancel/Reschedule']"  
                    selected = "1"
                    placeholder="Select Time"  
                    :option = "[
                        {id: 1, name: '10 minutes'},  
                        {id: 1, name: '10 minutes'},   
                    ]"
                />
                <!-- Minimum time required before Booking/Cancel/Reschedule -->
                 
            </div>  
            <!--Bookings -->


        </div>
    </div>
 
</template>