import { defineStore } from 'pinia'
// import { iInProcess } from './intercafes'
import { ref, computed, Ref } from 'vue'
import moment from 'moment'
import axios from 'axios'
export const inATB = defineStore('inATB', () => {
    const transports: Ref = ref()
    const session = wialon.core.Session.getInstance()
    const token = "94e3f3e1ac97def632645f3655f7c9325FBF8B37B9070360D58D2FC728179D2C5ABA96B9";
    const baseUrl = "http://wl.ngmk.uz"
    const sid = session.getId()

    function getAllTrips() {
        return false
        session.initSession(baseUrl);
        session.loginToken(token, "", (code) => init())

        function init() {
            var sess = session
    
            sess.loadLibrary("resourceReports");
            sess.updateDataFlags([
                { type: "type", data: "avl_resource", flags: wialon.item.Item.dataFlag.base | wialon.item.Resource.dataFlag.reports, mode: 0 },
                { type: "type", data: "avl_unit", flags: wialon.item.Item.dataFlag.base | wialon.item.Unit.dataFlag.lastMessage, mode: 0 },
                { type: "type", data: "avl_unit_group", flags: wialon.item.Item.dataFlag.base | wialon.item.Unit.dataFlag.lastMessage, mode: 0 },
                { type: "type", data: "avl_geofence", flags: 1024, mode: 0 }

                
            ], function (code) {
                const to = Math.round(new Date().getTime() / 1000)
                const from = to - 1800 * 1 - 1
                // const user = sess.getItems("avl_resource")[1]
                // console.log(sess.getItems("avl_unit"));

                // console.log(sess.getItems('avl_geofence'));
                // const reportUat = user.getReports()[2]
                const qaynarov = sess.getItems("avl_resource")[0]
                // console.log(qaynarov)
                
                const reportUat = qaynarov.getReports()[52]
                qaynarov.cleanupResult(() => {
                    qaynarov.execReport(reportUat, 7381, 0, { "from": from, "to": to, "flags": 0 }, (code, layer) => {
                        if (layer == null) transports.value = []

                        layer.selectRows(
                            1,
                            { type: 'range', data: { "from": 0, "to": 100, "level": 1 } },
                            function (con, data) {

                                transports.value = data.map(element => {
                                    return {
                                        name: element?.c[1]?.replace('ШКБ ', ''),
                                        // dateTime: moment(element?.c[3].t), timer: null,
                                        location: element?.c[2].t
                                    }
                                })
                            }
                        )

                        // layer.selectRows(1, { type: 'range', data: { "from": 0, "to": 100, "level": 1 } }, function (con, data) {
                        //     console.log(data[0], 'row2');

                        //     // transports.value = data[0].r.map(element => {
                        //     //     return { name: element?.c[1]?.replace('ШКБ ', ''), dateTime: moment(element?.c[3].t), timer: null, location: element?.c[2].t }
                        //     // })
                        // })
                    })
                })
            })
        }
    }

    const summaTrips = computed(() => transports.value?.length)
    const ATB = computed(() => {
        return transports.value?.filter((trans) => {
            return trans.location == "УАТ"
        })
    })

    const OIL = computed(() => {
        return transports.value?.filter((trans) => {
            if (trans.location == undefined) return false
            return trans.location.toLowerCase().includes('заправочный')

        })
    })

    const SMENA = computed(() => {
        return transports.value?.filter((trans) => {
            if (trans.location == undefined) return false
            return trans.location.toLowerCase().includes('пересменка')
        })
    })

    return { transports, summaTrips, getAllTrips, ATB, OIL, SMENA }
})