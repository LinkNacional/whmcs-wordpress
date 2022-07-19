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
          :rules="[
            val => validateEmail,
            val => !errors.enableEmailNotRegistered || translations.enableEmailNotRegistered
          ]"
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
            :loading="isNextBtnLoading"
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
import { api } from '../boot/axios'
import translations from 'src/boot/translations'

export default defineComponent({
  name: 'WhmcsLoginForm',

  setup () {
    return {
      step: ref(1)
    }
  },

  data () {
    return {
      email: 'ferreira.bruno@linknacional.com',
      password: String(),
      isNextBtnLoading: false,

      errors: {
        enableEmailNotRegistered: false
      },
      translations
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
            this.requestIsEmailRegistered()
          }
          break

        case 2:
          if (!this.$refs.password.validate()) {
            console.log(this.$refs.password.validate())
            return false
          }
          break
      }
    },

    requestIsEmailRegistered () {
      const requestBody = { email: this.email }

      this.isNextBtnLoading = true

      api.post('/v1/email/is-registered', requestBody)
        .then(res => {
          if (res.data.isRegistered) {
            this.$refs.stepper.next()
          } else {
            this.errors.enableEmailNotRegistered = true
            this.$refs.email.validate()
          }
        })
        .catch(() => {
          //
        })
        .finally(() => {
          this.isNextBtnLoading = false
        })
    },

    requestResetPassword () {
      const requestBody = { email: this.email }

      api.post('/v1/password/reset', requestBody)
        .then(res => {
        //
        })
        .catch(() => {
        //
        })
        .finally(() => {
        //
        })
    },

    requestLogin () {
      const requestBody = {
        email: this.email,
        password: this.password
      }

      api.post('/v1/login', requestBody)
        .then(res => {
          //
        })
        .catch(() => {
          //
        })
        .finally(() => {
          //
        })
    },

    validateEmail,
    requiredField
  }
})
</script>
