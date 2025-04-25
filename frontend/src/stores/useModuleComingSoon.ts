import { ref } from 'vue'

const isOpen = ref(false)
const moduleName = ref('')

export function useModuleComingSoon() {
  const openModal = (name: string) => {
    moduleName.value = name
    isOpen.value = true
  }
  const closeModal = () => {
    isOpen.value = false
    moduleName.value = ''
  }
  return {
    isOpen,
    moduleName,
    openModal,
    closeModal,
  }
}