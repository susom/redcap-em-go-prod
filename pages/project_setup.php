<?php

namespace Stanford\GoProd;

/** @var GoProd $this */

$name = $this->getJavascriptModuleObjectName();


echo $this->initializeJavascriptModuleObject();


$url = $this->getUrl('pages/index.php');

?>
<script>
    $(document).ready(function () {
        var module = <?=$this->getJavascriptModuleObjectName()?>;
        let ready_to_prod = module.getUrlParameter('to_prod_plugin')
        let MoveProd = $("button[onclick='btnMoveToProd();']")
        console.log(ready_to_prod)

        // Make a new button!
        let newButton = $('<button id="go_prod_plugin" class="btn btn-defaultrc btn-xs fs13">Check and move your project to PRODUCTION</button>')
            .bind('click', function () {
                let production = "<?php echo $url?>";
                location.href = production;
            });

        // Replace the original button
        MoveProd.hide().after(newButton);


        if (ready_to_prod === '1') {
            MoveProd.click();
        }
    });
</script>
