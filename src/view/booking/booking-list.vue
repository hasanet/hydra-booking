<script setup>
import { ref } from 'vue';
import { useRouter, RouterView } from 'vue-router' 
import Icon from '@/components/icon/LucideIcon.vue'
import HbSelect from '@/components/form-fields/HbSelect.vue';
import HbPopup from '@/components/widgets/HbPopup.vue'; 
const BookingDetailsPopup = ref(true);
</script>
<template>

<!-- {{ tfhbClass }} -->
<div class="tfhb-dashboard-heading tfhb-flexbox">
    <div class="tfhb-filter-box tfhb-flexbox">
        <div class="tfhb-header-filters">
            <input type="text" placeholder="Host name or meeting title" /> 
            <span><Icon name="Search" size="20" /></span>
        </div>
    </div>
    <div class="thb-admin-btn right tfhb-flexbox tfhb-action-filter-button">
        <HbSelect 
            :selected = "1"
            placeholder="Status"  
            :option = "{'12_hours': '30 minutes', '24_hours': '10 minutes'}" 
        />
        <router-link to="/meetings/create" class="tfhb-btn boxed-btn flex-btn"><Icon name="PlusCircle" size="20" /> {{ $tfhb_trans['Add New Booking'] }}</router-link>
    </div> 
</div>

<HbPopup :isOpen="BookingDetailsPopup" @modal-close="BookingDetailsPopup = false" max_width="750px" name="first-modal">
    <template #header> 
        <h3>Discussion about design system</h3>
    </template>

    <template #content> 

        <div class="tfhb-attendee-info tfhb-full-width tfhb-flexbox tfhb-gap-16">
            <h3 class="tfhb-m-0 tfhb-full-width">Attendee</h3>
            <div class="tfhb-attendee-box tfhb-p-24 tfhb-pt-0 tfhb-flexbox tfhb-align-baseline tfhb-full-width">
                <div class="tfhb-attendee-name">
                    <h4>Name</h4>
                    <p>John Snow</p>
                </div>
                <div class="tfhb-attendee-name">
                    <h4>E-mail</h4>
                    <p>john@gmail.com</p>
                </div>
                <div class="tfhb-attendee-name">
                    <h4>Phone</h4>
                    <p>+88058464654</p>
                </div>
                <div class="tfhb-attendee-name">
                    <h4>Address</h4>
                    <p>4517 Washington Ave. Manchester, Kentucky 39495</p>
                </div>
                <div class="tfhb-attendee-name" style="width: calc(66% - 4px);">
                    <h4>Notes</h4>
                    <p>I found a 2007 study on effects of hand sanitizers on blood alcohol level in adults. The 12 subjects alcohol level in adults. The 12 </p>
                </div>
            </div>
        </div>

        <div class="tfhb-attendee-info tfhb-full-width tfhb-flexbox tfhb-gap-16">
            <h3 class="tfhb-m-0 tfhb-full-width">Host</h3>
            <div class="tfhb-attendee-box tfhb-p-24 tfhb-pt-0 tfhb-flexbox tfhb-align-baseline tfhb-full-width">
                <div class="tfhb-attendee-name">
                    <h4>Name</h4>
                    <p>John Snow</p>
                </div>
                <div class="tfhb-attendee-name">
                    <h4>E-mail</h4>
                    <p>john@gmail.com</p>
                </div>
                <div class="tfhb-attendee-name">
                    <h4>Time zone</h4>
                    <p>Asia/Dhaka</p>
                </div>
            </div>
        </div>

        <div class="tfhb-attendee-info tfhb-full-width tfhb-flexbox tfhb-gap-16 tfhb-border-none">
            <h3 class="tfhb-m-0 tfhb-full-width">Meeting</h3>
            <div class="tfhb-attendee-box tfhb-p-24 tfhb-pt-0 tfhb-pb-0 tfhb-flexbox tfhb-align-baseline tfhb-full-width">
                <div class="tfhb-attendee-name">
                    <h4>Time</h4>
                    <p>11:00 pm - 12:00 am</p>
                </div>
                <div class="tfhb-attendee-name">
                    <h4>Date</h4>
                    <p>12 Sep, 24</p>
                </div>
                <div class="tfhb-attendee-name">
                    <h4>Location</h4>
                    <p>Google meet</p>
                </div>
            </div>
        </div>

    </template> 
</HbPopup>

<div class="tfhb-booking-details tfhb-mt-32">
    <table class="table" cellpadding="0" :cellspacing="0">
        <thead>
            <tr>
                <th></th>
                <th>{{ $tfhb_trans['Date & Time'] }}</th>
                <th>{{ $tfhb_trans['Title'] }}</th>
                <th>{{ $tfhb_trans['Host'] }}</th>
                <th>{{ $tfhb_trans['Attendee'] }}</th>
                <th>{{ $tfhb_trans['Duration'] }}</th>
                <th>{{ $tfhb_trans['Status'] }}</th>
                <th>{{ $tfhb_trans['Action'] }}</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>
                    <input type="checkbox">
                </td>
                <td>
                    24 May, 2020
                    <span>03:48 am</span>
                </td>
                <td>
                    Australian Accessibility Conference
                </td>
                <td>
                    Ralph Edwards
                    <span>sara.cruz@example.com</span>
                </td>
                <td>
                    Guy Hawkins
                    <span>sara.cruz@example.com</span>
                </td>
                <td>
                    30 minutes
                </td>
                <td>
                    <div class="tfhb-details-status tfhb-flexbox tfhb-justify-normal tfhb-gap-0">
                        <div class="status">
                            pending
                        </div>
                        <div class="tfhb-status-bar">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 13.334L5 8.33398H15L10 13.334Z" fill="#765664"/>
                            </svg>
                            <div class="tfhb-status-popup">
                                <ul class="tfhb-flexbox tfhb-gap-2">
                                    <li>Approved</li>
                                    <li class="pending">Pending</li>
                                    <li class="schedule">Re-schedule</li>
                                    <li class="canceled">Canceled</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="tfhb-details-action tfhb-flexbox tfhb-justify-normal tfhb-gap-16">
                        <a href="">
                            <Icon name="Eye" width="20" />
                        </a>
                        <a href="">
                            <Icon name="Settings" width="20" />
                        </a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="checkbox">
                </td>
                <td>
                    24 May, 2020
                    <span>03:48 am</span>
                </td>
                <td>
                    Australian Accessibility Conference
                </td>
                <td>
                    Ralph Edwards
                    <span>sara.cruz@example.com</span>
                </td>
                <td>
                    Guy Hawkins
                    <span>sara.cruz@example.com</span>
                </td>
                <td>
                    30 minutes
                </td>
                <td>
                    <div class="tfhb-details-status tfhb-flexbox tfhb-justify-normal tfhb-gap-0">
                        <div class="status approved">
                            pending
                        </div>
                        <div class="tfhb-status-bar">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 13.334L5 8.33398H15L10 13.334Z" fill="#765664"/>
                            </svg>
                            <div class="tfhb-status-popup">
                                <ul class="tfhb-flexbox tfhb-gap-2">
                                    <li>Approved</li>
                                    <li class="pending">Pending</li>
                                    <li class="schedule">Re-schedule</li>
                                    <li class="canceled">Canceled</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="tfhb-details-action tfhb-flexbox tfhb-justify-normal tfhb-gap-16">
                        <a href="">
                            <Icon name="Eye" width="20" />
                        </a>
                        <a href="">
                            <Icon name="Settings" width="20" />
                        </a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="checkbox">
                </td>
                <td>
                    24 May, 2020
                    <span>03:48 am</span>
                </td>
                <td>
                    Australian Accessibility Conference
                </td>
                <td>
                    Ralph Edwards
                    <span>sara.cruz@example.com</span>
                </td>
                <td>
                    Guy Hawkins
                    <span>sara.cruz@example.com</span>
                </td>
                <td>
                    30 minutes
                </td>
                <td>
                    <div class="tfhb-details-status tfhb-flexbox tfhb-justify-normal tfhb-gap-0">
                        <div class="status canceled">
                            pending
                        </div>
                        <div class="tfhb-status-bar">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 13.334L5 8.33398H15L10 13.334Z" fill="#765664"/>
                            </svg>
                            <div class="tfhb-status-popup">
                                <ul class="tfhb-flexbox tfhb-gap-2">
                                    <li>Approved</li>
                                    <li class="pending">Pending</li>
                                    <li class="schedule">Re-schedule</li>
                                    <li class="canceled">Canceled</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="tfhb-details-action tfhb-flexbox tfhb-justify-normal tfhb-gap-16">
                        <a href="">
                            <Icon name="Eye" width="20" />
                        </a>
                        <a href="">
                            <Icon name="Settings" width="20" />
                        </a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="tfhb-booking-details-pagination tfhb-flexbox tfhb-mt-32">
        <div class="tfhb-prev-next-button">
            <a href="" class="tfhb-flexbox tfhb-gap-8 tfhb-justify-normal"><Icon name="ArrowLeft" width="20" />Previous</a>
        </div>
        <div class="tfhb-pagination">
            <ul class="tfhb-flexbox tfhb-gap-0 tfhb-justify-normal">
                <li class="active">1</li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
            </ul>
        </div>
        <div class="tfhb-prev-next-button">
            <a href="" class="tfhb-flexbox tfhb-gap-8 tfhb-justify-normal">Next<Icon name="ArrowRight" width="20" /></a>
        </div>
    </div>
</div>
</template>

<style scoped>

</style>