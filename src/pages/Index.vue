<template>
  <q-page class="flex flex-center">
    <!-- <formulario/> -->
    <steps/>
  </q-page>
</template>

<script>
// import formulario from 'src/components/formulario.vue'
import steps from 'src/components/steps.vue'
export default {
  components: { steps },
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
