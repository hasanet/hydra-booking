<script setup>
const props = defineProps([
    'name',
    'modelValue',
    'required',
    'type',
    'label',
    'width',
    'description', 
])
const emit = defineEmits(['update:modelValue']);

const checkedValue = (e) => {   
    if(e.target.checked){
        emit('update:modelValue', 1);
    }else{
        emit('update:modelValue', 0);
    } 
}
</script>

<template>
  <div class="tfhb-single-form-field" :class="name" 
      :style="{ 'width':  width ? 'calc('+(width || 100)+'% - 12px)' : '100%' }" 
    >
    <div class="tfhb-single-form-field-wrap tfhb-field-checkbox">
        <div class="tfhb-flexbox tfhb-gap-8 tfhb-justify-normal">
            
            <label v-if="label" :for="name">
                <input 
                :id="name" 
                :v-model="props.modelValue"  
                @change="checkedValue" 
                :name="name"  
                :checked="props.modelValue == 1 ? true : false"
                type="checkbox"
                />     
                <span class="checkmark"></span>
                {{ label }} <span  v-if="required == 'true'"> *</span> 
            </label>
        </div>
    </div> 
  </div>
   
</template>

<style scoped>
</style> 