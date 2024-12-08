<script setup>
import { router } from '@inertiajs/vue3';
import CreateProviderDialog from '@/Components/CreateProviderDialog.vue';
import SectionHeader from '@/Components/SectionHeader.vue';
import App from '@/Layouts/App.vue';

const props = defineProps(['providers'])
const headers = [
	{ title: 'Nombre', key: 'name' },
	{ title: 'Teléfono', key: 'phone' },
	{ title: 'Email', key: 'email' },
	{ title: 'Contacto', key: 'contact' },
]

function providerCreated(){
	router.visit('/proveedores', {
		only: ['providers']
	})
}

</script>
<template>
	<App>
		<template v-slot:section>
			<SectionHeader title="Proveedores">
				<CreateProviderDialog @created="providerCreated" />
			</SectionHeader>
		</template>

		<v-data-table :items="providers" :headers="headers"></v-data-table>
	</App>
</template>
