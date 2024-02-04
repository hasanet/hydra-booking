<script setup>
const props = defineProps([
    'modelValue',
    'name',
    'required',
    'type',
    'label',
    'subtitle',
    'placeholder',
    'description', 
    'option'
])
const emit = defineEmits(['update:modelValue'])
</script>

<template>
  <div class="thb-single-field" :class="name">
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

        <option v-for="item in option" :key="item.id" selected :value="item.id">{{ item.name }}</option>
    </select>
    
    
  </div>
   
</template>

<style scoped>
</style> 