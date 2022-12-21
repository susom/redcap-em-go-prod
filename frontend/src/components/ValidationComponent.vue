<template>
  <div>
    <div class="row">
      <div v-if="showAlert === false" class="col-12">
        <button @click="validate('ALL_VALIDATIONS')" class="btn btn-md btn-primary btn-block">{{
            notifications.RUN
          }}
        </button>
      </div>
      <div v-if="showAlert === true" class="col-12">
        <p :class="alertVariant" class="alert">{{ alertMessage }}</p>
      </div>
    </div>
    <hr>
    <div v-if="showErrorContainer === true" class="col-12">
      <table class="table table-striped">
        <thead>
        <tr>
          <th><h6 class="projhdr">Issues that you may need to fix</h6></th>
          <th><h6 class="projhdr">Type</h6></th>
          <th><h6 class="projhdr">Options</h6></th>
          <th></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="rule in rulesArray" :key="rule.name">
          <td class="gp-info-content">
            <div class="gp-title-content">
              <strong>
                <span v-html="rule.title"></span><span class="title-text-plus" style="color: #5492a3"><small>(more)</small></span>
              </strong>
            </div>

            <div class="gp-body-content">
              <p>
                <span v-html="rule.body"></span>
              </p>
            </div>
          </td>
          <td>
            <h6><span :class="rule.badge" class="badge">{{rule.type}}</span></h6>
          </td>
          <td>
            <div v-for="link in rule.links" :key="link.url">
                <div class="row">
                  <div class="col-12">
                    <a target="_blank" :href="link.url">{{link.title}}</a>
                  </div>
                </div>
            </div>
          </td>
          <td><button class="btn btn-sm btn-outline-primary text-center" @click="validate(rule.key)">{{notifications.RELOAD}}</button></td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  name: "ValidationComponent",
  methods: {
    validate: function (action) {
      var obj = this
      window.module.ajax(action).then(function (response) {
        // Do stuff with response
        console.log("ajax complete", response);
        console.log("length", response.length);
        if (response != undefined) {
          obj.showErrorContainer = true
          for (var key in response) {

            obj.rulesArray[key] = response[key]
            obj.rulesArray[key]['name'] = key
            obj.rulesArray[key]['badge'] = 'badge-' + response[key]['type'].toLowerCase()
            console.log(obj.rulesArray)
          }
        }
      }).catch(function (err) {
        obj.showAlert = true
        obj.alertMessage = err
        console.log(err)
      });
    }
  },
  data() {
    return {
      notifications: window.notifications,
      rulesArray: {},
      showAlert: false,
      showErrorContainer: false,
      alertMessage: '',
      alertVariant: 'alert-danger'
    }
  },
}
</script>

<style scoped>

</style>