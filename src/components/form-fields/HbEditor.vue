<script setup>
import Editor from '@tinymce/tinymce-vue';
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
  <div class="tfhb-single-form-field" :class="name" 
      :style="{ 'width':  width ? 'calc('+(width || 100)+'% - 12px)' : '100%' }" 
    >
    <div class="tfhb-single-form-field-wrap tfhb-field-input">
         <!--if has label show label with tag else remove tags  -->
         
        <label v-if="label" :for="name">{{ label }} <span  v-if="required == 'true'"> *</span> </label>
        <h4 v-if="subtitle">{{ subtitle }}</h4>
        <p v-if="description">{{ description }}</p>
        
        <editor
            api-key="no-api-key"
            :init="{
                height: 500,
                menubar: false,
                plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
                ],
                toolbar:
                'undo redo | formatselect | bold italic backcolor | \
                alignleft aligncenter alignright alignjustify | \
                bullist numlist outdent indent | removeformat | help'
            }"
        />
             
    </div> 
  </div>
   
</template>

<style scoped>
</style> 