import axios from 'axios'

let url = `http://${window.location.hostname}/Projects/covid-case-count/Backend/index.php/`
if (window.location.hostname.indexOf('apps.binnyva.com') >= 0) {
  url = 'https://apps.binnyva.com/ccc/Backend/index.php/'
}

export default axios.create({
  baseURL: url,
  headers: {
    'Content-type': 'application/json'
  }
})
