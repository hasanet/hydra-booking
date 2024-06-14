<script setup>
import { reactive } from 'vue';

const props = defineProps( { 
    name : String,
    modelValue : [String, Number, Array],
    required : String,
    label : String,
    width : String,
    subtitle : String,
    description : String,
    groups : Boolean,
    options : Array,
    names : String

    
 
})
const emit = defineEmits(['update:modelValue']);
const groupsvalue = reactive([]);

const checkedValue = (e) => {   
    if(e.target.checked){
        emit('update:modelValue',  1);
    }else{
        emit('update:modelValue', 0);
    } 
}

const MulticheckedValue = (e) => {   
    if(e.target.checked){
        groupsvalue.push(e.target.value);
        emit('update:modelValue', groupsvalue);
    }else{
        groupsvalue.splice(groupsvalue.indexOf(e.target.value), 1);
        emit('update:modelValue', groupsvalue);
    }
}
</script>

<template>
  <div class="tfhb-single-form-field" :class="name" 
      :style="{ 'width':  width ? 'calc('+(width || 100)+'% - 12px)' : '100%' }" 
    > 
    <div class="tfhb-single-form-field-wrap tfhb-field-checkbox">
        <div class="tfhb-flexbox tfhb-gap-8 tfhb-justify-normal"> 
            <span style="width: 100%; font-size: 13px;" v-if="label && groups == true " :for="name">{{ label }} <span  v-if="required == 'true'"> *</span> </span>
            <h4 v-if="subtitle && groups == true">{{ subtitle }}</h4>
            <p v-if="description && groups == true">{{ description }}</p>
            
            <label v-if="label && groups != true" :for="name">
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
            <label v-else-if="groups == true && options" v-for="(value, key) in options"  :for="key+'-'+name"> 
                <input 
                :id="key+'-'+name" 
                :v-model="groupsvalue"  
                @change="MulticheckedValue" 
                :name="name"
                :value="value"
                type="checkbox"
                :checked="modelValue.includes(value)"
                />     
                <span class="checkmark"></span>
                {{ value }} 
            </label>
        </div>
    </div> 
  </div>
   
</template>

<style scoped>
</style> 