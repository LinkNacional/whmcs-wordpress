<template>
  <div
    class="q-pa-md"
    style="max-width: 500px;"
  >
    <q-stepper
      ref="stepper"
      v-model="step"
      animated
    >
      <q-step
        :name="1"
        title="E-mail"
        :done="step > 1"
      >
        <q-input
          ref="email"
          v-model="email"
          :rules="[validateEmail]"
          label="E-mail"
          type="email"
        />
      </q-step>

      <q-step
        ref="password"
        :name="2"
        title="Senha"
        :done="step > 2"
      >
        <q-input
          v-model="password"
          label="Senha"
        />
      </q-step>

      <template #navigation>
        <q-stepper-navigation>
          <q-btn
            color="primary"
            :label="step === 2 ? 'Entrar' : 'Continuar'"
            @click="nextStep()"
          />
        </q-stepper-navigation>
      </template>
    </q-stepper>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { validateEmail, requiredField } from '../utils/validators'

export default defineComponent({
  name: 'WhmcsLoginForm',

  setup () {
    return {
      step: ref(1)
    }
  },

  data () {
    return {
      email: String(),
      password: String()
    }
  },

  methods: {
    nextStep () {
      switch (this.step) {
        case 1:
          if (!this.$refs.email.validate()) {
            console.log(this.$refs.email.validate())
            return false
          } else {
            this.isEmailRegisteredInWHMCS()
          }
          break

        case 2:
          if (!this.$refs.password.validate()) {
            console.log(this.$refs.password.validate())
            return false
          }
          break
      }

      this.step++
    },

    isEmailRegisteredInWHMCS () {
      //
    },

    validateEmail,
    requiredField
  }
})
</script>
