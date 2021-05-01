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
export default {
  name: 'Home',
  data() {
    const locations = this.$store.getters.locations
    // console.log(locations)
    return {
      locations: locations
    }
  },

  methods: {
    formattedCount(count) {
      return new Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format(count)
    }
  }

}
</script>

<style scoped>
.location {
  margin-bottom: 20px;
}
.label {
  font-weight: bold;
}
</style>