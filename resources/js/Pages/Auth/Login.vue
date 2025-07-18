<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
    canResetPassword: {
        type: Boolean,
        default: false,
    },
    status: {
        type: String,
        default: '',
    },
})

const form = useForm({
    correo: '',
    password: '',
    remember: false,
})

function submit() {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <Head title="Iniciar sesión" />

    <div class="w-full max-w-md mx-auto mt-12 bg-white dark:bg-slate-800 shadow-lg rounded px-8 py-6">
        <h2 class="text-2xl mb-6 text-center font-bold text-blue-700 dark:text-blue-300">Iniciar sesión</h2>

        <div v-if="status" class="mb-4 text-sm text-green-600 dark:text-green-400">
            {{ status }}
        </div>

        <form @submit.prevent="submit" autocomplete="off">
            <div class="mb-4">
                <label for="correo" class="block mb-1 text-gray-700 dark:text-gray-200">Correo</label>
                <input
                    id="correo"
                    v-model="form.correo"
                    type="email"
                    class="w-full border px-3 py-2 rounded bg-white dark:bg-slate-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100"
                    required
                    autofocus
                    autocomplete="username"
                />
                <div v-if="form.errors.correo" class="text-red-500 text-sm mt-1">{{ form.errors.correo }}</div>
            </div>
            <div class="mb-4">
                <label for="password" class="block mb-1 text-gray-700 dark:text-gray-200">Contraseña</label>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="w-full border px-3 py-2 rounded bg-white dark:bg-slate-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100"
                    required
                    autocomplete="current-password"
                />
                <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</div>
            </div>
            <div class="mb-4 flex items-center">
                <input
                    id="remember"
                    type="checkbox"
                    v-model="form.remember"
                    class="mr-2"
                />
                <label for="remember" class="text-sm text-gray-700 dark:text-gray-200">Recordarme</label>
            </div>
            <div class="flex items-center justify-between">
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    :disabled="form.processing"
                >
                    Ingresar
                </button>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-blue-600 underline hover:text-blue-800 ml-4"
                >
                    ¿Olvidaste tu contraseña?
                </Link>
            </div>
            <div v-if="form.errors.general" class="mt-2 text-red-500">{{ form.errors.general }}</div>
        </form>
    </div>
</template>

