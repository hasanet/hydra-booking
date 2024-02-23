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
])
const emit = defineEmits(['update:modelValue'])
</script>

<template>
  <div class="tfhb-single-form-field" :class="name" :style="{ 'width': width + '%' }">
    <label v-if="label !=''" :for="name">{{ label }} <span  v-if="required == 'true'"> *</span> </label>
    <h4 v-if="subtitle !=''">{{ subtitle }}</h4>
    <p v-if="description !=''">{{ description }}</p>

    <input 
      :value="props.modelValue" 
      :required= "required"
      :name= "name"
      :id="name" 
      @input="emit('update:modelValue', $event.target.value)" 
      :type="type"
      :placeholder="placeholder"
      
    />
  </div>
   
</template>

<style scoped>
</style> 