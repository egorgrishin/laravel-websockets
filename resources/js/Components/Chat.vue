<template>
    <div class="dialog" v-if="selectedUser">
        <div class="selected-user">
            <span>{{ selectedUser?.nickname ?? '' }}</span>
        </div>
        <div class="chat" id="chat">
            <p
                v-for="message of messages"
                :class="{
                    my: message.fromId === currentUser.id,
                    other: message.fromId !== currentUser.id,
                }"
            >{{ message.message }}</p>
        </div>
        <div class="text-field">
            <textarea
                rows="1"
                placeholder="Введите сообщение..."
                v-model="message"
                @keydown.enter.exact.prevent="sendMessage"
                @input="resizeTextarea"
            ></textarea>
            <button @click="sendMessage">S</button>
        </div>
    </div>
    <div class="dialog empty-dialog" v-else>
        <span>Диалог не выбран</span>
    </div>
</template>

<script setup>
import {computed, ref, toRefs, watch} from "vue";

const id = localStorage.getItem('id');
const props = defineProps({
    currentUser: Object,
    selectedUser: Object,
});
const { currentUser, selectedUser } = toRefs(props);
const message = ref('');
const messages = ref([]);
const usersCount = ref(0);
const isActive = computed(() => usersCount.value === 2);

watch(selectedUser, (newUser, oldUser) => {
    // При изменении собеседника очищаем чат
    if (newUser !== oldUser) {
        messages.value = [];
    }
    // Если мы были подключены к диалогу - покидаем канал
    if (oldUser) {
        Echo.leave(getDialogChannelName(oldUser.id));
        usersCount.value = 0;
    }
    // Если мы выбрали пользователя для диалога - подключаемся к каналу
    if (newUser) {
        joinToDialog(newUser);
    }
});

// Отправляет сообщение в чат
const sendMessage = () => {
    if (message.value === '') {
        return;
    }

    sendMessageToChat();
    messages.value.push({ fromId: id, message: message.value});
    message.value = '';
    setChatScroll();
}

// Отправляет сообщение собеседнику в чат
const sendMessageToChat = () => {
    Echo.join(getDialogChannelName(selectedUser.value.id))
        .whisper('new-message', {
            fromId: id,
            message: message.value,
        });
}

// Присоединяется к каналу диалога
const joinToDialog = (user) => {
    const channelName = getDialogChannelName(user.id);
    const channel = Echo.join(channelName);
    channel
        .here(users => usersCount.value = users.length)
        .joining(() => {
            usersCount.value++;
            // Отправляем собеседнику существующие в чате сообщения
            channel.whisper('sync-messages', messages.value)
        })
        .leaving(() => usersCount.value--)
        .listenForWhisper('new-message', message => {
            messages.value.push(message);
            setChatScroll();
        })
        .listenForWhisper('sync-messages', oldMessages => {
            messages.value = oldMessages;
            setChatScroll();
        })
}

// Возвращает название канала
const getDialogChannelName = (userId) => {
    const ids = [+id, +userId].sort((a, b) => a - b);
    return `dialog.${ids[0]}.${ids[1]}`;
}

// Устанавливает скролл в диалоге на последнее сообщение
const setChatScroll = () => {
    setTimeout(() => {
        const chat = document.getElementById('chat');
        if (chat) {
            chat.scrollTop = chat.scrollHeight;
        }
    }, 25);
};

// Изменяет высоту textarea
function resizeTextarea(event) {
    event.target.style.height = 'auto';
    event.target.style.height = event.target.scrollHeight + 'px';
}
</script>

<style scoped>
.dialog {
    width: 100%;
    max-height: calc(100vh - 5rem);
    padding: 0.5rem 1rem;
    margin-left: 2rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    border-radius: 1rem;
    box-shadow: 2px 4px 12px #00000018;
    box-sizing: border-box;
}

.selected-user {
    padding: 0.5rem 1rem;
    font-weight: 400;
    border-bottom: 1px solid #eee;
}

.chat {
    max-height: calc(100% - 7rem);
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

.chat p {
    white-space: pre-line;
}

.text-field {
    min-height: 4rem;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: stretch;
}

.text-field textarea {
    padding: 0.5rem 0.75rem;
    max-height: 4.75rem;
    width: 100%;
    border: none;
    border-radius: 0.25rem;
    box-shadow: inset 0 0 0 1px #dddddd;
    font-size: 1rem;
    resize: none;
    transition: 0.25s;
    box-sizing: border-box;
}

.text-field textarea:hover, .text-field textarea:focus-visible {
    box-shadow: inset 0 0 0 1px #eeeeee;
    outline: none;
}

.text-field button {
    margin: auto 0 0 1rem;
    border: none;
    background: #2780b9;
    color: #fff;
    border-radius: 3rem;
    width: 3rem;
    height: 3rem;
    flex-shrink: 0;
}

.empty-dialog {
    justify-content: center;
    align-items: center;
}

.empty-dialog span {
    font-weight: 200;
    font-style: italic;
}

.my {
    background: #9fcdff;
    margin: 0.5rem 0 0.5rem auto;
    max-width: 75%;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
}

.other {
    background: #eee;
    max-width: 75%;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    margin: 0.5rem auto 0.5rem 0;
}
</style>
