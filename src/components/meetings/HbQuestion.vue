<script setup>
import Icon from '@/components/icon/LucideIcon.vue'
const props = defineProps([
    'name',
    'modelValue',
    'required',
    'type',
    'label',
    'width',
    'subtitle',
    'placeholder',
    'description', 
    'disabled', 
])
const emit = defineEmits(['update:modelValue'])
</script>

<template>
    <div class="tfhb-question-box">
        <div class="tfhb-single-form-field" :class="name" 
            :style="{ 'width':  width ? 'calc('+(width || 100)+'% - 12px)' : '100%' }" 
            >
            <div class="tfhb-single-form-field-wrap">
                <label v-if="label" :for="name">{{ label }} <span  v-if="required == 'true'"> *</span> </label>
                <h4 v-if="subtitle">{{ subtitle }}</h4>
                <p v-if="description">{{ description }}</p>
                <div class="tfhb-flexbox tfhb-gap-16 tfhb-field-select">
                    <select 
                        :value="props.modelValue" 
                        :required= "required"
                        :id="name" 
                        @input="emit('update:modelValue', $event.target.value)" 
                        :type="type"
                        :placeholder="placeholder"
                    >  
                        <option value="">{{ placeholder }}</option>
                        <option v-for="(value, key) in option" :key="key" selected :value="key">{{ value }}</option>
                    </select> 
                    <div class="tfhb-swicher-wrap">
                        <!-- Checkbox swicher -->
                        <label class="switch">
                            <input id="swicher" true-value="1" type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <button class="question-edit-btn">
                        <Icon name="PencilLine" :width="16"/>
                    </button>
                </div>
                    
            </div> 
        </div>
    </div>
</template>

<style scoped>
</style> 