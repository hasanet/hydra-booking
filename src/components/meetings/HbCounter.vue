<script setup>
import {ref} from 'vue'
import Icon from '@/components/icon/LucideIcon.vue'
import HbDropdown from '@/components/form-fields/HbDropdown.vue'
const props = defineProps([
    'name',
    'modelValue',
    'required',
    'type',
    'label',
    'counterLabel',
    'width',
    'subtitle',
    'counter_value',
    'description', 
    'repater', 
    'limit'
])
const emit = defineEmits(['update:counter_value','limits-frequency-add', 'limits-frequency-remove'])
const counter_number = ref(1);
function CounterInc(key){
    props.counter_value[key].limit ++;
}
function CounterDec(key){
    if ( props.limit && props.counter_value[key].limit > props.limit ) {
        props.counter_value[key].limit --;
    }

    if ( !props.limit ) {
        props.counter_value[key].limit --;
    }
}

</script>

<template>
    <div class="tfhb-counter-box" :class="name" 
            :style="{ 'width':  width ? 'calc('+(width || 100)+'% - 12px)' : '100%' }">
        <div class="tfhb-single-form-field">
            <div class="tfhb-single-form-field-wrap">
                <label v-if="label" :for="name">{{ label }} <span  v-if="required == 'true'"> *</span> </label>
                <h4 v-if="subtitle">{{ subtitle }}</h4>
                <div class="tfhb-flexbox tfhb-gap-0 tfhb-counter-wrap tfhb-flexbox-nowrap" v-for="(counter, key)  in counter_value" :key="key">
                    <div class="tfhb-counter tfhb-flexbox">
                        <div class="tfhb-dec" @click="CounterDec(key)">
                            <Icon name="Minus" />
                        </div>

                        <span>{{ counter.limit = counter.limit}} {{ counterLabel }}</span>
                        <div class="tfhb-inc" @click="CounterInc(key)">
                            <Icon name="Plus" />
                        </div>
                    </div>
                    
                    <HbDropdown 
                        v-model="counter.times"  
                        required= "true" 
                        width="50"
                        :selected = "1"   
                        placeholder="Select" 
                        :option = "[ 
                            {'name': 'days', 'value': 'days'},  
                            {'name': 'weeks', 'value': 'weeks'},  
                            {'name': 'months', 'value': 'months'},  
                            {'name': 'years', 'value': 'years'},
                        ]"
                    /> 

                    <div v-if="repater && key == 0" class="tfhb-availability-schedule-clone-single">
                        <button class="tfhb-availability-schedule-btn" @click="emit('limits-frequency-add')"><Icon name="Plus" size="20" /> </button> 
                    </div>
                    <div v-if="repater && key != 0" class="tfhb-availability-schedule-clone-single">
                        <button class="tfhb-availability-schedule-btn" @click="emit('limits-frequency-remove', key)"><Icon name="X" size="20" /> </button> 
                    </div>
                </div>
                <p v-if="description">{{ description }}</p>
            </div> 
        </div>
    </div>
</template>

<style scoped>
</style> 