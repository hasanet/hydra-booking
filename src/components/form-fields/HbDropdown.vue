<script setup>
import {ref} from 'vue';
import Dropdown from 'primevue/dropdown';
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
    'option',
    'errors',
    'filter'
  ])

 
const emit = defineEmits(['update:modelValue', 'tfhb-onchange'])
const handleChange = (e) => {  
    emit('update:modelValue', e.value)
    emit('tfhb-onchange', e)
}
</script>

<template> 
  <div class="tfhb-single-form-field" :class="name" 
      :style="{ 'width':  width ? 'calc('+(width || 100)+'% - 12px)' : '100%' }" 
    >
      <div class="tfhb-single-form-field-wrap tfhb-field-dropdown"> 
          <label v-if="label" :for="name">{{ label }} <span  v-if="required == 'true'"> *</span> </label>
          <h4 v-if="subtitle">{{ subtitle }}</h4>
          <p v-if="description">{{ description }}</p> 
            <div>
                <Dropdown 
                    v-model="props.modelValue"  
                    @change="handleChange"   
                    :filter="filter == true ? true : false"
                    :options="option" 
                    optionLabel="name"
                    optionValue="value"
                    :placeholder="placeholder" 
                    :style="{ 'width': '100%' }"  
                />
            </div>
     
    
      </div>

  </div>
   
</template> 
<style scoped>
</style> 