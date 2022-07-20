import axios from 'axios'

export const api = axios.create({
  baseURL: 'https://dev.criarsite.online/dev_bruno/wordpress/wp-json/whmcs-wordpress',
  headers: {
    'Content-Type': 'application/json'
  }
})
