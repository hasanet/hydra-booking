<script setup>
// Use children routes for the tabs
import { reactive, onBeforeMount } from 'vue';
import HbText from  '@/components/form-fields/HbText.vue'
import HbTextarea from  '@/components/form-fields/HbTextarea.vue'
import HbSelect from '@/components/form-fields/HbSelect.vue';
import HBSwitch from '@/components/form-fields/HBSwitch.vue';
//  get the event from the parent route 
import { useRoute } from 'vue-router'
const props = defineProps([
    'events', 
])

//  stored props value  in reactive data  
// const data = reactive(
//     {
//         title: 'allah mohan',
//         description: '',
//         duration: '',
//         location: '',
//         host: '',
//         status: '',
//     }
// )

</script>
<template>
    <h1>{{events}}</h1>
    <div>
        <HbText 
            v-model="events.data.title" 
            type="text" 
            required= "true" 
            label="Meeting title "   
            placeholder="Type meeting title"  
        />
        <HbTextarea 
            v-model="events.data.description"  
            label="Description"
            placeholder="Type description"  
        />

        <HbSelect 
            v-model="events.data.duration"  
            required= "true" 
            label="Duration"
            placeholder="Enter category"  
            :option = "[
                    {id: 5, name: '5 Minutes'},
                    {id: 10, name: '10 Minutes'},
                    {id: 20, name: '20 Minutes'},
                    {id: 30, name: '30 Minutes'},
                    {id: 40, name: '40 Minutes'},
                    {id: 50, name: '50 Minutes'},
                    {id: 60, name: '60 Minutes'},
                ]"
        />
        <HbSelect 
            v-model="events.data.location"  
            required= "true" 
            label="Location"  
            placeholder="Enter category"  
            :option = "[
                    {id: 'online', name: 'online'}, 
                    {id: 'offline', name: 'Offline'}, 
                ]"
        />
        <HbSelect 
            v-model="events.data.host"  
            required= "true" 
            label="Location"  
            placeholder="Select host"  
            :option = "[
                    {id: 'sydur', name: 'Sydur'}, 
                    {id: 'rahman', name: 'Rahman'}, 
                ]"
        />
     
       
        
    </div>
</template>

 
<style scoped>
/* Your component styles go here */
</style>

