<?php
function validate_password($password) {
    return strlen($password) >= 8;
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}