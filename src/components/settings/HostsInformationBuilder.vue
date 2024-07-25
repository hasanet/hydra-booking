<script setup>

import { ref, reactive, onBeforeMount, } from 'vue'; 
import { useRouter, RouterView,} from 'vue-router' 
import HbQuestion from '@/components/widgets/HbQuestion.vue';
import Icon from '@/components/icon/LucideIcon.vue';
import HbQuestionForm from '@/components/widgets/HbQuestionForm.vue';
import HbPopup from '@/components/widgets/HbPopup.vue';
import HbSwitch from '@/components/form-fields/HbSwitch.vue';


const informationPopup = ref(false);
const emit = defineEmits(["update-meeting", "limits-frequency-add"]); 
const props = defineProps({
    hostsSettings : {
        type: Object,
        required: true
    }
}); 

// Popup Open.
const QuestionPopupOpen = () => {
    props.hostsSettings.others_information.fields.push({
        label: '',
        type:'',
        placeholder:'',
        options: ['Option 1', 'Option 2'],
        required: 1
    });
    const lastIndexOfQuestion = props.hostsSettings.others_information.fields.length - 1;
    questions_data.key = lastIndexOfQuestion;
    questions_data.label = '';
    questions_data.type = '';
    questions_data.placeholder = '';
    questions_data.options = ['Option 1', 'Option 2'];
    questions_data.required = '';
    informationPopup.value = true;
}

// Popup Saved addExtraQuestion
function addExtraInformation(){  
    props.hostsSettings.others_information.fields[questions_data.key].label = questions_data.label
    props.hostsSettings.others_information.fields[questions_data.key].type = questions_data.type
    props.hostsSettings.others_information.fields[questions_data.key].placeholder = questions_data.placeholder

    // if type is radio, select, multi-select, checkbox then options will be added
    if (questions_data.type === 'radio' || questions_data.type === 'select' || questions_data.type === 'multi-select' || questions_data.type === 'checkbox') {
        props.hostsSettings.others_information.fields[questions_data.key].options = questions_data.options
    }else{
        props.hostsSettings.others_information.fields[questions_data.key].options = []
    }
    
    props.hostsSettings.others_information.fields[questions_data.key].required = questions_data.required
    informationPopup.value = false;
}

// Edit Extra Question
function EditExtraInformation(key){ 
    props.hostsSettings.others_information.fields.forEach((question, qkey) => {
        if (qkey === key) {
            questions_data.key = key;
            questions_data.label = question.label;
            questions_data.type = question.type;
            questions_data.placeholder = question.placeholder;
            questions_data.options = question.options;
            questions_data.required = question.required;
            informationPopup.value = true;
        }
    });
}

 

// Extra Qestion Data
const questions_data =  reactive({});
 
</script>

<template>   
    <div class="tfhb-admin-title" >
        <h2 class="tfhb-flexbox tfhb-gap-8 tfhb-justify-normal">{{ $tfhb_trans['Information Builder'] }}  
            <HbSwitch 
                v-model="hostsSettings.others_information.enable_others_information" 
            />
        </h2> 
        <p>{{ $tfhb_trans['Date and Time Settings'] }}</p>
    </div>
    <div v-if="hostsSettings.others_information.enable_others_information "  class="tfhb-admin-card-box  tfhb-gap-24 tfhb-m-0"  >   
        <div v-if="hostsSettings.others_information.fields !=''"  class="tfhb-host-info-builder-wrap  tfhb-mb-16" >
            <HbQuestion 
                :question_value="hostsSettings.others_information.fields"
                :skip_remove="-1"
                @question-edit="EditExtraInformation"
                @question-remove="props.hostsSettings.others_information.fields.splice(key, 1)"
            />
        </div>
        
        <button class="tfhb-btn tfhb-flexbox tfhb-gap-8"  @click="QuestionPopupOpen()" >
            <Icon name="PlusCircle" :width="20"/>
            {{ $tfhb_trans['Add more Information'] }}
        </button>

        <HbPopup :isOpen="informationPopup" @modal-close="informationPopup = false" max_width="400px" name="first-modal">
            <template #header> 
                <h3>{{ $tfhb_trans['Add Information for Hosts'] }}</h3>
            </template>

            <template #content>  
                <HbQuestionForm 
                    :questions_data="questions_data"
                    @question-add="addExtraInformation"
                    @question-cancel="informationPopup = false"
                />
            </template> 
        </HbPopup>
    </div>
        
</template>

<style scoped>
</style> 