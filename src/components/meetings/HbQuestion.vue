<script setup>
import Icon from '@/components/icon/LucideIcon.vue'
import HbText from '../form-fields/HbText.vue';
import HbSwitch from '../form-fields/HbSwitch.vue';
const props = defineProps([
    'name',
    'modelValue',
    'width',
    'question_value',
])
const emit = defineEmits(['update:modelValue', 'question-edit', 'question-remove'])
</script>

<template>
    <div class="tfhb-question-box">
        <div class="tfhb-single-form-field tfhb-flexbox tfhb-gap-24" :style="{ 'width': '100%' }">

            <div class="tfhb-single-form-field-wrap tfhb-full-width" v-for="(question, key)  in question_value" :key="key">
                <label>{{ question.label }}</label>
                <div class="tfhb-flexbox tfhb-gap-16 tfhb-field-select">
                    <HbText  
                        v-model="question.type"
                        disabled="disabled"
                    /> 
                    <HbSwitch 
                        v-model="question.required"
                    />
                    <button class="question-edit-btn">
                        <Icon name="PencilLine" :width="16" @click="emit('question-edit', key)"/>
                    </button>
                    <button class="question-edit-btn" v-if="key > 2" @click="emit('question-remove', key)">
                        <Icon name="X" :width="16"/>
                    </button>
                </div>
                    
            </div> 

        </div>
    </div>
</template>

<style scoped>
</style> 