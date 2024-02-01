
import { createRouter, createWebHashHistory } from 'vue-router';
import Dashboard from '../view/Dashboard.vue';
import Availability from '../view/Availability.vue';
import AvailabilityEdit from '../view/AvailabilityEdit.vue';
import Booking from '../view/Booking.vue';
import Event from '../view/event/Event.vue';
import EventCreate from '../view/event/create.vue';
import Settings from '../view/Settings.vue';


 

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
        component: Event
    },
    {
        path: '/event/create',
        name: 'EventCreate',
        component: EventCreate
    },
    // Event routes
    {
        path: '/settings',
        component: Settings
    },
    // ...
];

const router = createRouter({
  history: createWebHashHistory(),
  routes
});

export default router;
