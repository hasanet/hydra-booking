<script setup>
import { ref, reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue'
import HbDateTime from '@/components/form-fields/HbDateTime.vue';
import CreateMeetingPopup from '@/components/meetings/CreateMeetingPopup.vue';
import { toast } from "vue3-toastify"; 

const router = useRouter();

const FilterPreview = ref(false);
const FilterHostPreview = ref(false);
const FilterCatgoryPreview = ref(false);
const isModalOpened = ref(false);
const skeleton = ref(true);

const openModal = () => {
  isModalOpened.value = true;
};
const closeModal = () => { 
  isModalOpened.value = false;
};

// Fetch Meetings List
const meetings = reactive({}); 
const fetchMeetings = async () => {
    try { 
        const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/lists');
        if (response.data.status) { 
            meetings.data = response.data.meetings;  
            skeleton.value = false;
        }
    } catch (error) {
        console.log(error);
    } 
} 

// Meeting_data
const meeting = reactive({});
const CreateMeeting = async (type) => {    
   meeting.data = type
   try { 
        // axisos sent dataHeader Nonce Data
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/create', meeting, {
            headers: {
                'X-WP-Nonce': tfhb_core_apps.rest_nonce
            } 
        } );

        if (response.data.status) {  
            toast.success(response.data.message, {
                position: 'bottom-right', // Set the desired position
                "autoClose": 1500,
            });  
            closeModal(); 
            router.push({ name: 'MeetingsCreate', params: { id: response.data.id} });
        }else{
            toast.error(response.data.message, {
                position: 'bottom-right', // Set the desired position
                "autoClose": 1500,
            });
        }
    } catch (error) {
        console.log(error);
    }   
}


// Delete Meeting 
const deleteMeeting = async ($id, $post_id) => { 
    let deleteMeeting = {
        id: $id,
        post_id: $post_id
    }
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/delete', deleteMeeting, {
               
        } );
        if (response.data.status) { 
            meetings.data = response.data.meetings;  
            toast.success(response.data.message); 
        }
    } catch (error) {
        console.log(error);
    }
}

onBeforeMount(() => { 
    fetchMeetings();
});

const Tfhb_Meeting_Filter = async (e) =>{
    skeleton.value = true;
    try {
        const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/filter', {
            params: {
                title: e.target.value,
            },
        });
        
        if (response.data.status) { 
            meetings.data = response.data.meetings;  
            skeleton.value = false;
        }

    } catch (error) {
        console.error('Error:', error);
    }
}

</script>
<template>

    <div class="tfhb-dashboard-heading tfhb-flexbox">
        <div class="tfhb-filter-box tfhb-flexbox">
            <div class="tfhb-filter-btn tfhb-flexbox" @click="FilterPreview=!FilterPreview" :class="FilterPreview ? 'active' : ''">
                <Icon name="Filter" size="20" /> 
                Filter
            </div>
            <div class="tfhb-header-filters">
                <input type="text" @keyup="Tfhb_Meeting_Filter" placeholder="Search by meeting title" /> 
                <span><Icon name="Search" size="20" /></span>
            </div>
        </div>
        <div class="thb-admin-btn right">
            <button class="tfhb-btn boxed-btn flex-btn" @click="openModal"><Icon name="PlusCircle" size="20" /> {{ $tfhb_trans['Create New Meeting'] }}</button>
        </div> 
    </div>

    <CreateMeetingPopup v-if="isModalOpened" @modal-close="closeModal" @meetings-create="CreateMeeting"  />

    <div class="tfhb-filter-box-content tfhb-mt-32" v-show="FilterPreview">
        <div class="tfhb-filter-form">
            <div class="tfhb-filter-category">
                <div class="tfhb-host-filter-box tfhb-flexbox" @click="FilterHostPreview=!FilterHostPreview">
                    All Host <Icon name="ChevronUp" size="20" v-if="FilterHostPreview"/> <Icon name="ChevronDown" size="20" v-else="FilterHostPreview"/>
                </div>
                <div class="tfhb-filter-category-box" v-show="FilterHostPreview">
                    <ul class="tfhb-flexbox">
                        <li class="tfhb-flexbox">
                            <label for="checkbox1">
                                <input type="checkbox" id="checkbox1">
                                Darrell Steward
                            </label>
                            <div class="tfhb-category-items">
                                25
                            </div>
                        </li>
                        <li class="tfhb-flexbox">
                            <label for="checkbox2">
                                <input type="checkbox" id="checkbox2">
                                Darrell Steward
                            </label>
                            <div class="tfhb-category-items">
                                25
                            </div>
                        </li>
                        <li class="tfhb-flexbox">
                            <label for="checkbox3">
                                <input type="checkbox" id="checkbox3">
                                Darrell Steward
                            </label>
                            <div class="tfhb-category-items">
                                25
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tfhb-filter-category">
                <div class="tfhb-host-filter-box tfhb-flexbox" @click="FilterCatgoryPreview=!FilterCatgoryPreview">
                    All Category <Icon name="ChevronUp" size="20" v-if="FilterCatgoryPreview"/> <Icon name="ChevronDown" size="20" v-else="FilterCatgoryPreview"/>
                </div>
                <div class="tfhb-filter-category-box" v-show="FilterCatgoryPreview">
                    <ul class="tfhb-flexbox">
                        <li class="tfhb-flexbox">
                            <label for="checkbox1">
                                <input type="checkbox" id="checkbox1">
                                Darrell Steward
                            </label>
                            <div class="tfhb-category-items">
                                25
                            </div>
                        </li>
                        <li class="tfhb-flexbox">
                            <label for="checkbox2">
                                <input type="checkbox" id="checkbox2">
                                Darrell Steward
                            </label>
                            <div class="tfhb-category-items">
                                25
                            </div>
                        </li>
                        <li class="tfhb-flexbox">
                            <label for="checkbox3">
                                <input type="checkbox" id="checkbox3">
                                Darrell Steward
                            </label>
                            <div class="tfhb-category-items">
                                25
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tfhb-filter-dates tfhb-flexbox">
                <div class="tfhb-filter-start-date">
                    <HbDateTime 
                        selected = "1"
                        width="45"
                        enableTime='true'
                        placeholder="From"   
                    /> 
                    <Icon name="CalendarDays" size="20" /> 
                </div>
                <div class="tfhb-calender-move-icon">
                    <Icon name="MoveRight" size="20px" /> 
                </div>
                <div class="tfhb-filter-end-date">
                    <HbDateTime 
                        selected = "1"
                        width="45"
                        enableTime='true'
                        placeholder="To"   
                    /> 
                    <Icon name="CalendarDays" size="20" /> 
                </div>
            </div>

        </div>
        <div class="tfhb-reset-btn">
            <a href="#" class="tfhb-flexbox">
                <Icon name="RefreshCw" size="20" /> 
                Reset Filter
            </a>
        </div>
    </div>

    <div class="tfhb-meetings-list-content" :class="{ 'tfhb-skeleton': skeleton }">
        <div class="tfhb-meetings-list-wrap tfhb-flexbox tfhb-justify-normal">

            <!-- Single Meeting -->
            <div class="tfhb-single-meeting" v-for="(smeeting, key) in meetings.data"> 
                <div class="single-meeting-content-box tfhb-flexbox">
                    <div class="single-meeting-content">
                        <h3> {{ smeeting.title ? smeeting.title : 'No Title' }} </h3>
                        <div class="meeting-user-info">
                            <ul class="tfhb-flexbox">
                                <li v-if="smeeting.duration">
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="Clock" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            {{ smeeting.duration }}
                                        </div>
                                    </div>
                                </li>
                                <li v-if="smeeting.meeting_type">
                                    <div class="tfhb-flexbox" v-if="'one-to-one'==smeeting.meeting_type">
                                        <div class="user-info-icon">
                                            <Icon name="UserRound" size="16" /> 
                                            <Icon name="ArrowRight" size="16" /> 
                                            <Icon name="UserRound" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            One to One
                                        </div>
                                    </div>
                                    <div class="tfhb-flexbox" v-if="'one-to-group'==smeeting.meeting_type">
                                        <div class="user-info-icon">
                                            <Icon name="UserRound" size="16" /> 
                                            <Icon name="ArrowRight" size="16" /> 
                                            <Icon name="UsersRound" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            One to Group
                                        </div>
                                    </div>
                                </li>
                                <li v-if="smeeting.meeting_price">
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="Banknote" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            {{ smeeting.meeting_price }}
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="User" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            Jack Sparrow
                                        </div>
                                    </div>
                                </li>
                                <li class="tfhb-booked-items">
                                    <div class="tfhb-flexbox booked-items">
                                        <div class="user-info-icon">
                                            <Icon name="CalendarCheck" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            10/20 Booked
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tfhb-single-hosts-action tfhb-dropdown">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 10.8334C10.4603 10.8334 10.8334 10.4603 10.8334 10.0001C10.8334 9.53984 10.4603 9.16675 10 9.16675C9.53978 9.16675 9.16669 9.53984 9.16669 10.0001C9.16669 10.4603 9.53978 10.8334 10 10.8334Z" stroke="#765664" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10 4.99992C10.4603 4.99992 10.8334 4.62682 10.8334 4.16659C10.8334 3.70635 10.4603 3.33325 10 3.33325C9.53978 3.33325 9.16669 3.70635 9.16669 4.16659C9.16669 4.62682 9.53978 4.99992 10 4.99992Z" stroke="#765664" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10 16.6667C10.4603 16.6667 10.8334 16.2936 10.8334 15.8333C10.8334 15.3731 10.4603 15 10 15C9.53978 15 9.16669 15.3731 9.16669 15.8333C9.16669 16.2936 9.53978 16.6667 10 16.6667Z" stroke="#765664" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="tfhb-dropdown-wrap"> 
                            <!-- route link -->
                            <router-link :to="{ name: 'MeetingsCreate', params: { id: smeeting.id } }" class="tfhb-dropdown-single">Edit</router-link>
                            
                            <span class="tfhb-dropdown-single" @click="deleteMeeting(smeeting.id, smeeting.post_id)">Delete</span>
                        </div>
                    </div>
                </div>
                <div class="single-meeting-action-btn tfhb-flexbox">
                    <a href="#" class="tfhb-flexbox">
                        <Icon name="Eye" size="20" /> 
                        Preview
                    </a>
                    <a href="#" class="tfhb-flexbox">
                        <Icon name="Share2" size="20" /> 
                        Share
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</template>

<style scoped>
.tfhb-meetings-list-content {
    padding: 24px;
    margin-top: 24px;
    border-radius: 16px;
    border: 1px #F6EEF2;
    background: #FFF;
    margin-right: 16px;
}
</style>