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

const checkedValue = (e) => {   
    if(e.target.checked){
        emit('update:modelValue', 1);
    }else{
        emit('update:modelValue', 0);
    } 
 
}
</script>

<template>
    
    <div class="tfhb-single-form-field">
        <div class="tfhb-single-form-field-wrap">
            <div class="tfhb-swicher-wrap tfhb-flexbox">
                <!-- Checkbox swicher -->
                <!--   @change="checkedValue" -->
                <label class="switch">
                    <input 
                        type="checkbox"
                        :id="name" 
                        :v-model="props.modelValue"  
                        @change="checkedValue" 
                        :name="name"  
                        :checked="props.modelValue == 1 ? true : false"
                        >
                    <span class="slider"></span>
                </label>
                <label class="tfhb-schedule-swicher" v-if="label !=''" :for="name"> {{ label }} <span  v-if="required == 'true'"> *</span></label>
            </div>
        </div>
    </div>
</template>

