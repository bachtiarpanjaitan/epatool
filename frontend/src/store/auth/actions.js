import { api } from 'boot/axios'
import * as Role from './../../constants/role'
export const login = async ({ rootGetters, state, commit }, payload) => {
  const { username, password } = payload
  const request = {
    username: username,
    password: password
  }
  try {
    const { data } = await api.post('/auth/login', request)
    if(data.data == null){
      return data
    }
    commit('setMe', data.data.user)
    localStorage.setItem('token', data.data.token)
    return data
  } catch (err) {
    return { status: false, error: err }
  }
}

export const logout = async ({ rootGetters, state, commit }, payload) => {
  try {
    const { data } = await api.post('/auth/logout')
    localStorage.setItem('token', null)
    commit('setMe', null)
    return data
  } catch (err) {
    return { status: false, error: err }
  }
}

export const actionProfile = ({ rootGetters, state }, payload) => {
  const { firstname, lastname } = payload
  const request = {
    Firstname: firstname,
    Lastname: lastname
  }
  return api.post('/me', request)
}
