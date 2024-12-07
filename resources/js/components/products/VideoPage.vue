<template>
  <div class="videos-page">
    <h1 class="title-product">Products</h1>
    <hr style="margin-bottom: 10px;"/>
    <VideoPlayer :url="videoUrl" />

    <div class="mt-3">
      <button @click="changeVideo" class="btn btn-secondary">Change Video</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import VideoPlayer from './VideoPlayer.vue';


const videos = [
  '/videos/PRAXXYS Demo Reel MoGraph Retimed with VO with CC v2.mp4',
  '/videos/ROSÉ - toxic till the end (official music video).mp4',
  '/videos/whiplash.mp4'
];


let currentIndex = 0;
const videoUrl = ref(videos[currentIndex]);


const changeVideo = () => {
  currentIndex = (currentIndex + 1) % videos.length;
  videoUrl.value = videos[currentIndex];
};
</script>

<style scoped>
.videos-page {
  padding: 20px;
}
</style>
