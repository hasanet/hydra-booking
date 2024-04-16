<script setup>
import {reactive, ref} from 'vue'
import HbQuestion from '@/components/meetings/HbQuestion.vue'
import HbQuestionForm from '@/components/meetings/HbQuestionForm.vue'
import Icon from '@/components/icon/LucideIcon.vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue'
import HbPopup from '@/components/widgets/HbPopup.vue'; 

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

const QuestionPopup = ref(false);
// Extra Qestion Data
const questions_data = reactive({});
// Remove removeExtraQuestion
const removeExtraQuestion = (key) => {
    props.meeting.questions.splice(key, 1);
}
function EditExtraQuestion(key){
    props.meeting.questions.forEach((question, qkey) => {
        if (qkey === key) {
            questions_data.key = key;
            questions_data.label = question.label;
            questions_data.type = question.type;
            questions_data.placeholder = question.placeholder;
            questions_data.required = question.required;
            QuestionPopup.value = true;
        }
    });
}
// Popup Saved addExtraQuestion
function addExtraQuestion(){
    props.meeting.questions[questions_data.key].label = questions_data.label
    props.meeting.questions[questions_data.key].type = questions_data.type
    props.meeting.questions[questions_data.key].placeholder = questions_data.placeholder
    props.meeting.questions[questions_data.key].required = questions_data.required
    QuestionPopup.value = false;
}
// Add New Question
function QuestionPopupAdd(){
    props.meeting.questions.push({
        label: '',
        type:'',
        placeholder:'',
        required: 1
    });
    const lastIndexOfQuestion = props.meeting.questions.length - 1;
    questions_data.key = lastIndexOfQuestion;
    questions_data.label = '';
    questions_data.type = '';
    questions_data.placeholder = '';
    questions_data.required = '';
    QuestionPopup.value = true;
}
// Popup close
function QuestionPopupClose(){
    QuestionPopup.value = false;
}

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
        <div class="tfhb-submission-btn">
            <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="emit('update-meeting')">{{ $tfhb_trans['Save & Continue'] }} </button>
        </div>
        <!--Bookings -->
    </div>
</template>