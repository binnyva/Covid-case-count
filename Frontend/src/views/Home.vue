<template>
  <div class="home">
    <!-- 
    <h3>Settings</h3>
    Notification Time:<br />
    <div class="detail">
      Detail:<br />
      AND DELTA for everyone of these.
      <input type="checkbox" id="detail-confirmed" v-model="detail_confirmed" /> <label for="detail-confirmed">Confirmed</label><br />
      <input type="checkbox" id="detail-active" v-model="detail_active" /> <label for="detail-active">Active</label><br />
      <input type="checkbox" id="detail-recovered" v-model="detail_recovered" /> <label for="detail-recovered">Recovered</label><br />
      <input type="checkbox" id="detail-deceased" v-model="detail_deceased" /> <label for="detail-deceased">Deceased</label><br />
      <input type="checkbox" id="detail-tested" v-model="detail_tested" /> <label for="detail-tested">Tested</label><br />
      <input type="checkbox" id="detail-vaccinated" v-model="detail_vaccinated" /> <label for="detail-vaccinated">Vaccinated</label><br />
    </div> 
    -->

    Locations of Interest:<br />
    <ul v-if="locations.length > 0">
      <li v-for="(loc, index) in locations" :key="index">{{ loc }}</li>
    </ul>

    <form action="" method="post" @submit="addLocation">
      Add new location from a list of {{ all_locations.length }} locations...<br />
      <input type="text" placeholder="Location" list="all-locations" v-model="new_location" />
      <datalist id="all-locations">
        <option v-for="(loc,index) in all_locations" :key="index" :value="loc" />
      </datalist>
      <input type="submit" value="Add" class="btn btn-primary" /><br />
    </form>

    <input v-if="data_changed" type="button" class="btn btn-success" value="Save Subscriptions" @click="saveSubscriptions" />

    <!-- TODO 
    Get Notification Permission
    -->
  </div>
</template>

<script>
import { all_locations } from '@/utils/india-locations'
import http from '@/http'

export default {
  name: 'Home',
  data() {
    return {
      data_changed: false,
      new_location: "",
      all_locations: all_locations,
      locations: [
        "Karnataka",
        "Bangalore",
      ]
    }
  },
  methods: {
    addLocation(e) {
      e.preventDefault()
      this.locations.push(this.new_location)
      this.new_location = ""
      this.data_changed = true
    },

    removeLocation() { // :TODO:
      this.data_changed = true
    },

    async saveSubscriptions() {
      const response = await http.post(`/users/${this.$store.state.user.uid}/subscriptions`, {
        subscriptions: this.locations
      })

      if(response) {
        this.data_changed = false
      }
    }
  }
}
</script>
