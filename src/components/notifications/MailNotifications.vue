<script setup> 
// Use children routes for the tabs 
import { ref, reactive, onBeforeMount } from 'vue';
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
 

//  Load Time Zone 
const skeleton = ref(false);
const isPopup = ref(false);


const props = defineProps([
    'title', 
    'label', 
    'data'
])
const emit = defineEmits(['update-notification']);



</script>
<template>
    <!-- Single Notification  -->
    <div class="tfhb-notification-single tfhb-flexbox">
        <div class="tfhb-swicher-wrap  tfhb-flexbox">

            <!-- Checkbox swicher -->
            <HbSwitch v-model="props.data.status"  @change="emit('update-notification')"  :label="props.label "  /> 

        </div>

        <button class="tfhb-btn tfhb-edit flex-btn" @click="isPopup = true" ><Icon name="PencilLine" size="15px" /> Edit </button>

        <HbPopup :isOpen="isPopup" @modal-close="isPopup = false" max_width="700px" name="first-modal">
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

                <HbText  
                    v-model="props.data.body"  
                    required= "true"  
                    :label="$tfhb_trans['Mail Body']"  
                    selected = "1" 
                    :placeholder="$tfhb_trans['Mail Body']"  
                /> 
            <HbEditor />
                <button class="tfhb-btn boxed-btn" @click.stop="emit('update-notification')">{{ $tfhb_trans['Update'] }}</button>
            </template> 
        </HbPopup>
    </div>
    <!-- Single Integrations  -->

 
</template>