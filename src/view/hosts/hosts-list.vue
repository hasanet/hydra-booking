<script setup>
import Icon from '@/components/icon/LucideIcon.vue'
const emit = defineEmits(["delete-host", "update-host-status"]); 
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
                    <span class="tfhb-hosts-status" v-if="host.status == 'activate'">{{ $tfhb_trans['Activate'] }}</span> 
                    <span class="tfhb-hosts-status tfhb-hosts-status-warning" v-else>{{ $tfhb_trans['Deactivate'] }}</span>

                    <div class="tfhb-hosts-info tfhb-flexbox">
                        <div class="hosts-avatar" v-if="host.avatar !='' " >
                            <img :src="host.avatar" alt="Hosts Avatar">
                        </div>
                        <div class="hosts-details">
                            <h3>{{ host.first_name }} {{ host.last_name }}</h3> 
                        </div>
                    </div>
                    <hr>
                    <div class="tfhb-hosts-info tfhb-flexbox">  
                         <span class="tfhb-info-icon-text tfhb-flexbox" v-if="host.email !=''"> <Icon name="Mail" size="20" />{{ host.email }}</span>
                         <span class="tfhb-info-icon-text tfhb-flexbox" v-if="host.phone_number !=''"> <Icon name="Phone" size="20" />{{ host.phone_number }}</span>
                    </div>
                    <!-- <button class="tfhb-single-hosts-action"><Icon name="ListCollapse" size="20" /></button> -->
                    <div class="tfhb-single-hosts-action tfhb-dropdown">
                        <img :src="$tfhb_url+'/assets/images/more-vertical.svg'" alt="">
                        <div class="tfhb-dropdown-wrap"> 
                            <!-- route link -->
                            <router-link :to="{ name: 'HostsProfile', params: { id: host.user_id } }" class="tfhb-dropdown-single">{{ $tfhb_trans['Edit'] }}</router-link>
                            <!-- <span class="tfhb-dropdown-single">Duplicate</span> -->
                            <span class="tfhb-dropdown-single" @click="emit('update-host-status',host.id, host.user_id, host.status)">{{host.status == 'activate' ? 'Deactivate' : 'Activate'}}</span>
                       
                            <span class="tfhb-dropdown-single" @click="emit('delete-host', host.id, host.user_id)">{{ $tfhb_trans['Delete'] }}</span>
                         </div>
                    </div>
                </div> 
            </div>
            <!-- Single Hosts -->
            
        </div>

    </div>
   
</template>


 