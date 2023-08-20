<template>
    <ChatList
        v-model:users="users"
        @select-user="selectUser"
    />
    <Chat
        v-model:selected-user="selectedUser"
        v-model:current-user="currentUser"
    />
</template>

<script setup>
import Chat from "./Chat.vue";
import ChatList from "./ChatList.vue";
import { computed, ref, toRefs } from "vue";

const props = defineProps({
    users: Array
})
const { users } = toRefs(props);
const id = localStorage.getItem('id');
const currentUser = computed(() => users.value.find(user => user.id === id));
const selectedUser = ref(null);

const selectUser = (user) => selectedUser.value = user;
</script>
