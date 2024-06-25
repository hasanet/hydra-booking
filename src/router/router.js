
import { createRouter, createWebHashHistory } from 'vue-router';
import Dashboard from '../view/dashboard/Dashboard.vue';  
import Booking from '../view/booking/booking.vue'; 
import Settings from '../view/settings/Settings.vue';
import Hosts from '../view/hosts/hosts.vue';
import Meetings from '../view/meetings/meetings.vue';
import { AuthData } from '@/store/auth';

// Event 
const user = tfhb_core_apps.user || '';
const user_id = user.id || '';
const user_role = user.role[0] || '';

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
        redirect: { name: 'BookingLists' },
        children: [ 
            {
                path: 'list',
                name: 'BookingLists',
                meta: { Capabilities: 'tfhb_manage_options' },
                component: () => import('../view/booking/booking-list.vue')
            }, 
            {
                path: 'create-new',
                name: 'BookingCreate',
                meta: { Capabilities: 'tfhb_manage_settings' },
                component: () => import('../view/booking/booking-create.vue')
            },
            {
                path: 'update/:id',
                name: 'bookingUpdate',
                meta: { Capabilities: 'tfhb_manage_options' },
                component: () => import('../view/booking/booking-update.vue')
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
                meta: { Capabilities: 'tfhb_manage_options' },
                component: () => import('../view/hosts/hosts-list.vue')
            }, 
            {
                path: 'profile/:id',
                name: 'HostsProfile',
                meta: { Capabilities: 'tfhb_manage_options' },
                props: true,
                component: () => import('../view/hosts/hosts-profile.vue'),
                redirect: { name: 'HostsProfileInformation' },
                children: [
                    {
                        path: 'information',
                        name: 'HostsProfileInformation',
                        meta: { Capabilities: 'tfhb_manage_options' },
                        props: true,
                        component: () => import('../view/hosts/hosts-information.vue')
                    }, 
                    {
                        path: 'availability',
                        name: 'HostsAvailability',
                        meta: { Capabilities: 'tfhb_manage_options' },
                        props: true,
                        component: () => import('../view/hosts/hosts-availability.vue')
                    }, 
                    {
                        path: 'integrations',
                        name: 'HostsProfileIntegrations',
                        meta: { Capabilities: 'tfhb_manage_integrations' },
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
                meta: { Capabilities: 'tfhb_manage_options' },
                component: () => import('../view/meetings/meetings-list.vue')
            }, 
            {
                path: 'single/:id',
                name: 'MeetingsCreate',
                meta: { Capabilities: 'tfhb_manage_options' },
                component: () => import('../view/meetings/meetings-create.vue'),
                props: true,
                redirect: { name: 'MeetingsCreateDetails' },
                children: [
                    {
                        path: 'details',
                        props: true,
                        name: 'MeetingsCreateDetails',
                        meta: { Capabilities: 'tfhb_manage_options' },
                        component: () => import('../view/meetings/meetings-details.vue')
                    },
                    {
                        path: 'availability',
                        name: 'MeetingsCreateAvailability',
                        meta: { Capabilities: 'tfhb_manage_options' },
                        component: () => import('../view/meetings/meetings-availability.vue')
                    },
                    {
                        path: 'limits',
                        name: 'MeetingsCreateLimits',
                        meta: { Capabilities: 'tfhb_manage_options' },
                        component: () => import('../view/meetings/meetings-limits.vue')
                    },
                    {
                        path: 'questions',
                        name: 'MeetingsCreateQuestions',
                        meta: { Capabilities: 'tfhb_manage_options' },
                        component: () => import('../view/meetings/meetings-questions.vue')
                    },
                    {
                        path: 'notifications',
                        name: 'MeetingsCreateNotifications',
                        meta: { Capabilities: 'tfhb_manage_options' },
                        component: () => import('../view/meetings/meetings-notifications.vue')
                    },
                    {
                        path: 'payment',
                        name: 'MeetingsCreatePayment',
                        meta: { Capabilities: 'tfhb_manage_options' },
                        component: () => import('../view/meetings/meetings-payment.vue')
                    },
                    {
                        path: 'webhook',
                        name: 'MeetingsCreateWebhook',
                        meta: { Capabilities: 'tfhb_manage_options' },
                        component: () => import('../view/meetings/meetings-webhook.vue')
                    },
                    {
                        path: 'integrations',
                        name: 'MeetingsCreateIntegrations',
                        meta: { Capabilities: 'tfhb_manage_options' },
                        component: () => import('../view/meetings/meetings-integrations.vue')
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
                meta: { Capabilities: 'tfhb_manage_settings' },
                component: () => import('../view/settings/General.vue')
            },
            {
                path: 'hosts-settings',
                name: 'HostsSettings',
                meta: { Capabilities: 'tfhb_manage_settings' },
                component: () => import('../view/settings/HostsSettings.vue'),
                redirect: { name: 'HostsSettingsInformationBuilder' },
                children: [ 
                    {
                        path: 'information-builder',
                        name: 'HostsSettingsInformationBuilder',
                        meta: { Capabilities: 'tfhb_manage_settings' },
                        component: () => import('@/components/settings/HostsInformationBuilder.vue')
                    },
                    {
                        path: 'permission',
                        name: 'HostsSettingsPermission',
                        meta: { Capabilities: 'tfhb_manage_settings' },
                        component: () => import('@/components/settings/hostsPermission.vue')
                    }
                ]
            },
            {
                path: 'availability',
                name: 'SettingsAvailability',
                meta: { Capabilities: 'tfhb_manage_settings' },
                component: () => import('../view/settings/Availability.vue')
            }, 
            {
                path: 'notifications',
                name: 'SettingsAotifications',
                meta: { Capabilities: 'tfhb_manage_settings' },
                component: () => import('../view/settings/Notifications.vue')
            },
            {
                path: 'integrations',
                name: 'SettingsAntegrations',
                meta: { Capabilities: 'tfhb_manage_settings' },
                component: () => import('../view/settings/Integrations.vue')
            },  
            {
                path: 'appearance',
                name: 'SettingsAppearance',
                component: () => import('../view/settings/Appearance.vue')
            },  
            {
                path: 'category',
                name: 'SettingsCategory',
                component: () => import('../view/settings/Category.vue')
            },  
             
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

//   alert('to.meta.Capabilities: ' + to.meta.Capabilities);
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
            // Host Route for if use is host 
            if(  user_role == 'tfhb_host' && to.name == 'HostsLists' ){
                // next({ name: 'HostsProfile', params: { id: user_id } }); 
                next({ name: 'HostsProfile', params: { id: user_id } });
        
                
            }
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
