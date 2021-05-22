<template>
    <breeze-authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Formulário
            </h2>
        </template>

        <div class="container py-12 flex m-auto">
            <breeze-validation-errors class="mb-4" />

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <breeze-label for="name" value="Nome" />
                    <breeze-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="Grupo de usuario" />
                </div>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Permissões
                </h2>

                <div v-for="(section,index) in permissions">
                    <h3>{{ section[0].section }}</h3>
                    <div class="block mt-4" v-for="(permission,index) in section">
                    <label class="flex items-center">
                        <breeze-checkbox name="permission[]" @click="toglePermission(permission)" :checked="hasPermission(permission)" />
                        <span class="ml-2 text-sm text-gray-600">{{ permission.name }}</span>
                    </label>
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
            console.log(this.form)
        },
        data() {
            return {
                form: this.$inertia.form({
                    id: this.role.id,
                    name: this.role.name,
                    permissions: this.role.permissions
                })
            }
        },

        props: {
            role: Object,
            permissions: Object,
            errors: Object,
        },
        methods: {
            submit() {
               if (this.form.id) {
                    this.form.put(this.route('roles.update', this.role.id))
               } else {
                   this.form.post(this.route('roles.store'))
               }
            },
            hasPermission(permission) {
                let hasPermission = this.form.permissions.find((item) => {
                   if (item.id === permission.id) {
                       return item
                   }
                })
                return (hasPermission) ? true : false
            },
            toglePermission (permission) {
               let hasPermission = this.form.permissions.find((item) => {
                   if (item.id === permission.id) {
                       return item
                   }
               })
               if (hasPermission) {
                   this.form.permissions = this.form.permissions.filter((item) => {
                       if (item.id != permission.id) {
                           return item
                       }
                   })
               } else {
                   this.form.permissions.push(permission);
               }
            }
        }
    }
</script>
