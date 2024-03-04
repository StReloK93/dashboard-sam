import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
// @ts-ignore
window.Pusher = Pusher
class Singleton {
	
	private static instance: Singleton;
	constructor() {
		const echo = new Echo({
			// @ts-ignore
			wsHost: import.meta.env.VITE_URL,
			wsPort: 6001,
			broadcaster: 'pusher',
			// @ts-ignore
			key: import.meta.env.VITE_PUSHER_APP_KEY,
			// @ts-ignore
			cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
			disableStats: true,
			forceTLS: false,
		});
		
		return echo
	}


	public static getInstance(): Singleton {
		if (!Singleton.instance) {
			Singleton.instance = new Singleton();
		}

		return Singleton.instance;
	}
}

export default Singleton.getInstance()
