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
  