import axios from 'axios'

export const tokenKey = 'auth_token'
export const userKey = 'user'
const api = axios.create({baseURL: '/', withCredentials: false})

api.interceptors.request.use(cfg => {
    const t = localStorage.getItem(tokenKey)
    if (t) cfg.headers.Authorization = `Bearer ${t}`
    return cfg
})

api.interceptors.response.use(r => r, err => {
    if (err.response?.status === 401) localStorage.removeItem(tokenKey)
    return Promise.reject(err)
})
export default api
