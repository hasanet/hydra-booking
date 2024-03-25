<script setup>
import HbSelect from '@/components/form-fields/HbSelect.vue'
import HbDateTime from '@/components/form-fields/HbDateTime.vue';
import Icon from '@/components/icon/LucideIcon.vue'
</script>

<template>
    <div class="meeting-create-details tfhb-gap-24">
        <!-- Select Host -->
        <HbSelect 
            required= "true" 
            :label="$tfhb_trans['Select Host']"  
            :selected = "1"
            :placeholder="$tfhb_trans['Select Host']"  
            :option = "{'12_hours': '30 minutes', '24_hours': '10 minutes'}" 
        />

        <!-- Choose Schedule -->
        <HbSelect 
            required= "true" 
            :label="$tfhb_trans['Choose Schedule']"  
            :selected = "1"
            :placeholder="$tfhb_trans['Choose Schedule']"   
            :option = "{'12_hours': '30 minutes', '24_hours': '10 minutes'}" 
        />

        <div class="tfhb-admin-card-box tfhb-gap-24">  
            <div  class="tfhb-availability-schedule-single tfhb-schedule-heading tfhb-flexbox">
                <div class="tfhb-admin-title"> 
                    <h3 >Weekly hours </h3>  
                </div>
                <div class="thb-admin-btn right"> 
                    <span>Asia/Dhaka</span> 
                </div> 
            </div>
            <div class="tfhb-availability-schedule-single tfhb-flexbox">
                <div class="tfhb-swicher-wrap  tfhb-flexbox">
                    <!-- Checkbox swicher -->
                    <label class="switch">
                        <input id="swicher" true-value="1" type="checkbox">
                        <span class="slider"></span>
                    </label>
                    <label class="tfhb-schedule-swicher" for="swicher"> Monday</label>
                    <!-- Swicher -->
                </div>
                <div class="tfhb-availability-schedule-wrap "> 
                    <div class="tfhb-availability-schedule-inner tfhb-flexbox">
                        <div class="tfhb-availability-schedule-time tfhb-flexbox">
                            <HbDateTime   
                                selected = "1" 
                                :config="{
                                    enableTime: true,
                                    noCalendar: true,
                                    dateFormat: 'H:i',
                                }"
                                width="45"
                                placeholder="Type your schedule title"   
                            /> 
                            <Icon name="MoveRight" size="20px" /> 
                            <HbDateTime  
                                :label="$tfhb_trans['End']"  
                                selected = "1"
                                :config="{
                                    enableTime: true,
                                    noCalendar: true,
                                    dateFormat: 'H:i',
                                }"
                                width="45"
                                placeholder="Type your schedule title"   
                            /> 

                        </div>
                        <div v-if="tkey == 0" class="tfhb-availability-schedule-clone-single">
                            <button class="tfhb-availability-schedule-btn" ><Icon name="Plus" size="20px" /> </button> 
                        </div>
                        <div v-else class="tfhb-availability-schedule-clone-single">
                            <button class="tfhb-availability-schedule-btn" ><Icon name="X" size="20px" /> </button> 
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="tfhb-availability-schedule-single tfhb-flexbox">
                <div class="tfhb-swicher-wrap  tfhb-flexbox">
                    <!-- Checkbox swicher -->
                    <label class="switch">
                        <input id="swicher" true-value="1" type="checkbox">
                        <span class="slider"></span>
                    </label>
                    <label class="tfhb-schedule-swicher" for="swicher"> Monday</label>
                    <!-- Swicher -->
                </div>
                <div class="tfhb-availability-schedule-wrap "> 
                    <div class="tfhb-availability-schedule-inner tfhb-flexbox">
                        <div class="tfhb-availability-schedule-time tfhb-flexbox">
                            <HbDateTime   
                                selected = "1" 
                                :config="{
                                    enableTime: true,
                                    noCalendar: true,
                                    dateFormat: 'H:i',
                                }"
                                width="45"
                                placeholder="Type your schedule title"   
                            /> 
                            <Icon name="MoveRight" size="20px" /> 
                            <HbDateTime  
                                :label="$tfhb_trans['End']"  
                                selected = "1"
                                :config="{
                                    enableTime: true,
                                    noCalendar: true,
                                    dateFormat: 'H:i',
                                }"
                                width="45"
                                placeholder="Type your schedule title"   
                            /> 

                        </div>
                        <div v-if="tkey == 0" class="tfhb-availability-schedule-clone-single">
                            <button class="tfhb-availability-schedule-btn" ><Icon name="Plus" size="20px" /> </button> 
                        </div>
                        <div v-else class="tfhb-availability-schedule-clone-single">
                            <button class="tfhb-availability-schedule-btn" ><Icon name="X" size="20px" /> </button> 
                        </div>
                    </div>
                    
                </div>
            </div>
        
            <div class="tfhb-availability-schedule-single tfhb-flexbox">
                <div class="tfhb-swicher-wrap  tfhb-flexbox">
                    <!-- Checkbox swicher -->
                    <label class="switch">
                        <input id="swicher" true-value="1" type="checkbox">
                        <span class="slider"></span>
                    </label>
                    <label class="tfhb-schedule-swicher" for="swicher"> Monday</label>
                    <!-- Swicher -->
                </div>
                <div class="tfhb-availability-schedule-wrap "> 
                    <div class="tfhb-availability-schedule-inner tfhb-flexbox">
                        <div class="tfhb-availability-schedule-time tfhb-flexbox">
                            <HbDateTime   
                                selected = "1" 
                                :config="{
                                    enableTime: true,
                                    noCalendar: true,
                                    dateFormat: 'H:i',
                                }"
                                width="45"
                                placeholder="Type your schedule title"   
                            /> 
                            <Icon name="MoveRight" size="20px" /> 
                            <HbDateTime  
                                :label="$tfhb_trans['End']"  
                                selected = "1"
                                :config="{
                                    enableTime: true,
                                    noCalendar: true,
                                    dateFormat: 'H:i',
                                }"
                                width="45"
                                placeholder="Type your schedule title"   
                            /> 

                        </div>
                        <div v-if="tkey == 0" class="tfhb-availability-schedule-clone-single">
                            <button class="tfhb-availability-schedule-btn" ><Icon name="Plus" size="20px" /> </button> 
                        </div>
                        <div v-else class="tfhb-availability-schedule-clone-single">
                            <button class="tfhb-availability-schedule-btn" ><Icon name="X" size="20px" /> </button> 
                        </div>
                    </div>
                    
                </div>
            </div>
        
            <div class="tfhb-availability-schedule-single tfhb-flexbox">
                <div class="tfhb-swicher-wrap  tfhb-flexbox">
                    <!-- Checkbox swicher -->
                    <label class="switch">
                        <input id="swicher" true-value="1" type="checkbox">
                        <span class="slider"></span>
                    </label>
                    <label class="tfhb-schedule-swicher" for="swicher"> Monday</label>
                    <!-- Swicher -->
                </div>
                <div class="tfhb-availability-schedule-wrap "> 
                    <div class="tfhb-availability-schedule-inner tfhb-flexbox">
                        <div class="tfhb-availability-schedule-time tfhb-flexbox">
                            <HbDateTime   
                                selected = "1" 
                                :config="{
                                    enableTime: true,
                                    noCalendar: true,
                                    dateFormat: 'H:i',
                                }"
                                width="45"
                                placeholder="Type your schedule title"   
                            /> 
                            <Icon name="MoveRight" size="20px" /> 
                            <HbDateTime  
                                :label="$tfhb_trans['End']"  
                                selected = "1"
                                :config="{
                                    enableTime: true,
                                    noCalendar: true,
                                    dateFormat: 'H:i',
                                }"
                                width="45"
                                placeholder="Type your schedule title"   
                            /> 

                        </div>
                        <div v-if="tkey == 0" class="tfhb-availability-schedule-clone-single">
                            <button class="tfhb-availability-schedule-btn" ><Icon name="Plus" size="20px" /> </button> 
                        </div>
                        <div v-else class="tfhb-availability-schedule-clone-single">
                            <button class="tfhb-availability-schedule-btn" ><Icon name="X" size="20px" /> </button> 
                        </div>
                    </div>
                    
                </div>
            </div>
        
        </div>  

        <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="UpdateGeneralSettings">{{ $tfhb_trans['Save & Continue'] }} </button>
        <!--Bookings -->
    </div>
</template>