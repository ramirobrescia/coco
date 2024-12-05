<template>
	<App>
		<template v-slot:section>
			<v-card title="Proveedores" flat></v-card>
		</template>

		<v-data-table :items="providers" :headers="headers"></v-data-table>
	</App>
</template>

<script setup>
import App from '@/Layouts/App.vue';

const props = defineProps(['providers'])
const headers = [
	{ title: 'Nombre', key: 'name' },
	{ title: 'Teléfono', key: 'phone' },
	{ title: 'Email', key: 'email' },
	{ title: 'Contacto', key: 'contact' },
]

</script>