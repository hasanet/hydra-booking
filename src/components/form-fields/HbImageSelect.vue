<script setup>
const props = defineProps([
    'name',
    'modelValue',
    'width',
    'option'
])
const emit = defineEmits(['update:modelValue']);
const ImagecheckedValue = (e) => {    
  if(e.target.checked){
        emit('update:modelValue',   e.target.value);
    }else{
        emit('update:modelValue', 0);
    } 
}
</script>

<template>

<ul class="tfhb-flexbox" v-if="option">
    <li v-for="(soption, key) in option" :key="key">
        <input 
        type="radio" 
        :id="soption.name" 
        :name="name" 
        :value="soption.name"
        @change="ImagecheckedValue"
        :checked="props.modelValue == soption.name ? true : false"
        />
        <label :for="soption.name">
            <div class="image-box" :style="{'background': soption.value}"></div>
            <h3>{{soption.name}}</h3>
        </label>
    </li>
</ul>
   
</template>

<style scoped>
</style> 