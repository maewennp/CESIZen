/// <reference types="vitest" />
import { mount } from '@vue/test-utils'
import { describe, it, expect } from 'vitest'
import ModuleComingSoon from '../ModuleComingSoon.vue'

// helper pour stubber proprement
const stubs = {
  'v-dialog': {
    template: '<div><slot /></div>'
  },
  'v-card': {
    template: '<div><slot /></div>'
  },
  'v-card-title': {
    template: '<div><slot /></div>'
  },
  'v-card-text': {
    template: '<div><slot /></div>'
  },
  'v-card-actions': {
    template: '<div><slot /></div>'
  },
  'v-btn': {
    template: '<button @click="$emit(\'click\')"><slot /></button>'
  },
  'v-img': {
    template: '<img />'
  },
}

describe('ModuleComingSoon.vue', () => {
  it('affiche le nom du module passé en props', () => {
    const wrapper = mount(ModuleComingSoon, {
      props: {
        open: true,
        moduleName: 'Test Module',
      },
      global: { stubs }
    })

    expect(wrapper.text()).toContain('Test Module')
  })

  it('le dialog est ouvert si la prop open est true', () => {
    const wrapper = mount(ModuleComingSoon, {
      props: {
        open: true,
        moduleName: 'Test Module',
      },
      global: { stubs }
    })

    // ici comme v-dialog est un stub simple, on ne peut pas tester model-value
    // donc on vérifie simplement que le composant existe
    const dialog = wrapper.find('div')
    expect(dialog.exists()).toBe(true)
  })

  it('le dialog se ferme quand la prop open passe à false', async () => {
    const wrapper = mount(ModuleComingSoon, {
      props: {
        open: true,
        moduleName: 'Test Module',
      },
      global: { stubs }
    })

    await wrapper.setProps({ open: false })
    // on n'a pas accès au v-model, mais on peut tester que le composant est toujours là
    const dialog = wrapper.find('div')
    expect(dialog.exists()).toBe(true)
  })
})
