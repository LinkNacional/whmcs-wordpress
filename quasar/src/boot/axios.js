/* eslint-disable camelcase */
import axios from 'axios'

// const whmcs_wordpress_whmcs_url = 'https://dev.criarsite.online/dev_bruno/wordpress/wp-json/whmcs-wordpress'

export const api = axios.create({
  // eslint-disable-next-line no-undef
  baseURL: whmcs_wordpress_whmcs_url,
  headers: {
    'Content-Type': 'application/json'
  }
})
