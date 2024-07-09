<template>
	<main ref="chartColumn" class="inner-shadow-second flex-grow relative min-w-80">
		<div class="absolute inset-0 overflow-y-auto scroll indigo-scroll overflow-hidden pt-8">
			<ChartLine />
			<ChartCircle v-if="transportState.cars.length != 0" chartname="Faol avtoag'dargichlar" :startcolor="'#01b0b0'"
				:endcolor="'#0198f7'" v-model="transportState.summaTransports" />
			<ChartCircle v-if="excavatorState.ExcavatorList.length != 0" chartname="Faol ekskavatorlar"
				:startcolor="'#01b0b0'" :endcolor="'#2dd4bf'" v-model="excavatorState.informationProsent" />
		</div>
	</main>
</template>
<script setup lang="ts">
import ChartCircle from '@/components/ChartCircle.vue'
import ChartLine from '@/components/ChartLine.vue'
import { Transports } from '@/entities/transports'
import { Excavators } from '@/entities/transports/Excavators'
const transportState = Transports()
const excavatorState = Excavators()
</script>