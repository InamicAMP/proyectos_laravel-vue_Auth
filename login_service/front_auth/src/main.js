import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router'

createApp(App).use(router).mount('#app') // se van a poer usar las rutas en toda la aplicación

//createApp(App).mount('#app')
