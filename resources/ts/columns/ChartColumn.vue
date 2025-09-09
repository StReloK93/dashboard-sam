<template>
	<main ref="chartColumn" class="inner-shadow-second flex-grow relative min-w-60">
		<div class="absolute inset-0 overflow-y-auto scroll indigo-scroll overflow-hidden pt-8">
			<ChartLine :label="true"/>
			<ChartCircle v-if="transportState.DumpTrucks.length != 0" chartname="activeTrucks" :startcolor="'#01b0b0'"
				:endcolor="'#0198f7'" v-model="transportState.summaTransports" />
			<template v-if="settingCurrent.excavators">
				<ChartCircle v-if="excavatorState.ExcavatorList.length != 0" chartname="activeExcavators"
					:startcolor="'#01b0b0'" :endcolor="'#2dd4bf'" v-model="excavatorState.informationProsent" />
			</template>

			<template v-if="settingCurrent.pistali_mans">
				<ChartCircle chartname="activeMans"
					:startcolor="'#01b0b0'" :endcolor="'#2dd4bf'" v-model="transportState.summaMans" />
			</template>
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

const settingCurrent = settings
</script>