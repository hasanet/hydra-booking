<script setup>
import HbText from '../form-fields/HbText.vue';
import HbSwitch from '../form-fields/HbSwitch.vue';
import HbSelect from '../form-fields/HbSelect.vue';
import HbDropdown from '@/components/form-fields/HbDropdown.vue';
import Icon from '@/components/icon/LucideIcon.vue';

const emit = defineEmits(['update:modelValue', 'question-add', 'question-cancel'])

const props = defineProps({
    questions_data: {
        type: Object,
        required: true
    }
});

const AddNewOptions = () => { 
    props.questions_data.options.push('Option ' + (props.questions_data.options.length + 1));
}

const DeleteOptions = (index) => {
    props.questions_data.options.splice(index, 1);
}
 
</script>

<template> 
    <!-- if change  questions_data.type -->
    <HbDropdown 
        v-model="questions_data.type"
        required= "true" 
        :label="$tfhb_trans['Field type']"  
        :selected = "1"  
        :placeholder="$tfhb_trans['Field type']" 
        :option = "[
            {name: 'Text', value: 'text'}, 
            {name: 'Email', value: 'email'}, 
            {name: 'Textarea', value: 'textarea'}, 
            {name: 'Number', value: 'number'}, 
            {name: 'Phone', value: 'phone'}, 
            {name: 'Radio', value: 'radio'}, 
            {name: 'Select', value: 'select'},  
            {name: 'Checkbox', value: 'checkbox'}, 
            {name: 'Date', value: 'date'}
        ]" 
    />

    <HbText  
        v-model="questions_data.label"
        required= "true"  
        :label="$tfhb_trans['Level']"  
        :placeholder="$tfhb_trans['Type level here']" 
    /> 
    <HbText  
        v-model="questions_data.placeholder"
        required= "true"  
        :label="$tfhb_trans['Placeholder']"  
        :placeholder="$tfhb_trans['Type Placeholder here']" 
    /> 

    <!-- Options -->
    <div 
        v-if="
            questions_data.type == 'radio' || 
            questions_data.type == 'select' ||
            questions_data.type == 'multi-select' ||
            questions_data.type == 'checkbox'  
        " 
        class="tfhb-single-form-field"   :style="{ 'width': '100%' }" 
    > 
        <div class="tfhb-single-form-field-wrap tfhb-field-options"> 
            <label   for="name">Options <span  > *</span> </label>
            <div  class="tfhb-options-fields tfhb-flexbox tfhb-gap-16" v-for="(option, index) in questions_data.options" :key="index"> 
                <input 
                    v-model="questions_data.options[index]"
                    type="text"
                    placeholder="Option 1"
                />
                <button class="tfhb-btn tfhb-flexbox tfhb-gap-8"  @click="DeleteOptions(index)">
                    <Icon name="Trash" :width="20"/> 
                </button>   
            </div>
            <button class="tfhb-btn tfhb-flexbox tfhb-gap-8" @click="AddNewOptions" >
                <Icon name="PlusCircle" :width="20"/>
                Add New Option
            </button>
        </div> 
    </div>
    <!-- Options -->

    <HbSwitch  
        v-model="questions_data.required"
        :label="$tfhb_trans['Required']"  
    /> 

    <div class="tfhb-action-btn tfhb-full-width tfhb-flexbox tfhb-gap-16 tfhb-justify-normal">
        <button class="tfhb-btn secondary-btn" @click="emit('question-cancel')">Cancel</button>
        <button class="tfhb-btn boxed-btn" @click="emit('question-add')">Save</button>
    </div>
</template>

<style scoped>
</style> 