<script setup>
import {reactive, ref} from 'vue'
import HbQuestion from '@/components/widgets/HbQuestion.vue'
import HbQuestionForm from '@/components/widgets/HbQuestionForm.vue'
import Icon from '@/components/icon/LucideIcon.vue' 
import HbDropdown from '@/components/form-fields/HbDropdown.vue'
import HbPopup from '@/components/widgets/HbPopup.vue'; 
import axios from 'axios';

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
    integrations: {
        type: Object,
        required: true
    },
    formsList: {
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
            questions_data.options = question.options;
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
    // if type is radio, select, multi-select, checkbox then options will be added
    if (questions_data.type === 'radio' || questions_data.type === 'select' || questions_data.type === 'multi-select' || questions_data.type === 'checkbox') {
        props.meeting.questions[questions_data.key].options = questions_data.options
    }else{
        props.meeting.questions[questions_data.key].options = []
    }
    props.meeting.questions[questions_data.key].required = questions_data.required
    QuestionPopup.value = false;
}
// Add New Question
function QuestionPopupAdd(){
    props.meeting.questions.push({
        label: '',
        type:'',
        placeholder:'',
        options: ['Option 1', 'Option 2'],
        required: 1
    });
    const lastIndexOfQuestion = props.meeting.questions.length - 1;
    questions_data.key = lastIndexOfQuestion;
    questions_data.label = '';
    questions_data.type = '';
    questions_data.placeholder = '';
    questions_data.options = ['Option 1', 'Option 2'],
    questions_data.required = '';
    QuestionPopup.value = true;
}
// Popup close
function QuestionPopupClose(){
    QuestionPopup.value = false;
}
// Get Forms Data
 
const GetFormsData = async (e) => {
    let form_type =  e.value; 
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/question/forms-list', {
            form_type: form_type
        });
        if (response.data.status) { 
            props.formsList.value = response.data.questionForms;
        }
    } catch (error) {
        console.log(error);
    } 
}
</script>

<template>
    <div class="meeting-create-details tfhb-gap-24"> 
        <div class="tfhb-meeting-range tfhb-full-width">
            <div class="tfhb-admin-title   tfhb-full-width">
                <h2 class="tfhb-flexbox tfhb-gap-8 tfhb-justify-normal">
                    {{ $tfhb_trans['Meeting Questions for Attendee'] }}
                    <!-- <HbSwitch 
                        v-model="meeting.questions_type" 
                    /> -->
                </h2> 
                <p>{{ $tfhb_trans['Create your own booking page questions'] }}</p>
            </div>

            <div class="tfhb-flexbox tfhb-gap-0 tfhb-align-normal">
                <div class="tfhb-single-meeting-range tfhb-admin-card-box tfhb-border-box tfhb-m-0 tfhb-align-baseline">
                    <label for="tfhb_continuos_date" class="tfhb-m-0 tfhb-flexbox tfhb-gap-16 tfhb-align-normal">
                        <div class="tfhb-range-checkbox">
                            <input id="tfhb_continuos_date" name="tfhb_range_date" value="custom" type="radio" v-model="meeting.questions_type" :checked="meeting.questions_type == 'custom' ? true : false">
                            <span class="checkmark"></span> 
                        </div>
                        <div class="tfhb-range-title">
                            <h4 class="tfhb-m-0">{{ $tfhb_trans['Create custom form'] }}</h4> 
                            <!-- <p class="tfhb-m-0">{{ $tfhb_trans['Meeting will be go for indefinitely into the future'] }}</p> -->
                        </div>
                    </label>
                </div>
                <div class="tfhb-single-meeting-range tfhb-admin-card-box tfhb-border-box tfhb-m-0 tfhb-align-baseline"> 
                    <label for="tfhb_specific_date" class="tfhb-m-0 tfhb-flexbox tfhb-gap-16 tfhb-align-normal">
                        <div class="tfhb-range-checkbox">
                            <input id="tfhb_specific_date" name="tfhb_range_date" type="radio" value="existing" v-model="meeting.questions_type" :checked="meeting.questions_type == 'existing' ? true : false">
                            <span class="checkmark"></span> 
                        </div>
                        <div class="tfhb-range-title">
                            <h4 class="tfhb-m-0">{{ $tfhb_trans['Use existing form'] }}</h4> 
                            <!-- <p class="tfhb-m-0">{{ $tfhb_trans['Meeting will be only available on specific dates'] }}</p> -->
                        </div>
                    </label> 
                </div>
            </div>
        </div>
     

        <div class="tfhb-admin-card-box tfhb-gap-24 tfhb-m-0 tfhb-full-width" v-if="meeting.questions_type == 'custom'">  

            <HbQuestion 
                :question_value="meeting.questions"
                :skip_remove="2"
                @question-edit="EditExtraQuestion"
                @question-remove="removeExtraQuestion"
            />

            <div class="tfhb-add-new-question tfhb-flexbox tfhb-gap-8"  @click="QuestionPopupAdd()" >
                <Icon name="PlusCircle" :width="20"/>
                {{ $tfhb_trans['Add more questions'] }}
            </div>

            <HbPopup :isOpen="QuestionPopup" @modal-close="QuestionPopup = false" max_width="400px" name="first-modal">
                <template #header> 
                    <h3>{{ $tfhb_trans['Add Question for Attendee'] }}</h3>
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

        <div class="tfhb-admin-card-box tfhb-gap-24 tfhb-m-0 tfhb-full-width" v-if="meeting.questions_type == 'existing'">  
  
               <!-- Time format -->
               <HbDropdown 
                    
                    v-model="meeting.questions_form_type"  
                    required= "true" 
                    :label="$tfhb_trans['Select Form Types']"  
                    width="50"
                    :selected = "1"
                    placeholder="Select Form Types"   
                    :option = "[
                        {'name': 'Contact Form 7', 'value': 'wpcf7', disable:  integrations.cf7_status},  
                        {'name': 'Fluent Forms', 'value': 'fluent-forms', disable:  integrations.fluent_status},  
                        {'name': 'Forminator Forms', 'value': 'forminator', disable:  integrations.forminator_status},  
                        {'name': 'Gravity Forms', 'value': 'gravityforms', disable:  integrations.gravity_status},  
                    ]"
                    @tfhb-onchange="GetFormsData" 
                    
                />

                <!-- Time format -->
               <HbDropdown 
                    v-if = "meeting.questions_form_type != ''"
                    v-model="meeting.questions_form"  
                    required= "true" 
                    :label="$tfhb_trans['Select Form Types']"  
                    width="50" 
                    placeholder="Select Form Types"   
                    :option = "formsList" 
                   
                />

        </div>

        <div class="tfhb-submission-btn">
            <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="emit('update-meeting')">{{ $tfhb_trans['Save & Continue'] }} </button>
        </div>
        <!--Bookings -->
    </div>
</template>