<script setup>
import { useForm } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';

defineEmits([ 'created' ])

const dialog = ref(false);

const snackbar = reactive({
	active: false,
	timeout: 5000
});

const form = useForm({
	name: null,
	phone: null,
	email: null,
	contact: null
})

function cancelar(){
	form.reset()
	dialog.value = false
}

function guardar(){
	form.post('/proveedores', {
		onSuccess: function () {
			snackbar.message = 'El proveedor fue creado con éxito'
			snackbar.color = 'success'
			snackbar.active = true
			form.reset()
			dialog.value = false
		},
		onError: function (errors) {
			snackbar.message = 'Los datos enviados no son válidos'
			snackbar.color = 'red'
			snackbar.active = true
		}
	})
}

</script>
<template>
	<v-btn class="text-none" color="success" rounded="lg" slim variant="flat">
		Nuevo
		<v-dialog activator="parent" v-model="dialog" max-width="500" persistent>
			<template v-slot:default="{ isActive }">
				<v-card title="Nuevo proveedor">
					<v-card-text>
						<form id="createProvider" @submit.prevent="guardar">

							<div class="ga-3">
								<v-text-field v-model="form.name" label="Nombre"
									:error-messages="form.errors.name" />
								<v-text-field v-model="form.phone" label="Teléfono"
									type="phone"
									:error-messages="form.errors.phone"/>
								<v-text-field v-model="form.email" label="E-mail"
									type="email"
									:error-messages="form.errors.email"/>
								<v-text-field v-model="form.contact" label="Contacto"
									:error-messages="form.errors.contact" />
							</div>

						</form>
					</v-card-text>

					<v-card-actions class="justify-space-between mx-2 mb-2">
						<v-btn text="Cancelar" color="red" variant="outlined" @click="cancelar" />
						<v-btn text="Guardar" color="success" variant="flat" @click="guardar" />
					</v-card-actions>
				</v-card>
			</template>
		</v-dialog>
	</v-btn>
	<v-snackbar v-model="snackbar.active" 
		:color="snackbar.color"
		:timeout="snackbar.timeout"
		location="top center"
		close-on-content-click>
		{{ snackbar.message }}
	</v-snackbar>
</template>