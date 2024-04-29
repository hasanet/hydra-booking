<script setup>
import { ref, reactive, onBeforeMount } from 'vue';
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue'
import HbDateTime from '@/components/form-fields/HbDateTime.vue';
import CreateMeetingPopup from '@/components/meetings/CreateMeetingPopup.vue';
import { Host } from '@/store/hosts'
import { Meeting } from '@/store/meetings'

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

onBeforeMount(() => { 
    Host.fetchHosts();
    Meeting.fetchMeetings();
    Meeting.fetchMeetingCategory()
});

// Filtering
const filterData = reactive({
    title: '',
    fhosts: [],
    fcategory: [],
    startDate: '',
    endDate: ''
})


</script>
<template>
<!-- {{ filterData }} -->

    <div class="tfhb-dashboard-heading tfhb-flexbox">
        <div class="tfhb-filter-box tfhb-flexbox">
            <div class="tfhb-filter-btn tfhb-flexbox" @click="FilterPreview=!FilterPreview" :class="FilterPreview ? 'active' : ''">
                <Icon name="Filter" size="20" /> 
                Filter
            </div>
            <div class="tfhb-header-filters">
                <input type="text" v-model="filterData.title" @keyup="Meeting.Tfhb_Meeting_Filter(filterData)" placeholder="Search by meeting title" /> 
                <span><Icon name="Search" size="20" /></span>
            </div>
        </div>
        <div class="thb-admin-btn right">
            <button class="tfhb-btn boxed-btn flex-btn" @click="openModal"><Icon name="PlusCircle" size="20" /> {{ $tfhb_trans['Create New Meeting'] }}</button>
        </div> 
    </div>

    <CreateMeetingPopup v-if="isModalOpened" @modal-close="closeModal" @meetings-create="Meeting.CreatePopupMeeting"  />

    <div class="tfhb-filter-box-content tfhb-mt-32" v-show="FilterPreview">
        <div class="tfhb-filter-form">
            <div class="tfhb-filter-category">
                <div class="tfhb-host-filter-box tfhb-flexbox" @click="FilterHostPreview=!FilterHostPreview">
                    All Host <Icon name="ChevronUp" size="20" v-if="FilterHostPreview"/> <Icon name="ChevronDown" size="20" v-else="FilterHostPreview"/>
                </div>
                <div class="tfhb-filter-category-box" v-show="FilterHostPreview">
                    <ul class="tfhb-flexbox">
                        <li class="tfhb-flexbox" v-for="(shost, key) in Host.hosts" :key="key">
                            <label>
                                <input type="checkbox" :value="key" v-model="filterData.fhosts" @change="Meeting.Tfhb_Meeting_Select_Filter(filterData)">
                                <span class="checkmark"></span>
                                {{ shost }}
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
                        <li class="tfhb-flexbox" v-for="(mcategory, key) in Meeting.meetingCategory.data" :key="key">
                            <label>
                                <input type="checkbox" :value="mcategory.id" v-model="filterData.fcategory" @change="Meeting.Tfhb_Meeting_Select_Filter(filterData)">
                                <span class="checkmark"></span>
                                {{ mcategory.name }}
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
                        v-model="filterData.startDate"
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
                        v-model="filterData.endDate"
                        width="45"
                        enableTime='true'
                        placeholder="To"   
                    /> 
                    <Icon name="CalendarDays" size="20" /> 
                </div>
            </div>

        </div>
        <div class="tfhb-reset-btn" v-if="filterData.fcategory.length > 0 || filterData.fhosts.length > 0 || filterData.title">
            <a href="#" class="tfhb-flexbox">
                <Icon name="RefreshCw" size="20" /> 
                Reset Filter
            </a>
        </div>
    </div>

    <div class="tfhb-meetings-list-content" v-if="Meeting.meetings.length > 0">
        <div class="tfhb-meetings-list-wrap tfhb-flexbox tfhb-justify-normal">

            <!-- Single Meeting -->
            <div class="tfhb-single-meeting" v-for="(smeeting, key) in Meeting.meetings"> 
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
                        <img :src="$tfhb_url+'/assets/images/more-vertical.svg'" alt="">
                        <div class="tfhb-dropdown-wrap"> 
                            <!-- route link -->
                            <router-link :to="{ name: 'MeetingsCreate', params: { id: smeeting.id } }" class="tfhb-dropdown-single">Edit</router-link>
                            
                            <span class="tfhb-dropdown-single" @click="Meeting.deleteMeeting(smeeting.id, smeeting.post_id)">Delete</span>
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