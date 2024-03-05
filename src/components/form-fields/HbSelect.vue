<script setup>
const props = defineProps([
    'modelValue',
    'name',
    'required',
    'type',
    'width',
    'label',
    'subtitle',
    'placeholder',
    'description', 
    'option'
])
const emit = defineEmits(['update:modelValue'])
</script>

<template> 
  <div class="tfhb-single-form-field" :class="name" 
      :style="{ 'width':  width ? 'calc('+(width || 100)+'% - 12px)' : '100%' }" 
    >
      <div class="tfhb-single-form-field-wrap tfhb-field-select">
              
          <label v-if="label !=''" :for="name">{{ label }} <span  v-if="required == 'true'"> *</span> </label>
          <h4 v-if="subtitle !=''">{{ subtitle }}</h4>
          <p v-if="description !=''">{{ description }}</p>
          
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
      </div>

  </div>
   
</template>

<style scoped>
</style> 