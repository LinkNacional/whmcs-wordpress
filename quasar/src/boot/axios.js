import axios from 'axios'

export const api = axios.create({
  baseURL: 'https://wplocal.com',
  headers: {
    'Content-Type': 'application/json'
  }
})
