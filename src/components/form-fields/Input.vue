<script setup>
const props = defineProps([
    'modelValue',
    'ftype'
])
const emit = defineEmits(['update:modelValue'])
</script>

<template>
    <input :value="props.modelValue" @input="emit('update:modelValue', $event.target.value)" :type="ftype"
  />
</template>

<style scoped>
</style> 