<template>
    <div>
        <label>{{title}} :</label>
        <!-- <el-input :type="type"  placeholder="Please input" /> -->
        <input v-model="vvvmodel" :type="type">
    </div>
</template>

<script>
export default {
    name: 'Input',
    props: ['title','type', 'vvvmodel']
}
console.log(props)
</script>

<style scoped>
/* Add your custom styles here */
</style>
