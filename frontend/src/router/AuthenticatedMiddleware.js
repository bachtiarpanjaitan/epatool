export default function authenticated({ next, store }) {
    let me = store.getters['auth/getMe']
    if (me !== null) return next('/')
    else return next()
}   