<script setup>
import {ref} from 'vue'
import Icon from '@/components/icon/LucideIcon.vue'
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
const counter_number = ref(1);
function CounterInc(){
    counter_number.value ++;
}
function CounterDec(){
    counter_number.value --;
}
</script>

<template>
    <div class="tfhb-counter-box" :class="name" 
            :style="{ 'width':  width ? 'calc('+(width || 100)+'% - 12px)' : '100%' }">
        <div class="tfhb-single-form-field">
            <div class="tfhb-single-form-field-wrap">
                <label v-if="label" :for="name">{{ label }} <span  v-if="required == 'true'"> *</span> </label>
                <h4 v-if="subtitle">{{ subtitle }}</h4>
                <div class="tfhb-flexbox tfhb-gap-0 tfhb-counter-wrap">
                    <div class="tfhb-counter tfhb-flexbox">
                        <div class="tfhb-dec" @click="CounterDec()">
                            <Icon name="Minus" />
                        </div>
                        <span>{{counter_number}} Booking</span>
                        <div class="tfhb-inc" @click="CounterInc()">
                            <Icon name="Plus" />
                        </div>
                    </div>
                    <select 
                        :value="props.modelValue" 
                        :required= "required"
                        :id="name" 
                        @input="emit('update:modelValue', $event.target.value)" 
                        :type="type"
                        :placeholder="placeholder"
                    >  
                        <option value="">Week</option>
                        <option value="Month">Month</option>
                        <option value="Years">Years</option>
                    </select> 
                </div>
                <p v-if="description">{{ description }}</p>
            </div> 
        </div>
    </div>
</template>

<style scoped>
</style> 