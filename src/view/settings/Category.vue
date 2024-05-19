<script setup> 
// Use children routes for the tabs 
import { ref, reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView,} from 'vue-router' 
import axios from 'axios' 
import Icon from '@/components/icon/LucideIcon.vue'
import { toast } from "vue3-toastify";
import { Meeting } from '@/store/meetings';


import HbText from '@/components/form-fields/HbText.vue'
import HbTextarea from '@/components/form-fields/HbTextarea.vue'; 

const CategoryData = reactive({
  id: '',
  title: '',
  description: '',
});

// Create and Update Category
const UpdateCategory = async () => { 
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/categories/create-update', CategoryData, {
        } );
      
        // if (response.data.status) {    
        //     toast.success(response.data.message, {
        //         position: 'bottom-right', // Set the desired position
        //         "autoClose": 1500,
        //     }); 
        // }
    } catch (error) {
        toast.error('Action successful', {
            position: 'bottom-right', // Set the desired position
        });
    }
}

// Edit Category
const editCategory = (data) => {
    console.log(data);
    CategoryData.id = data.id;
    CategoryData.title = data.name;
    CategoryData.description = data.description;
}

// Delete Category
const removeCategory = async (key) => { 
    const DeleteData = {
        id: key
    }
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/meetings/categories/delete', DeleteData, {
        } );
      
        if (response.data.status) {    
            toast.success(response.data.message, {
                position: 'bottom-right', // Set the desired position
                "autoClose": 1500,
            }); 
        }
    } catch (error) {
        toast.error('Action successful', {
            position: 'bottom-right', // Set the desired position
        });
    }
}

onBeforeMount(() => { 
    Meeting.fetchMeetingCategory();
});


</script>
<template>
    
    <div :class="{ 'tfhb-skeleton': skeleton }" class="thb-event-dashboard ">
  
        <div  class="tfhb-dashboard-heading ">
            <div class="tfhb-admin-title tfhb-m-0"> 
                <h1 >{{ $tfhb_trans['Meeting Category'] }}</h1> 
                <p>{{ $tfhb_trans['Manage your time zone settings and bookings'] }}</p>
            </div>
            <div class="thb-admin-btn right"> 
                <a href="#" target="_blank" class="tfhb-btn"> {{ $tfhb_trans['View Documentation'] }}<Icon name="ArrowUpRight" size="15" /></a>
            </div> 
        </div>
        <div class="tfhb-content-wrap">

            <div class="tfhb-category-warp tfhb-flexbox tfhb-align-normal tfhb-gap-0">
                <div class="tfhb-admin-card-box tfhb-category-create-box tfhb-flexbox tfhb-gap-tb-24">  
                    <HbText  
                        v-model="CategoryData.title"
                        required= "true"  
                        :label="$tfhb_trans['Category Title']"  
                        name="title"
                    /> 
                    <HbTextarea  
                        v-model="CategoryData.description"
                        required= "true"  
                        name="description"
                        :label="$tfhb_trans['Description']"  
                    /> 
                    <button class="tfhb-btn boxed-btn" @click="UpdateCategory">{{ CategoryData.id ? $tfhb_trans['Update Category'] : $tfhb_trans['Save Category'] }}</button>
                </div>  
                <div class="tfhb-category-list">
                    <table class="table" cellpadding="0" :cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="Meeting.meetingCategory">
                            <tr v-for="category in Meeting.meetingCategory">
                                <td>
                                    {{category.name}}
                                </td>
                                <td>
                                    {{category.description}}
                                </td>
                                <td>
                                    <div class="tfhb-overrides-action tfhb-flexbox tfhb-gap-16 tfhb-justify-normal">
                                        <button class="question-edit-btn" @click="editCategory(category)">
                                            <Icon name="PencilLine" :width="16" />
                                        </button>
                                        <button class="question-edit-btn" @click="removeCategory(category.id)">
                                            <Icon name="Trash" :width="16"/>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            


        </div>
    </div>
 
</template>