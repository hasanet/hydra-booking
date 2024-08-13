<script setup>
import Icon from '@/components/icon/LucideIcon.vue'
const props = defineProps([
    'title',
     
]) 
</script>


<template>
    <div class="thb-admin-header">
        <div class="thb-admin-header-icon tfhb-flexbox">
            <svg width="40" height="40" viewBox="0 0 128 128" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="128" height="128" rx="32" fill="url(#paint0_radial_1_31)"/>
                <path d="M69.9489 96.522L52.2774 78.8506L48.0965 74.6696L45.6437 72.2168L35.9996 62.5728V103.1H69.9489C71.9557 103.1 73.9068 102.821 75.7465 102.32L69.9489 96.522Z" fill="white"/>
                <path d="M52.2774 65.5273L35.9996 49.2495V25H38.8427L50.2706 36.4279L65.7679 51.981L52.2774 65.5273Z" fill="white"/>
                <path d="M92.4703 78.6275L72.4576 58.6147L69.949 61.1233L62.2003 68.872L58.9113 72.161L69.8933 83.1429L84.4429 97.6926C89.3486 93.5674 92.4703 87.3239 92.4703 80.4114C92.5261 79.8539 92.5261 79.2407 92.4703 78.6275Z" fill="white"/>
                <path d="M91.188 39.8842C90.0731 36.8181 88.2892 34.0308 86.0036 31.7453C81.9342 27.5643 76.1923 25 69.8931 25H52.1659L56.5698 29.4039L88.1777 61.0119C90.965 57.2769 92.5816 52.65 92.5816 47.6328C92.5259 44.9013 92.0799 42.2812 91.188 39.8842Z" fill="white"/>
                <defs>
                <radialGradient id="paint0_radial_1_31" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(64 128) rotate(-90) scale(128)">
                <stop stop-color="#FA056F"/>
                <stop offset="1" stop-color="#ED85B2"/>
                </radialGradient>
                </defs>
            </svg> 
            <h2 class="tfhb-admin-header-title">{{ props.title }}</h2>
        </div>
        <div class="tfhb-header-notification">
            <div class="tfhb-dropdown tfhb-mega-dropdown">
            <span> <Icon name="Bell" size="24" /> </span>
           
            <div class="tfhb-dropdown-wrap ">   <!-- active class-->
                <div class="tfhb-flexbox">
                    <h3>{{ $tfhb_trans['Notifications'] }}</h3>
                    <a href="#" class="tfhb-btn">{{ $tfhb_trans['Mark as read'] }}</a>
                </div>

                <div class="tfhb-notification-wrap">
                    <!-- Single Notifaction wrap -->
                    <div class="tfhb-single-notification tfhb-flexbox tfhb-gap-8"> 
                        <div class="tfhb-single-notification-img">
                            <img src="https://via.placeholder.com/32" alt="Notification Image">
                        </div> 
                        <div class="tfhb-single-notification-content">
                            <h4>John Doe</h4>
                            <p>Has booked a room</p>
                        </div>
                        <span class="tfhb-notification-time">6m</span>
                    </div>
                    <!-- Single Notifaction wrap --> 
                    <!-- Single Notifaction wrap -->
                    <div class="tfhb-single-notification tfhb-flexbox tfhb-gap-8"> 
                        <div class="tfhb-single-notification-img">
                            <img src="https://via.placeholder.com/32" alt="Notification Image">
                        </div> 
                        <div class="tfhb-single-notification-content">
                            <h4>John Doe</h4>
                            <p>Has booked a room</p>
                        </div>
                        <span class="tfhb-notification-time">6m</span>
                    </div>
                    <!-- Single Notifaction wrap --> 
                    <!-- Single Notifaction wrap -->
                    <div class="tfhb-single-notification tfhb-flexbox tfhb-gap-8"> 
                        <div class="tfhb-single-notification-img">
                            <img src="https://via.placeholder.com/32" alt="Notification Image">
                        </div> 
                        <div class="tfhb-single-notification-content">
                            <h4>John Doe</h4>
                            <p>Has booked a room</p>
                        </div>
                        <span class="tfhb-notification-time">6m</span>
                    </div>
                    <!-- Single Notifaction wrap --> 
                </div>
            </div>
        </div>
        </div>
    </div>
</template> 

<style scoped>
/* Your component styles go here */
    .thb-admin-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 24px;
        height: 60px;
        background-color: #fff;
        border-bottom: 1px solid #eaeaea;
        border-radius: 8px;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
        /* margin-right: 16px; */
        margin-bottom: 24px;
    }
    .thb-admin-header .header-navigation ul { 
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .thb-admin-header .header-navigation ul li {
        display: inline-block; 

    }
    .thb-admin-header .header-navigation ul li a,
    .thb-admin-header .header-navigation ul li a:focus { 
        padding: 8px 16px;
        font-size: 16px;
        font-weight: 600;
        color: #301C25;
        text-decoration: none;
        outline: none;
        box-shadow: none;
        margin-left: 5px;
    }
    .thb-admin-header .header-navigation ul li a:hover,
    .thb-admin-header .header-navigation ul li a.active{
        background-color:   #fee9f2;
        color: #F62681;
        border-radius: 4px;

    }
</style>

<style scoped>
/* Your component styles go here */
</style>

