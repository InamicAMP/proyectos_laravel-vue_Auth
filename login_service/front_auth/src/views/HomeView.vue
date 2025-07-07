<script setup>
import {ref, computed} from "vue";
import axios from "axios";

//Variable reactiva para controlar el estado del icono
const isPasswordVisible = ref(false);

const inputType = computed(() =>
    isPasswordVisible.value ? "text" : "password"

);
const iconClass= computed(() =>
    isPasswordVisible.value ? "bx bx-show" : "bx bx-hide"
);

//alternar visibilidad de la contraseña
const togglePasswordVisibility = () =>{
    isPasswordVisible.value = !isPasswordVisible.value;
};

//Variables reactivas para el formulario y mensajes de error
const email = ref("");
const password = ref("");
const errorMessage = ref("");
const isLoading = ref(false);
const logged = ref(false);

//URL del endpoint de Login
const loginUrl = import.meta.env.VITE_API_AUTH_URL + "/v1/auth/me";

//Configuración del cliente Axios
const apiClient = axios.create({
    baseURL: import.meta.env.VITE_API_URL, // URL base de la API
    headers: {
      "Content-Type": "aplication/json",
    },
});

//Agregar un interceptor para incluir el token en todas las solicitudes
apiClient.interceptors.request.use((config) =>{
        const token = localStorage.getItem('token');
        const token_type = localStorage.getItem('token_type');

        if(token && token_type){
            config.headers.Authorization = '$tokenType $token';
        }
        return config;
    },(error) => {
        return Promise.reject(error);
    }
);

//Función para obtener el perfil de usuario
const getUserProfile = async () => {
    try{
        //No es necesario repetir el encabezado aquí
        const response = await apiClient.get(meUrl);

        localStorage.setItem('user', JSON.stringify(response.data));
        logged.value = true;

        return response.data;
    }catch(error){
        localStorage.clear();

        //Redirigir al usuario 
        window.location.href = "/";
        isLoading.value = false;
        console.error("Error al obtener perfil:", error.response?.data || error.errorMessage);
        throw error;
    }
};
getUserProfile();

</script>

<template>
    <div v-if="logged" class="container-xxl">
        Este es home
    </div>
    <div v-else class="container-xxl">
        Espere...
    </div>
</template>

<style scoped>

</style>