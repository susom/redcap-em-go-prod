<template>
   <PageHeader class="mb-2"/>
  <ValidationComponent/>
</template>

<script>
import PageHeader from "./components/PageHeader.vue";
import ValidationComponent from "./components/ValidationComponent.vue";

export default {
  name: 'App',
  components: {
    PageHeader,
    ValidationComponent
  }
}
</script>

