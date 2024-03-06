<script setup>

import {ref} from 'vue';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

const props = defineProps([
    'name',
    'modelValue',
    'fieldClass',
    'required',
    'type',
    'label',
    'width',
    'subtitle',
    'placeholder',
    'description', 
])
const emit = defineEmits(['update:modelValue'])

// Read more at https://flatpickr.js.org/options/
const config = ref({ 
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    defaultDate: "13:45",
    // mode: "range" 
});
</script>

<template>
  <div class="tfhb-single-form-field " :class="[name,fieldClass]" 
      :style="{ 'width':  width ? 'calc('+(width || 100)+'% - 12px)' : '100%' }" 
    >
    <div class="tfhb-single-form-field-wrap tfhb-field-date">
         <!--if has label show label with tag else remove tags  -->
         
        <label v-if="label !=''" :for="name">{{ label }} <span  v-if="required == 'true'"> *</span> </label>
        <h4 v-if="subtitle !=''">{{ subtitle }}</h4>
        <p v-if="description !=''">{{ description }}</p>
        
        <flatPickr :value="props.modelValue" :config="config" />
        <!-- <input 
          :value="props.modelValue" 
          :required= "required"
          :name= "name"
          :id="name" 
          @input="emit('update:modelValue', $event.target.value)" 
          :type="type"
          :placeholder="placeholder"
          
        />  -->
             
    </div> 
  </div>
   
</template>

<style scoped>
</style> 