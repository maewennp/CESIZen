/// <reference types="vitest" />
import { config, mount } from '@vue/test-utils'
import ContentCard from '../ContentCard.vue'
import { describe, it, expect } from 'vitest'

config.global.stubs = {
  'v-img': true, // juste v-img parce que v-img dépend de plein d'autres props
  // PAS les autres, v-card, v-card-text, v-icon 
}

describe('ContentCard.vue', () => {
  it('affiche correctement les props title et description', () => {
    const wrapper = mount(ContentCard, {
      props: {
        title: 'Titre test',
        description: 'Description test',
        image: 'https://example.com/image.jpg',
        showFavorite: false,
      },
    })

    expect(wrapper.text()).toContain('Titre test')
    expect(wrapper.text()).toContain('Description test')
  })

  it('émet un event "click" quand on clique sur la carte', async () => {
    const wrapper = mount(ContentCard, {
      props: {
        title: 'Titre',
        description: 'Description',
        image: 'https://example.com/image.jpg',
        showFavorite: false,
      },
    })

    await wrapper.trigger('click')

    expect(wrapper.emitted()).toHaveProperty('click')
    expect(wrapper.emitted('click')).toHaveLength(1)
  })

  it('affiche l’icône de favori si showFavorite est true', () => {
    const wrapper = mount(ContentCard, {
      props: {
        title: 'Titre',
        description: 'Description',
        image: 'https://example.com/image.jpg',
        showFavorite: true,
      },
    })

    expect(wrapper.html()).toContain('mdi-heart-outline')
  })
})
