<template>
  <div class="settings">

    <ul class="nav nav-tabs" id="main-tabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button @click="step=1" v-bind:class="['nav-link', { active: step === 1 }]" id="locations-tab" data-bs-toggle="tab" data-bs-target="#locations" type="button" role="tab" aria-controls="locations" aria-selected="true">Locations</button>
      </li>
      <!-- <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Settings</button>
      </li> -->
      <li class="nav-item" role="presentation">
        <button @click="step=3" v-bind:class="['nav-link', { active: step === 3 }]" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" role="tab" aria-controls="permissions" aria-selected="false">Permissions</button>
      </li>
    </ul>

    <div class="tab-content" id="main-tabs-content">
      <div v-bind:class="['tab-pane', 'fade', 'show', { active: step === 1 }]" role="tabpanel" aria-labelledby="locations-tab" id="locations">
        <br />
        <h4>Locations of Interest</h4>

        <p>Enter the locations you want the Covid data of...</p>
        <ul v-if="subscriptions.length > 0">
          <draggable :list="subscriptions" item-key="index" group="subscriptions" @start="drag=true" @end="drag=false">
            <template #item="{ loc, index }">
              <li>
                {{ subscriptions[index] }}
                <span class="delete-subscription" @click="deleteSubscription(subscriptions[index])">x {{ loc }}</span>
              </li>
            </template>
          </draggable>
        </ul>

        <form action="" method="post" @submit="addLocation">
          <input type="text" placeholder="Location" list="all-locations" v-model="new_subscription" />
          <datalist id="all-locations">
            <option v-for="(loc,index) in all_locations" :key="index" :value="loc" />
            <!-- Shortcuts -->
            <option value="Bengaluru Urban">Bangalore</option>
          </datalist>
          <input type="submit" value="Add" class="btn-sm btn-primary" /><br />
        </form>

        <input v-if="app_init && subscriptions.length > 0" type="button" class="btn btn-success" value="Save Subscriptions" @click="saveSubscriptions" />
        <input v-if="!app_init && subscriptions.length > 0" type="button" class="btn btn-success" value="Save and Move to Next Step &gt;" @click="saveSubscriptions" />

        <br /><br />
        <p><input type="button" class="btn btn-danger" value="Logout" @click="logout" /></p>
      </div>

      <!-- <div class="container" id="settings" v-if="step === 2">
        <h4>Notification Settings</h4>
        <label for="time">Notification Time:</label> <input type="time" id="time" name="time" v-model="time" /><br />

        <strong>Send Data from:</strong><br />
        <input type="radio" id="data_date_today" name="data_date" value="today" v-model="data_date" /> <label for="data_date_today">Today</label><br />
        <input type="radio" id="data_date_yesterday" name="data_date" value="yesterday" v-model="data_date" /> <label for="data_date_yesterday">Yesterday</label> <span class="info">(Use this if you want to be notified in the morning - this approch is more accurate)</span><br />

        <div class="detail">
          <strong>Notification Details</strong><br />
          <p class="info">These numbers will be included in the notification...</p>
          <input type="checkbox" id="detail-confirmed" v-model="detail_confirmed" /> <label for="detail-confirmed">Confirmed | </label>
            <label for="delta-confirmed" class="delta-text"> &nbsp; Change from previous day</label> <input type="checkbox" id="delta-confirmed" v-model="delta_confirmed" /><br />
          <input type="checkbox" id="detail-active" v-model="detail_active" /> <label for="detail-active">Active | </label>
            <label for="delta-active" class="delta-text"> &nbsp; Change from previous day</label> <input type="checkbox" id="delta-active" v-model="delta_active" /><br />
          <input type="checkbox" id="detail-recovered" v-model="detail_recovered" /> <label for="detail-recovered">Recovered | </label>
            <label for="delta-recovered" class="delta-text"> &nbsp; Change from previous day</label> <input type="checkbox" id="delta-recovered" v-model="delta_recovered" /><br />
          <input type="checkbox" id="detail-deceased" v-model="detail_deceased" /> <label for="detail-deceased">Deceased | </label>
            <label for="delta-deceased" class="delta-text"> &nbsp; Change from previous day</label> <input type="checkbox" id="delta-deceased" v-model="delta_deceased" /><br />
          <input type="checkbox" id="detail-tested" v-model="detail_tested" /> <label for="detail-tested">Tested | </label>
            <label for="delta-tested" class="delta-text"> &nbsp; Change from previous day</label> <input type="checkbox" id="delta-tested" v-model="delta_tested" /><br />
          <input type="checkbox" id="detail-vaccinated" v-model="detail_vaccinated" /> <label for="detail-vaccinated">Vaccinated | </label>
            <label for="delta-vaccinated" class="delta-text"> &nbsp; Change from previous day</label> <input type="checkbox" id="delta-vaccinated" v-model="delta_vaccinated" /><br />
        </div>

        <input v-if="data_changed_settings" type="button" class="btn btn-success" value="Save and Move to Step 3 &gt;" @click="saveSettings" />
      </div> -->

      <div v-bind:class="['tab-pane', 'fade', 'show', { active: step === 3 }]" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
        <br />
        <h4>Permissions</h4>

        <p>You have to autherize this app to be able to send you notifications.</p>
        
        <p>First, you'll have to add this app to your phone.</p>

        <p v-if="browser === 'firefox'">
          Click on the Home at the top-right corner of the browser in firefox and then click on '+ Add to Home Screen'
        </p>

        <p v-if="browser !== 'firefox'">
          Click on the 'Add Covid Case Count to Home Screen' at the bottom of the browser in Chrome"
        </p>

        <p>
          Once that is done, click on the 'Allow Notifications' button - the app will attempt to send you a notification. <strong>Click on the 'Allow' button</strong> and you are done.
        </p>

        <input type="button" class="btn btn-primary" value="Allow Notifications" @click="notificationPermission" />

        <p v-if="error_text">{{ error_text }}</p>
      </div>

    </div>
  </div>
</template>

<script>
import { all_locations } from '@/utils/india-locations'
import { requestPermission } from '@/firebase'
import http from '@/http'
import draggable from 'vuedraggable'

export default {
  name: 'Home',
  components: {
    draggable
  },
  data() {
    // Browser Detection - needed to tell the user how to add the app to phone.
    let browser = 'other'
    if (typeof InstallTrigger !== 'undefined') browser = 'firefox'
    else if (
      !!window.chrome &&
      (!!window.chrome.webstore || !!window.chrome.runtime)
    )
    browser = 'chrome'

    const current_subscriptions = this.$store.getters.subscriptions

    return {
      app_init: current_subscriptions.length > 0,
      error_text: "",
      step: 1,
      data_changed_subs: false,
      data_changed_settings: true,

      browser: browser,
      new_subscription: "",
      all_locations: all_locations,
      subscriptions: current_subscriptions,

      time: "10:00:00",
      data_date: "yesterday",

      detail_confirmed: true,
      delta_confirmed: true,
      detail_active: false,
      delta_active:  false,
      detail_recovered:  false,
      delta_recovered:  false,
      detail_deceased:  false,
      delta_deceased:  false,
      detail_tested:  false,
      delta_tested:  false,
      detail_vaccinated:  false,
      delta_vaccinated:  false
    }
  },
  methods: {
    addLocation(e) {
      e.preventDefault()
      this.subscriptions.push(this.new_subscription)
      this.new_subscription = ""
      this.data_changed_subs = true
    },

    deleteSubscription(sub) {
      const index = this.subscriptions.indexOf(sub);
      if (index > -1) {
        this.subscriptions.splice(index, 1);
      }
      this.data_changed_subs = true
    },

    unsubscribe() { // :TODO:

    },

    logout() {
      this.$router.replace('logout')
    },

    async saveSubscriptions() {
      const response = await http.post(`/users/${this.$store.state.user.uid}/subscriptions`, {
        subscriptions: this.subscriptions
      })

      if(response.data.status == "success") {
        this.$store.dispatch("setSubscriptions", this.subscriptions)
        this.$store.dispatch("setLocations", response.data.data)
        this.data_changed_subs = false
      }

      if(!this.app_init) {
        this.step = 3
      } else {
        this.$router.replace('home')
      }
    },

    async saveSettings() {
      this.step = 3
    },

    async notificationPermission() {
      const success = await requestPermission()
      if(success) {
        this.$router.replace('home')
      } else {
        this.error_text = "Failed getting notification permission. Did you click on the Allow button?";
      }
    }
  }
}
</script>

<style scoped>
.delta-text, .info {
  font-size: .7em;
}
.delete-subscription {
  font-size: smaller;
  color: red;
}
</style>