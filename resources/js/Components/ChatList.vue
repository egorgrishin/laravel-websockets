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
        </ul>
    </div>
</template>

<script setup>
import {computed, ref, toRefs, watch} from "vue";

const props = defineProps({
    users: Array
})
const { users } = toRefs(props);
watch(users, (n) => console.log(n));
const emit = defineEmits(['selectUser']);
const selectedUser = ref(null);

const id = localStorage.getItem('id');
const currentUser = computed(() => {
    // console.log(users.value);
    return users.value.find(user => user.id === id)
});
const otherUsers = computed(() => users.value.filter(user => user.id !== id));
// const otherUsers = [
//     {id: 1, nickname: "Name 1 Surname"},
//     {id: 2,nickname: "Name 2 Surname"},
//     {id: 3,nickname: "Name 3 Surname"},
//     {id: 4,nickname: "Name 4 Surname"},
//     {id: 5,nickname: "Name 5 Surname"},
//     {id: 6,nickname: "Name 6 Surname"},
//     {id: 7,nickname: "Name 7 Surname"},
//     {id: 8,nickname: "Name 8 Surname"},
//     {id: 9,nickname: "Name 1 Surname"},
//     {id: 10,nickname: "Name 2 Surname"},
//     {id: 11,nickname: "Name 3 Surname"},
//     {id: 12,nickname: "Name 4 Surname"},
//     {id: 13,nickname: "Name 5 Surname"},
//     {id: 14,nickname: "Name 6 Surname"},
//     {id: 15,nickname: "Name 7 Surname"},
//     {id: 16,nickname: "Name 8 Surname"},
// ];

const selectUser = (userId) => {
    selectedUser.value = userId;
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
    background: #eee;
}
</style>
