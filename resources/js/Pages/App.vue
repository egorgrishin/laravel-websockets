<template>
    <Header v-model:nick="nick"/>
    <div v-if="!isLogged" class="auth full-page">
        <Login @join="joinToChannel"/>
    </div>
    <div v-else class="messenger full-page">
        <ChatList v-model:users="users"/>
        <Dialog/>

    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import ChatList from "../Components/ChatList.vue";
import Dialog from "../Components/Dialog.vue";
import Login from "../Components/Login.vue";
import Header from "../Components/Header.vue";

const users = ref([]);
const nick = ref(localStorage.getItem('nick') ?? '');
const isLogged = computed(() => {
    channelHandler();
    return Boolean(nick.value);
});

const joinToChannel = () => nick.value = localStorage.getItem('nick');
const channelHandler = () => {
    Echo.options.auth.headers.id = localStorage.getItem('id');
    Echo.options.auth.headers.nickname = nick.value;
    if (!Boolean(nick.value)) {
        Echo.leave('online');
        return;
    }
    Echo.join('online')
        .here(onlineUsers => users.value = onlineUsers)
        .joining(user => users.value.push(user))
        .leaving(user => users.value = users.value.filter(u => u.id !== user.id));
}
</script>

<style scoped>
.full-page {
    display: flex;
    min-height: calc(100vh - 3rem);
}

.auth {
    flex-direction: column;
    align-content: center;
    justify-content: center;
}

.messenger {
    padding: 1rem 2rem;
    flex-direction: row;
    box-sizing: border-box;
}
</style>
