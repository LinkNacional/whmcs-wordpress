<template>
  <q-page class="flex flex-center">
    <formulario/>
  </q-page>
</template>

<script>
import formulario from 'src/components/formulario.vue'
export default {
  components: { formulario },
  name: 'PageIndex',
  plano: null,
  created () {
  },
  methods: {
    onUpdateTextColor () {
      this.$on('continue', (formulario) => {
        console.log(formulario)
      })
    }
  }
}
</script>
