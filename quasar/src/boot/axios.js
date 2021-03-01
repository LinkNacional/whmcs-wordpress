import axios from 'axios'
const axiosInstance = axios.create({
    baseURL: login_whmcs_url + '/wp-json/login-whmcs/v1/login/',
    timeout: 90000
  })
export { axiosInstance }
