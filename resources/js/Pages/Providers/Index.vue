<script setup>
import { Link, router } from '@inertiajs/vue3';
import CreateProviderDialog from '@/Components/CreateProviderDialog.vue';
import SectionHeader from '@/Components/SectionHeader.vue';
import App from '@/Layouts/App.vue';
import { reactive, ref } from 'vue';

const loading = ref(true)

const props = defineProps({
	providers: {
    type: Object,
    default() {
      return {
				data: [],
				total: 0,
				per_page: 10
			}
    }
  }
})

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
				<Link href="/proveedores/create">
					<v-btn class="text-none" color="success" rounded="lg" slim variant="flat">Nuevo</v-btn>
				</Link>
			</SectionHeader>
		</template>

		<v-data-table-server
			:loading="loading"
			:headers="headers"
			:items="providers.data"
			:items-length="providers.total"
			:items-per-page="providers.per_page"
			item-value="name"
			@update:options="loadItems">
		</v-data-table-server>
	</App>
</template>
