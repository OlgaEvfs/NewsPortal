<?php ob_start() ?>
<h2>404 error</h2>
<article>
    <h3>404 error - what is it?</h3>
    <p>The requested page was not found</p>

</article>
<?php $content = ob_get_clean(); ?>

<?php include "viewAdmin/templates/layout.php";