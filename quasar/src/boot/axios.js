import axios from 'axios'
var link;

window.onload = function() {
  var url = '/url.json'
  fetch(url).then(res => res.json())
    .then((out) => {
      link = out.link;
    }).catch(err => console.error(err))
};

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

const getConfigs = axios.create({
  baseURL: link+"wp-json/hello-world/v1/phrase",
  timeout: 90000
})

export { setUrl, getJson }
