<script setup>
import { router } from '@inertiajs/vue3';
import CreateProviderDialog from '@/Components/CreateProviderDialog.vue';
import SectionHeader from '@/Components/SectionHeader.vue';
import App from '@/Layouts/App.vue';
import { reactive, ref } from 'vue';

const props = defineProps({
	providers: {
    type: Object,
    default() {
      return { }
    }
  }
})
const loading = ref(false)

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

function loadItems(options){
	console.log(options)
	
	router.reload({
		replace: false,
		data: {
			page: options.page,
			perPage: options.itemsPerPage,
			sortBy: options.sortBy[0]
		},
		only: ['providers'],
		onStart: () => loading.value = true,
		onFinish: () => loading.value = false,
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

		<v-data-table-server
			:headers="headers"
			:items="providers.data"
			:items-length="providers.total"
			:items-per-page="providers.per_page"
			:loading="loading"
			item-value="name"
			@update:options="loadItems">
		</v-data-table-server>
	</App>
</template>
