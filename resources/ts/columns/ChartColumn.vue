<template>
	<main class="inner-shadow-second flex-grow relative">
		<div class="absolute inset-0">
			<ChartLine />
			<ChartCircle chartname="Faol avtoag'dargichlar" :startcolor="'#01b0b0'" :endcolor="'#0198f7'" :process="state.summaTransports" />
			<ChartCircle chartname="Faol ekskavatorlar" :startcolor="'#01b0b0'" :endcolor="'#2dd4bf'"/>
		</div>
	</main>
</template>
<script setup lang="ts">
import ChartLine from '@/components/ChartLine.vue'
import ChartCircle from '@/components/ChartCircle.vue'

import { Transports } from '@/entities/transports'
const state = Transports()
</script>