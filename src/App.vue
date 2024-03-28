<script setup> 
import "vue3-loading-skeleton/dist/style.css"; 
import 'vue3-toastify/dist/index.css';

//Admin Menu Active
const tfhbn_parentMenuItem = document.getElementById('toplevel_page_hydra-booking');
const tfhb_menuItems = tfhbn_parentMenuItem.querySelectorAll('li');

tfhb_menuItems.forEach((item, index) => {

  if(1==index){
    item.classList.add('current');
  }
  item.addEventListener('click', () => {

    tfhb_menuItems.forEach(item => {
      item.classList.remove('current');
    });
    item.classList.add('current');
  });
});

</script>

<template>    
  <router-view /> 
</template> 
<style scoped>  
  #toplevel_page_hydra-booking .wp-submenu-wrap li.current a{
    color: #fff;
    font-weight: 600;
  }
</style>
