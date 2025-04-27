/// <reference types="vitest" />
import { mount } from '@vue/test-utils'
import { describe, it, expect, vi } from 'vitest'
import DetailsCard from '../DetailsCard.vue'

// Mock du router
vi.mock('vue-router', () => ({
  useRouter: () => ({
    back: vi.fn()
  })
}))

// Mock de Vuetify display
vi.mock('vuetify', () => ({
  useDisplay: () => ({
    mdAndUp: { value: true }
  })
}))

describe('DetailsCard.vue', () => {
  it('affiche correctement le titre et la description', () => {
    const wrapper = mount(DetailsCard, {
      props: {
        title: 'Titre Test',
        description: 'Description Test',
        image: 'https://example.com/test.jpg'
      },
      global: {
        stubs: {
          'v-img': true,
        },
      },
    })

    expect(wrapper.text()).toContain('Titre Test')
    expect(wrapper.text()).toContain('Description Test')
  })

  it('affiche l’icône de favori si showFavorite est true', () => {
    const wrapper = mount(DetailsCard, {
      props: {
        title: 'Titre Test',
        description: 'Description Test',
        image: 'https://example.com/test.jpg',
        showFavorite: true,
      },
      global: {
        stubs: {
          'v-img': true,
        },
      },
    })

    expect(wrapper.html()).toContain('mdi-heart-outline')
  })

})
