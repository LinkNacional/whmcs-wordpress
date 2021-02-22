import axios from 'axios'

const setUrl = function (url) {
  const axiosInstance = axios.create({
    baseURL: url,
    timeout: 90000
  })
  return axiosInstance
}

const getJson = axios.create({
  baseURL: '/',
  timeout: 90000
})

export { setUrl, getJson }
