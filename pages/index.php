<?php

namespace Stanford\GoProd;

/** @var GoProd $module */

$name = $module->getJavascriptModuleObjectName();


echo $module->initializeJavascriptModuleObject();

// init REDCap VUEJS
print loadJS('vue/vue-factory/dist/js/app.js');

$user = $module->framework->getUser();
?>
<style>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }

    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
    {
        opacity: 0;
    }
</style>
<script src="<?php echo $module->getUrl("frontend/dist/go_prod_vue.umd.js") ?>"></script>
<div id="go_prod_vue"></div>
<script>
    window.productionURL = <?php  echo json_encode(APP_PATH_WEBROOT . 'ProjectSetup/index.php?pid=' . $module->getProjectId() . '&to_prod_plugin=1')?>;
    window.module = <?=$module->getJavascriptModuleObjectName()?>;
    window.notifications = <?php echo json_encode($module->getNotifications()) ?>;
    window.isSuperUser = <?=$user->isSuperUser() ?>;
    window.addEventListener('DOMContentLoaded', function (event) {
        const componentPromise = window.renderVueComponent(go_prod_vue, '#go_prod_vue')
        componentPromise.then(component => {
            console.log('component is ready')
        })
    })
</script>

