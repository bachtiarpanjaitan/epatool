import auth from './AuthMiddleware'
import authenticated from './AuthenticatedMiddleware'
const routes = [
  {
    path: '/auth',
    component: () => import('layouts/AuthLayout.vue'),
    children: [
      { path: 'login', component: () => import('pages/auth/Login.vue'), meta: {middlewares: [authenticated]}},
      { path: 'logout', component: () => import('pages/auth/Logout.vue'), meta: {middlewares: [auth]}},
    ]
  },
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    meta: { middlewares: [auth] },
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') },
      { path: '/requests', component: () => import('pages/request/Index.vue')},
      { path: '/tasks', component: () => import('pages/task/Index.vue')},
      { path: '/users', component: () => import('pages/user/Index.vue')}
    ]
  },
  
  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
