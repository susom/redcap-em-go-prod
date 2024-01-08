<template>
  <div>
    <div class="row">
      <div v-if="showAlert === false" class="col-12 d-grid gap-2">
        <button :disabled="buttonDisabled" @click="validate('ALL_VALIDATIONS')"
                class="btn btn-md btn-primary btn-block">{{
            notifications.RUN
          }}
        </button>
      </div>
      <div v-if="showAlert === true" class="col-12">
        <p :class="alertVariant" class="alert">{{ alertMessage }}</p>
      </div>
    </div>
    <hr>
    <div class="row" v-if="showLoaderIcon === true">
      <div class="col-12">
        <div class="d-flex justify-content-center">
          <div class="spinner-border" role="status">

          </div>
        </div>
      </div>
    </div>
    <div v-if="!showLoaderIcon === true && showErrorContainer === true" class="col-12">
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
        <tr v-for="(rule, i) in rulesArray" :key="i">
          <td class="gp-info-content">
            <div class="gp-title-content">
              <strong>
                <span v-html="rule.title"></span><span class="title-text-plus"
                                                       style="color: #5492a3"><small
                  v-on:click="showDetails(i)">({{ rule.showText }})</small></span>
              </strong>
            </div>
            <transition name="fade">
              <div class="gp-body-content" v-if="rule.show === true">
                <p>
                  <span v-html="rule.body"></span>
                </p>
              </div>
            </transition>
          </td>
          <td>
            <h6><span :class="rule.badge" class="badge">{{ rule.type }}</span></h6>
          </td>
          <td>
            <div v-for="link in rule.links" :key="link.url">
              <div class="row">
                <div class="col-12 mb-1">
                  <a target="_blank" :href="link.url">{{ link.title }}</a>
                </div>
              </div>
            </div>
            <div v-if="'extra' in rule">
              <span v-html="rule.extra"></span>
            </div>
            <div v-if="'modal' in rule">
              <a href="#" @click="viewModal(rule)" class="btn active btn-sm btn-secondary text-center">{{
                  notifications['VIEW']
                }}</a>
            </div>
          </td>
          <td>
            <div class="row">
              <div class="col text-center">
                <div v-if="rule.loader" class="d-flex justify-content-center">
                  <div class="spinner-border" role="status">
                  </div>
                </div>
                <button v-if="!rule.loader" class=" btn btn-sm btn-outline-primary text-center"
                        @click="validate(rule.name)">
                  {{ notifications.RELOAD }}
                </button>
              </div>
            </div>

          </td>
        </tr>
        </tbody>
      </table>
    </div>

    <div v-if="showSuccessContainer === true" class="col-12">
      <ul class="list-group">
        <li class="list-group-item">
          <h5 class="list-group-item-heading"><span v-html="notifications['INFO_WHAT_NETX']"></span></h5>
          <p class="list-group-item-text"><span v-html="notifications['INFO_WHAT_NETX_BODY']"></span></p>
          <p class="list-group-item-text"><span v-html="notifications['INFO_WHAT_NETX_BODY_2']"></span></p>
        </li>
      </ul>
      <ul class="list-group">
        <li class="list-group-item">
          <h5 class="list-group-item-heading"><span v-html="notifications['INFO_CITATION']"></span></h5>
          <p class="list-group-item-text"><span v-html="notifications['INFO_CITATION_BODY']"></span></p>
        </li>

        <li class="list-group-item">
          <h5 class="list-group-item-heading"><span v-html="notifications['INFO_STATISTICIAN_REVIEW']"></span>
          </h5>
          <p class="list-group-item-text"><span
              v-html="notifications['INFO_STATISTICIAN_REVIEW_BODY']"></span></p>
        </li>
      </ul>


      <div class="col-md-12 col-sm-6 col-xs-12 col-lg-12 text-center well">
        <h5>
          <span v-html="notifications['I_AGREE_BODY']"></span>
        </h5> <br>
        <a :href="productionURL" class=" btn btn-md btn-success active text-center ">
          {{ notifications['I_AGREE'] }}
        </a>
      </div>
    </div>

    <div v-if="superUserBypass === true" class="col-12">
      <div class="col-md-12 col-sm-6 col-xs-12 col-lg-12 text-center well">
        <h5>
          <span class="alert alert-warning" v-html="notifications['BYPASS_WARNING']"></span>
        </h5> <br>
        <a :href="productionURL" class=" btn btn-md btn-success active text-center ">
          {{ notifications['I_AGREE'] }}
        </a>
      </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="ruleModal" ref="ruleModal" tabindex="-1" aria-labelledby="ruleModalLabel"
         aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ modalObject.title }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body table-responsive">
            <table class="table table-hover">
              <thead>
              <tr>
                <th v-for="(column, index) in modalObject.modalHeader" :key="index">
                  {{ column }}
                </th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(item, index) in modalObject.modal" :key="index">
                <td v-for="(column, indexColumn) in modalObject.modalHeader" :key="indexColumn"
                    data-column="{{column}}"><span v-html="item[indexColumn]"></span>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn active btn-secondary" data-bs-dismiss="modal">Close</button>
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
    showDetails: function (key) {

      this.rulesArray[key]['show'] = !this.rulesArray[key]['show'];
      if (this.rulesArray[key]['show']) {
        this.rulesArray[key]['showText'] = this.notifications['LESS']
      } else {
        this.rulesArray[key]['showText'] = this.notifications['MORE']
      }
      this.$forceUpdate();
    },
    showLoader: function (key, value) {
      if (value === true) {
        this.showSuccessContainer = false
      }
      if (key === 'ALL_VALIDATIONS') {
        this.showLoaderIcon = value
      } else {
        if (key in this.rulesArray) {
          this.rulesArray[key]['loader'] = value
          this.$forceUpdate();
        }
      }
    },
    validate: function (action) {
      var obj = this
      this.showLoader(action, true)

      window.module.ajax(action).then(function (response) {
        // Do stuff with response
        console.log("ajax complete", response);
        if (response != undefined) {
          obj.showErrorContainer = true
          for (var key in response) {
            if (typeof response[key] === "object") {

              obj.rulesArray[key] = response[key]
              obj.rulesArray[key]['name'] = key
              obj.rulesArray[key]['showText'] = obj.notifications['LESS']
              obj.rulesArray[key]['loader'] = false
              obj.rulesArray[key]['show'] = true
              obj.rulesArray[key]['badge'] = 'badge-' + response[key]['type'].toLowerCase()

              // if this error increase counter
              if (response[key]['type'].toLowerCase() === 'danger') {
                // make sure the rule not already in the array.
                const index = obj.dangerErrorsArray.indexOf(key);
                if (index === -1) { // only splice array when item is found
                  obj.dangerErrorsArray.push(key) // 2nd parameter means remove one item only
                }
              }
            } else {
              // if failed rule succeeded remove it from rules list.
              if (key in obj.rulesArray) {
                var temp = obj.rulesArray
                obj.rulesArray = {}
                delete temp[key]
                obj.rulesArray = temp
                // delete rule from failed rules
                const index = obj.dangerErrorsArray.indexOf(key);
                if (index > -1) { // only splice array when item is found
                  obj.dangerErrorsArray.splice(index, 1); // 2nd parameter means remove one item only
                }

                console.log('Count after fix error: ' + obj.dangerErrorsArray)
              }
            }

          }
        }
        obj.showLoader(action, false)
        console.log(obj.dangerErrorsArray)
        // if no danger errors display success container.
        if (obj.dangerErrorsArray.length === 0) {
          obj.showSuccessContainer = true
          console.log('Count after: ' + obj.dangerErrorsArray)
        }

        // if logged in user is a superuser allow them to bypass go_prod
        if (window.isSuperUser === 1) {
          obj.superUserBypass = true
          console.log('Bypass checks')
        }
        obj.buttonDisabled = false;
      }).catch(function (err) {
        obj.showAlert = true
        obj.alertMessage = err
        console.log(err)
        obj.buttonDisabled = false;
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
      productionURL: window.productionURL,
      rulesArray: {},
      modalObject: {},
      showAlert: false,
      showLoaderIcon: false,
      showErrorContainer: false,
      showSuccessContainer: false,
      superUserBypass: false,
      buttonDisabled: false,
      dangerErrorsArray: [],
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