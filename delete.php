<?php
// Redirect to front controller with delete action
header('Location: index.php?action=delete&id=' . ($_GET['id'] ?? ''));
exit;
