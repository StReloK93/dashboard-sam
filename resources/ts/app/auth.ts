
import { defineStore } from "pinia"
import { ref , computed } from "vue"
import router from "@/app/router"
import axios from 'axios'

export const useAuthStore = defineStore('Auth', () => {
    const user = ref(null)

    async function login(data) {
        const result = await axios.post('api/login', data)
        if (result.status == 299) return result.data
        else {
            localStorage.setItem('token', `${result.data.type} ${result.data.token}`) // local
            await getUser()
            router.push({ name: 'home'})
        }
    }

    const userRoles = computed(() => {
        if(user.value) return user.value?.roles?.map((role) => role.role_id)
        else return []
    })
    

    async function getUser() {

        axios.defaults.headers.common['Authorization'] = localStorage.getItem('token')
        await axios.get('api/user').then(({ data }) => {
            user.value = data
        }).catch(() => { console.clear() })

    }


    async function register(props) {
        try{
            const result = await axios.post('api/register', props)
            if (result.status == 200) login(props)
            else return result.data
        } catch (err){
            return err.response.data
        }
    }

    async function logout() {
        const data = await axios.get('api/logout')

        if (data.status == 200) {
            axios.defaults.headers.common['Authorization'] = null
            localStorage.removeItem('token')
            user.value = null
            router.push({ name: 'login' })
        }
    }

    return { user, userRoles,  getUser, login, register, logout }
})


export const printStore = defineStore('printStore', () => {
    const printForm = ref(false)
    const employe = ref(null)
    const employeProducts = ref()
    const position = ref(null)
    return { printForm, employe, employeProducts , position}
})
