<template>
	<App>
		<template v-slot:section>
			<v-card title="Compras" flat></v-card>
		</template>

		<v-row>
			<v-col>
				<v-text-field 
					v-model="search" label="Buscar" prepend-inner-icon="mdi-magnify" 
					variant="solo" density="compact"
					hide-details single-line ></v-text-field>
			</v-col>
			<v-col cols="3">
				<v-btn variant="tonal">Crear</v-btn>
			</v-col>
		</v-row>

		<v-data-table :items="purchases" :headers="headers" :search="search"></v-data-table>
	</App>
</template>

<script setup>
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