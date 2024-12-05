<template>
  <App>
    <v-container>
      <v-card class="mx-auto pa-4" max-width="1200">
        <v-card-title class="text-h5 mb-4 d-flex justify-space-between align-center">
          Reporte de Pedidos
          <v-btn
            color="primary"
            prepend-icon="mdi-download"
            @click="exportReport"
            :loading="isExporting"
          >
            Exportar
          </v-btn>
        </v-card-title>

        <!-- Filtros -->
        <v-row class="mb-4">
          <v-col cols="12" sm="4">
            <v-text-field
              v-model="search"
              label="Buscar consumidor"
              variant="outlined"
              density="compact"
              prepend-inner-icon="mdi-magnify"
              clearable
            ></v-text-field>
          </v-col>
          <v-col cols="12" sm="4">
            <v-select
              v-model="productFilter"
              :items="productsList"
              label="Filtrar por producto"
              variant="outlined"
              density="compact"
              clearable
            ></v-select>
          </v-col>
          <v-col cols="12" sm="4">
            <v-select
              v-model="orderBy"
              :items="sortOptions"
              label="Ordenar por"
              variant="outlined"
              density="compact"
            ></v-select>
          </v-col>
        </v-row>

        <!-- Tabla de Pedidos -->
        <v-table fixed-header height="500px">
          <thead>
            <tr>
              <th class="text-left">Consumidor</th>
              <th class="text-left">Producto</th>
              <th class="text-right">Cantidad</th>
              <th class="text-right">Peso Unit.</th>
              <th class="text-right">Peso Total</th>
              <th class="text-right">Precio Unit.</th>
              <th class="text-right">Subtotal</th>
              <th class="text-center">Estado</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in filteredOrders" :key="order.id">
              <td>{{ order.consumer }}</td>
              <td>{{ order.product }}</td>
              <td class="text-right">{{ order.quantity }}</td>
              <td class="text-right">{{ order.unitWeight }} Kg</td>
              <td class="text-right">{{ (order.quantity * order.unitWeight).toFixed(2) }} Kg</td>
              <td class="text-right">${{ order.unitPrice }}</td>
              <td class="text-right">${{ (order.quantity * order.unitPrice).toFixed(2) }}</td>
              <td class="text-center">
                <v-chip
                  :color="getStatusColor(order.status)"
                  size="small"
                >
                  {{ order.status }}
                </v-chip>
              </td>
            </tr>
          </tbody>
          <tfoot v-if="filteredOrders.length">
            <tr>
              <td colspan="2" class="text-right"><strong>Totales:</strong></td>
              <td class="text-right"><strong>{{ totalQuantity }}</strong></td>
              <td></td>
              <td class="text-right"><strong>{{ totalWeight.toFixed(2) }} Kg</strong></td>
              <td></td>
              <td class="text-right"><strong>${{ totalAmount.toFixed(2) }}</strong></td>
              <td></td>
            </tr>
          </tfoot>
        </v-table>

        <!-- Mensaje cuando no hay resultados -->
        <v-alert
          v-if="!filteredOrders.length"
          type="info"
          class="mt-4"
        >
          No se encontraron pedidos que coincidan con los filtros aplicados
        </v-alert>

        <!-- Snackbar -->
        <v-snackbar
          v-model="snackbar.show"
          :color="snackbar.color"
          :timeout="3000"
        >
          {{ snackbar.message }}
        </v-snackbar>
      </v-card>
    </v-container>
  </App>
</template>

<script setup>
import App from '@/Layouts/App.vue';
import { ref, computed, reactive } from 'vue'

// Estados
const search = ref('')
const productFilter = ref(null)
const orderBy = ref('consumer')
const isExporting = ref(false)

// Datos de ejemplo (deberían venir de una API)
const orders = ref([
  {
    id: 1,
    consumer: 'Juan Pérez',
    product: 'Manzanas',
    quantity: 5,
    unitWeight: 1,
    unitPrice: 100,
    status: 'Confirmado'
  },
  {
    id: 2,
    consumer: 'María García',
    product: 'Peras',
    quantity: 3,
    unitWeight: 0.8,
    unitPrice: 90,
    status: 'Pendiente'
  },
  // ... más pedidos
])

// Opciones de ordenamiento
const sortOptions = [
  { title: 'Consumidor', value: 'consumer' },
  { title: 'Producto', value: 'product' },
  { title: 'Mayor cantidad', value: 'quantity-desc' },
  { title: 'Menor cantidad', value: 'quantity-asc' },
  { title: 'Mayor monto', value: 'amount-desc' },
  { title: 'Menor monto', value: 'amount-asc' }
]

// Lista de productos única para el filtro
const productsList = computed(() => {
  const uniqueProducts = [...new Set(orders.value.map(order => order.product))]
  return uniqueProducts.map(product => ({
    title: product,
    value: product
  }))
})

// Snackbar
const snackbar = reactive({
  show: false,
  message: '',
  color: 'success'
})

// Computed properties
const filteredOrders = computed(() => {
  let result = [...orders.value]

  // Aplicar búsqueda por consumidor
  if (search.value) {
    result = result.filter(order => 
      order.consumer.toLowerCase().includes(search.value.toLowerCase())
    )
  }

  // Aplicar filtro por producto
  if (productFilter.value) {
    result = result.filter(order => order.product === productFilter.value)
  }

  // Aplicar ordenamiento
  result.sort((a, b) => {
    switch (orderBy.value) {
      case 'consumer':
        return a.consumer.localeCompare(b.consumer)
      case 'product':
        return a.product.localeCompare(b.product)
      case 'quantity-desc':
        return b.quantity - a.quantity
      case 'quantity-asc':
        return a.quantity - b.quantity
      case 'amount-desc':
        return (b.quantity * b.unitPrice) - (a.quantity * a.unitPrice)
      case 'amount-asc':
        return (a.quantity * a.unitPrice) - (b.quantity * b.unitPrice)
      default:
        return 0
    }
  })

  return result
})

const totalQuantity = computed(() => {
  return filteredOrders.value.reduce((sum, order) => sum + order.quantity, 0)
})

const totalWeight = computed(() => {
  return filteredOrders.value.reduce((sum, order) => 
    sum + (order.quantity * order.unitWeight), 0
  )
})

const totalAmount = computed(() => {
  return filteredOrders.value.reduce((sum, order) => 
    sum + (order.quantity * order.unitPrice), 0
  )
})

// Métodos
const getStatusColor = (status) => {
  switch (status) {
    case 'Confirmado':
      return 'success'
    case 'Pendiente':
      return 'warning'
    case 'Cancelado':
      return 'error'
    default:
      return 'default'
  }
}

const exportReport = async () => {
  try {
    isExporting.value = true
    
    // Aquí iría la lógica para exportar el reporte
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    snackbar.color = 'success'
    snackbar.message = 'Reporte exportado exitosamente'
    snackbar.show = true
  } catch (error) {
    snackbar.color = 'error'
    snackbar.message = 'Error al exportar el reporte'
    snackbar.show = true
  } finally {
    isExporting.value = false
  }
}
</script>