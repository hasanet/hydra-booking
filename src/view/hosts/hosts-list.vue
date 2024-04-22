<script setup>
import { reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue'
import { User } from 'lucide-vue-next';
import { toast } from "vue3-toastify";

const emit = defineEmits(["delete-host"]); 
const props = defineProps({
    host_list: {
        type: Object,
        required: true
    },
    host_skeleton: {
        type: Boolean,
        required: true
    }
});

</script>
<template>

    <div class="tfhb-hosts-list-content" >
        <div class="tfhb-hosts-list-wrap tfhb-flexbox" :class="{ 'tfhb-skeleton': host_skeleton }"> 
            <!-- Single Hosts -->
            <div   v-for="(host, key) in host_list"  class="tfhb-single-hosts"> 
                <div class="tfhb-single-hosts-wrap ">
                    <span class="tfhb-hosts-status" v-if="host.status == 1">Active</span> 
                    <span class="tfhb-hosts-status" v-else>Inactive</span>

                    <div class="tfhb-hosts-info tfhb-flexbox">
                        <div class="hosts-avatar" v-if="host.avatar !='' " >
                            <img :src="host.avatar" alt="Hosts Avatar">
                        </div>
                        <div class="hosts-details">
                            <h3>{{ host.first_name }} {{ host.last_name }}</h3> 
                        </div>
                    </div>
                    <hr>
                    <div class="tfhb-hosts-info">  
                         <span class="tfhb-info-icon-text tfhb-flexbox" v-if="host.email !=''"> <Icon name="Mail" size="20" />{{ host.email }}</span>
                         <span class="tfhb-info-icon-text tfhb-flexbox" v-if="host.phone_number !=''"> <Icon name="Phone" size="20" />{{ host.phone_number }}</span>
                    </div>
                    <!-- <button class="tfhb-single-hosts-action"><Icon name="ListCollapse" size="20" /></button> -->
                    <div class="tfhb-single-hosts-action tfhb-dropdown">
                        <Icon name="ListCollapse" size="20px" /> 
                        <div class="tfhb-dropdown-wrap"> 
                            <!-- route link -->
                            <router-link :to="{ name: 'HostsProfile', params: { id: host.user_id } }" class="tfhb-dropdown-single">Edit</router-link>
                            <!-- <span class="tfhb-dropdown-single">Duplicate</span> -->
                            <span class="tfhb-dropdown-single" @click="emit('delete-host', host.id, host.user_id)">Delete</span>
                        </div>
                    </div>
                </div> 
            </div>
            <!-- Single Hosts -->
            
        </div>

    </div>
   
</template>


 