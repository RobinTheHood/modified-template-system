<?php
// listing
if (isset($_GET['show'])) {
  $_SESSION['listbox'] = (($_GET['show'] == 'box') ? 'true' : 'false');
}

// load Template config
if (file_exists($modCoreTemplate->getPath('config/config.php'))) {
  require $modCoreTemplate->getPath('config/config.php');
}
?>