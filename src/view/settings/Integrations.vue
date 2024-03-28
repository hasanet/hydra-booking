<script setup> 
// Use children routes for the tabs 
import { ref, reactive, onBeforeMount } from 'vue';
import { useRouter, RouterView,} from 'vue-router' 
import axios from 'axios' 
import Icon from '@/components/icon/LucideIcon.vue'
import { toast } from "vue3-toastify"; 

// import Form Field 
import HbSelect from '@/components/form-fields/HbSelect.vue' 
import HbSwitch from '@/components/form-fields/HbSwitch.vue';
import HbPopup from '@/components/widgets/HbPopup.vue';


 

//  Load Time Zone 
const skeleton = ref(false);
 
onBeforeMount(() => {  
});

// Const for Modal
const wooPopup = ref(false);
const openWooPopup = () => { 
    wooPopup.value = true;
};
const closeWooPopup = () => { 
    wooPopup.value = false;
};


</script>
<template>
    
    <div :class="{ 'tfhb-skeleton': skeleton }" class="thb-event-dashboard ">
  
        <div  class="tfhb-dashboard-heading ">
            <div class="tfhb-admin-title"> 
                <h1 >{{ $tfhb_trans['Integrations'] }}</h1> 
                <p>{{ $tfhb_trans['One-liner description'] }}</p>
            </div>
            <div class="thb-admin-btn right"> 
                <a href="#" target="_blank" class="tfhb-btn"> {{ $tfhb_trans['View Documentation'] }}<Icon name="ArrowUpRight" size="15px" /></a>
            </div> 
        </div>
        <div class="tfhb-content-wrap">
            <div class="tfhb-integrations-wrap tfhb-flexbox">

                <!-- Single Integrations  -->
                <div class="tfhb-integrations-single-block tfhb-admin-card-box ">
                    <span class="tfhb-integrations-single-block-icon">
                        <svg width="56" height="32" viewBox="0 0 56 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.98896 0H48.7473C51.5165 0 53.7584 2.24168 53.7584 5.01106V21.7143C53.7584 24.4835 51.5167 26.7254 48.7473 26.7254H33.0552L35.2091 32L25.7365 26.7254H5.01122C2.24205 26.7254 0.000160227 24.4837 0.000160227 21.7143V5.01106C-0.0218176 2.26373 2.21979 0 4.98896 0Z" fill="#7F54B3"/>
                            <path d="M3.10546 4.542C3.4114 4.1268 3.87032 3.90826 4.4822 3.86456C5.59669 3.77715 6.23043 4.30162 6.38341 5.43798C7.06085 10.0053 7.80385 13.8732 8.59065 17.042L13.3764 7.92934C13.8134 7.09892 14.3598 6.66186 15.0154 6.61816C15.9769 6.5526 16.5669 7.16448 16.8073 8.45381C17.3536 11.3603 18.0529 13.8296 18.8833 15.9275C19.4515 10.3767 20.413 6.37782 21.768 3.9083C22.0958 3.29643 22.5766 2.99049 23.2103 2.94677C23.7129 2.90306 24.1718 3.05603 24.587 3.38383C25.0022 3.71163 25.2208 4.12682 25.2645 4.62944C25.2863 5.0228 25.2208 5.35058 25.0459 5.67838C24.1937 7.25179 23.4944 9.89609 22.9263 13.5672C22.3799 17.1293 22.1833 19.9046 22.3144 21.8932C22.3581 22.4395 22.2707 22.9203 22.0521 23.3355C21.7899 23.8163 21.3965 24.0785 20.894 24.1222C20.3258 24.1659 19.7358 23.9037 19.1676 23.3136C17.1353 21.2376 15.5181 18.1346 14.3382 14.0042C12.9177 16.8013 11.8688 18.8994 11.1914 20.2979C9.9021 22.7672 8.80946 24.0348 7.89158 24.1003C7.30154 24.144 6.79892 23.6414 6.36187 22.5924C5.24737 19.7296 4.04543 14.2009 2.75608 6.00611C2.66866 5.43794 2.79978 4.93532 3.10571 4.54196L3.10546 4.542ZM50.0683 7.97287C49.2816 6.59613 48.1234 5.76563 46.5719 5.43783C46.1567 5.35042 45.7634 5.30671 45.3919 5.30671C43.294 5.30671 41.5895 6.39937 40.2565 8.5847C39.1201 10.4422 38.552 12.4965 38.552 14.7472C38.552 16.4298 38.9016 17.8721 39.6009 19.0741C40.3876 20.4508 41.5458 21.2813 43.0973 21.6091C43.5125 21.6965 43.9058 21.7402 44.2773 21.7402C46.397 21.7402 48.1015 20.6476 49.4127 18.4622C50.5491 16.5829 51.1172 14.5286 51.1172 12.2779C51.1391 10.5734 50.7676 9.15303 50.0683 7.97287ZM47.3149 14.0261C47.0089 15.4684 46.4626 16.5391 45.6541 17.2604C45.0203 17.8286 44.4303 18.069 43.884 17.9597C43.3595 17.8505 42.9224 17.3916 42.5947 16.5393C42.3324 15.8619 42.2013 15.1844 42.2013 14.5507C42.2013 14.0044 42.245 13.458 42.3543 12.9554C42.5509 12.0595 42.9224 11.1853 43.5125 10.3549C44.2336 9.28409 44.9985 8.84703 45.7852 9.00001C46.3097 9.10927 46.7468 9.56818 47.0745 10.4204C47.3368 11.0979 47.4679 11.7753 47.4679 12.4091C47.4679 12.9772 47.4241 13.5235 47.3149 14.0261ZM36.3883 7.97287C35.6016 6.59613 34.4215 5.76563 32.8919 5.43783C32.4767 5.35042 32.0834 5.30671 31.7119 5.30671C29.614 5.30671 27.9095 6.39937 26.5765 8.5847C25.4401 10.4422 24.872 12.4965 24.872 14.7472C24.872 16.4298 25.2216 17.8721 25.9209 19.0741C26.7076 20.4508 27.8658 21.2813 29.4173 21.6091C29.8325 21.6965 30.2258 21.7402 30.5973 21.7402C32.717 21.7402 34.4215 20.6476 35.7327 18.4622C36.8691 16.5829 37.4372 14.5286 37.4372 12.2779C37.4372 10.5734 37.0876 9.15303 36.3883 7.97287ZM33.613 14.0261C33.3071 15.4684 32.7608 16.5391 31.9522 17.2604C31.3185 17.8286 30.7285 18.069 30.1821 17.9597C29.6577 17.8505 29.2206 17.3916 28.8928 16.5393C28.6306 15.8619 28.4995 15.1844 28.4995 14.5507C28.4995 14.0044 28.5432 13.458 28.6524 12.9554C28.8491 12.0595 29.2206 11.1853 29.8106 10.3549C30.5318 9.28409 31.2966 8.84703 32.0834 9.00001C32.6079 9.10927 33.0449 9.56818 33.3727 10.4204C33.6349 11.0979 33.7661 11.7753 33.7661 12.4091C33.7879 12.9772 33.7223 13.5235 33.613 14.0261Z" fill="white"/>
                        </svg>
                    </span> 

                    <h3 >Woo Payment</h3>
                    <p>New standard in online payment</p>

                    <div class="tfhb-integrations-single-block-btn tfhb-flexbox">
                        <button href="#" @click="openWooPopup" class=" tfhb-btn tfhb-btn-primary">Connect</button>
                         <!-- Checkbox swicher -->
                         <HbSwitch  />
                        <!-- Swicher --> 
                    </div>

                </div> 
                <HbPopup :isOpen="wooPopup" @modal-close="closeWooPopup" name="first-modal">
                    <template #header>Custom header</template>
                    <template #content>Custom content</template>
                    <template #footer>Custom content</template>
                </HbPopup>

                <!-- Single Integrations  -->
 

            </div> 


        </div>
    </div>
 
</template>