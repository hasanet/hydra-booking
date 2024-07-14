<script setup>
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
    'limit',
    'disabled', 
    'errors'
])
const emit = defineEmits(['update:modelValue'])
</script>

<template>
  <div class="tfhb-single-form-field" :class="name" 
      :style="{ 'width':  width ? 'calc('+(width || 100)+'% - 12px)' : '100%' }" 
    >
    <div class="tfhb-single-form-field-wrap tfhb-field-input">
         <!--if has label show label with tag else remove tags  -->
         
        <label v-if="label" :for="name">{{ label }} <span  v-if="required == 'true'"> *</span> </label>
        <h4 v-if="subtitle">{{ subtitle }}</h4>
        <p v-if="description">{{ description }}</p>
        
        <input 
          :value="props.modelValue" 
          :required= "required"
          :name= "name"
          :id="name" 
          @input="emit('update:modelValue', $event.target.value)" 
          :type="type || 'text'"
          :placeholder="placeholder"
          :disabled="disabled"
          :class="errors ? 'tfhb-required' : ''"
          :min="limit"
        /> 
             
    </div> 
  </div>
   
</template>

<style scoped>
</style> 