<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
	<v-list>
		<Link href="/proveedores">
			<v-list-item prepend-icon="mdi-store" title="Proveedores" link />
		</Link>
		<Link href="/compras">
			<v-list-item prepend-icon="mdi-basket" title="Compras" link />
		</Link>
		<Link href="/profile">
			<v-list-item prepend-icon="mdi-account" title="Mi Cuenta" link />
		</Link>
		<Link :href="route('logout')" method="post" as="button">
			<v-list-item title="Salir" link />
		</Link>
	</v-list>
</template>
