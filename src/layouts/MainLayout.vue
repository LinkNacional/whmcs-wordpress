<template>
  <q-layout view="hHh lpR fFf">
    <q-page-container>
      <transition name="slide">
        <div class="q-pa-md row items-start q-gutter-md">
          <q-card class="my-card">
            <q-card-section>
              <router-view />
            </q-card-section>
          </q-card>
        </div>
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
