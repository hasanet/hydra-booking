
import { createRouter, createWebHashHistory } from 'vue-router';
import Dashboard from '../view/dashboard/Dashboard.vue';  
import Booking from '../view/booking/booking.vue'; 
import Settings from '../view/settings/Settings.vue';
import Hosts from '../view/hosts/hosts.vue';
import Meetings from '../view/meetings/meetings.vue';

// Event 


const routes = [
    // Define your routes here
    // For example:
    {
        path: '/',
        component: Dashboard
    },  
    {
        path: '/booking',
        name: 'booking',
        component: Booking,
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
        redirect: { name: 'MeetingsLists' },
        children: [ 
            {
                path: 'list',
                name: 'MeetingsLists',
                component: () => import('../view/meetings/meetings-list.vue')
            }, 
            {
                path: 'create',
                name: 'MeetingsCreate',
                component: () => import('../view/meetings/meetings-create.vue'),
                props: true,
                redirect: { name: 'MeetingsCreateDetails' },
                children: [
                    {
                        path: 'details',
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
                    }
                ]
            }, 
            // {
            //     path: 'profile/:id',
            //     name: 'HostsProfile',
            //     component: () => import('../view/hosts/hosts-profile.vue'),
            //     redirect: { name: 'HostsProfileInformation' },
            //     children: [
            //         {
            //             path: 'information',
            //             name: 'HostsProfileInformation',
            //             component: () => import('../view/hosts/hosts-information.vue')
            //         }, 
            //         {
            //             path: 'meeting',
            //             name: 'HostsProfileMeeting',
            //             component: () => import('../view/hosts/hosts-meeting.vue')
            //         }, 
            //         {
            //             path: 'integrations',
            //             name: 'HostsProfileIntegrations',
            //             component: () => import('../view/hosts/hosts-integrations.vue')
            //         }, 
            //     ]
            // }, 
        ]
    }, 
 
    
    // Settings routes
    {
        path: '/settings',
        component: Settings,
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

export default router;
