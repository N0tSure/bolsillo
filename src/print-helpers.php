<?php

function print_header($title) {
    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<style>';
    echo 'span.error {';
    echo 'background: #F5B7B1;';
    echo '}';
    echo '</style>';
    echo '<title>Bolsillo :: '.htmlspecialchars($title).'</title>';
    echo '</head>';
    echo '<body>';
}

function print_footer() {
    echo '</body>';
    echo '</html>';
}

?>
