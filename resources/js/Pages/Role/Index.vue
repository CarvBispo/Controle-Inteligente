<template>
    <breeze-authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Instituições
            </h2>
        </template>

        <div class="container py-12 flex m-auto">
            <breeze-validation-errors class="mb-4" />

            <table class="min-w-full table-auto">
                <thead class="justify-between">
                    <tr class="bg-gray-800">
                      	<th class="px-16 py-2" data-column="id">ID</th>
                        <th data-column="name">Nome</th>
                        <th data-column="created_at">Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(role, index) in roles.data">
                        <td>{{ role.id }}</td>
                        <td>{{ role.name }}</td>
                        <td>{{ role.created_at }}</td>
                        <td>
                            <inertia-link :href="route('roles.edit', role.id)" class="underline text-sm text-gray-600 hover:text-gray-900">
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
            console.log(this.roles)
        },
        data() {
            return {
                form: this.$inertia.form({
                    search: ''
                })
            }
        },

        props: {
            roles: Object,
            errors: Object,
        },
        methods: {
            submit() {
                this.form.post(this.route('login'), {
                    onFinish: () => this.form.reset('password'),
                })
            }
        }
    }
</script>


<style>

    th {
        color: #e2e8f0;
    }

</style>
