import axios from 'axios'

const setUrl = function (url) {
  url = decodeURI(url)
  const axiosInstance = axios.create({
    baseURL: url,
    timeout: 90000
    // headers: { Accept: 'application/json;', 'Content-Type': 'application/json' }
  })
  return axiosInstance
}

export { setUrl }
