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
          input-class="whmcs-wordpress-form-input"
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
          input-class="whmcs-wordpress-form-input"
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
            v-if="showResetPasswdBtn"
            flat
            style="color: #e31e17"
            :label="translations.labelBtnForgetPasswd"
            :loading="isResetPasswdBtnLoading"
            @click="requestResetPassword()"
          />
        </q-stepper-navigation>
      </template>
    </q-stepper>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { LocalStorage, Notify } from 'quasar'
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
      email: '',
      password: '',
      isNextBtnLoading: false,
      showResetPasswdBtn: false,
      isResetPasswdBtnLoading: false,

      errors: {
        enableEmailNotRegistered: false,
        enableWrongPassword: false
      },
      translations,
      timeBetweenRequestingPasswdReset: 5000 // TODO: set to five minutes.
    }
  },

  methods: {
    nextStep () {
      if (this.step === 1) {
        if (!this.$refs.email.validate()) {
          return false
        } else {
          this.requestIsEmailRegistered()
        }
      } else {
        this.requestLogin()
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
      const lastPasswdResetTime = LocalStorage.getItem('lastPasswdResetTime') ?? null
      const currentTime = new Date().getTime()

      const passedInternal = currentTime - lastPasswdResetTime >= this.timeBetweenRequestingPasswdReset

      if (passedInternal) {
        this.isResetPasswdBtnLoading = true

        const requestBody = { email: this.email }

        api.post('/v1/password/reset', requestBody)
          .then(res => {
            if (res.data.success) {
              LocalStorage.set('lastPasswdResetTime', new Date().getTime())
            }
          })
          .catch(() => {
            //
          })
          .finally(() => {
            this.isResetPasswdBtnLoading = false
          })
      } else {
        Notify.create({
          type: 'warning',
          message: 'Aguarde 5 minutos para solicitar novamente.'
        })
      }
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
            this.showResetPasswdBtn = true
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
