<script setup>
import Icon from '@/components/icon/LucideIcon.vue'
</script>

<template>
    <div class="meeting-create-details tfhb-gap-24">

        <div class="tfhb-notification-wrap tfhb-admin-card-box tfhb-m-0 tfhb-gap-32">
            <div class="tfhb-notification-button-tabs tfhb-flexbox tfhb-gap-16">
                <button class="tfhb-btn boxed-secondary-btn flex-btn active"><Icon name="UserRound" size="15px" /> To host </button>
                <button class="tfhb-btn boxed-secondary-btn flex-btn"><Icon name="UsersRound" size="15px" /> To Attendee </button>
            </div>
            <!-- Single Notification  -->
            <div class="tfhb-notification-single tfhb-flexbox">
                <div class="tfhb-swicher-wrap  tfhb-flexbox">
                    <!-- Checkbox swicher -->
                    <label class="switch">
                        <input id="swicher" v-model="date_status" true-value="1"  type="checkbox">
                        <span class="slider"></span>
                    </label>
                    <label class="tfhb-schedule-swicher"  for="swicher">Booking Confirmation</label>
                    <!-- Checkbox Swicher -->
                </div>

                <button class="tfhb-btn tfhb-edit flex-btn"><Icon name="PencilLine" size="15px" /> Edit </button>
            </div>
            <!-- Single Integrations  -->

            <!-- Single Notification  -->
            <div class="tfhb-notification-single tfhb-flexbox">
                <div class="tfhb-swicher-wrap  tfhb-flexbox">
                    <!-- Checkbox swicher -->
                    <label class="switch">
                        <input id="swicher" v-model="date_status" true-value="1"  type="checkbox">
                        <span class="slider"></span>
                    </label>
                    <label class="tfhb-schedule-swicher"  for="swicher">Booking Cancel</label>
                    <!-- Checkbox Swicher -->
                </div>

                <button class="tfhb-btn tfhb-edit flex-btn"><Icon name="PencilLine" size="15px" /> Edit </button>
            </div>
            <!-- Single Integrations  -->

        </div> 

        <button class="tfhb-btn boxed-btn tfhb-flexbox" @click="UpdateGeneralSettings">{{ $tfhb_trans['Save & Continue'] }} </button>
        <!--Bookings -->
    </div>
</template>