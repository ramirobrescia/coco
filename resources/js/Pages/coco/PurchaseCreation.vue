<template>
  <v-container>
    <v-card class="mx-auto pa-4" max-width="800">
      <v-card-title class="text-h5 mb-4">
        Crear Nueva Compra Comunitaria
      </v-card-title>

      <v-form @submit.prevent="handleSubmit" v-model="isFormValid">
        <!-- Selección de Proveedor -->
        <v-autocomplete
          v-model="provider"
          :items="providers"
          item-title="name"
          item-value="id"
          label="Seleccionar Proveedor"
          :rules="[v => !!v || 'El proveedor es requerido']"
          variant="outlined"
          class="mb-4"
        ></v-autocomplete>

        <!-- Fecha de la compra -->
        <v-text-field
          v-model="purchaseDate"
          type="date"
          label="Fecha de la compra"
          :rules="[v => !!v || 'La fecha es requerida']"
          variant="outlined"
          class="mb-4"
        ></v-text-field>

        <!-- Fecha límite para pedidos -->
        <v-text-field
          v-model="orderDeadline"
          type="datetime-local"
          label="Fecha límite para pedidos"
          :rules="[
            v => !!v || 'La fecha límite es requerida',
            v => new Date(v) > new Date() || 'La fecha debe ser futura'
          ]"
          variant="outlined"
          class="mb-4"
        ></v-text-field>

        <!-- Notas adicionales -->
        <v-textarea
          v-model="notes"
          label="Notas adicionales"
          variant="outlined"
          class="mb-4"
          rows="3"
        ></v-textarea>

				<v-file-input
						v-model="priceList"
						label="Lista de precios"
						accept=".pdf,.xls,.xlsx,.doc,.docx"
						:rules="[
							v => !v || v.size < 5000000 || 'El archivo debe ser menor a 5MB'
						]"
						variant="outlined"
						class="mb-4"
						show-size
						prepend-icon="mdi-file-document"
						:loading="isUploading"
					>
						<template v-slot:selection="{ fileNames }">
							<template v-for="fileName in fileNames" :key="fileName">
								<v-chip
									size="small"
									label
									color="primary"
									class="me-2"
								>
									{{ fileName }}
								</v-chip>
							</template>
						</template>
				</v-file-input>

        <div class="d-flex justify-end">
          <v-btn
            color="secondary"
            class="me-4"
            @click="handleCancel"
          >
            Cancelar
          </v-btn>
          <v-btn
            color="primary"
            type="submit"
            :disabled="!isFormValid"
            :loading="isSubmitting"
          >
            Crear Compra
          </v-btn>
        </div>
      </v-form>
    </v-card>

    <!-- Snackbar para mensajes -->
    <v-snackbar
      v-model="snackbar.show"
      :color="snackbar.color"
      :timeout="3000"
    >
      {{ snackbar.message }}
    </v-snackbar>
  </v-container>
</template>

<script setup>
import { ref, reactive } from 'vue'
// import { useRouter } from 'vue-router'

// const router = useRouter()

// Estado del formulario
const isFormValid = ref(false)
const isSubmitting = ref(false)
const priceList = ref(null)
const isUploading = ref(false)

// Campos del formulario
const provider = ref(null)
const purchaseDate = ref('')
const orderDeadline = ref('')
const notes = ref('')

// Datos de ejemplo para proveedores (deberían venir de una API)
const providers = ref([
  { id: 1, name: 'Proveedor 1' },
  { id: 2, name: 'Proveedor 2' },
  { id: 3, name: 'Proveedor 3' },
])

// Estado del snackbar
const snackbar = reactive({
  show: false,
  message: '',
  color: 'success'
})

// Métodos
const handleSubmit = async () => {
  try {
    isSubmitting.value = true
    isUploading.value = true
    
    // Crear objeto con datos de la compra
    const purchaseData = {
      providerId: provider.value,
      purchaseDate: purchaseDate.value,
      orderDeadline: orderDeadline.value,
      notes: notes.value
    }

		if (priceList.value) {
      purchaseData.priceList = priceList.value
    }

    // Aquí iría la llamada a la API para crear la compra
    console.log('Datos de la compra:', purchaseData)
    
    // Simular llamada exitosa
    await new Promise(resolve => setTimeout(resolve, 1000))

    // Mostrar mensaje de éxito
    snackbar.color = 'success'
    snackbar.message = 'Compra creada exitosamente'
    snackbar.show = true

    // Redirigir a la página de la compra (asumiendo que la API devuelve el ID)
    // router.push('/purchase/1/order')
  } catch (error) {
    // Manejar error
    snackbar.color = 'error'
    snackbar.message = 'Error al crear la compra'
    snackbar.show = true
  } finally {
    isSubmitting.value = false
  }
}

const handleCancel = () => {
  // router.push('/purchases')
}
</script>

<style scoped>
.v-card {
  border-radius: 8px;
}
</style>