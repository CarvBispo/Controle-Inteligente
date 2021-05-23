<template>
    <breeze-authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Contas
            </h2>
        </template>

        <div class="container py-12 flex m-auto flex-col">
            <breeze-validation-errors class="mb-4" />

            <div class="flex flex-end">
                <div class="align-middle flex items-end py-2">
                    <breeze-input id="search" type="text" class="mt-1 block w-full" v-on:blur="searchFilter()" v-model="search" autofocus placeholder="Buscar..."/>
                </div>
                <inertia-link :href="route('bills.create')" class="inline-flex ml-auto items-center px-4 py-2 mb-4 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">
                   Novo
                </inertia-link>
            </div>

            <table class="min-w-full table-auto">
                <thead class="justify-between">
                    <tr class="bg-gray-800">
                      	<th class="" data-column="id">ID</th>
                        <th data-column="name">Nome</th>
                        <th>Responsavel</th>
                        <th data-column="created_at">Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(bill, index) in bills.data">
                        <td>{{ bill.id }}</td>
                        <td>{{ bill.name }}</td>
                        <td>{{ bill.owner.name }}</td>
                        <td>{{ bill.created_at }}</td>
                        <td>
                            <inertia-link :href="route('bills.edit', bill.id)" class="underline text-sm text-gray-600 hover:text-gray-900">
                                Editar
                            </inertia-link>
                        </td>
                    </tr>
                </tbody>
            </table>

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
            console.log(this.bills)
        },
        data() {
            return {
                search: '',
            }
        },

        props: {
            bills: Object,
            errors: Object,
        },
        methods: {
            searchFilter() {
                this.$inertia.replace(this.route('bills.index', {search: this.search}))
            }
        }
    }
</script>


<style>

    th {
        color: #e2e8f0;
    }

</style>
