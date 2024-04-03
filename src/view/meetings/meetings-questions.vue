<script setup>
import {reactive, ref} from 'vue'
import HbQuestion from '@/components/meetings/HbQuestion.vue'
import HbQuestionForm from '@/components/meetings/HbQuestionForm.vue'
import Icon from '@/components/icon/LucideIcon.vue'

const emit = defineEmits(["update-meeting", "limits-frequency-add"]); 
const props = defineProps({
    meetingId: {
        type: Number,
        required: true
    },
    meeting: {
        type: Object,
        required: true
    },

});

</script>

<template>
    <div class="meeting-create-details tfhb-gap-24">

        <div class="tfhb-admin-title" >
            <h2 class="tfhb-flexbox tfhb-gap-8 tfhb-justify-normal">
                Meeting Questions for Attendee
                <HbSwitch 
                    v-model="meeting.questions_status"
                    width="50"
                />
            </h2> 
            <p>Create your own booking page questions</p>
        </div>

        <div class="tfhb-admin-card-box tfhb-gap-24 tfhb-m-0" v-if="meeting.questions_status!=0">  

            <HbQuestion 
                :question_value="meeting.questions"
                @question-edit="EditExtraQuestion"
                @question-remove="removeExtraQuestion"
            />

            <div class="tfhb-add-new-question tfhb-flexbox tfhb-gap-8"  @click="QuestionPopupAdd()" >
                <Icon name="PlusCircle" :width="20"/>
                Add more questions
            </div>

            <HbPopup :isOpen="QuestionPopup" @modal-close="QuestionPopup = false" max_width="400px" name="first-modal">
                <template #header> 
                    <h3>Add Question for Attendee</h3>
                </template>

                <template #content>  
                    <HbQuestionForm 
                    :questions_data="questions_data"
                    @question-add="addExtraQuestion"
                    @question-cancel="QuestionPopupClose"
                    />
                </template> 
            </HbPopup>

        </div>
        <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="emit('update-meeting')">{{ $tfhb_trans['Save & Continue'] }} </button>
        <!--Bookings -->
    </div>
</template>