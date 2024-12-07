<!-- VideoPlayer.vue -->
<template>
  <div>
    <video
      ref="videoPlayer"
      id="video"
      class="video-js vjs-default-skin"
      controls
      preload="auto"
      data-setup="{}"
    >
      <source :src="url" type="video/mp4" />
    </video>
  </div>
</template>

<script>
import videojs from 'video.js';
import 'video.js/dist/video-js.css';

export default {
props: ['url'],
mounted() {
  this.player = videojs(this.$refs.videoPlayer, {}, function onPlayerReady() {
    console.log('onPlayerReady', this);
  });
},
watch: {
  url(newUrl) {
    this.player.src({ type: 'video/mp4', src: newUrl });
  },
},
beforeUnmount() {
  if (this.player) {
    this.player.dispose();
  }
},
};
</script>
