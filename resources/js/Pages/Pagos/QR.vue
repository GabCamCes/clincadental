<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useToast } from 'vue-toastification';
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  pago: Object,
  qr: String,
  alias: String,
  monto: String,
  concepto: String,
  tiempoExpiracion: {
    type: Number,
    default: 900 // 15 minutos en segundos
  }
});

const toast = useToast();
const tiempoRestante = ref(props.tiempoExpiracion);
let intervalo = null;

const copiarDatos = async (texto) => {
  try {
    await navigator.clipboard.writeText(texto);
    toast.success('¡Copiado al portapapeles!');
  } catch (err) {
    console.error('Error al copiar:', err);
    toast.error('No se pudo copiar al portapapeles');
  }
};

const formatearTiempo = (segundos) => {
  const mins = Math.floor(segundos / 60);
  const secs = segundos % 60;
  return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
};

onMounted(() => {
  // Iniciar cuenta regresiva
  intervalo = setInterval(() => {
    if (tiempoRestante.value > 0) {
      tiempoRestante.value--;
    } else {
      clearInterval(intervalo);
      toast.warning('El código QR ha expirado');
    }
  }, 1000);
});

onUnmounted(() => {
  if (intervalo) clearInterval(intervalo);
});
</script>

<template>
  <AuthenticatedLayout>
    <BackToDashboard />
  <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-lg mt-8">
    <div class="text-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Pago con Código QR</h1>
      <p class="text-gray-600">Escanea el código con tu app bancaria</p>
    </div>
    
    <!-- Código QR -->
    <div class="bg-white p-4 rounded-lg border border-gray-200 mb-6 flex justify-center">
      <img 
        :src="'data:image/png;base64,' + qr" 
        alt="QR de pago" 
        class="w-64 h-64 object-contain"
      />
    </div>

    <!-- Tiempo restante -->
    <div class="bg-blue-50 text-blue-800 p-3 rounded-lg mb-6 text-center">
      <div class="text-sm font-medium mb-1">Tiempo restante</div>
      <div class="text-2xl font-bold">{{ formatearTiempo(tiempoRestante) }}</div>
    </div>

    <!-- Detalles del pago -->
    <div class="space-y-3 mb-6">
      <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
        <span class="text-gray-600">Monto a pagar:</span>
        <span class="font-semibold">Bs {{ monto }}</span>
      </div>
      <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
        <span class="text-gray-600">Concepto:</span>
        <span class="font-semibold">{{ concepto }}</span>
      </div>
      <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
        <span class="text-gray-600">Cuenta:</span>
        <div class="flex items-center">
          <span class="font-mono mr-2">{{ alias }}</span>
          <button 
            @click="copiarDatos(alias)"
            class="text-blue-600 hover:text-blue-800"
            title="Copiar cuenta"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Instrucciones -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-yellow-700">
            Después de realizar el pago, por favor sube el comprobante o notifica al personal para validar tu pago.
          </p>
        </div>
      </div>
    </div>

    <!-- Botones de acción -->
    <div class="flex space-x-4">
      <button 
        @click="copiarDatos(`Monto: Bs ${monto}, Cuenta: ${alias}, Concepto: ${concepto}`)"
        class="flex-1 flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
        </svg>
        Copiar datos
      </button>
      <button 
        @click="$inertia.visit(route('pagos.index'))"
        class="flex-1 flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Volver
      </button>
    </div>
  </div>
  </AuthenticatedLayout>
</template>
