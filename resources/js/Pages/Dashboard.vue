<template>
    <breeze-authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                       <line-chart :chart-data="datacollection"></line-chart>
                    </div>
                </div>
            </div>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'

    export default {
        components: {
            BreezeAuthenticatedLayout
        },
        mounted() {
            this.fillData();
        },
        data: () => ({
            datacollection: null
        }),

        methods: {
            fillData() {
            this.datacollection = {
                labels: [this.getRandomInt(), this.getRandomInt()],
                datasets: [
                {
                    label: "Data One",
                    backgroundColor: "#f87979",
                    data: [this.getRandomInt(), this.getRandomInt()]
                },
                {
                    label: "Data One",
                    backgroundColor: "#f87979",
                    data: [this.getRandomInt(), this.getRandomInt()]
                }
                ]
            };
            },
            getRandomInt() {
            return Math.floor(Math.random() * (50 - 5 + 1)) + 5;
            }
        },

        props: {
            auth: Object,
            menu: Object,
            errors: Object,
        },
    }
</script>
