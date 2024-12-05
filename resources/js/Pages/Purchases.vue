<template>
	<App>
		<template v-slot:section>
			<SectionHeader title="Compras">
				<v-btn class="text-none" color="success" slim text="Crear" variant="flat" />
			</SectionHeader>
		</template>

		<v-row>
			<v-col>
				<v-text-field 
					v-model="search" label="Buscar" prepend-inner-icon="mdi-magnify" 
					variant="solo" density="compact"
					hide-details single-line ></v-text-field>
			</v-col>
		</v-row>

		<v-data-table :items="purchases" :headers="headers" :search="search"></v-data-table>
	</App>
</template>

<script setup>
import SectionHeader from '@/Components/SectionHeader.vue';
import App from '@/Layouts/App.vue';
import { ref } from 'vue';

const props = defineProps(['purchases'])
const headers = [
	{ title: 'Proveedor', key: 'provider.name' },
	{ title: 'Creador', key: 'user.name' },
	{ title: 'Fecha de cierre', key: 'deadline' },
]
const search = ref('')

</script>