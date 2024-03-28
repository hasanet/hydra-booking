<script setup>
import { ref } from 'vue';
import Icon from '@/components/icon/LucideIcon.vue'
import HbDateTime from '@/components/form-fields/HbDateTime.vue';
const FilterPreview = ref(false);
const FilterHostPreview = ref(false);
const FilterCatgoryPreview = ref(false);
</script>
<template>
    <div class="tfhb-dashboard-heading tfhb-flexbox">
        <div class="tfhb-filter-box tfhb-flexbox">
            <div class="tfhb-filter-btn tfhb-flexbox" @click="FilterPreview=!FilterPreview" :class="FilterPreview ? 'active' : ''">
                <Icon name="Filter" size="20" /> 
                Filter
            </div>
            <div class="tfhb-header-filters">
                <input type="text" placeholder="Search by meeting title" /> 
                <span><Icon name="Search" size="20" /></span>
            </div>
        </div>
        <div class="thb-admin-btn right">
            <router-link to="/meetings/create" class="tfhb-btn boxed-btn flex-btn"><Icon name="PlusCircle" size="20" /> {{ $tfhb_trans['Create New Meeting'] }}</router-link>
        </div> 
    </div>

    <div class="tfhb-question-popup tfhb-p-24">
        <div class="tfhb-flexbox tfhb-gap-16">
            <div class="question-header tfhb-flexbox tfhb-full-width">
                <h3>Create New Meeting Type</h3>
                <div class="tfhb-question-times" @click="QuestionPopupClose()">
                    <Icon name="X" :width="20"/>
                </div>
            </div>
            <div class="tfhb-meeting-person-type">
                <div class="tfhb-meeting-type-card tfhb-flexbox tfhb-gap-32 tfhb-p-24">
                    <div class="tfhb-meeting-type-content">
                        <div class="tfhb-flexbox tfhb-justify-normal tfhb-gap-8">
                            <div class="tfhb-flexbox tfhb-justify-normal tfhb-gap-4">
                                <Icon name="UserRound" size="20" /> 
                                <Icon name="ArrowRight" size="20" /> 
                                <Icon name="UserRound" size="20" /> 
                            </div>
                            <h3>One to One</h3>
                        </div>
                        <p>One host with one invitee. Good for: 1:1 interview, coffee chats</p>
                    </div>
                    <div class="tfhb-meeting-type-icon">
                        <Icon name="ArrowRight" width="20"/>
                    </div>
                </div>
            </div>
            <div class="tfhb-meeting-person-type">
                <div class="tfhb-meeting-type-card tfhb-flexbox tfhb-gap-32 tfhb-p-24">
                    <div class="tfhb-meeting-type-content">
                        <div class="tfhb-flexbox tfhb-justify-normal tfhb-gap-8">
                            <div class="tfhb-flexbox tfhb-justify-normal tfhb-gap-4">
                                <Icon name="UserRound" size="20" /> 
                                <Icon name="ArrowRight" size="20" /> 
                                <Icon name="UsersRound" size="20" /> 
                            </div>
                            <h3>One to Group</h3>
                        </div>
                        <p>One host with group of invitee. Good for: webinars, online clasess</p>
                    </div>
                    <div class="tfhb-meeting-type-icon">
                        <Icon name="ArrowRight" width="20"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tfhb-filter-box-content tfhb-mt-32" v-show="FilterPreview">
        <div class="tfhb-filter-form">
            <div class="tfhb-filter-category">
                <div class="tfhb-host-filter-box tfhb-flexbox" @click="FilterHostPreview=!FilterHostPreview">
                    All Host <Icon name="ChevronUp" size="20" v-if="FilterHostPreview"/> <Icon name="ChevronDown" size="20" v-else="FilterHostPreview"/>
                </div>
                <div class="tfhb-filter-category-box" v-show="FilterHostPreview">
                    <ul class="tfhb-flexbox">
                        <li class="tfhb-flexbox">
                            <label for="checkbox1">
                                <input type="checkbox" id="checkbox1">
                                Darrell Steward
                            </label>
                            <div class="tfhb-category-items">
                                25
                            </div>
                        </li>
                        <li class="tfhb-flexbox">
                            <label for="checkbox2">
                                <input type="checkbox" id="checkbox2">
                                Darrell Steward
                            </label>
                            <div class="tfhb-category-items">
                                25
                            </div>
                        </li>
                        <li class="tfhb-flexbox">
                            <label for="checkbox3">
                                <input type="checkbox" id="checkbox3">
                                Darrell Steward
                            </label>
                            <div class="tfhb-category-items">
                                25
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tfhb-filter-category">
                <div class="tfhb-host-filter-box tfhb-flexbox" @click="FilterCatgoryPreview=!FilterCatgoryPreview">
                    All Category <Icon name="ChevronUp" size="20" v-if="FilterCatgoryPreview"/> <Icon name="ChevronDown" size="20" v-else="FilterCatgoryPreview"/>
                </div>
                <div class="tfhb-filter-category-box" v-show="FilterCatgoryPreview">
                    <ul class="tfhb-flexbox">
                        <li class="tfhb-flexbox">
                            <label for="checkbox1">
                                <input type="checkbox" id="checkbox1">
                                Darrell Steward
                            </label>
                            <div class="tfhb-category-items">
                                25
                            </div>
                        </li>
                        <li class="tfhb-flexbox">
                            <label for="checkbox2">
                                <input type="checkbox" id="checkbox2">
                                Darrell Steward
                            </label>
                            <div class="tfhb-category-items">
                                25
                            </div>
                        </li>
                        <li class="tfhb-flexbox">
                            <label for="checkbox3">
                                <input type="checkbox" id="checkbox3">
                                Darrell Steward
                            </label>
                            <div class="tfhb-category-items">
                                25
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tfhb-filter-dates tfhb-flexbox">
                <div class="tfhb-filter-start-date">
                    <HbDateTime 
                        selected = "1"
                        width="45"
                        enableTime='true'
                        placeholder="From"   
                    /> 
                    <Icon name="CalendarDays" size="20" /> 
                </div>
                <div class="tfhb-calender-move-icon">
                    <Icon name="MoveRight" size="20px" /> 
                </div>
                <div class="tfhb-filter-end-date">
                    <HbDateTime 
                        selected = "1"
                        width="45"
                        enableTime='true'
                        placeholder="To"   
                    /> 
                    <Icon name="CalendarDays" size="20" /> 
                </div>
            </div>

        </div>
        <div class="tfhb-reset-btn">
            <a href="#" class="tfhb-flexbox">
                <Icon name="RefreshCw" size="20" /> 
                Reset Filter
            </a>
        </div>
    </div>

    <div class="tfhb-meetings-list-content">
        <div class="tfhb-meetings-list-wrap tfhb-flexbox">

            <!-- Single Meeting -->
            <div class="tfhb-single-meeting"> 
                <div class="single-meeting-content-box tfhb-flexbox">
                    <div class="single-meeting-content">
                        <h3>Discussion about design system one one meeting for Design people for Design people</h3>
                        <div class="meeting-user-info">
                            <ul class="tfhb-flexbox">
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="Clock" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            30 minutes
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="UserRound" size="16" /> 
                                            <Icon name="ArrowRight" size="16" /> 
                                            <Icon name="UserRound" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            One to One
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="Banknote" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            $200
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="User" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            Jack Sparrow
                                        </div>
                                    </div>
                                </li>
                                <li class="booked-items">
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="CalendarCheck" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            10/20 Booked
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tfhb-single-hosts-action tfhb-dropdown">
                        <Icon name="ListCollapse" size="20" /> 
                        <div class="tfhb-dropdown-wrap"> 
                            <!-- route link -->
                            <a href="#" class="tfhb-dropdown-single">Edit</a>
                            <!-- <span class="tfhb-dropdown-single">Duplicate</span> -->
                            <span class="tfhb-dropdown-single">Delete</span>
                        </div>
                    </div>
                </div>
                <div class="single-meeting-action-btn tfhb-flexbox">
                    <a href="#" class="tfhb-flexbox">
                        <Icon name="Eye" size="20" /> 
                        Preview
                    </a>
                    <a href="#" class="tfhb-flexbox">
                        <Icon name="Share2" size="20" /> 
                        Share
                    </a>
                </div>
            </div>
            <div class="tfhb-single-meeting"> 
                <div class="single-meeting-content-box tfhb-flexbox">
                    <div class="single-meeting-content">
                        <h3>Discussion about design system one one meeting for Design people for Design people</h3>
                        <div class="meeting-user-info">
                            <ul class="tfhb-flexbox">
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="Clock" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            30 minutes
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="UserRound" size="16" /> 
                                            <Icon name="ArrowRight" size="16" /> 
                                            <Icon name="UserRound" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            One to One
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="Banknote" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            $200
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="User" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            Jack Sparrow
                                        </div>
                                    </div>
                                </li>
                                <li class="booked-items">
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="CalendarCheck" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            10/20 Booked
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tfhb-single-hosts-action tfhb-dropdown">
                        <Icon name="ListCollapse" size="20" /> 
                        <div class="tfhb-dropdown-wrap"> 
                            <!-- route link -->
                            <a href="#" class="tfhb-dropdown-single">Edit</a>
                            <!-- <span class="tfhb-dropdown-single">Duplicate</span> -->
                            <span class="tfhb-dropdown-single">Delete</span>
                        </div>
                    </div>
                </div>
                <div class="single-meeting-action-btn tfhb-flexbox">
                    <a href="#" class="tfhb-flexbox">
                        <Icon name="Eye" size="20" /> 
                        Preview
                    </a>
                    <a href="#" class="tfhb-flexbox">
                        <Icon name="Share2" size="20" /> 
                        Share
                    </a>
                </div>
            </div>
            <div class="tfhb-single-meeting"> 
                <div class="single-meeting-content-box tfhb-flexbox">
                    <div class="single-meeting-content">
                        <h3>Discussion about design system one one meeting for Design people for Design people</h3>
                        <div class="meeting-user-info">
                            <ul class="tfhb-flexbox">
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="Clock" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            30 minutes
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="UserRound" size="16" /> 
                                            <Icon name="ArrowRight" size="16" /> 
                                            <Icon name="UserRound" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            One to One
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="Banknote" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            $200
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="User" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            Jack Sparrow
                                        </div>
                                    </div>
                                </li>
                                <li class="booked-items">
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="CalendarCheck" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            10/20 Booked
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tfhb-single-hosts-action tfhb-dropdown">
                        <Icon name="ListCollapse" size="20" /> 
                        <div class="tfhb-dropdown-wrap"> 
                            <!-- route link -->
                            <a href="#" class="tfhb-dropdown-single">Edit</a>
                            <!-- <span class="tfhb-dropdown-single">Duplicate</span> -->
                            <span class="tfhb-dropdown-single">Delete</span>
                        </div>
                    </div>
                </div>
                <div class="single-meeting-action-btn tfhb-flexbox">
                    <a href="#" class="tfhb-flexbox">
                        <Icon name="Eye" size="20" /> 
                        Preview
                    </a>
                    <a href="#" class="tfhb-flexbox">
                        <Icon name="Share2" size="20" /> 
                        Share
                    </a>
                </div>
            </div>
            <div class="tfhb-single-meeting"> 
                <div class="single-meeting-content-box tfhb-flexbox">
                    <div class="single-meeting-content">
                        <h3>Discussion about design system one one meeting for Design people for Design people</h3>
                        <div class="meeting-user-info">
                            <ul class="tfhb-flexbox">
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="Clock" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            30 minutes
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="UserRound" size="16" /> 
                                            <Icon name="ArrowRight" size="16" /> 
                                            <Icon name="UserRound" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            One to One
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="Banknote" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            $200
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="User" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            Jack Sparrow
                                        </div>
                                    </div>
                                </li>
                                <li class="booked-items">
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="CalendarCheck" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            10/20 Booked
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tfhb-single-hosts-action tfhb-dropdown">
                        <Icon name="ListCollapse" size="20" /> 
                        <div class="tfhb-dropdown-wrap"> 
                            <!-- route link -->
                            <a href="#" class="tfhb-dropdown-single">Edit</a>
                            <!-- <span class="tfhb-dropdown-single">Duplicate</span> -->
                            <span class="tfhb-dropdown-single">Delete</span>
                        </div>
                    </div>
                </div>
                <div class="single-meeting-action-btn tfhb-flexbox">
                    <a href="#" class="tfhb-flexbox">
                        <Icon name="Eye" size="20" /> 
                        Preview
                    </a>
                    <a href="#" class="tfhb-flexbox">
                        <Icon name="Share2" size="20" /> 
                        Share
                    </a>
                </div>
            </div>
            <div class="tfhb-single-meeting"> 
                <div class="single-meeting-content-box tfhb-flexbox">
                    <div class="single-meeting-content">
                        <h3>Discussion about design system one one meeting for Design people for Design people</h3>
                        <div class="meeting-user-info">
                            <ul class="tfhb-flexbox">
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="Clock" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            30 minutes
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="UserRound" size="16" /> 
                                            <Icon name="ArrowRight" size="16" /> 
                                            <Icon name="UserRound" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            One to One
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="Banknote" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            $200
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="User" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            Jack Sparrow
                                        </div>
                                    </div>
                                </li>
                                <li class="booked-items">
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="CalendarCheck" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            10/20 Booked
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tfhb-single-hosts-action tfhb-dropdown">
                        <Icon name="ListCollapse" size="20" /> 
                        <div class="tfhb-dropdown-wrap"> 
                            <!-- route link -->
                            <a href="#" class="tfhb-dropdown-single">Edit</a>
                            <!-- <span class="tfhb-dropdown-single">Duplicate</span> -->
                            <span class="tfhb-dropdown-single">Delete</span>
                        </div>
                    </div>
                </div>
                <div class="single-meeting-action-btn tfhb-flexbox">
                    <a href="#" class="tfhb-flexbox">
                        <Icon name="Eye" size="20" /> 
                        Preview
                    </a>
                    <a href="#" class="tfhb-flexbox">
                        <Icon name="Share2" size="20" /> 
                        Share
                    </a>
                </div>
            </div>
            <div class="tfhb-single-meeting"> 
                <div class="single-meeting-content-box tfhb-flexbox">
                    <div class="single-meeting-content">
                        <h3>Discussion about design system one one meeting for Design people for Design people</h3>
                        <div class="meeting-user-info">
                            <ul class="tfhb-flexbox">
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="Clock" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            30 minutes
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="UserRound" size="16" /> 
                                            <Icon name="ArrowRight" size="16" /> 
                                            <Icon name="UserRound" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            One to One
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="Banknote" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            $200
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="User" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            Jack Sparrow
                                        </div>
                                    </div>
                                </li>
                                <li class="booked-items">
                                    <div class="tfhb-flexbox">
                                        <div class="user-info-icon">
                                            <Icon name="CalendarCheck" size="16" /> 
                                        </div>
                                        <div class="user-info-title">
                                            10/20 Booked
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tfhb-single-hosts-action tfhb-dropdown">
                        <Icon name="ListCollapse" size="20" /> 
                        <div class="tfhb-dropdown-wrap"> 
                            <!-- route link -->
                            <a href="#" class="tfhb-dropdown-single">Edit</a>
                            <!-- <span class="tfhb-dropdown-single">Duplicate</span> -->
                            <span class="tfhb-dropdown-single">Delete</span>
                        </div>
                    </div>
                </div>
                <div class="single-meeting-action-btn tfhb-flexbox">
                    <a href="#" class="tfhb-flexbox">
                        <Icon name="Eye" size="20" /> 
                        Preview
                    </a>
                    <a href="#" class="tfhb-flexbox">
                        <Icon name="Share2" size="20" /> 
                        Share
                    </a>
                </div>
            </div>
        </div>
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