<template>
  <div class="wrapper">

    <!-- Top Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <!-- Sidebar toggle button-->
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button">
            <i class="fas fa-bars"></i>
          </a>
        </li>
      </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="#" class="brand-link">
        
          
        <span class="brand-text font-weight-light text-align-center ml-4">Admin Dashboard</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
            <li class="nav-item">
              <router-link to="/" class="nav-link">
                <i class="nav-icon fas fa-list"></i>
                <p>Products</p>
              </router-link>
            </li>
            <li class="nav-item">
              <router-link to="/products/create" class="nav-link">
                <i class="nav-icon fas fa-plus"></i>
                <p>Create Product</p>
              </router-link>
            </li>
            <li class="nav-item">
              <router-link to="/videos" class="nav-link">
                <i class="nav-icon fas fa-video"></i>
                <p>Videos</p>
              </router-link>
            </li>
            <li class="nav-item">
              <button class="nav-link" @click="logout" style="background:none;border:none;text-align:left;width:100%;color:#c2c7d0">
                <i class="nav-icon fas fa-sign-out-alt"></i> 
                <p>Logout</p>
              </button>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper p-3">
      <router-view></router-view>
    </div>

  </div>
</template>

<script setup>
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

const logout = () => {
  axios.post('/logout').then(() => {
    localStorage.removeItem('token');
    router.push('/login');
  });
};
</script>

<style scoped>
/* Additional styling if needed */
</style>
