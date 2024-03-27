<script setup>
import { ref } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import Header from '@/components/Header.vue';
</script>
<template>

<!-- {{ tfhbClass }} -->
<div class="tfhb-admin-meetings">
    <Header title="Meetings" />
    
    <router-view />
</div> 
</template>

<style scoped>

</style>