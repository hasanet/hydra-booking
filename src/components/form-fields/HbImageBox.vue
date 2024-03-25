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
    // on clicked upload image button
    // click to input imagte file
    document.querySelector('input[type="file"]#'+props.name).click();


}
const imageChange = (e) => {
    // console.log(e.target.files[0]);
    // on change get the image and display it into image tag 
    const file = e.target.files[0]; 
    // selected image need to display .display
    const img = document.querySelector('img.'+props.name+'_display');
    img.src = URL.createObjectURL(file);
}
</script>

<template>
  <div class="tfhb-single-form-field tfhb-flexbox" :class="name" 
      :style="{ 'width':  width ? 'calc('+(width || 100)+'% - 12px)' : '100%' }" 
    >
    <div class="tfhb-single-form-field-wrap tfhb-flexbox">
        <div class="tfhb-field-image" > 
            <img :class="name+'_display'"  src="https://via.placeholder.com/100" alt="Hosts Avatar">
            <button class="tfhb-image-btn tfhb-btn" @click="UploadImage">Change</button> 
            <input 
              @change="imageChange"
              :value="props.modelValue" 
              :required= "required"
              :name= "name"
              :id="name" 
              @input="emit('update:modelValue', $event.target.value)" 
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