
import { createRouter, createWebHashHistory } from 'vue-router';
import Dashboard from '../view/Dashboard.vue';
import Availability from '../view/Availability.vue';
import AvailabilityEdit from '../view/AvailabilityEdit.vue';
import Booking from '../view/Booking.vue';
import Event from '../view/event/Event.vue';
import EventCreate from '../view/event/create.vue';
import Settings from '../view/settings/Settings.vue';

// Event 


 

const routes = [
    // Define your routes here
    // For example:
    {
        path: '/',
        component: Dashboard
    },
    {
        path: '/availability',
        component: Availability
    },
    {
        path: '/availability/edit/:id',
        name: 'availabilityEdit',
        component: AvailabilityEdit
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
