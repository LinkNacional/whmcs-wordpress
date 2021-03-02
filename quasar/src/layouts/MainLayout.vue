<template>
  <q-layout view="hHh lpR fFf">
    <q-page-container>
      <transition name="slide">
        <router-view />
      </transition>
    </q-page-container>
  </q-layout>
</template>
<script>
export default {
  name: 'MainLayout',
  components: { },
  data () {
    return {
    }
  }
}
</script>
