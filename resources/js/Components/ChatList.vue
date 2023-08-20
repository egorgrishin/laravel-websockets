<template>
    <div class="user-list">
        <div class="current-user">
            <span>{{ currentUser?.nickname }}</span>
        </div>
        <ul>
            <li
                class="user-list__user"
                :class="{ selected: user.id === selectedUser?.id }"
                v-for="user of otherUsers"
                :key="user.id"
                @click="selectUser(user)"
            >
                <span>{{ user.nickname }}</span>
            </li>
            <li class="user-list__user" v-if="!otherUsers.length">
                <span class="empty-online">Пользователей в сети сейчас нет</span>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { computed, ref, toRefs } from "vue";

const props = defineProps({
    users: Array
})
const { users } = toRefs(props);
const emit = defineEmits(['selectUser']);
const selectedUser = ref(null);

const id = localStorage.getItem('id');
const currentUser = computed(() => users.value.find(user => user.id === id));
const otherUsers = computed(() => users.value.filter(user => user.id !== id));

const selectUser = (user) => {
    selectedUser.value = user;
    emit('selectUser', user);
}
</script>

<style scoped>
.user-list {
    max-width: 240px;
    width: 100%;
    padding: 0.5rem 1rem;
    border-radius: 1rem;
    box-sizing: border-box;
    box-shadow: 2px 4px 12px #00000018;
    overflow-y: auto;
    max-height: calc(100vh - 5rem);
}

.current-user {
    padding: 0.5rem 1rem;
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
    font-weight: 700;
    text-align: center;
}

.user-list ul {
    padding: 0;
    margin-bottom: 0;
}

.user-list__user {
    padding: 0.75rem 0;
    list-style: none;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: 0.2s;
}

.user-list__user:last-child {
    border-bottom: none;
}

.selected {
    font-weight: 700;
}

.empty-online {
    font-weight: 200;
    font-style: italic;
}
</style>
