import { api } from 'boot/axios'
import qs from 'qs'
export function myrequest(context, params = {}) {
    return api.get('/request/myrequest', {
      params,
      paramsSerializer: params => {
        return qs.stringify(params, { arrayFormat: 'repeat' })
      }
    })
  }
  
export const updateRequest = async ({ rootGetters, state, commit }, payload) => {
  try {
    const { data } = await api.post('/request/update/'+ payload.id, payload)
    return data
  } catch (err) {
    return { status: false, error: err }
  }
}

export const createRequest = async ({ rootGetters, state, commit }, payload) => {
  try {
    const { data } = await api.post('/request/create', payload)
    return data
  } catch (err) {
    return { status: false, error: err }
  }
}