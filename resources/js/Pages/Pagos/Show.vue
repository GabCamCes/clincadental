<template>
  <AuthenticatedLayout>
    <BackToDashboard />
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
      <!-- Encabezado -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Comprobante de Pago</h1>
        <p class="mt-2 text-sm text-gray-600">#{{ pago.id }} - {{ new Date(pago.fecha_pago).toLocaleDateString() }}</p>
      </div>

      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- Sección de información del pago -->
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Detalles del Pago
          </h3>
        </div>
        
        <div class="border-t border-gray-200">
          <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Paciente</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ pago.venta.paciente.usuario.nombre }} {{ pago.venta.paciente.usuario.apellido }}
              </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Monto a pagar</dt>
              <dd class="mt-1 text-lg font-semibold text-gray-900 sm:mt-0 sm:col-span-2">
                Bs. {{ qrData.monto }}
              </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Referencia</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-mono">
                {{ qrData.referencia }}
                <button 
                  @click="copiarAlPortapapeles(qrData.referencia)"
                  class="ml-2 text-blue-600 hover:text-blue-800"
                  title="Copiar referencia"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  </svg>
                </button>
              </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Válido hasta</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ qrData.fecha_vencimiento }}
              </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Concepto</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                {{ qrData.concepto }}
              </dd>
            </div>
          </dl>
        </div>
      </div>

      <!-- Sección del Código QR -->
      <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Instrucciones de Pago
          </h3>
        </div>
        
        <div class="px-4 py-5 sm:p-6">
          <div class="flex flex-col md:flex-row gap-8">
            <!-- Columna del QR -->
            <div class="md:w-1/3 flex flex-col items-center">
              <div class="p-4 border-2 border-dashed border-gray-300 rounded-lg mb-4">
                <img :src="qr" alt="Código QR de pago" class="w-48 h-48 object-contain" />
              </div>
              <p class="text-xs text-gray-500 text-center">
                Escanea este código con tu app bancaria
              </p>
            </div>

            <!-- Columna de datos bancarios -->
            <div class="md:w-2/3">
              <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Datos del pago:</h4>
                
                <div class="space-y-3">
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Beneficiario:</span>
                    <span class="text-sm font-medium">{{ qrData.beneficiario }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Banco:</span>
                    <span class="text-sm">{{ qrData.banco }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Monto:</span>
                    <span class="text-sm font-semibold">Bs. {{ qrData.monto }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Referencia:</span>
                    <span class="text-sm font-mono">{{ qrData.referencia }}</span>
                  </div>
                </div>

                <!-- Botón para copiar datos -->
                <button
                  @click="copiarDatosBancarios"
                  class="mt-4 w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                  </svg>
                  Copiar datos bancarios
                </button>
              </div>

              <!-- Instrucciones -->
              <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <h4 class="text-sm font-medium text-blue-800">Importante</h4>
                    <div class="mt-2 text-sm text-blue-700">
                      <p>• El pago puede tardar hasta 24 horas en ser procesado.</p>
                      <p>• Guarda el comprobante de pago.</p>
                      <p>• Para confirmación inmediata, envía el comprobante al WhatsApp de la clínica.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Botón de acción -->
      <div class="mt-8 flex justify-end" v-if="user.tipo_usuario === 'A'">
        <button
          @click="marcarComoPagado"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          Marcar como pagado
        </button>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { useForm, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import BackToDashboard from '@/Components/BackToDashboard.vue'

const props = defineProps({
  pago: Object,
  qr: String,
  qrData: Object
});

const user = usePage().props.auth.user;

const copiarAlPortapapeles = async (texto) => {
  try {
    await navigator.clipboard.writeText(texto);
    toast.success('¡Copiado al portapapeles!', { autoClose: 2000 });
  } catch (err) {
    console.error('Error al copiar:', err);
    toast.error('No se pudo copiar al portapapeles', { autoClose: 2000 });
  }
};

const copiarDatosBancarios = () => {
  const datos = `*Datos para transferencia*\n` +
    `Banco: ${props.qrData.banco}\n` +
    `Beneficiario: ${props.qrData.beneficiario}\n` +
    `RIF: ${props.qrData.ci_rif}\n` +
    `Tipo de cuenta: ${props.qrData.tipo_cuenta}\n` +
    `Número de cuenta: ${props.qrData.numero_cuenta}\n` +
    `Monto: Bs. ${props.qrData.monto}\n` +
    `Referencia: ${props.qrData.referencia}\n` +
    `Concepto: ${props.qrData.concepto}`;
  
  copiarAlPortapapeles(datos);
};

const marcarComoPagado = () => {
  if (confirm('¿Estás seguro de marcar este pago como completado?')) {
    router.put(route('pagos.update', props.pago.id), {
      estado_pago: 'completado',
      _method: 'put'
    }, {
      onSuccess: () => {
        toast.success('¡Pago marcado como completado!', { autoClose: 3000 });
      },
      onError: () => {
        toast.error('Ocurrió un error al actualizar el pago', { autoClose: 3000 });
      }
    });
  }
};
</script>

