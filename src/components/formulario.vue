<template>
  <div class="q-px-xs">
    <div class="q-ma-none q-pb-lg">
            <div class="text-h4 text-weight-thin">Login:</div>
      <br />
    </div>
    <q-form class="q-gutter-md" v-on:submit.prevent="login">
      <q-input
        filled
        lazy-rules
        autofocus
        v-model="formulario.email"
        type="email"
        label="Seu email"
        hint="Insira um email válido"
      />

      <q-input
        v-model="formulario.senha"
        filled
        lazy-rules
        type ="password"
        hint="Criar uma senha de acesso"
      >
        <template v-slot:append>
          <q-icon
            :name="isPwd ? 'visibility_off' : 'visibility'"
            class="cursor-pointer"
            @click="isPwd = !isPwd"
          />
        </template>
      </q-input>

      <div>
        <q-btn label="Continuar" type="submit" color="primary"/>
        <q-btn
          label="Mais informações"
          type="reset"
          color="primary"
          flat
          class="q-ml-sm"
          v-if="false"
        />
      </div>
    </q-form>
  </div>
</template>

<style></style>

<script>
// import { email, required, senha } from '../utils/validations'

export default {
  data () {
    return {
      isPwd: true,
      formulario: {
        senha: '',
        email: ''
      }
    }
  },
  methods: {
    login () {
      console.log(this.formulario)

      // this.$http.post(url, this.formulario)
      //   .then((response) => {
      //     console.log(response, 'funcionou')
      //   })
      //   .catch((error) => {
      //     console.log(error, 'nao funcionou')
      //   })
    }
  },
  name: 'formulario'
}
</script>
