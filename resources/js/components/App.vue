<template>
    <div>

      <router-view></router-view>
    </div>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  
  const router = useRouter();
  
  const isLoggedIn = computed(() => {
    return !!localStorage.getItem('token');
  });
  
  const logout = () => {
    axios.post('/logout').then(() => {
      localStorage.removeItem('token');
      router.push('/login');
    });
  };
  </script>