<template>
	<main class="inner-shadow-second flex-grow relative min-w-80">
		<div class="absolute inset-0 overflow-y-auto scroll indigo-scroll overflow-hidden">
			<ChartLine />
			<ChartCircle chartname="Faol avtoag'dargichlar" :startcolor="'#01b0b0'" :endcolor="'#0198f7'" :process="'summaTransports'" />
			<ChartCircle chartname="Faol ekskavatorlar" :startcolor="'#01b0b0'" :endcolor="'#2dd4bf'" :process="'summaExcavators'"/>
		</div>
	</main>
</template>
<script setup lang="ts">
import ChartLine from '@/components/ChartLine.vue'
import ChartCircle from '@/components/ChartCircle.vue'
</script>