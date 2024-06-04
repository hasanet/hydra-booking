<script setup>

import Chart from 'primevue/chart';
import { ref, onMounted  } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import Header from '@/components/Header.vue';
import Icon from '@/components/icon/LucideIcon.vue'
import HbDateTime from '@/components/form-fields/HbDateTime.vue';


// Store 
import { Dashboard } from '@/store/dashboard';
 
const datachart_box_dropdown = ref(false);
const datachart_dropdown = ref(false);

onMounted(() => {
    Dashboard.fetcDashboard(); 
    Dashboard.fetcDashboardStatistics();
}); 

const updateDashboardDay = (day) => { 
    Dashboard.skeleton_chartbox = true;

    // selected attribute data-name
    const dropdown = document.getElementById('tfhb-datachart-filter');
    dropdown.querySelector('span').innerText = event.target.getAttribute('data-name');
    
    Dashboard.data_request.days = day;
    Dashboard.fetcDashboard();
    datachart_box_dropdown.value = false;
}

const updateDashboardDateRange = () => { 
    Dashboard.skeleton_chartbox = true;
    Dashboard.fetcDashboard();
    datachart_box_dropdown.value = false;
}

const  ChangeStatisticData = (day) => { 
    Dashboard.data_request.statistics_days = day;  
    Dashboard.skeleton_chart = true;
    datachart_dropdown.value = false;
    // selected attribute data-name
    const dropdown = document.getElementById('tfhb-chart-filter');
    dropdown.querySelector('span').innerText = event.target.getAttribute('data-name');
    
    Dashboard.fetcDashboardStatistics();
    
 
}


</script>
<template>

<!-- {{ tfhbClass }} -->
<div class="tfhb-admin-dashboard tfhb-admin-meetings ">
    <Header title="Dashboard" /> 
    <div  :class="{ 'tfhb-skeleton': Dashboard.skeleton }"  class="tfhb-dashboard-heading tfhb-flexbox">
        <div class="thb-admin-title">
            <h1>Data</h1>
            <p>One-liner description</p> 
        </div>  
        <div class="tfhb-dropdown tfhb-mega-dropdown tfhb-no-hover">
            <span class="tfhb-flexbox tfhb-gap-8 tfhb-mega-dropdown-heading " @click="datachart_box_dropdown = !datachart_box_dropdown"  id="tfhb-datachart-filter"> <span>Today</span>  <Icon name="ChevronDown" size="15" /> </span>
            <div 
                :class="{ 'active': datachart_box_dropdown }"
                class="tfhb-dropdown-wrap"
            > 
                <!-- route link -->
                <span @click="updateDashboardDay(1)" data-name="Today" class="tfhb-dropdown-single">Today</span>
                <span  @click="updateDashboardDay(7)" data-name="Last 7 week" class="tfhb-dropdown-single">Last 7 week</span> 
                <span  @click="updateDashboardDay(30)" data-name="Last 30 Days" class="tfhb-dropdown-single">Last 30 Days</span> 
                <span  @click="updateDashboardDay(60)" data-name="Last 3 months" class="tfhb-dropdown-single">Last 3 months</span> 
                <div class="tfhb-dropdown-single">
                    <div class="tfhb-filter-dates tfhb-flexbox tfhb-gap-8">
                        <div class="tfhb-filter-start-date">
                            <span>From</span>
                            <HbDateTime 
                                v-model="Dashboard.data_request.from_date"
                                selected = "1" 
                                enableTime='true'
                                placeholder="From"  
                                icon="CalendarDays"   
                            />  
                        </div>
                        <div class="tfhb-calender-move-icon">
                            <Icon name="MoveRight" size="15" /> 
                        </div>
                        <div class="tfhb-filter-end-date">
                            <span>To</span>
                            <HbDateTime 
                                v-model="Dashboard.data_request.to_date"
                                selected = "1" 
                                enableTime='true'
                                placeholder="To"   
                                icon="CalendarDays"   
                            />  
                        </div>
                    </div>

                    <button class="tfhb-btn tfhb-btn-primary boxed-btn tfhb-mt-16 tfhb-full-width" @click="updateDashboardDateRange">Apply</button>
                </div> 
            </div>
        </div>
    </div>
    <div  :class="{ 'tfhb-skeleton': Dashboard.skeleton }"  class="tfhb-dashboard-wrap">
      
        <div :class="{ 'tfhb-skeleton': Dashboard.skeleton_chartbox }"  class="tfhb-dashboard-chartbox tfhb-flexbox tfhb-gap-24">

            <!-- Single Chartbox -->
            <div class="tfhb-single-chartbox">
                <div class="tfhb-single-chartbox-wrap gradient-1">
                    <span class="tfhb-adminchartbox-shape">
                        <img  :src="$tfhb_url+'/assets/images/shape-1.svg'" alt="">
                    </span>
                    <div class="tfhb-single-cartbox-inner tfhb-flexbox tfhb-gap-8">
                        <div class="tfhb-single-chartbox-content">
                            <span class="cartbox-title">Total Booking</span> 
                            <span class="cartbox-value ">{{Dashboard.data.total_bookings.total}}</span>
                            
                        </div>
                        <div class="tfhb-chartbox-icon">
                            <svg width="68" height="58" viewBox="0 0 68 58" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g opacity="0.7">
                                <path d="M0.34219 22.3422H67.6578V50C67.6578 54.2293 64.2293 57.6578 60 57.6578H8C3.77071 57.6578 0.34219 54.2293 0.34219 50V22.3422Z" fill="url(#paint0_linear_557_6732)" stroke="url(#paint1_linear_557_6732)" stroke-width="0.68438"/>
                                <rect x="6.5" y="28.5" width="15" height="9" rx="2.5" fill="url(#paint2_linear_557_6732)" stroke="url(#paint3_linear_557_6732)"/>
                                <rect x="6.5" y="42.5" width="15" height="9" rx="2.5" fill="url(#paint4_linear_557_6732)" stroke="url(#paint5_linear_557_6732)"/>
                                <rect x="46.5" y="28.5" width="15" height="9" rx="2.5" fill="url(#paint6_linear_557_6732)" stroke="url(#paint7_linear_557_6732)"/>
                                <rect x="46.5" y="42.5" width="15" height="9" rx="2.5" fill="url(#paint8_linear_557_6732)" stroke="url(#paint9_linear_557_6732)"/>
                                <rect x="26.5" y="28.5" width="15" height="9" rx="2.5" fill="url(#paint10_linear_557_6732)" stroke="url(#paint11_linear_557_6732)"/>
                                <rect x="26.5" y="42.5" width="15" height="9" rx="2.5" fill="url(#paint12_linear_557_6732)" stroke="url(#paint13_linear_557_6732)"/>
                                <mask id="path-8-inside-1_557_6732" fill="white">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 5.00195C3.58172 5.00195 0 8.58367 0 13.002V22.002H68V13.002C68 8.58368 64.4183 5.00195 60 5.00195H8ZM17.0026 13.2309C17.0026 15.422 15.2264 17.1982 13.0353 17.1982C10.8442 17.1982 9.06803 15.422 9.06803 13.2309C9.06803 11.0399 10.8442 9.26367 13.0353 9.26367C15.2264 9.26367 17.0026 11.0399 17.0026 13.2309ZM58.9422 13.2309C58.9422 15.422 57.166 17.1982 54.975 17.1982C52.7839 17.1982 51.0077 15.422 51.0077 13.2309C51.0077 11.0399 52.7839 9.26367 54.975 9.26367C57.166 9.26367 58.9422 11.0399 58.9422 13.2309Z"/>
                                </mask>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 5.00195C3.58172 5.00195 0 8.58367 0 13.002V22.002H68V13.002C68 8.58368 64.4183 5.00195 60 5.00195H8ZM17.0026 13.2309C17.0026 15.422 15.2264 17.1982 13.0353 17.1982C10.8442 17.1982 9.06803 15.422 9.06803 13.2309C9.06803 11.0399 10.8442 9.26367 13.0353 9.26367C15.2264 9.26367 17.0026 11.0399 17.0026 13.2309ZM58.9422 13.2309C58.9422 15.422 57.166 17.1982 54.975 17.1982C52.7839 17.1982 51.0077 15.422 51.0077 13.2309C51.0077 11.0399 52.7839 9.26367 54.975 9.26367C57.166 9.26367 58.9422 11.0399 58.9422 13.2309Z" fill="url(#paint14_linear_557_6732)"/>
                                <path d="M0 22.002H-0.68438V22.6863H0V22.002ZM68 22.002V22.6863H68.6844V22.002H68ZM0.68438 13.002C0.68438 8.96165 3.95969 5.68633 8 5.68633V4.31757C3.20375 4.31757 -0.68438 8.2057 -0.68438 13.002H0.68438ZM0.68438 22.002V13.002H-0.68438V22.002H0.68438ZM68 21.3176H0V22.6863H68V21.3176ZM67.3156 13.002V22.002H68.6844V13.002H67.3156ZM60 5.68633C64.0403 5.68633 67.3156 8.96165 67.3156 13.002H68.6844C68.6844 8.2057 64.7963 4.31757 60 4.31757V5.68633ZM8 5.68633H60V4.31757H8V5.68633ZM13.0353 17.8826C15.6043 17.8826 17.6869 15.8 17.6869 13.2309H16.3182C16.3182 15.044 14.8484 16.5138 13.0353 16.5138V17.8826ZM8.38365 13.2309C8.38365 15.8 10.4663 17.8826 13.0353 17.8826V16.5138C11.2222 16.5138 9.75241 15.044 9.75241 13.2309H8.38365ZM13.0353 8.57929C10.4663 8.57929 8.38365 10.6619 8.38365 13.2309H9.75241C9.75241 11.4179 11.2222 9.94805 13.0353 9.94805V8.57929ZM17.6869 13.2309C17.6869 10.6619 15.6043 8.57929 13.0353 8.57929V9.94805C14.8484 9.94805 16.3182 11.4179 16.3182 13.2309H17.6869ZM54.975 17.8826C57.544 17.8826 59.6266 15.8 59.6266 13.2309H58.2579C58.2579 15.044 56.7881 16.5138 54.975 16.5138V17.8826ZM50.3233 13.2309C50.3233 15.8 52.4059 17.8826 54.975 17.8826V16.5138C53.1619 16.5138 51.6921 15.044 51.6921 13.2309H50.3233ZM54.975 8.57929C52.4059 8.57929 50.3233 10.6619 50.3233 13.2309H51.6921C51.6921 11.4179 53.1619 9.94805 54.975 9.94805V8.57929ZM59.6266 13.2309C59.6266 10.6619 57.544 8.57929 54.975 8.57929V9.94805C56.7881 9.94805 58.2579 11.4179 58.2579 13.2309H59.6266Z" fill="url(#paint15_linear_557_6732)" mask="url(#path-8-inside-1_557_6732)"/>
                                <path d="M11.2997 13.0013C11.2997 13.9404 12.061 14.7016 13 14.7016C13.939 14.7016 14.7003 13.9404 14.7003 13.0013H11.2997ZM14.7003 13.0013C14.7003 9.38077 14.2507 6.68584 13.489 4.74513C12.7295 2.81019 11.5848 1.45738 10.1117 0.889343C8.60991 0.310209 7.1186 0.686238 6.05903 1.49887C5.02409 2.29261 4.29974 3.57335 4.29974 5.00175H7.70026C7.70026 4.72974 7.85091 4.41006 8.12847 4.19718C8.3814 4.0032 8.64009 3.96643 8.88826 4.06213C9.16519 4.16892 9.77051 4.57859 10.3235 5.98757C10.8743 7.39079 11.2997 9.62125 11.2997 13.0013H14.7003Z" fill="url(#paint16_linear_557_6732)"/>
                                <path d="M53.2998 13.0013C53.2998 13.9404 54.061 14.7016 55 14.7016C55.939 14.7016 56.7003 13.9404 56.7003 13.0013H53.2998ZM56.7003 13.0013C56.7003 9.38077 56.2507 6.68584 55.489 4.74513C54.7295 2.81019 53.5848 1.45738 52.1118 0.889343C50.6099 0.310209 49.1186 0.686238 48.059 1.49887C47.0241 2.29261 46.2998 3.57335 46.2998 5.00175H49.7003C49.7003 4.72974 49.8509 4.41006 50.1285 4.19718C50.3814 4.0032 50.6401 3.96643 50.8883 4.06213C51.1652 4.16892 51.7705 4.57859 52.3235 5.98757C52.8743 7.39079 53.2998 9.62125 53.2998 13.0013H56.7003Z" fill="url(#paint17_linear_557_6732)"/>
                                </g>
                                <defs>
                                <linearGradient id="paint0_linear_557_6732" x1="33.942" y1="57.5689" x2="33.942" y2="21.5635" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint1_linear_557_6732" x1="33.942" y1="57.5689" x2="33.942" y2="21.5635" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint2_linear_557_6732" x1="13.9864" y1="37.8802" x2="13.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.5"/>
                                </linearGradient>
                                <linearGradient id="paint3_linear_557_6732" x1="13.9864" y1="37.8802" x2="13.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.8"/>
                                </linearGradient>
                                <linearGradient id="paint4_linear_557_6732" x1="13.9864" y1="51.8802" x2="13.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.4"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint5_linear_557_6732" x1="13.9864" y1="51.8802" x2="13.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint6_linear_557_6732" x1="53.9864" y1="37.8802" x2="53.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.4"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint7_linear_557_6732" x1="53.9864" y1="37.8802" x2="53.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint8_linear_557_6732" x1="53.9864" y1="51.8802" x2="53.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.5"/>
                                </linearGradient>
                                <linearGradient id="paint9_linear_557_6732" x1="53.9864" y1="51.8802" x2="53.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.8"/>
                                </linearGradient>
                                <linearGradient id="paint10_linear_557_6732" x1="33.9864" y1="37.8802" x2="33.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.4"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint11_linear_557_6732" x1="33.9864" y1="37.8802" x2="33.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint12_linear_557_6732" x1="33.9864" y1="51.8802" x2="33.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.5"/>
                                </linearGradient>
                                <linearGradient id="paint13_linear_557_6732" x1="33.9864" y1="51.8802" x2="33.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.8"/>
                                </linearGradient>
                                <linearGradient id="paint14_linear_557_6732" x1="33.942" y1="21.7984" x2="33.942" y2="4.79581" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.4"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint15_linear_557_6732" x1="33.942" y1="21.7984" x2="33.942" y2="4.79581" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint16_linear_557_6732" x1="9.10068" y1="13.2769" x2="9.10068" y2="2.04834" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.2"/>
                                </linearGradient>
                                <linearGradient id="paint17_linear_557_6732" x1="51.1007" y1="13.2769" x2="51.1007" y2="2.04834" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.2"/>
                                </linearGradient>
                                </defs>
                                </svg>

                        </div>
                    </div>
                    
                    <div class="cartbox-meta tfhb-flexbox tfhb-gap-8">
                        <span class="cartbox-badge tfhb-flexbox tfhb-gap-8"
                            :class = "{
                                'badge-down': Dashboard.data.total_bookings.growth == 'decrease',
                                'badge-up': Dashboard.data.total_bookings.growth == 'increase',
                            }"
                        >
                            <Icon v-if="Dashboard.data.total_bookings.growth == 'increase'" name="ArrowUp" :size="15"/>
                            <Icon v-else name="ArrowDown" :size="15"/>
                            <span> {{Dashboard.data.total_bookings.percentage}}%</span>
                        </span>
                        <span> VS </span>
                        <span class="cartbox-date">Last {{Dashboard.data_request.days}} days</span>
                    </div>
                </div>
            </div>
            <!-- Single Chartbox --> 
            <!-- Single Chartbox -->
            <div class="tfhb-single-chartbox">
                <div class="tfhb-single-chartbox-wrap gradient-2">
                    <span class="tfhb-adminchartbox-shape">
                        <img  :src="$tfhb_url+'/assets/images/shape-2.svg'" alt="">
                    </span>
                    <div class="tfhb-single-cartbox-inner tfhb-flexbox tfhb-gap-8">
                        <div class="tfhb-single-chartbox-content">
                            <span class="cartbox-title">Total Earnings</span> 
                            <span class="cartbox-value ">0</span>
                            
                        </div>
                        <div class="tfhb-chartbox-icon">
                            <svg width="68" height="58" viewBox="0 0 68 58" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g opacity="0.7">
                                <path d="M0.34219 22.3422H67.6578V50C67.6578 54.2293 64.2293 57.6578 60 57.6578H8C3.77071 57.6578 0.34219 54.2293 0.34219 50V22.3422Z" fill="url(#paint0_linear_557_6732)" stroke="url(#paint1_linear_557_6732)" stroke-width="0.68438"/>
                                <rect x="6.5" y="28.5" width="15" height="9" rx="2.5" fill="url(#paint2_linear_557_6732)" stroke="url(#paint3_linear_557_6732)"/>
                                <rect x="6.5" y="42.5" width="15" height="9" rx="2.5" fill="url(#paint4_linear_557_6732)" stroke="url(#paint5_linear_557_6732)"/>
                                <rect x="46.5" y="28.5" width="15" height="9" rx="2.5" fill="url(#paint6_linear_557_6732)" stroke="url(#paint7_linear_557_6732)"/>
                                <rect x="46.5" y="42.5" width="15" height="9" rx="2.5" fill="url(#paint8_linear_557_6732)" stroke="url(#paint9_linear_557_6732)"/>
                                <rect x="26.5" y="28.5" width="15" height="9" rx="2.5" fill="url(#paint10_linear_557_6732)" stroke="url(#paint11_linear_557_6732)"/>
                                <rect x="26.5" y="42.5" width="15" height="9" rx="2.5" fill="url(#paint12_linear_557_6732)" stroke="url(#paint13_linear_557_6732)"/>
                                <mask id="path-8-inside-1_557_6732" fill="white">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 5.00195C3.58172 5.00195 0 8.58367 0 13.002V22.002H68V13.002C68 8.58368 64.4183 5.00195 60 5.00195H8ZM17.0026 13.2309C17.0026 15.422 15.2264 17.1982 13.0353 17.1982C10.8442 17.1982 9.06803 15.422 9.06803 13.2309C9.06803 11.0399 10.8442 9.26367 13.0353 9.26367C15.2264 9.26367 17.0026 11.0399 17.0026 13.2309ZM58.9422 13.2309C58.9422 15.422 57.166 17.1982 54.975 17.1982C52.7839 17.1982 51.0077 15.422 51.0077 13.2309C51.0077 11.0399 52.7839 9.26367 54.975 9.26367C57.166 9.26367 58.9422 11.0399 58.9422 13.2309Z"/>
                                </mask>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 5.00195C3.58172 5.00195 0 8.58367 0 13.002V22.002H68V13.002C68 8.58368 64.4183 5.00195 60 5.00195H8ZM17.0026 13.2309C17.0026 15.422 15.2264 17.1982 13.0353 17.1982C10.8442 17.1982 9.06803 15.422 9.06803 13.2309C9.06803 11.0399 10.8442 9.26367 13.0353 9.26367C15.2264 9.26367 17.0026 11.0399 17.0026 13.2309ZM58.9422 13.2309C58.9422 15.422 57.166 17.1982 54.975 17.1982C52.7839 17.1982 51.0077 15.422 51.0077 13.2309C51.0077 11.0399 52.7839 9.26367 54.975 9.26367C57.166 9.26367 58.9422 11.0399 58.9422 13.2309Z" fill="url(#paint14_linear_557_6732)"/>
                                <path d="M0 22.002H-0.68438V22.6863H0V22.002ZM68 22.002V22.6863H68.6844V22.002H68ZM0.68438 13.002C0.68438 8.96165 3.95969 5.68633 8 5.68633V4.31757C3.20375 4.31757 -0.68438 8.2057 -0.68438 13.002H0.68438ZM0.68438 22.002V13.002H-0.68438V22.002H0.68438ZM68 21.3176H0V22.6863H68V21.3176ZM67.3156 13.002V22.002H68.6844V13.002H67.3156ZM60 5.68633C64.0403 5.68633 67.3156 8.96165 67.3156 13.002H68.6844C68.6844 8.2057 64.7963 4.31757 60 4.31757V5.68633ZM8 5.68633H60V4.31757H8V5.68633ZM13.0353 17.8826C15.6043 17.8826 17.6869 15.8 17.6869 13.2309H16.3182C16.3182 15.044 14.8484 16.5138 13.0353 16.5138V17.8826ZM8.38365 13.2309C8.38365 15.8 10.4663 17.8826 13.0353 17.8826V16.5138C11.2222 16.5138 9.75241 15.044 9.75241 13.2309H8.38365ZM13.0353 8.57929C10.4663 8.57929 8.38365 10.6619 8.38365 13.2309H9.75241C9.75241 11.4179 11.2222 9.94805 13.0353 9.94805V8.57929ZM17.6869 13.2309C17.6869 10.6619 15.6043 8.57929 13.0353 8.57929V9.94805C14.8484 9.94805 16.3182 11.4179 16.3182 13.2309H17.6869ZM54.975 17.8826C57.544 17.8826 59.6266 15.8 59.6266 13.2309H58.2579C58.2579 15.044 56.7881 16.5138 54.975 16.5138V17.8826ZM50.3233 13.2309C50.3233 15.8 52.4059 17.8826 54.975 17.8826V16.5138C53.1619 16.5138 51.6921 15.044 51.6921 13.2309H50.3233ZM54.975 8.57929C52.4059 8.57929 50.3233 10.6619 50.3233 13.2309H51.6921C51.6921 11.4179 53.1619 9.94805 54.975 9.94805V8.57929ZM59.6266 13.2309C59.6266 10.6619 57.544 8.57929 54.975 8.57929V9.94805C56.7881 9.94805 58.2579 11.4179 58.2579 13.2309H59.6266Z" fill="url(#paint15_linear_557_6732)" mask="url(#path-8-inside-1_557_6732)"/>
                                <path d="M11.2997 13.0013C11.2997 13.9404 12.061 14.7016 13 14.7016C13.939 14.7016 14.7003 13.9404 14.7003 13.0013H11.2997ZM14.7003 13.0013C14.7003 9.38077 14.2507 6.68584 13.489 4.74513C12.7295 2.81019 11.5848 1.45738 10.1117 0.889343C8.60991 0.310209 7.1186 0.686238 6.05903 1.49887C5.02409 2.29261 4.29974 3.57335 4.29974 5.00175H7.70026C7.70026 4.72974 7.85091 4.41006 8.12847 4.19718C8.3814 4.0032 8.64009 3.96643 8.88826 4.06213C9.16519 4.16892 9.77051 4.57859 10.3235 5.98757C10.8743 7.39079 11.2997 9.62125 11.2997 13.0013H14.7003Z" fill="url(#paint16_linear_557_6732)"/>
                                <path d="M53.2998 13.0013C53.2998 13.9404 54.061 14.7016 55 14.7016C55.939 14.7016 56.7003 13.9404 56.7003 13.0013H53.2998ZM56.7003 13.0013C56.7003 9.38077 56.2507 6.68584 55.489 4.74513C54.7295 2.81019 53.5848 1.45738 52.1118 0.889343C50.6099 0.310209 49.1186 0.686238 48.059 1.49887C47.0241 2.29261 46.2998 3.57335 46.2998 5.00175H49.7003C49.7003 4.72974 49.8509 4.41006 50.1285 4.19718C50.3814 4.0032 50.6401 3.96643 50.8883 4.06213C51.1652 4.16892 51.7705 4.57859 52.3235 5.98757C52.8743 7.39079 53.2998 9.62125 53.2998 13.0013H56.7003Z" fill="url(#paint17_linear_557_6732)"/>
                                </g>
                                <defs>
                                <linearGradient id="paint0_linear_557_6732" x1="33.942" y1="57.5689" x2="33.942" y2="21.5635" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint1_linear_557_6732" x1="33.942" y1="57.5689" x2="33.942" y2="21.5635" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint2_linear_557_6732" x1="13.9864" y1="37.8802" x2="13.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.5"/>
                                </linearGradient>
                                <linearGradient id="paint3_linear_557_6732" x1="13.9864" y1="37.8802" x2="13.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.8"/>
                                </linearGradient>
                                <linearGradient id="paint4_linear_557_6732" x1="13.9864" y1="51.8802" x2="13.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.4"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint5_linear_557_6732" x1="13.9864" y1="51.8802" x2="13.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint6_linear_557_6732" x1="53.9864" y1="37.8802" x2="53.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.4"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint7_linear_557_6732" x1="53.9864" y1="37.8802" x2="53.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint8_linear_557_6732" x1="53.9864" y1="51.8802" x2="53.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.5"/>
                                </linearGradient>
                                <linearGradient id="paint9_linear_557_6732" x1="53.9864" y1="51.8802" x2="53.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.8"/>
                                </linearGradient>
                                <linearGradient id="paint10_linear_557_6732" x1="33.9864" y1="37.8802" x2="33.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.4"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint11_linear_557_6732" x1="33.9864" y1="37.8802" x2="33.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint12_linear_557_6732" x1="33.9864" y1="51.8802" x2="33.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.5"/>
                                </linearGradient>
                                <linearGradient id="paint13_linear_557_6732" x1="33.9864" y1="51.8802" x2="33.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.8"/>
                                </linearGradient>
                                <linearGradient id="paint14_linear_557_6732" x1="33.942" y1="21.7984" x2="33.942" y2="4.79581" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.4"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint15_linear_557_6732" x1="33.942" y1="21.7984" x2="33.942" y2="4.79581" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint16_linear_557_6732" x1="9.10068" y1="13.2769" x2="9.10068" y2="2.04834" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.2"/>
                                </linearGradient>
                                <linearGradient id="paint17_linear_557_6732" x1="51.1007" y1="13.2769" x2="51.1007" y2="2.04834" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.2"/>
                                </linearGradient>
                                </defs>
                                </svg>

                        </div>
                    </div>
                    
                    <div class="cartbox-meta tfhb-flexbox tfhb-gap-8">
                        <span class="cartbox-badge badge-down tfhb-flexbox tfhb-gap-8">
                            <Icon name="ArrowUp" :size="15"/>
                            <span> 80%</span>
                        </span>
                        <span> VS </span>
                        <span class="cartbox-date">Last 30 days</span>
                    </div>
                </div>
            </div>
            <!-- Single Chartbox --> 

            <!-- Single Chartbox -->
            <div class="tfhb-single-chartbox">
                <div class="tfhb-single-chartbox-wrap gradient-3">
                    <span class="tfhb-adminchartbox-shape">
                        <img  :src="$tfhb_url+'/assets/images/shape-3.svg'" alt="">
                    </span>
                    <div class="tfhb-single-cartbox-inner tfhb-flexbox tfhb-gap-8">
                        <div class="tfhb-single-chartbox-content">
                            <span class="cartbox-title">Completed Bookings</span> 
                            <span class="cartbox-value ">{{Dashboard.data.total_completed_bookings.total}}</span>
                            
                        </div>
                        <div class="tfhb-chartbox-icon">
                            <svg width="68" height="58" viewBox="0 0 68 58" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g opacity="0.7">
                                <path d="M0.34219 22.3422H67.6578V50C67.6578 54.2293 64.2293 57.6578 60 57.6578H8C3.77071 57.6578 0.34219 54.2293 0.34219 50V22.3422Z" fill="url(#paint0_linear_557_6732)" stroke="url(#paint1_linear_557_6732)" stroke-width="0.68438"/>
                                <rect x="6.5" y="28.5" width="15" height="9" rx="2.5" fill="url(#paint2_linear_557_6732)" stroke="url(#paint3_linear_557_6732)"/>
                                <rect x="6.5" y="42.5" width="15" height="9" rx="2.5" fill="url(#paint4_linear_557_6732)" stroke="url(#paint5_linear_557_6732)"/>
                                <rect x="46.5" y="28.5" width="15" height="9" rx="2.5" fill="url(#paint6_linear_557_6732)" stroke="url(#paint7_linear_557_6732)"/>
                                <rect x="46.5" y="42.5" width="15" height="9" rx="2.5" fill="url(#paint8_linear_557_6732)" stroke="url(#paint9_linear_557_6732)"/>
                                <rect x="26.5" y="28.5" width="15" height="9" rx="2.5" fill="url(#paint10_linear_557_6732)" stroke="url(#paint11_linear_557_6732)"/>
                                <rect x="26.5" y="42.5" width="15" height="9" rx="2.5" fill="url(#paint12_linear_557_6732)" stroke="url(#paint13_linear_557_6732)"/>
                                <mask id="path-8-inside-1_557_6732" fill="white">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 5.00195C3.58172 5.00195 0 8.58367 0 13.002V22.002H68V13.002C68 8.58368 64.4183 5.00195 60 5.00195H8ZM17.0026 13.2309C17.0026 15.422 15.2264 17.1982 13.0353 17.1982C10.8442 17.1982 9.06803 15.422 9.06803 13.2309C9.06803 11.0399 10.8442 9.26367 13.0353 9.26367C15.2264 9.26367 17.0026 11.0399 17.0026 13.2309ZM58.9422 13.2309C58.9422 15.422 57.166 17.1982 54.975 17.1982C52.7839 17.1982 51.0077 15.422 51.0077 13.2309C51.0077 11.0399 52.7839 9.26367 54.975 9.26367C57.166 9.26367 58.9422 11.0399 58.9422 13.2309Z"/>
                                </mask>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 5.00195C3.58172 5.00195 0 8.58367 0 13.002V22.002H68V13.002C68 8.58368 64.4183 5.00195 60 5.00195H8ZM17.0026 13.2309C17.0026 15.422 15.2264 17.1982 13.0353 17.1982C10.8442 17.1982 9.06803 15.422 9.06803 13.2309C9.06803 11.0399 10.8442 9.26367 13.0353 9.26367C15.2264 9.26367 17.0026 11.0399 17.0026 13.2309ZM58.9422 13.2309C58.9422 15.422 57.166 17.1982 54.975 17.1982C52.7839 17.1982 51.0077 15.422 51.0077 13.2309C51.0077 11.0399 52.7839 9.26367 54.975 9.26367C57.166 9.26367 58.9422 11.0399 58.9422 13.2309Z" fill="url(#paint14_linear_557_6732)"/>
                                <path d="M0 22.002H-0.68438V22.6863H0V22.002ZM68 22.002V22.6863H68.6844V22.002H68ZM0.68438 13.002C0.68438 8.96165 3.95969 5.68633 8 5.68633V4.31757C3.20375 4.31757 -0.68438 8.2057 -0.68438 13.002H0.68438ZM0.68438 22.002V13.002H-0.68438V22.002H0.68438ZM68 21.3176H0V22.6863H68V21.3176ZM67.3156 13.002V22.002H68.6844V13.002H67.3156ZM60 5.68633C64.0403 5.68633 67.3156 8.96165 67.3156 13.002H68.6844C68.6844 8.2057 64.7963 4.31757 60 4.31757V5.68633ZM8 5.68633H60V4.31757H8V5.68633ZM13.0353 17.8826C15.6043 17.8826 17.6869 15.8 17.6869 13.2309H16.3182C16.3182 15.044 14.8484 16.5138 13.0353 16.5138V17.8826ZM8.38365 13.2309C8.38365 15.8 10.4663 17.8826 13.0353 17.8826V16.5138C11.2222 16.5138 9.75241 15.044 9.75241 13.2309H8.38365ZM13.0353 8.57929C10.4663 8.57929 8.38365 10.6619 8.38365 13.2309H9.75241C9.75241 11.4179 11.2222 9.94805 13.0353 9.94805V8.57929ZM17.6869 13.2309C17.6869 10.6619 15.6043 8.57929 13.0353 8.57929V9.94805C14.8484 9.94805 16.3182 11.4179 16.3182 13.2309H17.6869ZM54.975 17.8826C57.544 17.8826 59.6266 15.8 59.6266 13.2309H58.2579C58.2579 15.044 56.7881 16.5138 54.975 16.5138V17.8826ZM50.3233 13.2309C50.3233 15.8 52.4059 17.8826 54.975 17.8826V16.5138C53.1619 16.5138 51.6921 15.044 51.6921 13.2309H50.3233ZM54.975 8.57929C52.4059 8.57929 50.3233 10.6619 50.3233 13.2309H51.6921C51.6921 11.4179 53.1619 9.94805 54.975 9.94805V8.57929ZM59.6266 13.2309C59.6266 10.6619 57.544 8.57929 54.975 8.57929V9.94805C56.7881 9.94805 58.2579 11.4179 58.2579 13.2309H59.6266Z" fill="url(#paint15_linear_557_6732)" mask="url(#path-8-inside-1_557_6732)"/>
                                <path d="M11.2997 13.0013C11.2997 13.9404 12.061 14.7016 13 14.7016C13.939 14.7016 14.7003 13.9404 14.7003 13.0013H11.2997ZM14.7003 13.0013C14.7003 9.38077 14.2507 6.68584 13.489 4.74513C12.7295 2.81019 11.5848 1.45738 10.1117 0.889343C8.60991 0.310209 7.1186 0.686238 6.05903 1.49887C5.02409 2.29261 4.29974 3.57335 4.29974 5.00175H7.70026C7.70026 4.72974 7.85091 4.41006 8.12847 4.19718C8.3814 4.0032 8.64009 3.96643 8.88826 4.06213C9.16519 4.16892 9.77051 4.57859 10.3235 5.98757C10.8743 7.39079 11.2997 9.62125 11.2997 13.0013H14.7003Z" fill="url(#paint16_linear_557_6732)"/>
                                <path d="M53.2998 13.0013C53.2998 13.9404 54.061 14.7016 55 14.7016C55.939 14.7016 56.7003 13.9404 56.7003 13.0013H53.2998ZM56.7003 13.0013C56.7003 9.38077 56.2507 6.68584 55.489 4.74513C54.7295 2.81019 53.5848 1.45738 52.1118 0.889343C50.6099 0.310209 49.1186 0.686238 48.059 1.49887C47.0241 2.29261 46.2998 3.57335 46.2998 5.00175H49.7003C49.7003 4.72974 49.8509 4.41006 50.1285 4.19718C50.3814 4.0032 50.6401 3.96643 50.8883 4.06213C51.1652 4.16892 51.7705 4.57859 52.3235 5.98757C52.8743 7.39079 53.2998 9.62125 53.2998 13.0013H56.7003Z" fill="url(#paint17_linear_557_6732)"/>
                                </g>
                                <defs>
                                <linearGradient id="paint0_linear_557_6732" x1="33.942" y1="57.5689" x2="33.942" y2="21.5635" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint1_linear_557_6732" x1="33.942" y1="57.5689" x2="33.942" y2="21.5635" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint2_linear_557_6732" x1="13.9864" y1="37.8802" x2="13.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.5"/>
                                </linearGradient>
                                <linearGradient id="paint3_linear_557_6732" x1="13.9864" y1="37.8802" x2="13.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.8"/>
                                </linearGradient>
                                <linearGradient id="paint4_linear_557_6732" x1="13.9864" y1="51.8802" x2="13.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.4"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint5_linear_557_6732" x1="13.9864" y1="51.8802" x2="13.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint6_linear_557_6732" x1="53.9864" y1="37.8802" x2="53.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.4"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint7_linear_557_6732" x1="53.9864" y1="37.8802" x2="53.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint8_linear_557_6732" x1="53.9864" y1="51.8802" x2="53.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.5"/>
                                </linearGradient>
                                <linearGradient id="paint9_linear_557_6732" x1="53.9864" y1="51.8802" x2="53.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.8"/>
                                </linearGradient>
                                <linearGradient id="paint10_linear_557_6732" x1="33.9864" y1="37.8802" x2="33.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.4"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint11_linear_557_6732" x1="33.9864" y1="37.8802" x2="33.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint12_linear_557_6732" x1="33.9864" y1="51.8802" x2="33.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.5"/>
                                </linearGradient>
                                <linearGradient id="paint13_linear_557_6732" x1="33.9864" y1="51.8802" x2="33.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.8"/>
                                </linearGradient>
                                <linearGradient id="paint14_linear_557_6732" x1="33.942" y1="21.7984" x2="33.942" y2="4.79581" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.4"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint15_linear_557_6732" x1="33.942" y1="21.7984" x2="33.942" y2="4.79581" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint16_linear_557_6732" x1="9.10068" y1="13.2769" x2="9.10068" y2="2.04834" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.2"/>
                                </linearGradient>
                                <linearGradient id="paint17_linear_557_6732" x1="51.1007" y1="13.2769" x2="51.1007" y2="2.04834" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.2"/>
                                </linearGradient>
                                </defs>
                                </svg>

                        </div>
                    </div>
                    
                    <div class="cartbox-meta tfhb-flexbox tfhb-gap-8">
                        <span class="cartbox-badge tfhb-flexbox tfhb-gap-8"
                            :class = "{
                                'badge-down': Dashboard.data.total_completed_bookings.growth == 'decrease',
                                'badge-up': Dashboard.data.total_completed_bookings.growth == 'increase',
                            }"
                        >
                            <Icon v-if="Dashboard.data.total_completed_bookings.growth == 'increase'" name="ArrowUp" :size="15"/>
                            <Icon v-else name="ArrowDown" :size="15"/>
                            <span> {{Dashboard.data.total_completed_bookings.percentage}}%</span>
                        </span>
                        <span> VS </span>
                        <span class="cartbox-date">Last {{Dashboard.data_request.days}} days</span>
                    </div>
                </div>
            </div>
            <!-- Single Chartbox --> 
            <!-- Single Chartbox -->
            <div class="tfhb-single-chartbox">
                <div class="tfhb-single-chartbox-wrap gradient-4">
                    <span class="tfhb-adminchartbox-shape">
                        <img  :src="$tfhb_url+'/assets/images/shape-4.svg'" alt="">
                    </span>
                    <div class="tfhb-single-cartbox-inner tfhb-flexbox tfhb-gap-8">
                        <div class="tfhb-single-chartbox-content">
                            <span class="cartbox-title">Canceled Bookings</span> 
                            <span class="cartbox-value ">{{Dashboard.data.total_cancelled_bookings.total}}</span>
                            
                        </div>
                        <div class="tfhb-chartbox-icon">
                            <svg width="68" height="58" viewBox="0 0 68 58" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g opacity="0.7">
                                <path d="M0.34219 22.3422H67.6578V50C67.6578 54.2293 64.2293 57.6578 60 57.6578H8C3.77071 57.6578 0.34219 54.2293 0.34219 50V22.3422Z" fill="url(#paint0_linear_557_6732)" stroke="url(#paint1_linear_557_6732)" stroke-width="0.68438"/>
                                <rect x="6.5" y="28.5" width="15" height="9" rx="2.5" fill="url(#paint2_linear_557_6732)" stroke="url(#paint3_linear_557_6732)"/>
                                <rect x="6.5" y="42.5" width="15" height="9" rx="2.5" fill="url(#paint4_linear_557_6732)" stroke="url(#paint5_linear_557_6732)"/>
                                <rect x="46.5" y="28.5" width="15" height="9" rx="2.5" fill="url(#paint6_linear_557_6732)" stroke="url(#paint7_linear_557_6732)"/>
                                <rect x="46.5" y="42.5" width="15" height="9" rx="2.5" fill="url(#paint8_linear_557_6732)" stroke="url(#paint9_linear_557_6732)"/>
                                <rect x="26.5" y="28.5" width="15" height="9" rx="2.5" fill="url(#paint10_linear_557_6732)" stroke="url(#paint11_linear_557_6732)"/>
                                <rect x="26.5" y="42.5" width="15" height="9" rx="2.5" fill="url(#paint12_linear_557_6732)" stroke="url(#paint13_linear_557_6732)"/>
                                <mask id="path-8-inside-1_557_6732" fill="white">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 5.00195C3.58172 5.00195 0 8.58367 0 13.002V22.002H68V13.002C68 8.58368 64.4183 5.00195 60 5.00195H8ZM17.0026 13.2309C17.0026 15.422 15.2264 17.1982 13.0353 17.1982C10.8442 17.1982 9.06803 15.422 9.06803 13.2309C9.06803 11.0399 10.8442 9.26367 13.0353 9.26367C15.2264 9.26367 17.0026 11.0399 17.0026 13.2309ZM58.9422 13.2309C58.9422 15.422 57.166 17.1982 54.975 17.1982C52.7839 17.1982 51.0077 15.422 51.0077 13.2309C51.0077 11.0399 52.7839 9.26367 54.975 9.26367C57.166 9.26367 58.9422 11.0399 58.9422 13.2309Z"/>
                                </mask>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 5.00195C3.58172 5.00195 0 8.58367 0 13.002V22.002H68V13.002C68 8.58368 64.4183 5.00195 60 5.00195H8ZM17.0026 13.2309C17.0026 15.422 15.2264 17.1982 13.0353 17.1982C10.8442 17.1982 9.06803 15.422 9.06803 13.2309C9.06803 11.0399 10.8442 9.26367 13.0353 9.26367C15.2264 9.26367 17.0026 11.0399 17.0026 13.2309ZM58.9422 13.2309C58.9422 15.422 57.166 17.1982 54.975 17.1982C52.7839 17.1982 51.0077 15.422 51.0077 13.2309C51.0077 11.0399 52.7839 9.26367 54.975 9.26367C57.166 9.26367 58.9422 11.0399 58.9422 13.2309Z" fill="url(#paint14_linear_557_6732)"/>
                                <path d="M0 22.002H-0.68438V22.6863H0V22.002ZM68 22.002V22.6863H68.6844V22.002H68ZM0.68438 13.002C0.68438 8.96165 3.95969 5.68633 8 5.68633V4.31757C3.20375 4.31757 -0.68438 8.2057 -0.68438 13.002H0.68438ZM0.68438 22.002V13.002H-0.68438V22.002H0.68438ZM68 21.3176H0V22.6863H68V21.3176ZM67.3156 13.002V22.002H68.6844V13.002H67.3156ZM60 5.68633C64.0403 5.68633 67.3156 8.96165 67.3156 13.002H68.6844C68.6844 8.2057 64.7963 4.31757 60 4.31757V5.68633ZM8 5.68633H60V4.31757H8V5.68633ZM13.0353 17.8826C15.6043 17.8826 17.6869 15.8 17.6869 13.2309H16.3182C16.3182 15.044 14.8484 16.5138 13.0353 16.5138V17.8826ZM8.38365 13.2309C8.38365 15.8 10.4663 17.8826 13.0353 17.8826V16.5138C11.2222 16.5138 9.75241 15.044 9.75241 13.2309H8.38365ZM13.0353 8.57929C10.4663 8.57929 8.38365 10.6619 8.38365 13.2309H9.75241C9.75241 11.4179 11.2222 9.94805 13.0353 9.94805V8.57929ZM17.6869 13.2309C17.6869 10.6619 15.6043 8.57929 13.0353 8.57929V9.94805C14.8484 9.94805 16.3182 11.4179 16.3182 13.2309H17.6869ZM54.975 17.8826C57.544 17.8826 59.6266 15.8 59.6266 13.2309H58.2579C58.2579 15.044 56.7881 16.5138 54.975 16.5138V17.8826ZM50.3233 13.2309C50.3233 15.8 52.4059 17.8826 54.975 17.8826V16.5138C53.1619 16.5138 51.6921 15.044 51.6921 13.2309H50.3233ZM54.975 8.57929C52.4059 8.57929 50.3233 10.6619 50.3233 13.2309H51.6921C51.6921 11.4179 53.1619 9.94805 54.975 9.94805V8.57929ZM59.6266 13.2309C59.6266 10.6619 57.544 8.57929 54.975 8.57929V9.94805C56.7881 9.94805 58.2579 11.4179 58.2579 13.2309H59.6266Z" fill="url(#paint15_linear_557_6732)" mask="url(#path-8-inside-1_557_6732)"/>
                                <path d="M11.2997 13.0013C11.2997 13.9404 12.061 14.7016 13 14.7016C13.939 14.7016 14.7003 13.9404 14.7003 13.0013H11.2997ZM14.7003 13.0013C14.7003 9.38077 14.2507 6.68584 13.489 4.74513C12.7295 2.81019 11.5848 1.45738 10.1117 0.889343C8.60991 0.310209 7.1186 0.686238 6.05903 1.49887C5.02409 2.29261 4.29974 3.57335 4.29974 5.00175H7.70026C7.70026 4.72974 7.85091 4.41006 8.12847 4.19718C8.3814 4.0032 8.64009 3.96643 8.88826 4.06213C9.16519 4.16892 9.77051 4.57859 10.3235 5.98757C10.8743 7.39079 11.2997 9.62125 11.2997 13.0013H14.7003Z" fill="url(#paint16_linear_557_6732)"/>
                                <path d="M53.2998 13.0013C53.2998 13.9404 54.061 14.7016 55 14.7016C55.939 14.7016 56.7003 13.9404 56.7003 13.0013H53.2998ZM56.7003 13.0013C56.7003 9.38077 56.2507 6.68584 55.489 4.74513C54.7295 2.81019 53.5848 1.45738 52.1118 0.889343C50.6099 0.310209 49.1186 0.686238 48.059 1.49887C47.0241 2.29261 46.2998 3.57335 46.2998 5.00175H49.7003C49.7003 4.72974 49.8509 4.41006 50.1285 4.19718C50.3814 4.0032 50.6401 3.96643 50.8883 4.06213C51.1652 4.16892 51.7705 4.57859 52.3235 5.98757C52.8743 7.39079 53.2998 9.62125 53.2998 13.0013H56.7003Z" fill="url(#paint17_linear_557_6732)"/>
                                </g>
                                <defs>
                                <linearGradient id="paint0_linear_557_6732" x1="33.942" y1="57.5689" x2="33.942" y2="21.5635" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint1_linear_557_6732" x1="33.942" y1="57.5689" x2="33.942" y2="21.5635" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint2_linear_557_6732" x1="13.9864" y1="37.8802" x2="13.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.5"/>
                                </linearGradient>
                                <linearGradient id="paint3_linear_557_6732" x1="13.9864" y1="37.8802" x2="13.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.8"/>
                                </linearGradient>
                                <linearGradient id="paint4_linear_557_6732" x1="13.9864" y1="51.8802" x2="13.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.4"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint5_linear_557_6732" x1="13.9864" y1="51.8802" x2="13.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint6_linear_557_6732" x1="53.9864" y1="37.8802" x2="53.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.4"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint7_linear_557_6732" x1="53.9864" y1="37.8802" x2="53.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint8_linear_557_6732" x1="53.9864" y1="51.8802" x2="53.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.5"/>
                                </linearGradient>
                                <linearGradient id="paint9_linear_557_6732" x1="53.9864" y1="51.8802" x2="53.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.8"/>
                                </linearGradient>
                                <linearGradient id="paint10_linear_557_6732" x1="33.9864" y1="37.8802" x2="33.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.4"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint11_linear_557_6732" x1="33.9864" y1="37.8802" x2="33.9864" y2="27.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint12_linear_557_6732" x1="33.9864" y1="51.8802" x2="33.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.5"/>
                                </linearGradient>
                                <linearGradient id="paint13_linear_557_6732" x1="33.9864" y1="51.8802" x2="33.9864" y2="41.8787" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.8"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.8"/>
                                </linearGradient>
                                <linearGradient id="paint14_linear_557_6732" x1="33.942" y1="21.7984" x2="33.942" y2="4.79581" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.4"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint15_linear_557_6732" x1="33.942" y1="21.7984" x2="33.942" y2="4.79581" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white" stop-opacity="0.2"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.1"/>
                                </linearGradient>
                                <linearGradient id="paint16_linear_557_6732" x1="9.10068" y1="13.2769" x2="9.10068" y2="2.04834" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.2"/>
                                </linearGradient>
                                <linearGradient id="paint17_linear_557_6732" x1="51.1007" y1="13.2769" x2="51.1007" y2="2.04834" gradientUnits="userSpaceOnUse">
                                <stop stop-color="white"/>
                                <stop offset="1" stop-color="white" stop-opacity="0.2"/>
                                </linearGradient>
                                </defs>
                                </svg>

                        </div>
                    </div>
                    
                    <div class="cartbox-meta tfhb-flexbox tfhb-gap-8">
                        <span class="cartbox-badge tfhb-flexbox tfhb-gap-8"
                            :class = "{
                                'badge-down': Dashboard.data.total_cancelled_bookings.growth == 'decrease',
                                'badge-up': Dashboard.data.total_cancelled_bookings.growth == 'increase',
                            }"
                        >
                            <Icon v-if="Dashboard.data.total_cancelled_bookings.growth == 'increase'" name="ArrowUp" :size="15"/>
                            <Icon v-else name="ArrowDown" :size="15"/>
                            <span> {{Dashboard.data.total_cancelled_bookings.percentage}}%</span>
                        </span>
                        <span> VS </span>
                        <span class="cartbox-date">Last {{Dashboard.data_request.days}} days</span>
                    </div>
                </div>
            </div>
            <!-- Single Chartbox --> 

                
        </div>

        
        <!-- Notice Box -->
        <div :class="{ 'tfhb-skeleton': Dashboard.skeleton_chartbox }"  class="tfhb-flexbox tfhb-dashboard-notice-box tfhb-gap-24">

            <div class="tfhb-dashboard-notice-box-inner">
                <div class="tfhb-dashboard-notice-box-wrap tfhb-flexbox tfhb-gap-16">

                    <h3 class="tfhb-dashboard-notice-box-title tfhb-m-0 tfhb-full-width">Recent Bookings</h3>
                    <!-- Single Notice Box -->
                    <div class="tfhb-dashboard-notice-box-content tfhb-flexbox tfhb-gap-16 tfhb-full-width">
                        <div
                            v-for="(data, index) in Dashboard.data.recent_booking"
                                :key="index" 
                            class="tfhb-dashboard-notice-single-box tfhb-full-width" 
                        >
                            <div class="tfhb-admin-card-box">
                                
                                <p>{{data.title}}    </p>
                                <div class="tfhb-dashboard-notice-meta tfhb-flexbox tfhb-gap-8"> 
                                    <span class="tfhb-flexbox tfhb-gap-8"><Icon name="Clock" :size="15"/>{{ data.start_time}} </span>
                                    <span  class="tfhb-flexbox tfhb-gap-8">
                                        <Icon name="UserRound" :size="15"/> 
                                        <Icon name="ArrowRight" :size="15"/> 
                                        <Icon name="UserRound" :size="15"/> 
                                        <Icon v-if="data.meeting_type != 'one-to-one'" name="UserRound" :size="15"/> 
                                    </span>

                                    <span  class="tfhb-flexbox tfhb-gap-8"><Icon name="Banknote" :size="15"/> {{data.payment_status}} </span>
                                    <span  class="tfhb-flexbox tfhb-gap-8"><Icon name="UserRound" :size="15"/> {{data.host_first_name}} {{ data.host_last_name}} </span>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <!-- Single Notice Box -->
                    
                </div>
            </div>

            <div class="tfhb-dashboard-notice-box-inner">
                <div class="tfhb-dashboard-notice-box-wrap ">
                    <h3 class="tfhb-dashboard-notice-box-title tfhb-mb-24 tfhb-full-width">Upcoming Meetings</h3>

                    <div class="tfhb-dashboard-notice-box-content tfhb-flexbox tfhb-gap-16" >
                        <!-- Single Notice Box -->
                        <div 
                            v-for="(data, index) in Dashboard.data.upcoming_booking"
                            :key="index" 
                            class="tfhb-dashboard-notice-single-box tfhb-flexbox tfhb-gap-8 tfhb-full-width" >
                            <span > {{ data.start_time}} </span>
                            <div class="tfhb-admin-card-box">
                                <p>{{data.attendee_name}} ({{data.attendee_email}})  </p>
                                <div class="tfhb-dashboard-notice-meta tfhb-flexbox tfhb-gap-8"> 
                                    <span class="tfhb-flexbox tfhb-gap-8"><Icon name="CalendarDays" :size="15"/> 
                                        <!-- convert 2024-05-29 to 25 Sep, 24 --> 
                                        {{data.meeting_dates}}
                                    </span> 
                                    <span  class="tfhb-flexbox tfhb-gap-8"><Icon name="Clock" :size="15"/> {{ data.attendee_time_zone}}</span>
                                    <span  class="tfhb-flexbox tfhb-gap-8"><Icon name="UserRound" :size="15"/> {{data.host_first_name}} {{ data.host_last_name}}</span>
                                </div>
                            </div> 
                        </div> 
                    </div> 

                </div>
            </div>



        </div>

        <!-- Cart statistic -->
        <div   class="tfhb-chart-statistic-wrap tfhb-dashboard-notice-box"> 
            <div class="tfhb-dashboard-notice-box-wrap" >
                <div  class="tfhb-dashboard-heading tfhb-flexbox">
                    <div class="tfhb-admin-title"> 
                        <h3 >{{ $tfhb_trans['Statistics'] }}</h3>  
                    </div>
                    <div class="thb-admin-btn right"> 
                        <div class="tfhb-dropdown  tfhb-no-hover">
                            <a class="tfhb-flexbox tfhb-gap-8 tfhb-btn"  @click="datachart_dropdown = !datachart_dropdown" id="tfhb-chart-filter" > <span> {{ $tfhb_trans['This Week'] }}</span>  <Icon name="ChevronDown" size="15" /> </a>
                            <div  
                                :class="{ 'active': datachart_dropdown }"
                                class="tfhb-dropdown-wrap"
                            > 
                                <!-- route link --> 
                                <span class="tfhb-dropdown-single" data-name="Last 7 Days" @click="ChangeStatisticData(7)">Last 7 Days</span> 
                                <span class="tfhb-dropdown-single" data-name="This month" @click="ChangeStatisticData(30)">This month</span> 
                                <span class="tfhb-dropdown-single" data-name="Last 3 months" @click="ChangeStatisticData(3)">Last 3 months</span> 
                                <span class="tfhb-dropdown-single" data-name="This Year" @click="ChangeStatisticData(12)">This Year</span> 
                                
                            </div>
                        </div> 
                    </div> 
                </div>
                
                <Chart :class="{ 'tfhb-skeleton': Dashboard.skeleton_chart }" type="line" :data="Dashboard.chartData" :options="Dashboard.chartOptions" />
    
            </div>
        </div>
    </div> 
</div> 
</template>
 