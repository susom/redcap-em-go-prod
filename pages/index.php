<?php

namespace Stanford\GoProd;

/** @var GoProd $module */

$name = $module->getJavascriptModuleObjectName();


echo $module->initializeJavascriptModuleObject();

// init REDCap VUEJS
print loadJS('vue/vue-factory/dist/js/app.js');

?>
<script src="<?php echo $module->getUrl("frontend/dist/go_prod_vue.umd.js") ?>"></script>
<div id="go_prod_vue"></div>
<script>
    window.module = <?=$module->getJavascriptModuleObjectName()?>;
    window.notifications = <?php echo json_encode($module->getNotifications()) ?>;
    window.addEventListener('DOMContentLoaded', function (event) {
        const componentPromise = window.renderVueComponent(go_prod_vue, '#go_prod_vue')
        componentPromise.then(component => {
            console.log('component is ready')
        })
    })
</script>

