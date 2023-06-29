import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useRiddleStore = defineStore('riddle', () => {
  const completedRiddles = ref<string[]>([])

  function completeRiddle(riddleId: string) {
    completedRiddles.value.push(riddleId)
  }

  function isRiddleCompleted(riddleId: string) {
    return completedRiddles.value.includes(riddleId)
  }

  return { completedRiddles, completeRiddle, isRiddleCompleted }
})
