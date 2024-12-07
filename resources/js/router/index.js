import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/pages/Login.vue';
import Register from '../components/pages/Register.vue';
import Dashboard from '../components/pages/Dashboard.vue';
import ProductList from '../components/products/ProductList.vue';
import ProductForm from '../components/products/ProductForm.vue';
import VideosPage from '../components/products/VideoPage.vue';

const routes = [
    { path: '/register', name: 'register', component: Register },
    { path: '/login', name: 'login', component: Login },

  { 
    path: '/', 
    name: 'dashboard', 
    component: Dashboard,
    children: [
      { path: '', name:'products.index', component: ProductList },
      { path: 'products/create', name:'products.create', component: ProductForm },
      { path: 'products/:id/edit', name:'products.edit', component: ProductForm },
      { path: 'videos', name:'videos', component: VideosPage },
    ]
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});


export default router;
