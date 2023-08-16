<?php
if ($_SESSION['login_type'] != 0) {
    echo '<script>window.history.back();</script>';
    exit();
}
