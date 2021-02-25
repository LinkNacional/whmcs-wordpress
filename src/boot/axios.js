import axios from 'axios'
var link
var axiosInstance

window.onload = function () {
  var url = '/url.json'
  axios.post(url).then((response) => {
    console.log(response.data.link)
    link = response.data.link
    axiosInstance = axios.create({
      baseURL: link + '/wp-json/login-whmcs/v1/login/',
      timeout: 90000
    })
  }, (error) => {
    console.log(error)
  })
}

export { axiosInstance }
