<script setup>
import App from '@/Layouts/App.vue';
import { Link, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
import * as yup from 'yup';
import { useForm } from 'vee-validate';

const { provider } = defineProps(['provider'])

const snackbar = reactive({
	active: false,
	timeout: 5000
});

// Validación
const { errors, defineField, setFieldError, handleSubmit }  = useForm({
	initialValues: {
		name: provider.name,
		phone: provider.phone,
		email: provider.email,
		contact: provider.contact,
		_method: 'PUT'
	},
	validationSchema: yup.object({
		name: yup.string().required(),
		phone: yup.string().required(),
		email: yup.string().email().required(),
		contact: yup.string().required()
	})
})

const processing = ref(false)
const [name, nameAttrs] = defineField('name')
const [phone, phoneAttrs] = defineField('phone')
const [email, emailAttrs] = defineField('email')
const [contact, contactAttrs] = defineField('contact')

function cancelar(){
	router.get('/providers', { replace: true })
}

// use `onSubmit` as an event handler for your forms
const guardar = handleSubmit(values => {
	router.post('/providers/' + provider.id, values, {
		preserveState: true,
		onBefore: () => processing.value = true,
		onFinish: () => processing.value = false,
		onError: (serverErrors) => {
			for (const [field, error] of Object.entries(serverErrors)) {
				setFieldError(field, error)
			}
		}
	})
});

</script>
<template>
	<App>
		<v-card>
			<v-card-title class="my-4">Editar proveedor</v-card-title>
			<v-card-text>
				<v-form id="createProvider" @submit.prevent="guardar">
					<div class="ga-3">
						<v-text-field v-model="name" label="Nombre"
							:error-messages="errors.name" v-bind="nameAttrs" />
						<v-text-field v-model="phone" label="Teléfono" type="phone" 
							:error-messages="errors.phone" v-bind="phoneAttrs" />
						<v-text-field v-model="email" label="E-mail"
							:error-messages="errors.email" v-bind="emailAttrs" />
						<v-text-field v-model="contact" label="Contacto"
							:error-messages="errors.contact" v-bind="contactAttrs" />
					</div>
				</v-form>
			</v-card-text>

			<v-card-actions class="justify-space-between mx-2 mb-2">
				<Link href="/providers">
					<v-btn text="Cancelar" color="red" variant="outlined" @click="cancelar" />
				</Link>
				<v-btn text="Guardar" color="success" variant="flat" @click="guardar" :loading="processing" />
			</v-card-actions>
		</v-card>
			
		<v-snackbar v-model="snackbar.active" id="create-provider-snackbar"
			:color="snackbar.color"
			:timeout="snackbar.timeout"
			location="top center"
			close-on-content-click>
			{{ snackbar.message }}
		</v-snackbar>
	</App>
</template>