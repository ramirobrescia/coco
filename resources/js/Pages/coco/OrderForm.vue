<template>
  <v-container>
    <v-card class="mx-auto pa-4" max-width="1000">
      <v-card-title class="text-h5 mb-4">
        Crear Pedido
      </v-card-title>

      <!-- Búsqueda de Productos -->
      <v-autocomplete
        v-model="selectedProduct"
        :items="products"
        item-title="name"
        item-value="id"
        label="Buscar Producto"
        variant="outlined"
        class="mb-4"
        :loading="isSearching"
        return-object
      >
        <template v-slot:append>
          <v-btn
            icon="mdi-plus"
            size="small"
            color="primary"
            class="me-2"
            @click="showNewProductDialog = true"
            title="Crear nuevo producto"
          ></v-btn>
        </template>
      </v-autocomplete>

      <!-- Agregar Producto al Pedido -->
      <v-row v-if="selectedProduct" class="mb-4">
        <v-col cols="12" sm="4">
          <v-text-field
            v-model="selectedProduct.name"
            label="Producto"
            readonly
            variant="outlined"
            density="compact"
          ></v-text-field>
        </v-col>
        <v-col cols="12" sm="2">
          <v-text-field
            v-model="selectedProduct.price"
            label="Precio"
            readonly
            prefix="$"
            variant="outlined"
            density="compact"
          ></v-text-field>
        </v-col>
        <v-col cols="12" sm="2">
          <v-text-field
            v-model="selectedProduct.weight"
            label="Peso (Kg)"
            readonly
            suffix="Kg"
            variant="outlined"
            density="compact"
          ></v-text-field>
        </v-col>
        <v-col cols="12" sm="2">
          <v-text-field
            v-model="quantity"
            type="number"
            label="Cantidad"
            variant="outlined"
            density="compact"
            :rules="[v => v > 0 || 'La cantidad debe ser mayor a 0']"
          ></v-text-field>
        </v-col>
        <v-col cols="12" sm="2" class="d-flex align-center">
          <v-btn
            color="primary"
            block
            @click="addToOrder"
            :disabled="!quantity || quantity <= 0"
          >
            Agregar
          </v-btn>
        </v-col>
      </v-row>

      <!-- Lista de Productos en el Pedido -->
      <v-table v-if="orderItems.length">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Peso Total</th>
            <th>Subtotal</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in orderItems" :key="item.id">
            <td>{{ item.name }}</td>
            <td>${{ item.price }}</td>
            <td>{{ item.quantity }}</td>
            <td>{{ (item.weight * item.quantity).toFixed(2) }} Kg</td>
            <td>${{ (item.price * item.quantity).toFixed(2) }}</td>
            <td>
              <v-btn
                icon="mdi-delete"
                size="small"
                color="error"
                variant="text"
                @click="removeItem(item)"
              ></v-btn>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" class="text-right"><strong>Totales:</strong></td>
            <td><strong>{{ totalWeight.toFixed(2) }} Kg</strong></td>
            <td><strong>${{ totalAmount.toFixed(2) }}</strong></td>
            <td></td>
          </tr>
        </tfoot>
      </v-table>

      <!-- Botones de acción -->
      <div class="d-flex justify-end mt-4">
        <v-btn
          color="secondary"
          class="me-4"
          @click="handleCancel"
        >
          Cancelar
        </v-btn>
        <v-btn
          color="primary"
          @click="handleSaveOrder"
          :disabled="!orderItems.length"
          :loading="isSaving"
        >
          Guardar Pedido
        </v-btn>
      </div>

      <!-- Diálogo para crear nuevo producto -->
      <v-dialog v-model="showNewProductDialog" max-width="500px">
        <v-card>
          <v-card-title>Crear Nuevo Producto</v-card-title>
          <v-card-text>
            <v-form v-model="isNewProductFormValid" @submit.prevent="handleCreateProduct">
              <v-text-field
                v-model="newProduct.name"
                label="Nombre"
                :rules="[v => !!v || 'El nombre es requerido']"
                variant="outlined"
                class="mb-4"
              ></v-text-field>
              <v-text-field
                v-model="newProduct.price"
                label="Precio"
                type="number"
                prefix="$"
                :rules="[v => v > 0 || 'El precio debe ser mayor a 0']"
                variant="outlined"
                class="mb-4"
              ></v-text-field>
              <v-text-field
                v-model="newProduct.weight"
                label="Peso (Kg)"
                type="number"
                suffix="Kg"
                :rules="[v => v > 0 || 'El peso debe ser mayor a 0']"
                variant="outlined"
                class="mb-4"
              ></v-text-field>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="secondary"
              variant="text"
              @click="showNewProductDialog = false"
            >
              Cancelar
            </v-btn>
            <v-btn
              color="primary"
              @click="handleCreateProduct"
              :disabled="!isNewProductFormValid"
              :loading="isCreatingProduct"
            >
              Crear Producto
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <!-- Snackbar para mensajes -->
      <v-snackbar
        v-model="snackbar.show"
        :color="snackbar.color"
        :timeout="3000"
      >
        {{ snackbar.message }}
      </v-snackbar>
    </v-card>
  </v-container>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
// import { useRouter } from 'vue-router'

// const router = useRouter()

// Estados
const selectedProduct = ref(null)
const quantity = ref(1)
const orderItems = ref([])
const isSearching = ref(false)
const isSaving = ref(false)

// Estado para nuevo producto
const showNewProductDialog = ref(false)
const isNewProductFormValid = ref(false)
const isCreatingProduct = ref(false)
const newProduct = reactive({
  name: '',
  price: '',
  weight: ''
})

// Datos de ejemplo (deberían venir de una API)
const products = ref([
  { id: 1, name: 'Producto 1', price: 100, weight: 1 },
  { id: 2, name: 'Producto 2', price: 200, weight: 2 },
  { id: 3, name: 'Producto 3', price: 300, weight: 3 },
])

// Snackbar
const snackbar = reactive({
  show: false,
  message: '',
  color: 'success'
})

// Computed properties
const totalWeight = computed(() => {
  return orderItems.value.reduce((total, item) => {
    return total + (item.weight * item.quantity)
  }, 0)
})

const totalAmount = computed(() => {
  return orderItems.value.reduce((total, item) => {
    return total + (item.price * item.quantity)
  }, 0)
})

// Métodos
const addToOrder = () => {
  if (!selectedProduct.value || !quantity.value) return

  const existingItem = orderItems.value.find(item => item.id === selectedProduct.value.id)
  
  if (existingItem) {
    existingItem.quantity += Number(quantity.value)
  } else {
    orderItems.value.push({
      ...selectedProduct.value,
      quantity: Number(quantity.value)
    })
  }

  // Resetear selección
  selectedProduct.value = null
  quantity.value = 1
}

const removeItem = (item) => {
  const index = orderItems.value.findIndex(i => i.id === item.id)
  if (index > -1) {
    orderItems.value.splice(index, 1)
  }
}

const handleCreateProduct = async () => {
  try {
    isCreatingProduct.value = true
    
    // Crear objeto con datos del producto
    const productData = {
      name: newProduct.name,
      price: Number(newProduct.price),
      weight: Number(newProduct.weight)
    }

    // Aquí iría la llamada a la API para crear el producto
    console.log('Datos del producto:', productData)
    
    // Simular llamada exitosa
    await new Promise(resolve => setTimeout(resolve, 1000))

    // Agregar el producto a la lista (en producción vendría de la API)
    const newId = products.value.length + 1
    const createdProduct = { id: newId, ...productData }
    products.value.push(createdProduct)

    // Mostrar mensaje de éxito
    snackbar.color = 'success'
    snackbar.message = 'Producto creado exitosamente'
    snackbar.show = true

    // Cerrar diálogo y resetear form
    showNewProductDialog.value = false
    newProduct.name = ''
    newProduct.price = ''
    newProduct.weight = ''

  } catch (error) {
    snackbar.color = 'error'
    snackbar.message = 'Error al crear el producto'
    snackbar.show = true
  } finally {
    isCreatingProduct.value = false
  }
}

const handleSaveOrder = async () => {
  try {
    isSaving.value = true
    
    const orderData = {
      items: orderItems.value,
      totalWeight: totalWeight.value,
      totalAmount: totalAmount.value
    }

    // Aquí iría la llamada a la API para guardar el pedido
    console.log('Datos del pedido:', orderData)
    
    // Simular llamada exitosa
    await new Promise(resolve => setTimeout(resolve, 1000))

    snackbar.color = 'success'
    snackbar.message = 'Pedido guardado exitosamente'
    snackbar.show = true

    // Redirigir al resumen de la compra
    // router.push('/purchase/1')
  } catch (error) {
    snackbar.color = 'error'
    snackbar.message = 'Error al guardar el pedido'
    snackbar.show = true
  } finally {
    isSaving.value = false
  }
}

const handleCancel = () => {
  // router.push('/purchase/1')
}
</script>