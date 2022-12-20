<template>
    <div>
        <div class="row">
          <div class="col-12">
            <button class="btn btn-md btn-primary btn-block">{{notifications.RUN}}</button>
          </div>
        </div>
        <hr>
    </div>
</template>

<script>
export default {
  name: "ValidationComponent",
      data() {
    return {
      notifications: window.notifications
    }
  },
}
</script>

<style scoped>

</style>