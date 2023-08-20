<template>
    <div class="logout">
        <span>{{ nick }}</span>
        <button>Выйти</button>
    </div>
    <input type="button" @click="logout" value="Выход">
</template>

<script setup>
const nick = localStorage.getItem('nick');
</script>

<style scoped>
.logout {
    display: flex;
    flex-direction: row;
}

.logout span {

}

.logout button {

}
</style>
