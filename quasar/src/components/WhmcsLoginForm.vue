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
          type="password"
          :rules="[
            val => !errors.enableWrongPassword || translations.passwordError
          ]"
        />
      </q-step>

      <template #navigation>
        <q-stepper-navigation class="flex justify-between">
          <q-btn
            color="primary"
            :label="step === 2 ? 'Entrar' : 'Continuar'"
            :loading="isNextBtnLoading"
            @click="nextStep()"
          />
          <q-btn
            v-if="showForgetPasswdBtn"
            flat
            style="color: #e31e17"
            :label="translations.labelBtnForgetPasswd"
            @click="requestResetPassword()"
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
      step: ref(2)
    }
  },

  data () {
    return {
      email: 'ferreira.bruno@linknacional.com',
      password: String(),
      isNextBtnLoading: false,
      showForgetPasswdBtn: true,

      errors: {
        enableEmailNotRegistered: false,
        enableWrongPassword: false
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

      this.isNextBtnLoading = true

      api.post('/v1/login', requestBody)
        .then(res => {
          if (res.data.success) {
            window.location.href = res.data.redirectUrl
          } else {
            this.showForgetPasswdBtn = true
          }
        })
        .catch(() => {
          //
        })
        .finally(() => {
          this.isNextBtnLoading = false
        })
    },

    validateEmail,
    requiredField
  }
})
</script>
