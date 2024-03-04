<template>
    <div ref="mapdiv" class="absolute inset-0 opacity-100"></div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { Geofences } from '@/entities/geofences'
import { Transports } from '@/entities/transports'
const geofencesStore = Geofences()
const transportsStore = Transports()
const mapdiv = ref()

function color(bool) {
    if (bool) return 'green'
    return 'red'
}
onMounted(async () => {

    var map = L.map(mapdiv.value).setView([42.288903, 63.857793], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {}).addTo(map);
    const zones = await geofencesStore.getGeofenses()

    zones.forEach(geo => {

        if (geo.points.length == 1) {
            const circleInfo = geo.points[0]
            const circle = L.circle([circleInfo.y, circleInfo.x], { radius: circleInfo.r, color: 'green', weight: 0.85 }).addTo(map);
            circle.bindTooltip(geo.name)
            
        }
        else {
            const points = geo.points.map(point => [point.y, point.x]);
            const zone = L.polygon(points, { color: 'green', weight: 0.5 }).addTo(map);
            zone.bindTooltip(geo.name)
        }
    })

    const transports = await transportsStore.getTransports()
    transports.forEach((car) => {
        const circle = L.circle([car.y, car.x], { radius: 2, color: color(car.geozone), weight: 0.85 }).addTo(map)
        circle.bindTooltip(`${car.name} ${car.geozone}`)
    })
    

})
</script>