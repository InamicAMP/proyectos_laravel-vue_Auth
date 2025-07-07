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

//URL del endpoint de Login
const loginUrl = import.meta.env.VITE_API_AUTH_URL + "/v1/auth/login";

const login = async () => {
    isLoading.value = true;
    errorMessage.value = ""; //limpia el mensaje de error

    try{
        //Hacer la solicitud al servidor
        const response = await axios.post(loginUrl, {
            email: email.value,
            password: password.value,
        });

        //Guardar el token en LocalStorage
        const {acces_token, toke_type} = response.data;
        localStorage.setItem("token", acces_token);
        localStorage.setItem("token_type", token_type);
    }catch(error){
        //Manejar errores de la API o de la red
        if(error.response){
            //Errores de la API (por ejemplo, 401, 400)
            errorMessage.value =
            error.response.data.error || "Error en el inicio de sesión";
        }else{
            //Errores de la red
            errorMessage.value = error.message || "Error en la conexión";
        }
    }finally{
        isLoading.value = false;
    }

};
</script>

<template>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic contaner-p-y">
            <div class="authentication-inner">
                <!-- Register-->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand- justify-content-center">
                            <span class="app-brand-logo demo">
                                <img src="../assets/imp/cotiza.png" width="150"/>
                            </span>
                        </div>
                        <!-- /Logo-->
                        <div>
                            <h2 class="mb-3">Ingresa a tu cuenta</h2>
                            <form class="mb-3" methot="POST" @submit.prevent="login">
                                <div class="mb-3">
                                    <label for="emal" class="form-label">Correo electrónico</label>
                                    <input v-model="email" required type="email" class="form-control" id="email" name="email" placeholder="Ingresa tu correo electrónico" autofocus/>
                                </div>
                                <div class="mb-3 form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-larabel" for="password">Contraseña</label>
                                        <a href="#">
                                            <small>¿Olvidaste tu contraseña?</small>
                                        </a>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <input :type="inputType" id="password" v-model="password" required 
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="password"/>
                                        <span class="input-grouptext cursor-pointer" @click="togglePasswordVisibility">
                                            <i :class="iconClass"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary d-flex justify-content-center align-items-center w-100"
                                        type="submit"
                                        :disabled="isLoading">
                                        <!-- Spinner -->
                                         <span v-if="isloading" class="spinner-border text-light" role="status" aria-hiden="true">
                                         </span>
                                         <!-- Texto -->
                                          <span v-if="!isLoading">{{ "Ingresar" }}</span>
                                    </button>
                                </div>
                                <div v-if="errorMessage" class="mb-3 alert añert-danger alert-dismissible" role="alert">
                                    {{ errorMessage }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </form>
                            <p class="text-center">
                                <span>Aún no titnes cuenta?</span>
                                <RouterLink to="/register">
                                    <span>Crea una cuenta</span>
                                </RouterLink>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- /Register-->
            </div>
        </div>
    </div>

    <div>
        <h1>Estamos en Login</h1>
    </div>
</template>

<style lang="css" scoped>

</style>