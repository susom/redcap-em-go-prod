<template>
  <div>
    <div class="projhdr">{{ notifications.TITLE }}</div>
    <span v-html="notifications.MAIN_TEXT"></span>
  </div>
</template>

<script>
export default {
  name: "PageHeader",
  data() {
    return {
      notifications: window.notifications
    }
  },
}
</script>
