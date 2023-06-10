 <?php

 $addon = rex_addon::get('rexfactor');
 if (rex::isBackend() && rex::getUser()) {
     rex_view::addCssFile($addon->getAssetsUrl('styles.css'));
 }
