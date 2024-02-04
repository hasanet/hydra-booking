<script setup>
import { reactive, onBeforeMount } from 'vue';
import axios from 'axios'
import HbText from  '../components/form-fields/HbText.vue'
 

const availability = reactive({
  title: '',
  time_zone: '',
  slots: '',
});

const timeZone = reactive({});
const availabilityGet = reactive({});

// availability News
const fetchAvailability = async () => {
  const response = await axios.get(thb_wpvue.admin_url + '/wp-json/hydra-booking/v1/availability/', availability);
  

  if (response.data.status) { 
    availabilityGet.value = response.data.availability;
    timeZone.value = response.data.time_zone;
  }
}


const createAvailability = async () => {

  const response = await axios.post(thb_wpvue.admin_url + '/wp-json/hydra-booking/v1/availability/create', availability);
  if (response.data.status) {
    // console.log(response.data);
    availability.title =  '';
    availability.time_zone =  '';
    availability.slots =  '';
    fetchAvailability();
  }
}
const deleteAvailability = async ($id) => { 
  const response = await axios.get(thb_wpvue.admin_url + '/wp-json/hydra-booking/v1/availability/delete/'+$id+'/',);
  if (response.data.status) { 
    fetchAvailability();
  }
}
onBeforeMount(() => {
  fetchAvailability();
});

</script>

<template>
  <!-- {{ availabilityGet }} -->
  <div class="thb-event-dashboard">
    <div class="thb-dashboard-heading">
      <div class="thb-admin-title">
        <h1>Availability</h1>
        <span>Configure times when you are available for bookings.</span>
      </div>
      <div class="thb-admin-btn right">
        <router-link to="/event/create" class="thb-btn">Add New</router-link>
      </div>
    </div>
    <div class="thb-content-wrap">
      <div class="no-content">
        <div class="thb-create-form">
          <div class="thb-form-group">
            <label>Availability Name :</label>
            <Input v-model="availability.title" ftype="text" /> 
          </div>

          <div class="thb-form-group">
            <label>Time Zone:</label>
            <el-select v-model="availability.time_zone" class="m-2" placeholder="Select" size="large"
              style="width: 240px">
              <el-option v-for="item in timeZone.value" :key="item" :label="item" :value="item" />
            </el-select>
          </div>

          <div class="thb-form-group">
            <button class="thb-btn" @click="createAvailability">Save</button>
          </div>
            
          <div  class="thb-list-availability">
            <div class="thb-single-availability" v-for="item in availabilityGet.value">
              
              <router-link  :to="{ name: 'availabilityEdit', params: { id: item.id } }">
                <h3>{{item.title}}</h3>
                <span> <strong>Time Zone: </strong>  {{ item.time_zone }} </span>
              </router-link>
              
              <button @click="deleteAvailability(item.id)">Delete Items</button>
            </div>

          </div>

          <p>There are no events yet.</p>
        </div>
      </div>
    </div>
  </div>
</template>
 

<style scoped>
/* Your component styles go here */

.thb-event-dashboard .thb-dashboard-heading {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
  height: 60px;
  border-radius: 4px;
  margin-right: 16px;
}

.thb-event-dashboard .thb-admin-title h1 {
  font-size: 24px;
  font-weight: 600;
  color: #000;
  text-decoration: none;
  outline: none;
  box-shadow: none;
  margin-left: 5px;
}

.thb-event-dashboard .thb-admin-btn.right {
  margin-top: 10px;
}

.thb-event-dashboard .thb-admin-btn.right .thb-btn {
  background-color: #F62681;
  color: #fff;
  border-radius: 4px;
  padding: 8px 16px;
  font-size: 16px;
  font-weight: 600;
  text-decoration: none;
  outline: none;
  box-shadow: none;
  margin-left: 5px;
}
.thb-list-availability{
  display: flex; 
  justify-content: space-between;
  gap: 10px;
  flex-wrap: wrap;


} 
.thb-list-availability .thb-single-availability {
  background-color: #fee9f2;
  display: inline-block;
  width: 100%;
  padding: 10px;
  color: #301C25;
  text-decoration: none;
  border-radius: 5px;
  margin-top: 10px;
  flex-basis: calc(33% - 30px);
}
.thb-list-availability .thb-single-availability a {
  background-color: #fee9f2;
  display: inline-block;
  width: 100%; 
  color: #301C25;
  text-decoration: none;
  border-radius: 5px; 
}
.thb-list-availability .thb-single-availability h3 {
  margin: 0;
  margin-bottom: 5px;
}


.thb-content-wrap .no-content {
  padding: 20px;
  background-color: #fff;
  border-radius: 4px;
  box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
  margin-right: 16px;
  margin-top: 32px;
}
</style>
