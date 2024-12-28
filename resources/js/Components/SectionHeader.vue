<script setup>
defineProps(['title'])
</script>
<template>
	<div class="d-flex align-center justify-space-between mb-4">
		<h1 class="text-h4">{{ title }}</h1>
		
		<!-- Acciones -->
		<div class="d-flex w-50 ga-2">
			<slot></slot>
		</div>
	</div>
</template>