<template>
  <div
    class="q-pa-md"
    style="max-width: 500px;"
  >
    <q-stepper
      ref="stepper"
      v-model="step"
      animated
      flat
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
            val => validateEmail(val),
            val => !errors.enableEmailNotRegistered || 'Não existe usuário registrado com esse e-mail'
          ]"
          label="E-mail"
          type="email"
          input-class="whmcs-wordpress-form-input"
        />
      </q-step>

      <q-step
        :name="2"
        title="Senha"
        :done="step > 2"
      >
        <q-input
          ref="password"
          v-model="password"
          label="Senha"
          type="password"
          :rules="[
            val => requiredField(val) || 'Digite sua senha de acesso',
            val => !errors.enableWrongPassword || 'Digite sua senha de acesso'
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
            v-if="step === 1"
            color="primary"
            label="Registrar"
            :href="whmcsRegistrationUrl"
            flat
            target="_blank"
          />

          <q-btn
            v-if="showResetPasswdBtn"
            flat
            style="color: #e31e17"
            label="Esqueceu a senha?"
            :loading="isResetPasswdBtnLoading"
            class="whmcs-wordpress-register-btn"
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

// eslint-disable-next-line camelcase
// const whmcs_wordpress_registration_url = 'https://whmcs.linknacional.com.br/register.php'

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
      timeBetweenRequestingPasswdReset: 300000, // 5 minutes

      // eslint-disable-next-line camelcase, no-undef
      whmcsRegistrationUrl: whmcs_wordpress_registration_url ?? ''
    }
  },

  methods: {
    nextStep () {
      if (this.step === 1) {
        if (!this.$refs.email.validate()) {
          return false
        }

        this.requestIsEmailRegistered()
      } else {
        if (!this.$refs.password.validate()) {
          return false
        }

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
            this.errors.enableEmailNotRegistered = false
          }
        })
        .catch(() => {
          this.notify(
            'negative',
            'Não foi possível verificar o e-mail digitado.',
            'Tente novamente mais tarde.'
          )
        })
        .finally(() => {
          this.isNextBtnLoading = false
        })
    },

    requestResetPassword () {
      if (this.isTimeBetweenRequestingPasswdResetDone()) {
        this.isResetPasswdBtnLoading = true

        const requestBody = { email: this.email }

        api.post('/v1/password/reset', requestBody)
          .then(res => {
            if (res.data.success) {
              LocalStorage.set('lastPasswdResetTime', new Date().getTime())

              this.notify(
                'positive',
                'E-mail de recuperação enviado.',
                'Verifique a caixa de entrada do seu e-mail.'
              )
            } else {
              this.notify(
                'negative',
                'E-mail de recuperação não enviado.',
                'Verifique se seu e-mail digitado está correto.'
              )
            }
          })
          .catch(() => {
            this.notify(
              'negative',
              'E-mail de recuperação não enviado.',
              'Aguarde um momento e tente novamente.'
            )
          })
          .finally(() => {
            this.isResetPasswdBtnLoading = false
          })
      } else {
        this.notify(
          'warning',
          'E-mail de recuperação não enviado.',
          'Aguarde 5 minutos para solicitar novamente.'
        )
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
            this.triggerPasswdError()
            this.isNextBtnLoading = false
          }
        })
        .catch(() => {
          this.notify(
            'negative',
            'Não foi possível efetuar o login.',
            'Tente novamente mais tarde.'
          )
          this.isNextBtnLoading = false
        })
        .finally(() => {
          //
        })
    },

    isTimeBetweenRequestingPasswdResetDone () {
      const lastPasswdResetTime = LocalStorage.getItem('lastPasswdResetTime') ?? null
      const currentTime = new Date().getTime()

      return currentTime - lastPasswdResetTime >= this.timeBetweenRequestingPasswdReset
    },

    triggerPasswdError () {
      this.errors.enableWrongPassword = true
      this.$refs.password.validate()
      this.errors.enableWrongPassword = false
    },

    notify (type, msg, caption = null) {
      Notify.create({
        type,
        position: 'top',
        message: msg,
        caption
      })
    },

    validateEmail,
    requiredField
  }
})
</script>
