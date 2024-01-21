
import { createRouter, createWebHashHistory } from 'vue-router';
import Dashboard from '../components/page/Dashboard.vue';
import Availability from '../components/page/Availability.vue';
import Booking from '../components/page/Booking.vue';
import Event from '../components/page/Event.vue';
import Settings from '../components/page/Settings.vue';


 

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
        path: '/booking',
        component: Booking
    },
    {
        path: '/event',
        component: Event
    },
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
