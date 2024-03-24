<script setup>
import { useRouter, RouterView } from 'vue-router' 
import Header from '@/components/Header.vue';
import Icon from '@/components/icon/LucideIcon.vue'
</script>
<template>

<!-- {{ tfhbClass }} -->
<div class="tfhb-admin-meetings">
    <Header title="Meetings" />
    <div class="tfhb-dashboard-heading tfhb-flexbox">
        <div class="tfhb-header-filters">
            <input type="text" placeholder="Search by meeting title" /> 
            <span><Icon name="Search" size="20" /></span>
        </div>
        <div class="thb-admin-btn right">
            <button class="tfhb-btn boxed-btn flex-btn" @click="openModal"><Icon name="PlusCircle" size="20" /> {{ $tfhb_trans['Create New Meeting'] }}</button> 
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