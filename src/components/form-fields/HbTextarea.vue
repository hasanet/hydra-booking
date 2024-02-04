<script setup>
const props = defineProps([
    'name',
    'modelValue',
    'required', 
    'label',
    'subtitle',
    'placeholder',
    'description', 
])
const emit = defineEmits(['update:modelValue'])
</script>

<template>
  <div class="thb-single-field" :class="name">
    <label v-if="label !=''" :for="name">{{ label }} <span  v-if="required == 'true'"> *</span> </label>
    <h4 v-if="subtitle !=''">{{ subtitle }}</h4>
    <p v-if="description !=''">{{ description }}</p>
    
    <textarea 
      :value="props.modelValue" 
      :required= "required"
      :id="name" 
      @input="emit('update:modelValue', $event.target.value)"  
      :placeholder="placeholder"
      >{{ props.modelValue }}</textarea>
    
  </div>
   
</template>

<style scoped>
</style> 