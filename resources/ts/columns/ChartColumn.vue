<template>
	<main class="inner-shadow-second flex-grow relative">
		<div class="absolute inset-0">
			<ChartLine />
			<ChartCircle />
		</div>
	</main>
</template>
<script setup lang="ts">
import ChartLine from '@/components/ChartLine.vue'
import ChartCircle from '@/components/ChartCircle.vue'
</script>