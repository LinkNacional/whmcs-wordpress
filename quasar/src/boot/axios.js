import axios from 'axios'
var url = login_whmcs_url

const axiosInstance = axios.create({
    baseURL: url + '/wp-json/login-whmcs/v1/login/',
    timeout: 90000
  })
export { axiosInstance }
