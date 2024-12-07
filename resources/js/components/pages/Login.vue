<template>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          <!-- Brand -->
          <a class="navbar-brand" href="#">My Admin Panel</a>
  
          <!-- Navbar Toggler Button -->
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" 
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
  
          <!-- Collapsible Content -->
          
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <router-link class="nav-link" to="/login">Login</router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/register">Register</router-link>
              </li>
            </ul>
      
        </div>
      </nav>
      
    <div class="login-page d-flex justify-content-center align-items-center" style="height:100vh;">
      <div class="card p-3" style="width:300px;">
        <h2 style="color: black; text-align: center; margin-bottom: 10px;">Admin Login</h2>
        <input type="text" class="form-control my-2" placeholder="Username or Email" v-model="username"/>
        <input type="password" class="form-control my-2" placeholder="Password" v-model="password"/>
        <div class="form-check my-2">
          <input type="checkbox" id="remember" class="form-check-input" v-model="remember">
          <label for="remember" class="form-check-label" style="color: black;">Remember Me</label>
        </div>
        <button class="btn btn-primary w-100" @click="login">Login</button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  
  const router = useRouter();
  
  const username = ref('');
  const password = ref('');
  const remember = ref(false);
  
  const login = () => {
  axios.post('/login', {
    username: username.value,
    password: password.value,
    remember: remember.value
  }).then(response => {
    // Save token and redirect to dashboard
    localStorage.setItem('token', response.data.token);
    router.push('/');
  }).catch(e => {
    alert('Login failed.');
  });
}
  </script>
  