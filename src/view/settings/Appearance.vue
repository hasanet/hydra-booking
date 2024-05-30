<script setup> 
import {reactive, onBeforeMount} from 'vue'
import { useRouter, RouterView,} from 'vue-router'  
import Icon from '@/components/icon/LucideIcon.vue'
import HbDropdown from '@/components/form-fields/HbDropdown.vue'
import HbImageSelect from '@/components/form-fields/HbImageSelect.vue'
import ColorPicker from 'primevue/colorpicker';
import axios from 'axios' 
import { toast } from "vue3-toastify";
import LvColorpicker from 'lightvue/color-picker';
const router = useRouter();

const appearanceSettings = reactive({
  themes: 'System default',
  primary_color: 'F62881',
  secondary_color: '3F2731',
  titleTypo: '',
  desTypo: '',
});

// Fetch Appearance
const fetchAppearanceSettings = async () => {

try { 
    const response = await axios.get(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/appearance-settings');
    if (response.data.status) { 
  
        // Set Appearance Settings
        appearanceSettings.themes = response.data.appearance_settings.themes ? response.data.appearance_settings.themes : 'System default';
        appearanceSettings.primary_color = response.data.appearance_settings.primary_color ? response.data.appearance_settings.primary_color : 'F62881';
        appearanceSettings.secondary_color = response.data.appearance_settings.secondary_color ? response.data.appearance_settings.secondary_color : '3F2731';
        appearanceSettings.titleTypo = response.data.appearance_settings.titleTypo ? response.data.appearance_settings.titleTypo : '';
        appearanceSettings.desTypo = response.data.appearance_settings.desTypo ? response.data.appearance_settings.desTypo : '';
    }
} catch (error) {
    console.log(error);
} 
}

const UpdateAppearanceSettings = async () => { 
    try { 
        const response = await axios.post(tfhb_core_apps.admin_url + '/wp-json/hydra-booking/v1/settings/appearance-settings/update', appearanceSettings, {
             
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
    fetchAppearanceSettings();
});

</script>
<template>
    <div class="thb-event-dashboard">

        <div  class="tfhb-dashboard-heading ">
            <div class="tfhb-admin-title tfhb-m-0"> 
                <h1 >{{ $tfhb_trans['Appearance'] }}</h1> 
                <p>{{ $tfhb_trans['One-liner description'] }}</p>
            </div>
            <div class="thb-admin-btn right"> 
                <a href="#" target="_blank" class="tfhb-btn tfhb-flexbox tfhb-gap-8"> {{ $tfhb_trans['View Documentation'] }}<Icon name="ArrowUpRight" size="15" /></a>
            </div> 
        </div>
        
        <div class="thb-content-wrap">
            
            <div class="tfhb-admin-title" >
                <h2>{{ $tfhb_trans['Theme'] }}</h2> 
                <p>{{ $tfhb_trans['This only applies to your attendee booking pages'] }}</p>
            </div>

            <div class="tfhb-admin-card-box tfhb-flexbox tfhb-gap-tb-24">
                <div class="tfhb-imageselect-box">

                    <HbImageSelect 
                        v-model="appearanceSettings.themes"
                        width="50"
                        name="themes"
                        selected = "1"
                        :option = "[
                            {'name': 'System default', 'value': '#765664'}, 
                            {'name': 'Light', 'value': '#8F707D'},
                            {'name': 'Dark', 'value': '#3F2731'},
                        ]"
                    />
                </div>
            </div>

            <div class="tfhb-admin-title" >
                <h2>{{ $tfhb_trans['Custom brand colors'] }}</h2> 
                <p>{{ $tfhb_trans['Customize your own brand color into your booking page'] }}</p>
            </div>

            <div class="tfhb-admin-card-box tfhb-flexbox tfhb-gap-tb-24">
                <div class="tfhb-colorbox tfhb-full-width">
                    <div class="tfhb-single-colorbox tfhb-flexbox tfhb-mb-16">
                        <label>
                            Primary Color
                        </label>
                        <div class="color-select">
                            <LvColorpicker :value="appearanceSettings.primary_color" v-model="appearanceSettings.primary_color" :withoutInput="true"/>
                            <span>Select Color</span>
                        </div>
                    </div>
                    <div class="tfhb-single-colorbox tfhb-flexbox">
                        <label>
                            Secondary Color
                        </label>
                        <div class="color-select">
                            <LvColorpicker :value="appearanceSettings.secondary_color" v-model="appearanceSettings.secondary_color" :withoutInput="true"/>
                            <span>Select Color</span>
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="tfhb-admin-title" >
                <h2>{{ $tfhb_trans['Typography'] }}</h2> 
                <p>{{ $tfhb_trans['Set your own typography for your brand'] }}</p>
            </div>

            <div class="tfhb-admin-card-box tfhb-flexbox tfhb-gap-tb-24">  
                <HbDropdown 
                    v-model="appearanceSettings.titleTypo"
                    required= "true"  
                    :label="$tfhb_trans['For title']"   
                    width="50"
                    selected = "1"
                    placeholder="For title"  
                    :option = "[
                        {'name': 'Inter', 'value': 'Inter'}, 
                        {'name': 'Roboto', 'value': 'Roboto'}
                    ]"
                    
                />

                <HbDropdown 
                    v-model="appearanceSettings.desTypo"
                    required= "true"  
                    :label="$tfhb_trans['For paragraph']"   
                    width="50"
                    selected = "1"
                    placeholder="For paragraph"  
                    :option = "[
                        {'name': 'Inter', 'value': 'Inter'}, 
                        {'name': 'Roboto', 'value': 'Roboto'}
                    ]"
                    
                />
            </div>

            <button class="tfhb-btn boxed-btn" @click="UpdateAppearanceSettings">{{ $tfhb_trans['Save'] }}</button>

        </div>
    </div>
 
</template>