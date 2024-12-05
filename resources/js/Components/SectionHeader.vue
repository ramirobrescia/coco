<script setup>
defineProps(['title'])
</script>
<template>
	<div class="d-flex justify-space-between mb-4">
		<h1 class="text-h4">{{ title }}</h1>
		
		<!-- Acciones -->
		<div class="d-flex ga-2">
			<slot></slot>
		</div>
	</div>
</template>