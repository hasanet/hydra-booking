<script setup>
import { reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import axios from 'axios'  
import Icon from '@/components/icon/LucideIcon.vue'
 
</script>

<template>
    <div class="tfhb-hosts-list-content">
        <div class="tfhb-hosts-list-wrap tfhb-flexbox">
            <!-- Single Hosts -->
            <div class="tfhb-single-hosts">
                <div class="tfhb-single-hosts-wrap ">
                    <span class="tfhb-hosts-status">Active</span>
                    <div class="tfhb-hosts-info tfhb-flexbox">
                        <div class="hosts-avatar">
                            <img src="https://via.placeholder.com/32" alt="Hosts Avatar">
                        </div>
                        <div class="hosts-details">
                            <h3>Host Name</h3>
                            <p>Host Email</p>
                        </div>
                    </div>
                    <hr>
                    <div class="tfhb-hosts-info"> 
                         <span class="tfhb-info-icon-text tfhb-flexbox"> <Icon name="Mail" size="20" /> jacob.jones@example.com</span>
                         <span class="tfhb-info-icon-text tfhb-flexbox"> <Icon name="Phone" size="20" />(704) 555-0127</span>
                    </div>
                    <!-- <button class="tfhb-single-hosts-action"><Icon name="ListCollapse" size="20" /></button> -->
                    <div class="tfhb-single-hosts-action tfhb-dropdown">
                        <Icon name="ListCollapse" size="20px" /> 
                        <div class="tfhb-dropdown-wrap">
                            <span class="tfhb-dropdown-single" @click="editAvailability">Edit</span>
                            <!-- <span class="tfhb-dropdown-single">Duplicate</span> -->
                            <span class="tfhb-dropdown-single" @click="deleteAvailability">Delete</span>
                        </div>
                    </div>
                </div> 
            </div>
            <!-- Single Hosts -->
            <!-- Single Hosts -->
            <div class="tfhb-single-hosts">
                <div class="tfhb-single-hosts-wrap tfhb-admin-card-box">
                    <span class="tfhb-hosts-status">Active</span>
                    <div class="tfhb-hosts-info tfhb-flexbox">
                        <div class="hosts-avatar">
                            <img src="https://via.placeholder.com/32" alt="Hosts Avatar">
                        </div>
                        <div class="hosts-details">
                            <h3>Host Name</h3>
                            <p>Host Email</p>
                        </div>
                    </div>
                    <div class="tfhb-hosts-info"> 
                         <span class="tfhb-info-icon-text tfhb-flexbox"> <Icon name="Mail" size="20" /> jacob.jones@example.com</span>
                         <span class="tfhb-info-icon-text tfhb-flexbox"> <Icon name="Phone" size="20" />(704) 555-0127</span>
                    </div>
                    <!-- <button class="tfhb-single-hosts-action"><Icon name="ListCollapse" size="20" /></button> -->
                    <div class="tfhb-single-hosts-action tfhb-dropdown">
                        <Icon name="ListCollapse" size="20px" /> 
                        <div class="tfhb-dropdown-wrap">
                            <span class="tfhb-dropdown-single" @click="editAvailability">Edit</span>
                            <!-- <span class="tfhb-dropdown-single">Duplicate</span> -->
                            <span class="tfhb-dropdown-single" @click="deleteAvailability">Delete</span>
                        </div>
                    </div>
                </div> 
            </div>
            <!-- Single Hosts -->
            <!-- Single Hosts -->
            <div class="tfhb-single-hosts">
                <div class="tfhb-single-hosts-wrap tfhb-admin-card-box">
                    <span class="tfhb-hosts-status">Active</span>
                    <div class="tfhb-hosts-info tfhb-flexbox">
                        <div class="hosts-avatar">
                            <img src="https://via.placeholder.com/32" alt="Hosts Avatar">
                        </div>
                        <div class="hosts-details">
                            <h3>Host Name</h3>
                            <p>Host Email</p>
                        </div>
                    </div>
                    <div class="tfhb-hosts-info"> 
                         <span class="tfhb-info-icon-text tfhb-flexbox"> <Icon name="Mail" size="20" /> jacob.jones@example.com</span>
                         <span class="tfhb-info-icon-text tfhb-flexbox"> <Icon name="Phone" size="20" />(704) 555-0127</span>
                    </div>
                    <!-- <button class="tfhb-single-hosts-action"><Icon name="ListCollapse" size="20" /></button> -->
                    <div class="tfhb-single-hosts-action tfhb-dropdown">
                        <Icon name="ListCollapse" size="20px" /> 
                        <div class="tfhb-dropdown-wrap">
                            <span class="tfhb-dropdown-single" @click="editAvailability">Edit</span>
                            <!-- <span class="tfhb-dropdown-single">Duplicate</span> -->
                            <span class="tfhb-dropdown-single" @click="deleteAvailability">Delete</span>
                        </div>
                    </div>
                </div> 
            </div>
            <!-- Single Hosts -->
        </div>

    </div>
   
</template>


 