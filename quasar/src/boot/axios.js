import axios from 'axios'
var link = "";
var axiosInstance;

window.onload = function() {
  var url = '/url.json'
    axios.post(url)
    .then((response) => {
      console.log(response.data.link)
      link = response.data.link
      axiosInstance = axios.create({
        // baseURL: 'https://cliente.linknacional.com.br/solicitar/agenciamtc/whmcs/',
        baseURL: link+"/wp-json/login-whmcs/v1/login/",
        timeout: 90000
        // headers: { 'Access-Control-Allow-Origin': '*', 'Access-Control-Allow-Methods': '*', 'Access-Control-Allow-Headers': '*' }
      })
    }, (error) => {
      console.log(error);
    });
};

export { axiosInstance }
