<script setup>
const props = defineProps([
    'name',
    'modelValue',
    'required', 
    'label',
    'subtitle',
    'placeholder',
    'description', 
    'errors'
])
const emit = defineEmits(['update:modelValue'])
</script>

<template>
  <div class="tfhb-single-form-field" :class="name" :style="{ 'width':  width ? 'calc('+(width || 100)+'% - 12px)' : '100%' }">
    <div class="tfhb-single-form-field-wrap tfhb-field-textarea">
      <label v-if="label" :for="name">{{ label }} <span  v-if="required == 'true'"> *</span> </label>
      <h4 v-if="subtitle">{{ subtitle }}</h4>
      <p v-if="description">{{ description }}</p>
      
      <textarea 
        :value="props.modelValue" 
        :required= "required"
        :id="name" 
        :name="name" 
        @input="emit('update:modelValue', $event.target.value)"  
        :placeholder="placeholder"
        :class="errors ? 'tfhb-required' : ''"
        >{{ props.modelValue }}</textarea>
    </div>
  </div>
   
</template>

<style scoped>
</style> 