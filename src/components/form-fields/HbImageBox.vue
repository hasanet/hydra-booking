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
    'disabled', 
])
const emit = defineEmits(['update:modelValue'])

const UploadImage = () => {  
 
      wp.media.editor.send.attachment = (props, attachment) => { 
        // set the image url to the input field
        imageChange(attachment);
      }; 
      
      wp.media.editor.open();

}
const imageChange = (attachment) => { 
  console.log(attachment);
    const image = document.querySelector('.'+props.name+'_display'); 
    image.src = attachment.url; 
    // props.modelValue = attachment.url;
    emit('update:modelValue', attachment.url) 
}
</script>

<template> 
  <div class="tfhb-single-form-field tfhb-flexbox" :class="name" 
      :style="{ 'width':  width ? 'calc('+(width || 100)+'% - 12px)' : '100%' }" 
    >
    <div class="tfhb-single-form-field-wrap tfhb-flexbox">
        <div class="tfhb-field-image" > 
            <img  :class="name+'_display'"  :src="props.modelValue">
            <button class="tfhb-image-btn tfhb-btn" @click="UploadImage">Change</button> 
            <input  
              :value="props.modelValue" 
              :required= "required"
              :name= "name"
              :id="name"  
              :type="type"
              :placeholder="placeholder"  
            /> 

        </div>
        <div class="tfhb-image-box-content">  
          <h4 v-if="label !=''" :for="name">{{ label }} <span  v-if="required == 'true'"> *</span> </h4>
          <p v-if="description !=''">{{ description }}</p>
        </div>
    </div> 
  </div>
   
</template>

<style scoped>
</style> 