<template>
  <div class="home">
    <div id="display-area">
      <div class="location" v-for="(loc, key) in locations" :key="key">
        <h4>{{ loc.name }}</h4>
        <div class="details">
          <div class="detail-row">
            <span class="label">Confirmed:</span> <span class="value">{{ formattedCount(loc.confirmed) }}</span> 
            <span class="delta" v-if="loc.confirmed_delta">({{ formattedCount(loc.confirmed_delta) }})</span>
          </div>

          <div class="detail-row">
            <span class="label">Deceased:</span> <span class="value">{{ formattedCount(loc.deceased) }}</span> 
            <span class="delta" v-if="loc.deceased_delta">({{ formattedCount(loc.deceased_delta) }})</span>
          </div>

          <div class="detail-row">
            <span class="label">Recovered:</span> <span class="value">{{ formattedCount(loc.recovered) }}</span> 
            <span class="delta" v-if="loc.recovered_delta">({{ formattedCount(loc.recovered_delta) }})</span>
          </div>

          <div class="detail-row" v-if="loc.tested">
            <span class="label">Tested:</span> <span class="value">{{ formattedCount(loc.tested) }}</span> 
            <span class="delta" v-if="loc.tested_delta">({{ formattedCount(loc.tested_delta) }})</span>
          </div>

          <div class="detail-row" v-if="loc.vaccinated">
            <span class="label">Vaccinated:</span> <span class="value">{{ formattedCount(loc.vaccinated) }}</span> 
            <span class="delta" v-if="loc.vaccinated_delta">({{ formattedCount(loc.vaccinated_delta) }})</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import http from '@/http'

export default {
  name: 'Home',
  data() {
    return {
      locations: this.$store.getters.locations,
    }
  },

  async created() {
    const response = await http.get(`/users/${this.$store.state.user.uid}`) // Gets the latest data from backend on every pageload.

    if(response.data.status == "success") {
      this.locations = response.data.data.locations
      if(!this.locations.length) {
        this.$router.replace('settings')
      }
    }
  },

  methods: {
    formattedCount(count) {
      return new Intl.NumberFormat('en-IN').format(count)
    }
  }
}
</script>

<style scoped>
.delta {
  font-size: small;
}
.location {
  margin-bottom: 20px;
}
.label {
  font-weight: bold;
}
</style>