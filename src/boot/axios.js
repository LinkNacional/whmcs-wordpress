import axios from 'axios'

// Vue. prototype.$axios = axios
const axiosInstance = axios.create({
  baseURL: 'https://dev.linknacional.com.br/api/whmcs/',
  timeout: 90000
  // headers: { Accept: 'application/json;', 'Content-Type': 'application/json' }
})

const axiosInstanceDB = axios.create({
  baseURL: 'https://whmcs.linknacional.com.br/whmcs/api/',
  timeout: 90000
  // headers: { Accept: 'application/json; charset=utf-8', 'Content-Type': 'application/json' }
})

// Pega o arquivo remoto.php
const axiosInstanceCard = axios.create({
  baseURL: 'https://whmcs.linknacional.com.br/',
  timeout: 90000
})

export { axiosInstance, axiosCep, axiosInstanceDB, axiosInstanceCard }
