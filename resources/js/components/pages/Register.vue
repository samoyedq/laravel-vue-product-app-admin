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

    <div class="d-flex justify-content-center align-items-center" style="height:100vh;">
      <div class="card p-3" style="width:300px;">
        <h3>Admin Register</h3>
        <input type="text" class="form-control my-2" placeholder="Username" v-model="username"/>
        <input type="email" class="form-control my-2" placeholder="Email (optional)" v-model="email"/>
        <input type="password" class="form-control my-2" placeholder="Password" v-model="password"/>
        <input type="password" class="form-control my-2" placeholder="Confirm Password" v-model="password_confirmation"/>
        <button class="btn btn-primary w-100" @click="registerAdmin">Register</button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  
  const router = useRouter();
  const username = ref('');
  const email = ref('');
  const password = ref('');
  const password_confirmation = ref('');
  
  const registerAdmin = () => {
    axios.post('/register', {
      username: username.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value
    }).then(response => {
      localStorage.setItem('token', response.data.token);
      // Go to dashboard after registration
      router.push('/login');
    }).catch(e => {
      alert('Registration failed. Check console for details.');
      console.error(e);
    });
  };
  </script>
  