<template>
<svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
   <g id="_01" data-name="01">
      <path fill="transparent" d="m61.71 26.29-5-5a1 1 0 0 0 -.71-.29h-28.55l-6.7-7.66a1 1 0 0 0 -.75-.34h-17a1 1 0 0 0 -1 1 5 5 0 0 0 5 5 4.92 4.92 0 0 0 -1 3v7.1a5 5 0 0 0 -4 4.9v15a1 1 0 0 0 2 0v-7h3.06a8.26 8.26 0 0 0 -.06 1 9 9 0 0 0 17.47 3h3.31a3 3 0 0 0 2.22 1h2a3 3 0 0 0 2.22-1h3.31a9 9 0 1 0 9.78-11.89l3.43-3.44 10.51-2.67a1 1 0 0 0 .71-.71 1 1 0 0 0 -.25-1z" class="#263238"></path>
      <circle cx="16" cy="43" :class="props['colorSecond']" r="7"></circle>
      <circle cx="46" cy="43" :class="props['colorSecond']" r="7"></circle>
      <circle cx="16" cy="43" :class="props['colorSecond']" r="4"></circle>
      <circle cx="16" cy="43" :class="props['colorSecond']" r="2"></circle>
      <circle cx="46" cy="43" :class="props['colorSecond']" r="4"></circle>
      <circle cx="46" cy="43" :class="props['colorSecond']" r="2"></circle>
      <path d="m16.38 19 5 10h-13.38v-2h6a1 1 0 0 0 1-1v-7z" :class="props.color"></path>
      <path d="m13 25h-5v-3a3 3 0 0 1 3-3h2z" :class="props.color"></path>
      <path d="m4 40v-6a3 3 0 0 1 3-3h15.38l1.5 3h-7.88a9 9 0 0 0 -8.47 6z" :class="props.color"></path>
      <rect :class="props.color" height="4" rx="1" width="4" x="29" y="41"></rect>
      <path d="m44.44 34.14a9 9 0 0 0 -7.44 8.86 8.26 8.26 0 0 0 .06 1h-2.06v-2a3 3 0 0 0 -3-3h-2a3 3 0 0 0 -3 3v2h-2.06a8.26 8.26 0 0 0 .06-1 9 9 0 0 0 -3.36-7h3.24l.23.45a1 1 0 0 0 .89.55 1.07 1.07 0 0 0 .25 0l20.69-5.32z" :class="props.color"></path>
      <path d="m26.53 34.83-8.64-17.28a1 1 0 0 0 -.89-.55h-10a3 3 0 0 1 -2.83-2h15.38l6.7 7.66a1 1 0 0 0 .75.34h28.59l3.46 3.47z" :class="props.color" ></path>
   </g>
</svg>
</template>
<script setup lang="ts">
const props = defineProps(['color', 'colorSecond'])
</script>