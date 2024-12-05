<template>
	<App>
		<template v-slot:section>
			<SectionHeader title="Proveedores">
				<v-btn class="text-none" color="success" rounded="lg" slim text="Nuevo" variant="flat" />
			</SectionHeader>
		</template>

		<v-data-table :items="providers" :headers="headers"></v-data-table>
	</App>
</template>

<script setup>
import SectionHeader from '@/Components/SectionHeader.vue';
import App from '@/Layouts/App.vue';

const props = defineProps(['providers'])
const headers = [
	{ title: 'Nombre', key: 'name' },
	{ title: 'Teléfono', key: 'phone' },
	{ title: 'Email', key: 'email' },
	{ title: 'Contacto', key: 'contact' },
]

</script>