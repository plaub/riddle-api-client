<template>
    <RiddleIcon v-if="riddle2WrapperStyle.display === 'none'"></RiddleIcon>

    <div class="riddle2-wrapper" :data-rid-id="riddleId" :style="riddle2WrapperStyle">
        <iframe :src="`https://riddle.com/embed/a/${props.riddleId}?lazyImages=true`" allow="autoplay"></iframe>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import RiddleIcon from './RiddleIcon.vue';
import { useRiddleStore } from '@/stores/riddle';

const props = defineProps({
    riddleId: {
        type: String,
        required: true
    }
})

const riddle2WrapperStyle = ref({
    margin: '0 auto',
    maxWidth: '100%',
    width: '640px',
    display: 'none'
})

const isMessageListenerAdded = ref(false)
const isRiddleInited = ref(false)
const isRiddleCompleted = ref(false)

const riddleStore = useRiddleStore()

onMounted(() => {
    console.log('Riddle Embed mounted');

    if (!isMessageListenerAdded.value) {
        isMessageListenerAdded.value = true

        // add event listener for messages
        window.addEventListener('message', (event) => {
            if (event.data.isRiddle2Event && event.data.riddleId === props.riddleId) {
                if (event.data.type === 'RiddleInited' && !isRiddleInited.value) {
                    // show riddle
                    console.log('Riddle inited!');
                    riddle2WrapperStyle.value.display = 'block'
                    isRiddleInited.value = true
                }

                if (event.data.action === 'CoreMetrics' && event.data.name === 'Finish' && !isRiddleCompleted.value) {
                    // send event to your api and save completion for this riddle
                    console.log('Riddle completed!');
                    isRiddleCompleted.value = true

                    riddleStore.completeRiddle(props.riddleId)
                }
            }
        })
    }
})
</script>

<style lang="scss" scoped>
.riddle2-wrapper {
    iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
}
</style>