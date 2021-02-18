<template>
  <q-page class="">
    <loginForm :url="$route.params.url"/>
  </q-page>
</template>

<script>
import loginForm from 'src/components/loginForm.vue'
export default {
  components: { loginForm },
  name: 'PageIndex',
  created () {
  },
  methods: {
  }
}
</script>
