import { api } from 'boot/axios'
export const addworkspace = async ({ rootGetters, state, commit }, payload) => {
  const { name } = payload
  const request = { name }
  try {
    const { data } = await api.post('/common/addworkspace', request)
    return data
  } catch (err) {
    return { status: false, error: err }
  }
}

export const getworkspace = ({ rootGetters, state }) => {
  const request = {}
  return api.get('/common/getworkspace', request)
}

export const getusers = ({ rootGetters, state }) => {
  const request = {}
  return api.get('/common/getusers', request)
}