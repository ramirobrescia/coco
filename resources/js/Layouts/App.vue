<template>
	<v-responsive class="border rounded">
		<v-app :theme="theme" full-height>

			<v-app-bar color="pink" flat>
				<template v-slot:prepend>
					<v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
				</template>
				
				<template v-slot:title>
					<h1><strong>COCO</strong> <small>Compras Comunitarias</small></h1>
				</template>
				
				<template v-slot:append>
					<v-btn slim @click="toggleTheme">
						 <v-icon :icon="theme === 'light' ? 'mdi-weather-sunny' : 'mdi-weather-night'" />
					</v-btn>
				</template>
			</v-app-bar>

			<v-navigation-drawer v-model="drawer" temporary>
				<NavigationMenu />
			</v-navigation-drawer>

			<v-main class="d-flex h-100">
				<v-container>
					<slot name="section"></slot>
					<slot></slot>
				</v-container>
			</v-main>
		</v-app>
	</v-responsive>
</template>

<script setup>
import NavigationMenu from '@/Components/NavigationMenu.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue'

const theme = ref('light')
const drawer = ref(false)

function toggleTheme() {
	theme.value = theme.value === 'light' ? 'dark' : 'light'
}
</script>