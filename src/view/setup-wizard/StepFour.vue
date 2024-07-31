<script setup>
import { ref, reactive, onBeforeMount } from 'vue';
import { RouterView } from 'vue-router' 
import HbText from '@/components/form-fields/HbText.vue'
import HbDropdown from '@/components/form-fields/HbDropdown.vue'
import HbDateTime from '@/components/form-fields/HbDateTime.vue';
import Icon from '@/components/icon/LucideIcon.vue'
import HbPopup from '@/components/widgets/HbPopup.vue'; 
import HbCheckbox from '@/components/form-fields/HbCheckbox.vue';
import { setupWizard } from '@/store/setupWizard';

// Toast
import { toast } from "vue3-toastify"; 

const sharePopup = ref(false)

const props = defineProps({
    setupWizard : {
        type: Object,
        required: true
    }
}); 

// / Share Popup Data
const shareData = reactive({
    title: '',
    time: '',
    meeting_type: '',
    share_type: 'link',
    link: '',
    shortcode: '',
    embed: ''
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
    shareData.link = tfhb_core_apps.admin_url + '/' + data.slug
    shareData.embed = '<iframe src="'+ tfhb_core_apps.admin_url +'/?hydra-booking=meeting&meeting-id='+data.id+'&type=iframe" title="description"  height="600" width="100%" ></iframe>'

    // Popup open
    sharePopup.value = true;
}


const StepFour = () => {
    props.setupWizard.currentStep = 'step-end';
}

</script>

<template>
 

     <!-- Step Four -->
     <div class="tfhb-setup-wizard-content-wrap tfhb-admin-meetings tfhb-s-w-step-four tfhb-flexbox">
       
        <div class="tfhb-s-w-icon-text">
            <div class="tfhb-step-wizard-steper tfhb-flexbox tfhb-gap-16" >
                <span class="tfhb-step-bar step-1 active"></span>
                <span class="tfhb-step-bar step-1 active"></span>
                <span class="tfhb-step-bar step-1 active"></span>
                <span class="tfhb-step-bar step-1 active"></span>
            </div>
            <h2>Your Meeting is ready!</h2>
            <p>All set! Your Hydrabooking meeting is good to go.   Click "Preview" to peek at your booking page or "Share" to send the link to your attendees</p>
        </div>

        <div class="tfhb-meetings-list-content" >
            <div class="tfhb-meetings-list-wrap tfhb-flexbox tfhb-justify-normal">

                <!-- Single Meeting -->
                <div class="tfhb-single-meeting"> 
                    <div class="single-meeting-content-box tfhb-flexbox">
                        <div class="single-meeting-content">
                            <h3> {{ setupWizard.data.meeting ? setupWizard.data.meeting.title : 'No Title' }} </h3>
                            <div class="meeting-user-info">
                                <ul class="tfhb-flexbox">
                                    <li v-if="setupWizard.data.meeting.duration">
                                        <div class="tfhb-flexbox">
                                            <div class="user-info-icon">
                                                <Icon name="Clock" size="16" /> 
                                            </div>
                                            <div class="user-info-title">
                                                {{ setupWizard.data.meeting.duration }} {{ $tfhb_trans['minutes'] }}
                                            </div>
                                        </div>
                                    </li>
                                    <li v-if="setupWizard.data.meeting.meeting_type">
                                        <div class="tfhb-flexbox" v-if="'one-to-one'==setupWizard.data.meeting.meeting_type">
                                            <div class="user-info-icon">
                                                <Icon name="UserRound" size="16" /> 
                                                <Icon name="ArrowRight" size="16" /> 
                                                <Icon name="UserRound" size="16" /> 
                                            </div>
                                            <div class="user-info-title">
                                                {{ $tfhb_trans['One to One'] }}
                                            </div>
                                        </div>
                                        <div class="tfhb-flexbox" v-if="'one-to-group'==setupWizard.data.meeting.meeting_type">
                                            <div class="user-info-icon">
                                                <Icon name="UserRound" size="16" /> 
                                                <Icon name="ArrowRight" size="16" /> 
                                                <Icon name="UsersRound" size="16" /> 
                                            </div>
                                            <div class="user-info-title">
                                                {{ $tfhb_trans['One to Group'] }}
                                            </div>
                                        </div>
                                    </li>
                                    <li v-if="setupWizard.data.meeting.meeting_price">
                                        <div class="tfhb-flexbox">
                                            <div class="user-info-icon">
                                                <Icon name="Banknote" size="16" /> 
                                            </div>
                                            <div class="user-info-title">
                                                {{ setupWizard.data.meeting.meeting_price }}
                                            </div>
                                        </div>
                                    </li>
                                    <li v-if="setupWizard.data.meeting.host_first_name">
                                        <div class="tfhb-flexbox">
                                            <div class="user-info-icon">
                                                <Icon name="User" size="16" /> 
                                            </div>
                                            <div class="user-info-title">
                                                {{ setupWizard.data.meeting.host_first_name }} {{ setupWizard.data.meeting.host_last_name }}
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
                                <router-link :to="{ name: 'MeetingsCreate', params: { id: setupWizard.data.meeting.id } }" class="tfhb-dropdown-single">{{ $tfhb_trans['Edit'] }}</router-link>
                                
                            </div>
                        </div>
                    </div>
                    <div class="single-meeting-action-btn tfhb-flexbox">
                        <a :href="'/' + setupWizard.data.meeting.slug" class="tfhb-flexbox" target="_blank">
                            <Icon name="Eye" size="20" /> 
                            {{ $tfhb_trans['Preview'] }}
                        </a>
                        <a href="#" class="tfhb-flexbox" @click.prevent="sharePopupData(setupWizard.data.meeting)">
                            <Icon name="Share2" size="20" /> 
                            {{ $tfhb_trans['Share'] }}
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
                                            {{ shareData.time }} {{ $tfhb_trans['minutes'] }}
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
                                            {{ $tfhb_trans['One to One'] }}
                                        </div>
                                    </div>
                                    <div class="tfhb-flexbox tfhb-gap-8" v-if="'one-to-group'==shareData.meeting_type">
                                        <div class="user-info-icon">
                                            <Icon name="UserRound" size="16" /> 
                                            <Icon name="ArrowRight" size="16" /> 
                                            <Icon name="UsersRound" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            {{ $tfhb_trans['One to Group'] }}
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <div class="tfhb-share-type tfhb-full-width">
                                <ul class="tfhb-flexbox tfhb-gap-8">
                                    <li :class="'link'==shareData.share_type ? 'active' : ''" @click="ShareTabs('link')">{{ $tfhb_trans['Share link'] }}</li>
                                    <li :class="'short'==shareData.share_type ? 'active' : ''" @click="ShareTabs('short')">{{ $tfhb_trans['Short code'] }}</li>
                                    <li :class="'embed'==shareData.share_type ? 'active' : ''" @click="ShareTabs('embed')">Embed code</li>
                                </ul>
                            </div>

                            <div class="tfhb-shareing-data tfhb-full-width">
                                <div class="share-link" v-if="'link'==shareData.share_type">
                                    <input type="text" :value="shareData.link" readonly>

                                    <div class="tfhb-copy-btn tfhb-mt-32">
                                        <button class="tfhb-btn boxed-btn flex-btn" @click="copyMeeting(shareData.link)">{{ $tfhb_trans['Copy link'] }}</button>
                                    </div>
                                </div>
                                <div class="share-link" v-if="'short'==shareData.share_type">
                                    <input type="text" :value="shareData.shortcode" readonly>

                                    <div class="tfhb-copy-btn tfhb-mt-32">
                                        <button class="tfhb-btn boxed-btn flex-btn" @click="copyMeeting(shareData.shortcode)">{{ $tfhb_trans['Copy Code'] }}</button>
                                    </div>
                                </div>
                                <div class="share-link" v-if="'embed'==shareData.share_type">
                                    <input type="text" :value="shareData.embed" readonly>

                                    <div class="tfhb-copy-btn tfhb-mt-32">
                                        <button class="tfhb-btn boxed-btn flex-btn" @click="copyMeeting(shareData.embed)">{{ $tfhb_trans['Copy Code'] }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template> 
                </HbPopup>

                </div>
        </div>
      
        <div class="tfhb-submission-btn tfhb-flexbox">
            <button class="tfhb-btn secondary-btn tfhb-flexbox tfhb-gap-8" > <Icon name="ChevronLeft" size="20" /> Back </button>
            <button class="tfhb-btn boxed-btn tfhb-flexbox tfhb-gap-8" @click="StepFour" >Complete setup<Icon name="ChevronRight" size="20" />  </button>
        </div>
     </div>
     <!-- Step Four -->

</template>
 