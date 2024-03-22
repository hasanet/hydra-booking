<script setup>
import { reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue'
// Get Current Route url
const currentRoute = useRouter().currentRoute.value.path;

 
</script>

<template>
 <h1>integrations</h1>
</template>


 