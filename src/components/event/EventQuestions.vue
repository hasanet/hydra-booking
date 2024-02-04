<script setup>
// Use children routes for the tabs
import HbText from  '@/components/form-fields/HbText.vue'
import HbTextarea from  '@/components/form-fields/HbTextarea.vue'
import HbSelect from '@/components/form-fields/HbSelect.vue';
import HBSwitch from '@/components/form-fields/HBSwitch.vue';
</script>
<template>
    <h1>EventQuestions</h1>
    <div>
        <HbText 
            v-model="title" 
            type="text" 
            required= "true" 
            label="Title"   
            placeholder="Enter title"  
        />
        <HbTextarea 
            v-model="description" 
            type="text" 
            required= "true" 
            label="Description"   
            placeholder="Enter description"  
        />

        <HbSelect 
            v-model="category"  
            required= "true" 
            label="Category" 
            selected = "1"
            placeholder="Enter category"  
            :option = "[
                    {id: 1, name: 'Ram'},
                    {id: 2, name: 'Shyam'},
                    {id: 3, name: 'Hari'},
                    {id: 4, name: 'Krishna'},
                    {id: 5, name: 'Balram'},
                    {id: 6, name: 'Arjun'},
                    ]"
        />
        <HBSwitch 
            v-model="status" 
            type="checkbox" 
            required= "true" 
            label="Description"   
            placeholder="Enter description" 
        />

    </div>
</template>

 
<style scoped>
/* Your component styles go here */
</style>

