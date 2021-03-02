<template>
    <loginForm/>
</template>

<script>
import loginForm from 'src/components/loginForm.vue'
export default {
  components: { loginForm },
  name: 'PageIndex',
  created () {
  },
  methods: {
    myTweak (offset) {
      // "offset" is a Number (pixels) that refers to the total
      // height of header + footer that occupies on screen,
      // based on the QLayout "view" prop configuration

      // this is actually what the default style-fn does in Quasar
      return { minHeight: '10px' }
    }
  }
}
</script>
