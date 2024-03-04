export default function start() {
    const token = "94e3f3e1ac97def632645f3655f7c9320F482674258FFE1B89D5296855D502E753290349";
    const baseUrl = "http://wl.ngmk.uz"

    wialon.core.Session.getInstance().initSession(baseUrl);
    wialon.core.Session.getInstance().loginToken(token, "", () => {
        init()
    })

    function init() {
        var sess = wialon.core.Session.getInstance();
        sess.loadLibrary("resourceReports");
        sess.updateDataFlags([
            { type: "type", data: "avl_resource", flags: wialon.item.Item.dataFlag.base | wialon.item.Resource.dataFlag.reports, mode: 0 },
            { type: "type", data: "avl_unit", flags: wialon.item.Item.dataFlag.base | wialon.item.Unit.dataFlag.lastMessage, mode: 0 },
            { type: "type", data: "avl_unit_group", flags: wialon.item.Item.dataFlag.base | wialon.item.Unit.dataFlag.lastMessage, mode: 0 }
        ], function (code) {
            const to = Math.round(new Date().getTime() / 1000)
            const from = to - 1800 * 1 - 1
            const user = sess.getItems("avl_resource")[1]
            const reportUat = user.getReports()[2]
            user.cleanupResult((event) => {
                console.log(event);
                
                user.execReport(reportUat, 7381, 0, { "from": from, "to": to, "flags": 0 }, (code, layer) => {
                    layer.selectRows(0, { type: 'range', data: { "from": 0, "to": 100, "level": 1 } }, function (con, data) {
                        console.log(data)
                    })
                })
            })
        })
    }
}
