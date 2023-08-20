<template>
    <div class="login">
        <span class="login-header">Вход</span>
        <input
            class="nick-input"
            type="text"
            placeholder="Введите имя"
            v-model="nick"
            @keyup.enter="login"
        />
        <input
            class="login-button"
            type="button"
            @click="login"
            value="Войти"
        >
    </div>
</template>

<script setup>
import { ref } from "vue";

const emit = defineEmits(['join']);
const nick = ref('');

const login = () => {
    localStorage.setItem('nick', nick.value);
    emit('join')
}

</script>

<style scoped>
.login {
    display: flex;
    flex-direction: column;
    max-width: 20rem;
    margin: 0 auto;
    padding: 1rem;
    border-radius: 1rem;
    box-shadow: 0.125rem 0.25rem 1rem 0 #00000012;
    transition: 0.25s;
}

.login:hover {
    box-shadow: 0.125rem 0.25rem 1rem 0 #00000018;
}

.login-header {
    margin-bottom: 1.5rem;
    font-size: 1.25rem;
    font-weight: 700;
}

.nick-input {
    margin-bottom: 0.75rem;
    padding: 0.5rem 0.75rem;
    border-radius: 1rem;
    border: 1px solid #ddd;
    transition: 0.25s;
}

.nick-input:hover, .nick-input:focus-visible {
    border: 1px solid #eee;
    outline: none;
}

.login-button {
    border: none;
    border-radius: 1rem;
    color: #FFFFFF;
    background: #2780b9;
    padding: 0.5rem;
    font-size: 1rem;
    font-weight: 600;
    transition: 0.25s;
}

.login-button:hover {
    background: #2299DD;
}
</style>
