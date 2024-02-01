<script setup>
import { ref, reactive, onBeforeMount } from 'vue';
import { useRoute } from 'vue-router'
import axios from 'axios'
// import ThbInput from '../form-fields/Input.vue';
const route = useRoute()
const availability = reactive({
  id: '',
  title: '',
  time_zone: '',
  slots: '',
});

const timeZone = reactive({});
const availabilityGet = reactive({});

// availability News
const fetchAvailability = async () => {

  const response = await axios.get(thb_wpvue.admin_url + '/wp-json/hydra-booking/v1/availability/'+route.params.id+'/');
   
  if (response.data.status) {
    availability.id = response.data.availability.id;
    availability.title = response.data.availability.title;
    availability.time_zone = response.data.availability.time_zone;
    availability.slots = response.data.availability.slots;
    timeZone.value = response.data.time_zone;
  }
}


const UpdateAvailability = async () => {

  const response = await axios.post(thb_wpvue.admin_url + '/wp-json/hydra-booking/v1/availability/update', availability);
  
 
  if (response.data.status) {
    console.log(response.data); 
    // fetchAvailability();
  }
}
onBeforeMount(() => {
  
  fetchAvailability();
});

</script>

<template> 
  <div class="thb-event-dashboard">
    <div class="thb-dashboard-heading">
      <div class="thb-admin-title">
        <h1>Availability Edit</h1>
        <span>Configure times when you are available for bookings.</span>
      </div>
      <div class="thb-admin-btn right">
        <!-- <router-link to="/event/create" class="thb-btn">Add New</router-link> -->
      </div>
    </div>
    <div class="thb-content-wrap">
      <div class="no-content">
        <div class="thb-create-form">
          <div class="thb-form-group">
            <label>Availability Name :</label>
            <input type="text" v-model="availability.title" placeholder="Please input" />
          </div>

          <div class="thb-form-group">
            <label>Time Zone:</label>
            <el-select v-model="availability.time_zone" class="m-2" placeholder="Select" size="large"
              style="width: 240px">
              <el-option v-for="item in timeZone.value" :key="item" :label="item" :value="item" />
            </el-select>
          </div>

          <div class="thb-form-group">
            <button class="thb-btn" @click="UpdateAvailability">Save</button>
          </div>
             
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
