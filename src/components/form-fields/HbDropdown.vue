<script setup>
import {ref} from 'vue';
import Dropdown from 'primevue/dropdown';
import Icon from '@/components/icon/LucideIcon.vue'
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
    'filter',
    'optionType',
    'country',
    'parent_key',
    'single_key',
    'selected',
    'icon',
  ])

 
const emit = defineEmits(['update:modelValue', 'tfhb-onchange', 'add-change', 'add-click', 'tfhb_start_change', 'tfhb_body_value_change'])
const handleChange = (e) => {  
    emit('update:modelValue', e.value)
    emit('tfhb-onchange', e)
    emit('add-change', e)
    emit('tfhb_start_change', props.parent_key, props.single_key, e.value)
    emit('tfhb_body_value_change', props.single_key, e.value);
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
            <div v-if="country">
                <Dropdown 
                      v-model="props.modelValue"  
                      @change="handleChange"   
                      :filter="filter == true ? true : false"
                      :options="option"  
                      :overlay="false"
                      :placeholder="placeholder" 
                      :style="{ 'width': '100%' }"  
                
                >
                  <template #value="slotProps">
                      <div v-if="slotProps.value" class="flex align-items-center">
                          <img :alt="slotProps.value.label" :src="`https://flagsapi.com/${slotProps.value.value}/flat/64.png`" style="width: 18px" />
                          <div>{{ slotProps.value.name }}</div>
                      </div>
                      <span v-else>
                          {{ slotProps.placeholder }}
                      </span>
                  </template>
                  <template #option="slotProps">
                      <div class="flex align-items-center">
                          <img :alt="slotProps.option.label"  :src="`https://flagsapi.com/${slotProps.value.value}/flat/64.png`"  style="width: 18px" />
                          <div>{{ slotProps.option.name }}</div>
                      </div>
                  </template>
              </Dropdown>
            </div>

            <div v-else>
                <Dropdown 
                    v-if="optionType == 'array'"
                    v-model="props.modelValue"  
                    @change="handleChange"   
                    :filter="filter == true ? true : false"
                    :options="option"
                    :placeholder="placeholder"  
                    :optionDisabled="disabled"
                    :style="{ 'width': '100%' }"  
                    :selected="selected"
                >
                <template v-if="props.icon" #dropdownicon>

                    <Icon :name="props.icon" size="16" />
                </template>
                </Dropdown>
                <Dropdown 
                    v-else
                    v-model="props.modelValue"  
                    @change="handleChange"   
                    :filter="filter == true ? true : false"
                    :options="option" 
                    optionLabel="name"
                    optionValue="value"
                    optionDisabled="disable"
                    :placeholder="placeholder" 
                    :style="{ 'width': '100%' }"  
                    @click="emit('add-click')"
                    :class="errors ? 'tfhb-required' : ''"
                >
                <template v-if="props.icon" #dropdownicon>

                    <Icon :name="props.icon" size="16" />
                </template>
                </Dropdown>
            </div>
     
    
      </div>

  </div>
   
</template> 
<style scoped>
</style> 