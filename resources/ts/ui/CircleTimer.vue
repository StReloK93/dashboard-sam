<template>
	<div class="item">
		<svg class="-rotate-90" :width="coords.size" :height="coords.size">
			<g>
				<circle ref="animation" :class="color" class="ease-linear duration-1000" :r="props.radius" :cy="coords.coor" :cx="coords.coor"
					stroke-width="3" fill="none" />
			</g>
		</svg>
	</div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
const props = defineProps(['radius', 'color', 'timer'])
const emit = defineEmits(['start'])
const coords = computed(() => {
	const coor = props.radius + 2
	const size = coor * 2
	return { coor: coor, size: size }
})


const animation = ref()

function restart(timeout = null) {
	emit('start')
	const svg = animation.value
	
	const face = svg.getAttribute('r') * Math.PI * 2
	var i = 1
	svg.style.strokeDashoffset = face
	svg.style.strokeDasharray = face

	setTimeout(() => {
		var interval = setInterval(function () {
		if (i == props.timer) {
			clearInterval(interval)
			return restart(1000)
		}
		svg.style.strokeDashoffset = face - ((i + 1) * (face / props.timer))
		i++
	}, 1000)
	}, timeout);

}

onMounted(() => {
	if(props.timer) restart()
})
</script>