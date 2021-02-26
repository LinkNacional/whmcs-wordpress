import axios from 'axios'
var axiosInstance

window.onload = function () {
  axiosInstance = axios.create({
    baseURL: templateUrl + '/wp-json/login-whmcs/v1/login/',
    timeout: 90000
  })
}

export { axiosInstance }
