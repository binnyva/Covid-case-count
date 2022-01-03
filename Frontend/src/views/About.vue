<template>
  <div class="about">
    <h1>About Covid Case Count</h1>

    <p>Covid Case Count(or CCC for short) is a tiny tool to <strong>show the Covid-19 Case counts in the places of your interest</strong>. 
      This can be states or districts or even the entire Country.</p>

    <p>Update: This app is currently <strong>inactive</strong>(as of 2021 November). The source of the data for this app is <a href="https://covid19india.org">Covid19India</a> - and they have <a href="https://blog.covid19india.org/2021/08/07/end/">shut down opererations</a>. CCC will be available once I find another data source. Sorry about the inconvenience.</p>

    <p>You can <strong>subscribe to places</strong> in the <router-link to="settings">Settings Page</router-link>. 
      Once you have saved your subscribtions, you'll see the following details in the below format 
      about all the places you have subscribed to in the <router-link to="home">Home Screen</router-link>...</p>

    <ul>
      <li>Total Confirmed Cases<span class="detail">(new cases today)</span></li>
      <li>Total Deceased<span class="detail">(new deaths today)</span></li>
      <li>Total Recovered Cases<span class="detail">(recovered today)</span></li>
      <li>Total Tests Done<span class="detail">(tests done today)</span></li>
      <li>Total Vaccinated<span class="detail">(vaccinated today)</span></li>
    </ul>

    <p>Example...</p>

    <img src="../assets/about/sample-india.png" alt="Screenshot of how the location details will look like" />

    <h3>Notifications</h3>

    <p>If you have given the app the notification permission in the <router-link to="settings">Settings Page</router-link>, 
      it will <strong>send you a notification at 10 AM every day</strong> with the latest numbers.</p>

    <p>All the data is sourced from <a href="https://covid19india.org">Covid 19 India</a> Database. For more detailed information, visit their website.</p>
  </div>
</template>

<style scoped>
.detail {
  font-size: small;
}
</style>