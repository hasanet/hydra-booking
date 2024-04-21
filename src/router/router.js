
import { createRouter, createWebHashHistory } from 'vue-router';
import Dashboard from '../view/dashboard/Dashboard.vue';  
import Booking from '../view/booking/booking.vue'; 
import Settings from '../view/settings/Settings.vue';
import Hosts from '../view/hosts/hosts.vue';
import Meetings from '../view/meetings/meetings.vue';
import { AuthData } from '@/store/auth';

// Event 


const routes = [
    // Define your routes here
    // For example:
    {
        path: '/',
        name: 'dashboard', 
        component: Dashboard
    },  
    {
        path: '/booking',
        name: 'booking', 
        meta: { Capabilities: 'tfhb_manage_options' },
        component: Booking,
        Capabilities: 'tfhb_manage_options',
        redirect: { name: 'BookingLists' },
        children: [ 
            {
                path: 'list',
                name: 'BookingLists',
                component: () => import('../view/booking/booking-list.vue')
            }, 
        ]
    }, 
    // Hosts routes
    {
        path: '/hosts',
        name: 'hosts',
        component: Hosts,
        meta: { Capabilities: 'tfhb_manage_options' },
        redirect: { name: 'HostsLists' },
        children: [ 
            {
                path: 'list',
                name: 'HostsLists',
                component: () => import('../view/hosts/hosts-list.vue')
            }, 
            {
                path: 'profile/:id',
                name: 'HostsProfile',
                props: true,
                component: () => import('../view/hosts/hosts-profile.vue'),
                redirect: { name: 'HostsProfileInformation' },
                children: [
                    {
                        path: 'information',
                        name: 'HostsProfileInformation',
                        props: true,
                        component: () => import('../view/hosts/hosts-information.vue')
                    }, 
                    {
                        path: 'meeting',
                        name: 'HostsProfileMeeting',
                        component: () => import('../view/hosts/hosts-meeting.vue')
                    }, 
                    {
                        path: 'integrations',
                        name: 'HostsProfileIntegrations',
                        component: () => import('../view/hosts/hosts-integrations.vue')
                    }, 
                ]
            }, 
        ]
    }, 
   
    // Meetings routes
    {
        path: '/meetings',
        name: 'meetings',
        component: Meetings,
        meta: { Capabilities: 'tfhb_manage_options' },
        redirect: { name: 'MeetingsLists' },
        children: [ 
            {
                path: 'list',
                name: 'MeetingsLists',
                component: () => import('../view/meetings/meetings-list.vue')
            }, 
            {
                path: 'single/:id',
                name: 'MeetingsCreate',
                component: () => import('../view/meetings/meetings-create.vue'),
                props: true,
                redirect: { name: 'MeetingsCreateDetails' },
                children: [
                    {
                        path: 'details',
                        props: true,
                        name: 'MeetingsCreateDetails',
                        component: () => import('../view/meetings/meetings-details.vue')
                    },
                    {
                        path: 'availability',
                        name: 'MeetingsCreateAvailability',
                        component: () => import('../view/meetings/meetings-availability.vue')
                    },
                    {
                        path: 'limits',
                        name: 'MeetingsCreateLimits',
                        component: () => import('../view/meetings/meetings-limits.vue')
                    },
                    {
                        path: 'questions',
                        name: 'MeetingsCreateQuestions',
                        component: () => import('../view/meetings/meetings-questions.vue')
                    },
                    {
                        path: 'notifications',
                        name: 'MeetingsCreateNotifications',
                        component: () => import('../view/meetings/meetings-notifications.vue')
                    },
                    {
                        path: 'payment',
                        name: 'MeetingsCreatePayment',
                        component: () => import('../view/meetings/meetings-payment.vue')
                    }
                ]
            }, 
        ]
    }, 
 
    
    // Settings routes
    {
        path: '/settings',
        component: Settings,
        meta: { Capabilities: 'tfhb_manage_settings' },
        redirect: { name: 'SettingsGeneral' },
        children: [ 
            {
                path: 'general',
                name: 'SettingsGeneral',
                component: () => import('../view/settings/General.vue')
            },
            {
                path: 'availability',
                name: 'SettingsAvailability',
                component: () => import('../view/settings/Availability.vue')
            }, 
            {
                path: 'notifications',
                name: 'SettingsAotifications',
                component: () => import('../view/settings/Notifications.vue')
            },
            {
                path: 'integrations',
                name: 'SettingsAntegrations',
                component: () => import('../view/settings/Integrations.vue')
            },  
            // {
            //     path: 'appearance',
            //     name: 'SettingsAppearance',
            //     component: () => import('../view/settings/appearance.vue')
            // },  
             
        ]
        
    },
    // ...
];

const router = createRouter({
  history: createWebHashHistory(),
  routes
});


// Navigation guards to check authentication status
router.beforeEach(async (to, from, next) => { 
    if (to.meta.Capabilities === undefined) {
        // If no capabilities are defined for the route, proceed to the next route
        next();
        return;
    }

    try {
        // Fetch user authentication data based on capabilities
        await AuthData.fetchAuth();

        // Check if the user has the required capabilities for the route
        const hasCapabilities = AuthData.Capabilities(to.meta.Capabilities);
    
        
        if (hasCapabilities) {
            // User has the required capabilities, continue to the next route
            next();
        } else {
            // User is not authenticated
            // Redirect to the home page or display an alert
            alert('Sorry, you are not allowed to access this page.');
            next('/');
        }
    } catch (error) {
        // Handle error if fetching authentication data fails
        console.error('Error fetching authentication data:', error);
        // Redirect to the home page or display an alert
        alert('An error occurred while fetching authentication data.');
        next('/');
    }
});

export default router;
