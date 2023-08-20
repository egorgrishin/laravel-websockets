<template>
    <header>
        <span class="logo">Messenger</span>
        <div v-if="nick" class="logout">
            <button @click="logout">Выход</button>
        </div>
    </header>
</template>

<script setup>
const emit = defineEmits(['update:nick'])
defineProps({
    nick: String
});

const logout = () => {
    localStorage.removeItem('nick');
    emit('update:nick', '')
}
</script>

<style scoped>
header {
    height: 3rem;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    padding: 0 2rem;
    background: #2980b9;
}

header * {
    color: #FFFFFF;
}

.logo {
    font-size: 1.25rem;
    font-weight: 700;
}

.logout button {
    border: none;
    background: none;
    font-size: 1rem;
    font-weight: 300;
}
</style>
