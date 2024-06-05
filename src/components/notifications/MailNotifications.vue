<script setup> 
// Use children routes for the tabs 
import { ref, reactive, onBeforeMount, } from 'vue';
import { useRouter, RouterView,} from 'vue-router' 
import axios from 'axios' 
import Icon from '@/components/icon/LucideIcon.vue'
import { toast } from "vue3-toastify"; 


// import Form Field 
import HbSelect from '@/components/form-fields/HbSelect.vue'
import HbPopup from '@/components/widgets/HbPopup.vue';   
import HbText from '@/components/form-fields/HbText.vue' 
import HbSwitch from '@/components/form-fields/HbSwitch.vue';
import HbEditor from '@/components/form-fields/HbEditor.vue';
import Editor from 'primevue/editor';

//  Load Time Zone 
const skeleton = ref(false);


const props = defineProps([
    'title', 
    'label', 
    'data',
    'ispopup'
])
const emit = defineEmits(['update-notification', 'popup-open-control', 'popup-close-control']);

const meetingShortcode = ref([
    '{{meeting.title}}',
    '{{meeting.date}}', 
    '{{meeting.location}}', 
    '{{meeting.duration}}', 
    '{{meeting.time}}', 
    '{{host.name}}', 
    '{{host.email}}',  
    '{{attendee.name}}', 
    '{{attendee.email}}', 
])
const copyShortcode = (value) => { 
    //  copy to clipboard without navigator 
    const textarea = document.createElement('textarea');
    textarea.value = value;
    textarea.setAttribute('readonly', '');
    textarea.style.position = 'absolute';
    textarea.style.left = '-9999px';
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);
    
    // Show a toast notification or perform any other action
    toast.success(value + ' is Copied');
}

const closePopup = () => { 
    emit('popup-close-control', false)
}
</script>
<template>
    <!-- Single Notification  -->
    <div class="tfhb-notification-single tfhb-flexbox">
        <div class="tfhb-swicher-wrap  tfhb-flexbox">

            <!-- Checkbox swicher -->
            <HbSwitch v-model="props.data.status"  @change="emit('update-notification')"  :label="props.label "  /> 

        </div>

        <button class="tfhb-btn tfhb-edit flex-btn" @click="emit('popup-open-control')" ><Icon name="PencilLine" size="15" /> {{ $tfhb_trans['Edit'] }} </button>

        <HbPopup :isOpen="ispopup" @modal-close="closePopup" max_width="700px" name="first-modal">
            <template #header> 
                <h3>{{ title }}</h3>
                
            </template>

            <template #content>

                <HbSelect  
                    v-model="data.template"   
                    :label="$tfhb_trans['Select Template']"  
                    selected = "1"
                    placeholder="Select Template"  
                    :option = "{
                        'default': 'Default',
                        'default': 'Default',
                    }"  
                /> 

                <HbText  
                    v-model="props.data.form"   
                    type="email"   
                    :label="$tfhb_trans['From']"  
                    selected = "1"
                    :placeholder="$tfhb_trans['Enter From Email']"  
                /> 

                <HbText  
                    v-model="props.data.subject"  
                    required= "true"  
                    :label="$tfhb_trans['Subject']"  
                    selected = "1"
                    type = "text"
                    :placeholder="$tfhb_trans['Enter Mail Subject']"  
                /> 
 
                
                <div class="tfhb-single-form-field" style="width: 100%;"  >
                    <div class="tfhb-single-form-field-wrap tfhb-field-input">
                        <!--if has label show label with tag else remove tags  --> 
                        <label for="">{{$tfhb_trans['Mail Body']}}</label>  
                        <Editor 
                            v-model="props.data.body"  
                            :placeholder="$tfhb_trans['Mail Body']"    
                            editorStyle="height: 250px" 
                        />
                    </div> 
                </div> 
                <div class="tfhb-mail-shortcode tfhb-flexbox tfhb-gap-8"> 
                    <span  class="tfhb-mail-shortcode-badge"  v-for="(value, key) in meetingShortcode" :key="key" @click="copyShortcode(value)" >{{ value}}</span>

                </div>
                <button class="tfhb-btn boxed-btn" @click.stop="emit('update-notification')">{{ $tfhb_trans['Update'] }}</button>
            </template> 
        </HbPopup>
    </div>
    <!-- Single Integrations  -->

 
</template>