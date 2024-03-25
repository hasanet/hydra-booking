
import { createRouter, createWebHashHistory } from 'vue-router';
import Dashboard from '../view/Dashboard.vue';  
import Booking from '../view/Booking.vue';
import Event from '../view/event/Event.vue';
import EventCreate from '../view/event/create.vue';
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
        component: Booking
    },
    // Event routes
    {
        path: '/event',
        name : 'events',
        component: Event,  
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

    // Event routes
    {
        path: '/event/create',
        name: 'EventCreate',
        component: EventCreate,
        redirect: { name: 'EventDetails' },
        children: [ 
            {
                path: 'details',
                name: 'EventDetails',
                component: () => import('../components/event/EventDetails.vue')
            },
            {
                path: 'availability',
                name: 'EventAvailability',
                component: () => import('../components/event/EventAvailability.vue')
            },
            {
                path: 'questions',
                name: 'EventQuestions',
                component: () => import('../components/event/EventQuestions.vue')
            },
            {
                path: 'notifications',
                name: 'EventNotifications',
                component: () => import('../components/event/EventNotifications.vue')
            },
            {
                path: 'payment',
                name: 'EventPayment',
                component: () => import('../components/event/EventPayment.vue')
            },
            // {
            //     path: 'ticket',
            //     name: 'EventTicket',
            //     component: () => import('../components/event/EventTicket.vue')
            // },
            // {
            //     path: 'confirmation',
            //     name: 'EventConfirmation',
            //     component: () => import('../components/event/EventConfirmation.vue')
            // }
        ]
    }, 
   
    // {
    //     path: '/event/details',
    //     name: 'EventDetails',
    //     component: EventDetails
    // },
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
