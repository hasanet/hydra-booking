<script setup>

import { ref, reactive, onBeforeMount, } from 'vue'; 
import { useRouter, RouterView,} from 'vue-router' 
import Icon from '@/components/icon/LucideIcon.vue'
import axios from 'axios'

// import Form Field 
import HbSelect from '@/components/form-fields/HbSelect.vue' 
import HbText from '@/components/form-fields/HbText.vue'
import HbSwitch from '@/components/form-fields/HbSwitch.vue';
import HbPopup from '@/components/widgets/HbPopup.vue';  

const zoomPopup = ref(false);

const zoom_integration = reactive(  { 
    status: 0, 
    connection_status: 0,   
});

const props = defineProps([
    'woo_payment', 
])
const emit = defineEmits([ "update-integrations", ]); 
const plugin_url = tfhb_core_apps.admin_url+'/wp-admin/plugin-install.php?s=WooCommerce&tab=search';
</script>

<template>
    <div class="tfhb-integrations-single-block tfhb-admin-card-box ">
        <span class="tfhb-integrations-single-block-icon">
            <img :src="$tfhb_url+'/assets/images/Woo.png'" alt="">
        </span> 

        <h3 >Woo Payment</h3>
        <p>New standard in online payment</p>

<!-- Aadmin -->

        <div class="tfhb-integrations-single-block-btn tfhb-flexbox">
            <button v-if="woo_payment.connection_status == 1"  class="tfhb-btn tfhb-flexbox tfhb-gap-8"> Connected <Icon name="ChevronRight" size="18" /></button>
            <a v-else :href="plugin_url" target="__blank"  class="tfhb-btn tfhb-install-plugins tfhb-flexbox tfhb-gap-8" :data-connection-status="woo_payment.connection_status" @click="installPlugins"> Click to Install & Active <Icon name="ChevronRight" size="18" /></a>
                <!-- Checkbox swicher -->

                <HbSwitch v-if="woo_payment.connection_status" @change="emit('update-integrations', 'woo_payment', woo_payment)"  v-model="woo_payment.status"    />
            <!-- Swicher --> 
        </div>
 
    </div>  

</template>

<style scoped>
</style> 