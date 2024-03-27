<script setup>
import {ref} from 'vue'
import HbQuestion from '@/components/meetings/HbQuestion.vue'
import Icon from '@/components/icon/LucideIcon.vue'
import HbText from '@/components/form-fields/HbText.vue'
import HbSelect from '@/components/form-fields/HbSelect.vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue'

const QuestionPopup = ref(false);
function QuestionPopupOpen(){
    QuestionPopup.value = true;
}
function QuestionPopupClose(){
    QuestionPopup.value = false;
}
</script>

<template>
    <div class="meeting-create-details tfhb-gap-24">

        <div class="tfhb-admin-card-box tfhb-gap-24">  

            <HbQuestion 
                required= "true" 
                :label="$tfhb_trans['Name']"  
                :selected = "1"
                :placeholder="$tfhb_trans['Type your name']"   
                :option = "{'12_hours': '30 minutes', '24_hours': '10 minutes'}" 
            />
            <HbQuestion 
                required= "true" 
                :label="$tfhb_trans['Email']"  
                :selected = "1"
                :placeholder="$tfhb_trans['Type your email']"   
                :option = "{'12_hours': '30 minutes', '24_hours': '10 minutes'}" 
            />

            <div class="tfhb-add-new-question tfhb-flexbox tfhb-gap-8" @click="QuestionPopupOpen()">
                <Icon name="PlusCircle" :width="20"/>
                Add more questions
            </div>

            <div class="tfhb-question-popup tfhb-p-24" v-if="QuestionPopup">
                <div class="tfhb-flexbox tfhb-gap-24">
                    <div class="question-header tfhb-flexbox tfhb-full-width">
                        <h3>Add Question for Attendee</h3>
                        <div class="tfhb-question-times" @click="QuestionPopupClose()">
                            <Icon name="X" :width="20"/>
                        </div>
                    </div>
                    
                    <HbSelect 
                        required= "true" 
                        :label="$tfhb_trans['Field type']"  
                        :selected = "1"
                        :placeholder="$tfhb_trans['Field type']" 
                        :option = "{'12_hours': 'Textarea', '24_hours': 'Text'}" 
                    />

                    <HbText  
                        required= "true"  
                        :label="$tfhb_trans['Level']"  
                        :placeholder="$tfhb_trans['Type level here']" 
                    /> 
                    <HbSwitch  
                        :label="$tfhb_trans['Required']"  
                    /> 
                    <div class="tfhb-action-btn tfhb-full-width tfhb-flexbox tfhb-gap-16 tfhb-justify-normal">
                        <button class="tfhb-btn secondary-btn" @click="QuestionPopupClose()">Cancel</button>
                        <button class="tfhb-btn boxed-btn">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="UpdateGeneralSettings">{{ $tfhb_trans['Save & Continue'] }} </button>
        <!--Bookings -->
    </div>
</template>