<script setup>  
import Editor from 'primevue/editor';
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
const changeEditor = (value) => {
  alert(1);
    emit('update:modelValue', value)
}
</script>

<template>
  <div class="tfhb-single-form-field" :class="name" 
      :style="{ 'width':  width ? 'calc('+(width || 100)+'% - 12px)' : '100%' }" 
    >
    <div class="tfhb-single-form-field-wrap tfhb-field-input">
         <!--if has label show label with tag else remove tags  -->
         {{ props.modelValue }}
        <label v-if="label" :for="name">{{ label }} <span  v-if="required == 'true'"> *</span> </label>
        <h4 v-if="subtitle">{{ subtitle }}</h4>
        <p v-if="description">{{ description }}</p> 
        <Editor  
          :v-model="props.modelValue"  
          :required= "required"
          :name= "name"
          :id="name" 
          @change="changeEditor" 
          :type="type || 'text'"
          :placeholder="placeholder"
          :disabled="disabled"
          editorStyle="height: 250px" 
        />
    </div> 
  </div>
   
</template>

<style scoped>
</style> 