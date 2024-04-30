<script setup>
import HbText from '../form-fields/HbText.vue';
import HbSwitch from '../form-fields/HbSwitch.vue';
import HbSelect from '../form-fields/HbSelect.vue';
import HbDropdown from '@/components/form-fields/HbDropdown.vue'

const emit = defineEmits(['update:modelValue', 'question-add', 'question-cancel'])

const props = defineProps({
    questions_data: {
        type: Object,
        required: true
    }
});

</script>

<template>
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