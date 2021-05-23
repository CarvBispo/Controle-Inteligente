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
                            <breeze-label for="origin" value="Origem *" />
                            <breeze-input id="origin" type="text" class="mt-1 block w-full" v-model="form.origin" required autofocus />
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
            console.log(this.bill)
        },
        data() {
            return {
                form: this.$inertia.form({
                    id: this.bill.id
                })
            }
        },

        props: {
            bill: Object,
            errors: Object,
        },
        methods: {
            submit() {
               if (this.form.id) {
                    this.form.put(this.route('bill.update', this.form.id))
               } else {
                   this.form.post(this.route('bill.store'))
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
