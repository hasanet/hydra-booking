<script setup> 
import "vue3-loading-skeleton/dist/style.css"; 
import 'vue3-toastify/dist/index.css';

//Admin Menu Active
const tfhbn_parentMenuItem = document.getElementById('toplevel_page_hydra-booking');
const tfhb_menuItems = tfhbn_parentMenuItem.querySelectorAll('li');
const tfhb_parentMenuLinks = document.querySelectorAll('a.toplevel_page_hydra-booking');

// Set Active class when submenu Clickl
tfhb_menuItems.forEach((item, index) => {
  item.addEventListener('click', () => {
    tfhb_menuItems.forEach(item => {
      item.classList.remove('current');
    });
    item.classList.add('current');
    currentIndex = index;
    localStorage.setItem('currentMenuItemIndex', currentIndex);
  });
});

// Set 1 to Localstorage when click parent Menu
tfhb_parentMenuLinks.forEach(link => {
  link.addEventListener('click', (event) => {
    tfhb_menuItems.forEach((item, index) => {
      if(1==index){
        item.classList.add('current');
      }else{
      item.classList.remove('current');
      }
    });
    localStorage.setItem('currentMenuItemIndex', 1);
  });
});

// Update local Storage when page reload
let webHash = window.location.hash;
let hashParts = webHash.split('/');
let requiredPart = hashParts[1];
if("meetings"==requiredPart){
  localStorage.setItem('currentMenuItemIndex', 2);
}else if("booking"==requiredPart){
  localStorage.setItem('currentMenuItemIndex', 3);
}else if("hosts"==requiredPart){
  localStorage.setItem('currentMenuItemIndex', 4);
}else if("settings"==requiredPart){
  localStorage.setItem('currentMenuItemIndex', 5);
}else{
  localStorage.setItem('currentMenuItemIndex', 1);
}

// Set the initial current menu item
let currentIndex = localStorage.getItem('currentMenuItemIndex');
if (currentIndex !== null) {
  tfhb_menuItems[currentIndex].classList.add('current');
}

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
