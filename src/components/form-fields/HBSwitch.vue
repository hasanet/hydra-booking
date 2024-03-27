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
</script>

<template>
    
    <div class="tfhb-single-form-field">
        <div class="tfhb-single-form-field-wrap">
            <div class="tfhb-swicher-wrap tfhb-flexbox">
                <!-- Checkbox swicher -->
                <label class="switch">
                    <input 
                        type="checkbox"
                        id="swicher" 
                        :v-model="props.modelValue" 
                        @change="name" 
                        :name="name"  
                        :required="required === 'true'">
                    <span class="slider"></span>
                </label>
                <label class="tfhb-schedule-swicher" v-if="label !=''" :for="name"> {{ label }} <span  v-if="required == 'true'"> *</span></label>
            </div>
        </div>
    </div>
</template>

