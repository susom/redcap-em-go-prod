<template>
  <div id="app" style="margin-left: 0 !important;margin-left: 0 !important;" class="container">
    <PageHeader class="mb-2"/>
    <ValidationComponent/>
  </div>
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

