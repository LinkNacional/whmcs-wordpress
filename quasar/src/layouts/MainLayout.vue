<template>
  <WhmcsLoginForm />
</template>

<script>
import { defineComponent } from 'vue'
import WhmcsLoginForm from 'src/components/WhmcsLoginForm.vue'

export default defineComponent({
  name: 'MainLayout',

  components: {
    WhmcsLoginForm
  }
})
</script>
