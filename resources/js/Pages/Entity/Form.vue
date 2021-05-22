<template>
    <breeze-authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Formulário
            </h2>
        </template>

        <div class="container py-12 flex flex-col m-auto">
            <breeze-validation-errors class="mb-4" />

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="w-full">

                <div class="flex items-center w-full flex-wrap">
                    <div class="flex items-center w-full mt-4">
                        <div class="mx-3 mb-6 md:w-1/2">
                            <breeze-label for="name" value="Nome *" />
                            <breeze-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
                        </div>
                        <div class="mx-3 mb-6 md:w-1/2">
                            <breeze-label for="refer_id" value="Instituição Pai" />
                            <select v-model="this.form.refer_id" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" name="refer_id" id="refer_id">
                                <option value="">Selecione</option>
                                <option v-for="(entity, index) in entities" :value="entity.id"> {{ entity.name }} </option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center w-full mt-4">
                       <div class="mx-3 mb-6 md:w-1/2">
                            <breeze-label for="email" value="Email *" />
                            <breeze-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus/>
                        </div>
                        <div class="mx-3 mb-6 md:w-1/2">
                            <breeze-label for="phone" value="Telefone *" />
                            <breeze-input id="phone" type="tel" class="mt-1 block w-full" v-model="form.phone" required autofocus/>
                        </div>
                    </div>
                    <div class="flex items-center w-full mt-4">
                       <div class="mx-3 mb-6 md:w-1/4">
                            <breeze-label for="cep" value="CEP *" />
                            <breeze-input id="cep" type="text" class="mt-1 block w-full" v-on:blur="buscarCEP(form.address.cep)" v-model="form.address.cep" required autofocus/>
                        </div>
                        <div class="mx-3 mb-6 md:w-2/3">
                            <breeze-label for="logradouro" value="Logradouro *" />
                            <breeze-input id="logradouro" type="text" class="mt-1 block w-full" v-model="form.address.logradouro" required autofocus/>
                        </div>
                    </div>
                    <div class="flex items-center w-full mt-4">
                     <div class="mx-3 mb-6 md:w-1/3">
                            <breeze-label for="bairro" value="Bairro *" />
                            <breeze-input id="bairro" type="text" class="mt-1 block w-full" v-model="form.address.bairro" required autofocus/>
                        </div>
                       <div class="mx-3 mb-6 md:w-1/2">
                            <breeze-label for="localidade" value="Cidade *" />
                            <breeze-input id="localidade" type="text" class="mt-1 block w-full text-uppercase" v-model="form.address.localidade" required autofocus/>
                        </div>
                        <div class="mx-3 mb-6 md:w-1/5">
                            <breeze-label for="estado" value="Estado *" />
                            <breeze-input id="estado" type="text" class="mt-1 block w-full" v-model="form.address.uf" required autofocus/>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Salvar
                    </breeze-button>
                </div>
            </form>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
    import BreezeButton from '@/Components/Button'
    import BreezeGuestLayout from "@/Layouts/Guest"
    import BreezeInput from '@/Components/Input'
    import BreezeCheckbox from '@/Components/Checkbox'
    import BreezeLabel from '@/Components/Label'
    import BreezeValidationErrors from '@/Components/ValidationErrors'


    export default {
        components: {
            BreezeAuthenticatedLayout,
            BreezeButton,
            BreezeInput,
            BreezeCheckbox,
            BreezeLabel,
            BreezeValidationErrors
        },
        mounted() {
            console.log(this.entity)
        },
        data() {
            return {
                form: this.$inertia.form({
                    id: this.entity.id,
                    name: this.entity.name,
                    refer_id: this.entity.refer_id,
                    phone: this.entity.phone,
                    email: this.entity.email,
                    address: {
                        cep: this.entity.address?.cep,
                        logradouro: this.entity.address?.logradouro,
                        complemento: this.entity.address?.complemento,
                        bairro: this.entity.address?.bairro,
                        localidade: this.entity.address?.localidade,
                        uf: this.entity.address?.uf?.toUpperCase(),
                        ibge: this.entity.address?.ibge,
                        gia: this.entity.address?.gia,
                        ddd: this.entity.address?.ddd,
                        siafi: this.entity.address?.siafi
                    }
                })
            }
        },

        props: {
            entity: Object,
            entities: Object,
            errors: Object,
        },
        methods: {
            submit() {
               if (this.form.id) {
                    this.form.put(this.route('entities.update', this.role.id))
               } else {
                   this.form.post(this.route('entities.store'))
               }
            },
            buscarCEP (val) {
                window.axios.get(`https://viacep.com.br/ws/${val}/json/`).then((res) => {
                    if (res) {
                        this.form.address = res.data
                    }
                }).catch(err => console.log(err))

            }
        }
    }
</script>
