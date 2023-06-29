<template>
    <div v-if="uncompletedRiddleList.length > 0" class="riddle-fab" :class="{ isOpen: isOpen }">
        <a class="riddle-fab__button" @click="handleFabClick">
            <RiddleIcon />

            <div class="riddle-fab__button__badge">{{ uncompletedRiddleList.length
            }}</div>
        </a>

        <div class="riddle-fab__list">
            <div class="riddle-fab__list__item" v-for="riddle in uncompletedRiddleList" :key="riddle.UUID">
                <a @click="handleRiddleClick(riddle)">
                    {{ riddle.title }}
                </a>
            </div>
        </div>
    </div>

    <div v-if="selectedRiddle" class="riddle-modal">
        <div class="riddle-modal__riddle-wrapper">
            <RiddleEmbed :riddleId="selectedRiddle.UUID" />
        </div>

        <a class="riddle_modal__close-button" @click="handleCloseClick">
            close
        </a>
    </div>
</template>

<script setup lang="ts">
import { type RiddleDTO } from '@/types/DTO'
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import RiddleEmbed from './RiddleEmbed.vue';
import { api } from '@/api/api';
import RiddleIcon from './RiddleIcon.vue';
import { useRiddleStore } from '@/stores/riddle';

const riddleStore = useRiddleStore()

const riddleList = ref<RiddleDTO[]>([])
const timeout = ref<number | null>(null)
const isOpen = ref<boolean>(false)
const selectedRiddle = ref<RiddleDTO | null>(null)

const uncompletedRiddleList = computed(() => {
    return riddleList.value.filter(riddle => riddleStore.isRiddleCompleted(riddle.UUID) === false)
})

const handleRiddleClick = (riddle: RiddleDTO) => {
    selectedRiddle.value = riddle;
    isOpen.value = false;
}

const handleCloseClick = () => {
    selectedRiddle.value = null;
}

const handleFabClick = () => {
    if (riddleList.value.length > 1) {
        isOpen.value = !isOpen.value;
    } else if (riddleList.value.length === 1) {
        handleRiddleClick(riddleList.value[0])
    }
}

onMounted(async () => {
    console.log('Riddle Floating Button mounted');

    const user = await api.fetchFakeUser();

    const script = document.createElement('script')
    script.id = 'riddle-embed-js'
    script.src = 'https://www.riddle.com/embed/build-embedjs/embedV2.js'
    script.async = true;
    document.head.appendChild(script)

    // @ts-ignore
    window.riddleDataLayer = [{
        key: 'user',
        value: user.UUID
    }]

    try {
        riddleList.value = await api.fetchRiddleList()

        timeout.value = setInterval(async () => {
            riddleList.value = await api.fetchRiddleList()
        }, 5000)
    } catch (error) { /* empty */ }
})

onBeforeUnmount(() => {
    const script = document.getElementById('riddle-embed-js')
    if (script) {
        document.head.removeChild(script)

        // @ts-ignore
        // delete riddle2API
        delete window.riddle2API;
    }
})

onBeforeUnmount(() => {
    if (timeout.value) {
        clearTimeout(timeout.value)
    }
})
</script>

<style scoped lang="scss">
.riddle-fab {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: flex-end;
    pointer-events: none;
    width: 53px;
    height: 53px;

    &__button {
        pointer-events: all;
        width: 53px;
        height: 53px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        position: absolute;
        bottom: 0;
        right: 0;
        background-color: rgb(230, 230, 230);
        padding: 10px;

        svg {
            width: 100%;
            height: 100%;
        }

        &:hover {
            background-color: rgb(240, 240, 240);
            transform: scale(1.1);
        }

        &__badge {
            position: absolute;
            top: 0;
            right: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #4F4BC0;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        }
    }

    &__list {
        pointer-events: all;
        width: auto;
        height: auto;
        background-color: #fff;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: flex-end;
        padding: 20px;
        box-sizing: border-box;
        transition: all 0.2s ease-in-out;
        transform: scale(0);
        opacity: 0;
        position: absolute;
        bottom: 53px;
        min-width: 300px;

        &__item {
            margin-bottom: 10px;
            font-size: 14px;
            color: #4F4BC0;
            cursor: pointer;
            transition: all 0.2s ease-in-out;

            &:hover {
                color: #3e3aa3;
            }
        }
    }

    &.isOpen {

        .riddle-fab__list {
            transform: scale(1);
            opacity: 1;
        }
    }
}

.riddle-modal {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    width: 100vw;
    height: 100vh;
    background-color: rgb(230, 230, 230, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;

    &__riddle-wrapper {
        background-color: #fff;
        min-width: 100px;
        min-height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .riddle_modal__close-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1001;
        background-color: #fff;
        color: #4F4BC0;
        padding: 10px;
        border-radius: 50%;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        width: 53px;
        height: 53px;
        line-height: 33px;

        &:hover {
            background-color: #4F4BC0;
            color: #fff;
        }
    }
}
</style>