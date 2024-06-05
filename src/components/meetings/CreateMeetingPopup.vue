<script setup>
import { ref, reactive, onBeforeMount, } from 'vue';  
import Icon from '@/components/icon/LucideIcon.vue' 
import { useRouter, RouterView } from 'vue-router' 
const router = useRouter();

const meeting = reactive({});
const emit = defineEmits(["modal-close", "meetings-create"]); 

const TfhbMeetingType = (type, router) => {
    meeting.meeting_type = type;
    emit('meetings-create', meeting, router);
}


</script>

<template> 

    <div class="tfhb-popup tfhb-p-24">
        <div class="tfhb-flexbox tfhb-gap-16 tfhb-popup-wrap" :style="{ 'max-width': '400px' }">
            <div class="question-header tfhb-flexbox tfhb-full-width">
                <h3>{{ $tfhb_trans['Create New Meeting Type'] }}</h3>
                <div class="tfhb-question-times" @click.stop="emit('modal-close')">
                    <Icon name="X" :width="20"/>
                </div>
            </div>
            <div class="tfhb-meeting-person-type">
                <div class="tfhb-meeting-type-card tfhb-flexbox tfhb-gap-32 tfhb-p-24" @click="TfhbMeetingType('one-to-one', router)">
                    <div class="tfhb-meeting-type-content">
                        <div class="tfhb-flexbox tfhb-justify-normal tfhb-gap-8">
                            <div class="tfhb-flexbox tfhb-justify-normal tfhb-gap-4">
                                <Icon name="UserRound" size="20" /> 
                                <Icon name="ArrowRight" size="20" /> 
                                <Icon name="UserRound" size="20" /> 
                            </div>
                            <h3>{{ $tfhb_trans['One to One'] }}</h3>
                        </div>
                        <p>{{ $tfhb_trans['One host with one invitee. Good for: 1:1 interview, coffee chats'] }}</p>
                    </div>
                    <div class="tfhb-meeting-type-icon">
                        <Icon name="ArrowRight" width="20"/>
                    </div>
                </div>
            </div>
            <div class="tfhb-meeting-person-type">
                <div class="tfhb-meeting-type-card tfhb-flexbox tfhb-gap-32 tfhb-p-24" @click="TfhbMeetingType('one-to-group', router)">
                    <div class="tfhb-meeting-type-content">
                        <div class="tfhb-flexbox tfhb-justify-normal tfhb-gap-8">
                            <div class="tfhb-flexbox tfhb-justify-normal tfhb-gap-4">
                                <Icon name="UserRound" size="20" /> 
                                <Icon name="ArrowRight" size="20" /> 
                                <Icon name="UsersRound" size="20" /> 
                            </div>
                            <h3>{{ $tfhb_trans['One to Group'] }}</h3>
                        </div>
                        <p>{{ $tfhb_trans['One host with group of invitee. Good for: webinars, online clasess'] }}</p>
                    </div>
                    <div class="tfhb-meeting-type-icon">
                        <Icon name="ArrowRight" width="20"/>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<style scoped>
</style> 