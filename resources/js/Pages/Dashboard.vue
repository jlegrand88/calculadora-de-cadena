<template>
    <Head title="Calculadora de cadenas" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Calculadora de cadenas
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-full mx-auto sm:px-6 lg:px-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg w-100">
                    <div class="p-0 bg-white border-b border-gray-200">
                        <CalculatorForm/>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import CalculatorForm from '@/Components/CalculatorForm.vue';

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        CalculatorForm
    },
}
</script>
