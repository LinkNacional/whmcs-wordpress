<template>
  <div v-bind:class="varsFromWordpress.divSize ? varsFromWordpress.divSize : 'q-pa-xl' ">
      <q-stepper
      v-model="step"
      ref="stepper"
      color="primary"
      animated
      done-color="green"
      :header-nagivation="false"
      no-header-navigation
    >
      <q-step
        :name="1"
        icon="email"
        :done="step > 1"
        title="email"
      >
    <div v-if="varsFromWordpress.textHeader" class="q-ma-none q-pb-lg">
      <div class="text-h4 text-weight-thin">{{varsFromWordpress.textHeader}}</div>
      <hr />
    </div>
        <q-input
        filled
        autofocus
        v-model="formulario.email"
        ref="email"
        type="email"
        :label="varsFromWordpress.strings['emailLabel']"
        :rules="[ val => val.email !== '' || varsFromWordpress.strings['emailErrorNull'],
        val => val.indexOf('@') !== -1 || varsFromWordpress.strings['emailErrorNotDomain'],
        val => errorInput.errorEmail || varsFromWordpress.strings['emailErrorNotUser'],
        val => val.indexOf('@') !== val.length - 1 || varsFromWordpress.strings['emailErrorNotValid']]"
        @keydown.enter.prevent="nextStep"
        lazy-rules="ondemand"
        :loading="loadingState"
        />

      </q-step>
       <q-step :name="2" icon="password" :done="step > 2" title="Senha" >
      <div v-if="varsFromWordpress.textHeader" class="q-ma-none q-pb-lg">
      <div class="text-h4 text-weight-thin">{{varsFromWordpress.textHeader}}</div>
      <hr />
    </div>
       <q-input
        v-model="formulario.senha"
        filled
        ref="senha"
        :label="varsFromWordpress.strings['passwordLabel']"
        :type="isPwd ? 'password' : 'text'"
        @keydown.enter.prevent="nextStep"
        autocomplete="off"
        :rules="[ val => errorInput.errorPassword || varsFromWordpress.strings['passwordError'],checkButton]"
        :loading="loadingState"
        lazy-rules="ondemand" >
        <template v-slot:append>
          <q-icon
            :name="isPwd ? 'visibility_off' : 'visibility'"
            class="cursor-pointer"
            @click="isPwd = !isPwd"
          />
        </template>
      </q-input>
      </q-step>
      <template v-slot:navigation>
        <q-stepper-navigation>
          <div class="row">
              <q-btn class="col-3 responsive-button" @click="nextStep()" style="color:##0C71C3;min-width:5%;max-height: 35px;" color="primary"  :label="step === 2 ? varsFromWordpress.strings['btnEnter'] : varsFromWordpress.strings['btnNext']" />

            <div v-if="step == 1" class="col-7 responsive-div"></div>
            <div v-if="BtnPassword" class="col-5 responsive-div"></div>

              <q-btn class="col-2 responsive-button" v-if="step == 1" flat color="primary" v-on:click="redirect_register()" :label="varsFromWordpress.strings['btnRegister']" />

              <q-btn class="col-4 responsive-button" v-if="BtnPassword" flat v-on:click="redirect_password()" style="color:#E31E17" :label="varsFromWordpress.strings['btnPassword']" />
          </div>
        </q-stepper-navigation>
      </template>
    </q-stepper>
  </div>
</template>

<script>
import { axiosInstance } from 'boot/axios'
import { textHeader, divSize, strings } from 'boot/vars'

export default {
  data () {
    return {
      step: 1,
      loadingState: false,
      isPwd: true,
      urls: '',
      BtnPassword: false,
      formulario: {
        senha: '',
        email: '',
        idCliente: '',
        passwordhash: ''
      },
      errorInput: {
        errorEmail: true,
        errorPassword: true
      },
      varsFromWordpress: {
        textHeader: textHeader,
        divSize: divSize,
        strings: strings
      }
    }
  },
  methods: {
    async login () {
      this.$refs.senha.validate()
      if (this.$refs.senha.hasError || this.formulario.senha === '') {
        this.errorInput.errorPassword = false
        this.$refs.senha.validate()
        this.errorInput.errorPassword = true
        this.BtnPassword = true
      } else {
        this.loadingState = true
        axiosInstance.post('', { action: 'ValidateLogin', email: this.formulario.email, senha: this.formulario.senha, idCliente: this.formulario.idCliente })
          .then((response) => {
            if (response.data.result === 'success') {
              this.url = response.data.redirect_url
              window.location.href = this.url
              this.loadingState = false
            } else if (response.data.result === 'password') {
              this.BtnPassword = true
              this.loadingState = false
              this.errorInput.errorPassword = false
              this.$refs.senha.validate()
              this.errorInput.errorPassword = true
            }
          })
          .catch((error) => {
            this.loadingState = false
            console.log(error)
          })
      }
    },
    nextStep () {
      if (this.step === 1) {
        this.$refs.email.validate()
        if (this.$refs.email.hasError || this.formulario.email === '') {
          this.$refs.email.validate()
          // this.mostrarMensagem('Para continuar, por favor informe um email válido.')
          this.$refs.email.focus()
        } else {
          this.checkEmail()
        }
      } else {
        this.login()
      }
    },
    async checkEmail () {
      this.loadingState = true
      axiosInstance.post('', { action: 'checkEmail', email: this.formulario.email })
        .then((response) => {
          if (response.data.result === 'success') {
            this.formulario.idCliente = response.data.clients.client[0].id
            this.$refs.email.validate()
            this.$refs.stepper.next()
            setTimeout(() => {
              this.$refs.senha.focus()
            }, 200)
          } else if (response.data.result === 'notin') {
            this.errorInput.errorEmail = false
            this.$refs.email.validate()
            this.errorInput.errorEmail = true
          }
          this.loadingState = false
        }).catch((error) => {
          this.loadingState = false
          console.log(error)
        })
    },
    redirect_password () {
      axiosInstance.post('', { action: 'url_redirect' })
        .then((response) => {
          this.urls = response.data
          window.location.href = this.urls.password
        })
    },
    redirect_register () {
      axiosInstance.post('', { action: 'url_redirect' })
        .then((response) => {
          this.urls = response.data
          window.location.href = this.urls.register
        })
    }
  }
}
</script>
