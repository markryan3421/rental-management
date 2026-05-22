<?php ob_start(); ?>









<?php
$dashboardContent = ob_get_clean();
require __DIR__ . '/../layouts/admin/layout.php';
