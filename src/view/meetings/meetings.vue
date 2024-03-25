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
.tfhb-meetings-list-content {
    padding: 24px;
    margin-top: 24px;
    border-radius: 16px;
    border: 1px #F6EEF2;
    background: #FFF;
    margin-right: 16px;
}
</style>