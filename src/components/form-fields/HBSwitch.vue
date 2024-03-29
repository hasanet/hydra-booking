<script setup>
const props = defineProps([
        'name',
        'modelValue',
        'required',
        'type',
        'label',
        'subtitle',
        'placeholder',
        'description', 
])
const emit = defineEmits(['update:modelValue'])

const checkedValue = () => {  
    if(props.modelValue == 1){ 
        emit('update:modelValue', 0);
    }else{
        emit('update:modelValue', 1);
    }
}
</script>

<template>
    
    <div class="tfhb-single-form-field">
        <div class="tfhb-single-form-field-wrap">
            <div class="tfhb-swicher-wrap tfhb-flexbox">
                <!-- Checkbox swicher -->
                <label class="switch">
                    <input 
                        type="checkbox"
                        :id="name"
                        true-value="1" 
                        :v-model="props.modelValue"  
                        
                        @click="checkedValue"
                        :name="name"   
                        >
                    <span class="slider"></span>
                </label>
                <label class="tfhb-schedule-swicher" v-if="label !=''" :for="name"> {{ label }} <span  v-if="required == 'true'"> *</span></label>
            </div>
        </div>
    </div>
</template>

