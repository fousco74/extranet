<script setup lang="ts">
import AppComponent from '../../components/AppComponent.vue';
import NavBarComponent from '../../components/NavBarComponent.vue';
</script>

<template>
    <NavBarComponent :routeName="$page.props.routeName"></NavBarComponent>
    <AppComponent :apps="$page.props.apps"></AppComponent>
</template>