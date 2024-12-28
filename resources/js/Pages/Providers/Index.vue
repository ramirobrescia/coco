<script setup>
import { Link, router } from '@inertiajs/vue3';
import SectionHeader from '@/Components/SectionHeader.vue';
import App from '@/Layouts/App.vue';
import { reactive, ref, watch } from 'vue';
import { debounce } from 'lodash';

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
  },
	filters: {
    type: Object,
    default() {
      return {
				search: ''
			}
    }
  }
})

const loading = ref(true)
const search = ref(props.filters.search)
const searchInput = ref(props.filters.search)

const headers = [
	{ title: 'Nombre', key: 'name' },
	{ title: 'Teléfono', key: 'phone' },
	{ title: 'Email', key: 'email' },
	{ title: 'Contacto', key: 'contact' },
	{ title: '', key: 'actions', sortable: false },
]

// function providerCreated(){
// 	router.visit('/providers', {
// 		only: ['providers']
// 	})
// }

watch(searchInput, debounce(function(value){
	search.value = value;
}, 300))

function editItem(item){
	router.visit('/providers/' + item.id + '/edit')
}

function deleteItem(item){
	
}

function loadItems(options){
	router.reload({
		replace: false,
		data: {
			page: options.page,
			perPage: options.itemsPerPage,
			sortBy: options.sortBy[0],
			search: search.value
		},
		only: ['providers', 'filters'],
		onStart: () => loading.value = true,
		onFinish: () => loading.value = false,
	})
}

</script>
<template>
	<App>
		<template v-slot:section>
			<SectionHeader title="Proveedores">
				<v-text-field label="Buscar" class="align-self-center"
					v-model="searchInput"
					prepend-inner-icon="mdi-magnify"
					variant="solo"
					density="compact"></v-text-field>
				<Link href="/providers/create">
					<v-btn class="text-none" color="success" rounded="lg" slim variant="flat">Nuevo</v-btn>
				</Link>
			</SectionHeader>
		</template>

		<v-data-table-server :loading="loading" :headers="headers" :items="providers.data" :items-length="providers.total"
			:items-per-page="providers.per_page" item-value="name" :search="search"
			@update:options="loadItems">

			<template v-slot:item.actions="{ item }">
				<v-icon class="me-2" size="small" @click="editItem(item)">mdi-pencil</v-icon>
				<v-icon size="small" @click="deleteItem(item)">mdi-delete</v-icon>
			</template>
		</v-data-table-server>
	</App>
</template>
