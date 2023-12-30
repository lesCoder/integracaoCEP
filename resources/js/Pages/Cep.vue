

<template>
  <div class="bg-white">
    <div class="mx-auto max-w-7xl py-24 sm:px-6 sm:py-32 lg:px-8">
      <div
        class="relative isolate overflow-hidden bg-gray-900 px-6 pt-16 shadow-2xl sm:rounded-3xl sm:px-16 md:pt-24 lg:flex lg:gap-x-20 lg:px-24 lg:pt-0">
        <div class="max-w-xl lg:max-w-lg">
          <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Faça uma busca por um CEP</h2>
          <p class="mt-4 text-lg leading-8 text-gray-300">O resultado inicial será fornecido pelo serviço de API do <a
              class="text-blue-600 visited:text-purple-600" href="https://viacep.com.br/">Viacep</a></p>
          <div class="mt-6 flex max-w-md gap-x-4">
            <label for="CEP-address" class="sr-only">Número do CEP</label>
            <input v-on:input="validarInput" @keyup.enter.native="findCep(cepString)" id="CEP-address" v-model="cepString"
              name="cepString" autocomplete="cepString" required=""
              class="min-w-0 flex-auto rounded-md border-0 bg-white/5 px-3.5 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
              placeholder="Digite o CEP aqui" />

            <button @click="findCep(cepString)"
              class="flex-none rounded-md bg-indigo-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Pesquisar</button>
            <Link href="/"
              class="float-right	flex-none rounded-md bg-indigo-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
            Voltar</Link>
          </div>

          <br>
          <div v-if="error" class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
            role="alert">
            <span class="font-medium">Ocorreu um erro!</span><br>
            {{ error }}
          </div>
          <br>

        </div>
        <dl class="grid grid-cols-1 gap-x-8 gap-y-10 sm:grid-cols-2 lg:pt-2">
          <div class="flex flex-col items-start">
            <div class="rounded-md bg-white/5 p-2 ring-1 ring-white/10">
              <CalendarDaysIcon class="h-6 w-6 text-white" aria-hidden="true" />
            </div>
            <dt class="mt-4 font-semibold text-white">Back Data</dt>
            <dd class="mt-2 leading-7 text-gray-400">Apresenta o JSON Payload recebido da última consulta realizada</dd>
          </div>
          <div class="flex flex-col items-start">
            <div class="rounded-md bg-white/5 p-2 ring-1 ring-white/10">
              <HandRaisedIcon class="h-6 w-6 text-white" aria-hidden="true" />
            </div>
            <dt class="mt-4 font-semibold text-white">Mapa</dt>
            <dd class="mt-2 leading-7 text-gray-400">Integração com o GoogleMaps utilizando longitude e latitude como
              parâmentros para apontar, aproximadamente, a região do CEP</dd>
          </div>
        </dl>

      </div>
      <section class="relative isolate overflow-hidden bg-white px-6 py-24 sm:py-32 lg:px-8">
        <div
          class="absolute inset-0 -z-10 bg-[radial-gradient(45rem_50rem_at_top,theme(colors.indigo.100),white)] opacity-20" />
        <div
          class="absolute inset-y-0 right-1/2 -z-10 mr-16 w-[200%] origin-bottom-left skew-x-[-30deg] bg-white shadow-xl shadow-indigo-600/10 ring-1 ring-indigo-50 sm:mr-28 lg:mr-0 xl:mr-16 xl:origin-center" />

        <div v-if="logradouro" class="mx-auto max-w-2xl lg:max-w-4xl">

          <figure class="mt-10">
            <blockquote class="text-center text-xl font-semibold leading-8 text-gray-900 sm:text-2xl sm:leading-9">
              <p>Resultado para a pesquisa do CEP: {{ cep }}</p>
              <div class="flex flex-wrap">
                <div class="w-full">
                  <ul class="flex mb-0 list-none flex-wrap pt-3 pb-4 flex-row">
                    <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                      <a class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal"
                        v-on:click="toggleTabs(1)"
                        v-bind:class="{ 'text-indigo-600 bg-white': openTab !== 1, 'text-white bg-indigo-600': openTab === 1 }">
                        Endereço
                      </a>
                    </li>
                    <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                      <a class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal"
                        v-on:click="toggleTabs(2)"
                        v-bind:class="{ 'text-indigo-600 bg-white': openTab !== 2, 'text-white bg-indigo-600': openTab === 2 }">
                        Back Data
                      </a>
                    </li>
                    <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                      <a class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal"
                        v-on:click="toggleTabs(3)"
                        v-bind:class="{ 'text-indigo-600 bg-white': openTab !== 3, 'text-white bg-indigo-600': openTab === 3 }">
                        Mapa
                      </a>
                    </li>
                  </ul>
                  <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                    <div class="px-4 py-5 flex-auto">
                      <div class="tab-content tab-space">
                        <div class="text-left	" v-bind:class="{ 'hidden': openTab !== 1, 'block': openTab === 1 }">
                          <p>Rua: {{ logradouro }}</p>
                          <p>Cidade: {{ localidade }}</p>
                          <p>Bairro: {{ bairro }}</p>
                          <p>Estado: {{ uf }}</p>
                          <br />
                          <br />
                        </div>
                        <div class="text-left" v-bind:class="{ 'hidden': openTab !== 2, 'block': openTab === 2 }">
                          <pre>{{ consultaData }}</pre>
                        </div>
                        <div v-bind:class="{ 'hidden': openTab !== 3, 'block': openTab === 3 }">

                          <googlemaps :key="reload" :lat="lat" :lng="lng"></googlemaps>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </blockquote>
            <figcaption class="mt-10">
              <img class="mx-auto h-25 w-20 rounded-full"
                src="https://lh3.googleusercontent.com/pw/ABLVV85YUXqYu6hC5FhZ7tgCYS4-5plpO1g2ltBHhuh4PT8e9qQiZ_JqVgPxxWLa1ZEVSkKluyuuXXPs3l1r5h_tHiPFJ8UKTw216uflOpK3VBTs_2pS2MJ1t7_HF-IsEevrIXdjpqlleBDc-SqXtPkvJ9l78BsfNEnfHPWsyjcvqqmJOUzqUeWVJhOc-Vi5PKGDESKSs2_HWUhJNfmzXJKfh-QutPbOf79i_Rie4giaUpYGnA83PM_3ZTrIpA7vaRxzbR-tTZSIUfEdkuJ_o-Ib6InRRGEw8WJJN7ZDIimgTrG2cE1lvNoNq8JhSM2Hn56f8fvWo9Ud4gxg8iPGc6u3ZrMbyeJyKeMnOyMjYX2O_ryyHKsxt9-QwKVBrBlipeECegZpafFsy2ssYbbDK6_XPPGreus9DI49S8Xqfjqw74uOKmxZhR7yTeIJXcEPtJWBNmqQmp0pDI0uQuHZiUdqjcVv6A9xzyT41n6vLtz_x456_AoWLgngcmMaNa6bOSrjswVUqNWbZjbl-nwwIgXASBA04CFLAWWgJxlUYYJck6RCrdxljkupQGaJ2YKitXfCocdtE7K1tRjc2ngoHcDo6jlfC5QvDO4yYwbEh5HWMnfHs7Ja3_QqPCfwuY7mWIh8EIQTo1hyFhyjBWhYHN5K9TAR0gd1kNrsZ9cNs_f_oC7hxcJ3PnrZRy9tgMmnzMDAkcdHAy5kSoJQoMjWFeJHz5qh_6CNnAG-ynz8CZ2vRPCa-rO6LuQqxwQXpqKpzrm98ZdAUNFDuT_q3GeIPWK6HMXSPmKWqrsUTfOUvSqA8hq4tc2u92aS8k6lVg7KeLVSI9ahvZkBaRlJlt8pvwKn6Asliprz_lyHJwqOg8qWc0FHFo3GTeikMdep7zKqnuvE3UQdZPPLe4Eg5sMN9oPC6B9IhRlFCzRiEZvrPc72=w713-h953-s-no-gm?authuser=0"
                alt="" />
              <div class="mt-4 flex items-center justify-center space-x-3 text-base">
                <div class="font-semibold text-gray-900">Luis Eduardo</div>
                <svg viewBox="0 0 2 2" width="3" height="3" aria-hidden="true" class="fill-gray-900">
                  <circle cx="1" cy="1" r="1" />
                </svg>
                <div class="text-gray-600">lescoderbr@gmail.com</div>
              </div>
            </figcaption>
          </figure>
        </div>
      </section>
    </div>
    <div class="absolute left-1/2 top-0 -z-10 -translate-x-1/2 blur-3xl xl:-top-6" aria-hidden="true">
      <div class="aspect-[1155/678] w-[72.1875rem] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30"
        style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" />
    </div>
  </div>
</template>

  
<script>
import { CalendarDaysIcon, HandRaisedIcon } from '@heroicons/vue/24/outline'
import axios from 'axios';
import L from 'leaflet'; //deixando para lembrar que testei tbm, mas não deu boa
import 'leaflet/dist/leaflet.css';
import googlemaps from './GoogleMaps.vue';

export default {
  data() {
    return {
      cep: '',
      cepString: '',
      openTab: 1,
      endereco: '',
      error: null,
      lat: 0,
      lng: 0,
      uf: '',
      bairro: '',
      logradouro: '',
      reload: 0,
      consultaData: `JSON Recebido:
{
}`
    };
  },
  props: {
    title: String
  },
  methods: {
    toggleTabs: function (tabNumber) {
      this.openTab = tabNumber
    },
    validarInput() {
      this.cepString = this.cepString.replace(/[^0-9-]/g, '');
    },
    findCep(data) {
      const cep = { cep: data };
      axios.post('/consulta-cep', cep)
        .then(response => {
          this.logradouro = response.data.logradouro
          this.bairro = response.data.bairro
          this.localidade = response.data.localidade
          this.uf = response.data.uf
          this.cep = response.data.cep
          this.consultaData = JSON.stringify(response.data, null, 2);
          this.error = null
        })
        .catch(error => {
          console.error('Erro na requisição:', error);
          this.error = error.response.data.message;
        });
    },
    getGeoData() {
      const data = { logradouro: this.logradouro, uf: this.uf };
      axios.post('/geodata', data)
        .then(response => {
          this.lat = response.data.lat
          this.lng = response.data.lng
        });
    }
  },
  watch: {
    logradouro: function () {
      this.getGeoData()
      //existe um problema de delay sendo necessário atrasar a solicitação de reload
      setTimeout(() => {
        this.reload = Date.now()
      }, 1000)
    }
  },
  components: {
    googlemaps,
  },
  mounted() {    
    document.title = this.title;
  },
}
</script>