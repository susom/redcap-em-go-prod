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
                <span v-html="rule.title"></span><span class="title-text-plus"
                                                       style="color: #5492a3"><small>(more)</small></span>
              </strong>
            </div>

            <div class="gp-body-content">
              <p>
                <span v-html="rule.body"></span>
              </p>
            </div>
          </td>
          <td>
            <h6><span :class="rule.badge" class="badge">{{ rule.type }}</span></h6>
          </td>
          <td>
            <div v-for="link in rule.links" :key="link.url">
              <div class="row">
                <div class="col-12">
                  <a target="_blank" :href="link.url">{{ link.title }}</a>
                </div>
              </div>
            </div>
            <div v-if="'extra' in rule">
              <span v-html="rule.extra"></span>
            </div>
            <div v-if="'modal' in rule">
              <a href="#" @click="viewModal(rule)" class="btn btn-sm btn-secondary text-center">{{ notifications['VIEW'] }}</a>
            </div>
          </td>
          <td>
            <button class="btn btn-sm btn-outline-primary text-center" @click="validate(rule.name)">
              {{ notifications.RELOAD }}
            </button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="ruleModal" ref="ruleModal" tabindex="-1" aria-labelledby="ruleModalLabel"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ modalObject.title }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <table class="table table-striped">
              <thead>
              <tr>
                <th v-for="(column, index) in modalObject.modalHeader" :key="index">
                  {{ column }}
                </th>
              </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in modalObject.modal" :key="index">
                    <td v-for="(column, indexColumn) in modalObject.modalHeader" :key="indexColumn" data-column="{{column}}">{{item[indexColumn]}}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import {Modal} from 'bootstrap'

export default {
  name: "ValidationComponent",
  methods: {
    validate: function (action) {
      var obj = this
      window.module.ajax(action).then(function (response) {
        // Do stuff with response
        console.log("ajax complete", response);
        if (response != undefined) {
          obj.showErrorContainer = true

          for (var key in response) {
            if (typeof response[key] === "object") {

              obj.rulesArray[key] = response[key]
              obj.rulesArray[key]['name'] = key
              obj.rulesArray[key]['badge'] = 'badge-' + response[key]['type'].toLowerCase()

            } else {
              // if rule was failing then succeeded remove it from rules list.
              if (key in obj.rulesArray) {
                var temp = obj.rulesArray
                obj.rulesArray = {}
                delete temp[key]
                console.log(temp)
                obj.rulesArray = temp
              }
            }

            console.log(obj.rulesArray)
          }
        }
      }).catch(function (err) {
        obj.showAlert = true
        obj.alertMessage = err
        console.log(err)
      });
    },
    viewModal: function (rule) {
        this.modalObject = rule
        this.modal.show()
    }
  },
  data() {
    return {
      notifications: window.notifications,
      rulesArray: {},
      modalObject : {},
      showAlert: false,
      showErrorContainer: false,
      modal: null,
      alertMessage: '',
      alertVariant: 'alert-danger'
    }
  },
  mounted() {
    this.modal = new Modal(this.$refs.ruleModal)
  }
}
</script>

<style scoped>

</style>