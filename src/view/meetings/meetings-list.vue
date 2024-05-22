<script setup>
import { ref, reactive, onBeforeMount } from 'vue';
import { toast } from "vue3-toastify"; 
import Icon from '@/components/icon/LucideIcon.vue'
import HbDateTime from '@/components/form-fields/HbDateTime.vue';
import CreateMeetingPopup from '@/components/meetings/CreateMeetingPopup.vue';
import HbPopup from '@/components/widgets/HbPopup.vue'; 
import { Host } from '@/store/hosts'
import { Meeting } from '@/store/meetings'

const FilterPreview = ref(false);
const FilterHostPreview = ref(false);
const FilterCatgoryPreview = ref(false);
const isModalOpened = ref(false);
const skeleton = ref(true);
const sharePopup = ref(false)

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

// Share Popup Data
const shareData = reactive({
    title: '',
    time: '',
    meeting_type: '',
    share_type: 'link',
    link: '',
    shortcode: ''
})

const ShareTabs = (tab) => {
    shareData.share_type = tab;
}

const sharePopupData = (data) => {

    shareData.share_type = 'link'
    shareData.title = data.title
    shareData.time = data.duration
    shareData.meeting_type = data.meeting_type
    shareData.shortcode = '[hydra_booking id="'+data.id+'"]'
    shareData.link = tfhb_core_apps.admin_url + '/?hydra-booking=meeting&meeting=' + data.id

    // Popup open
    sharePopup.value = true;
}

const copyMeeting = (link) => {
    //  copy to clipboard without navigator 
    const textarea = document.createElement('textarea');
    textarea.value = link;
    textarea.setAttribute('readonly', '');
    textarea.style.position = 'absolute';
    textarea.style.left = '-9999px';
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);
    
    // Show a toast notification or perform any other action
    toast.success(link + ' is Copied');
}

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
                    <a :href="'/?hydra-booking=meeting&meeting=' + smeeting.id" class="tfhb-flexbox" target="_blank">
                        <Icon name="Eye" size="20" /> 
                        Preview
                    </a>
                    <a href="#" class="tfhb-flexbox" @click.prevent="sharePopupData(smeeting)">
                        <Icon name="Share2" size="20" /> 
                        Share
                    </a>
                </div>
            </div>


            <HbPopup :isOpen="sharePopup" @modal-close="sharePopup = false" max_width="600px" name="first-modal">
                <template #header> 
                    <h3>{{ shareData.title }}</h3>
                </template>

                <template #content>  
                    <div class="tfhb-meeting-durationinfo tfhb-flexbox tfhb-gap-32 tfhb-full-width">
                        <ul class="tfhb-locationtype tfhb-flexbox tfhb-justify-normal tfhb-full-width tfhb-gap-32">
                            <li v-if="shareData.time">
                                <div class="tfhb-flexbox tfhb-gap-8">
                                    <div class="user-info-icon">
                                        <Icon name="Clock" size="16" /> 
                                    </div>
                                    <div class="user-info-title">
                                        {{ shareData.time }}
                                    </div>
                                </div>
                            </li>
                            <li v-if="shareData.meeting_type">
                                <div class="tfhb-flexbox tfhb-gap-8" v-if="'one-to-one'==shareData.meeting_type">
                                    <div class="user-info-icon">
                                        <Icon name="UserRound" size="16" /> 
                                        <Icon name="ArrowRight" size="16" /> 
                                        <Icon name="UserRound" size="16" /> 
                                    </div>
                                    <div class="user-info-title">
                                        One to One
                                    </div>
                                </div>
                                <div class="tfhb-flexbox tfhb-gap-8" v-if="'one-to-group'==shareData.meeting_type">
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
                        </ul>

                        <div class="tfhb-share-type tfhb-full-width">
                            <ul class="tfhb-flexbox tfhb-gap-8">
                                <li :class="'link'==shareData.share_type ? 'active' : ''" @click="ShareTabs('link')">Share link</li>
                                <li :class="'short'==shareData.share_type ? 'active' : ''" @click="ShareTabs('short')">Short code</li>
                                <li :class="'embed'==shareData.share_type ? 'active' : ''" @click="ShareTabs('embed')">Embed code</li>
                            </ul>
                        </div>

                        <div class="tfhb-shareing-data tfhb-full-width">
                            <div class="share-link" v-if="'link'==shareData.share_type">
                                <input type="text" :value="shareData.link" readonly>

                                <div class="tfhb-copy-btn tfhb-mt-32">
                                    <button class="tfhb-btn boxed-btn flex-btn" @click="copyMeeting(shareData.link)">Copy link</button>
                                </div>
                            </div>
                            <div class="share-link" v-if="'short'==shareData.share_type">
                                <input type="text" :value="shareData.shortcode" readonly>

                                <div class="tfhb-copy-btn tfhb-mt-32">
                                    <button class="tfhb-btn boxed-btn flex-btn" @click="copyMeeting(shareData.shortcode)">Copy Code</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template> 
            </HbPopup>
            
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