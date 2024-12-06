<?php
// Authenticate user
require_once 'common-helpers.php';
authenticate();

// Functions
function halt($msg) {
    echo '<div class="error-message">';
    echo '<span class="error-message-text">'.htmlspecialchars($msg).'</span>';
    echo '</div>';
    exit;
}

// Print header
$title = 'Home';
include_once 'header.php';
include_once 'navbar.php';

// Header
echo '<div class="header">';
echo '<h1>Welcome to Bolsillo!</h1>';
echo '</div>';

$marcadores = false;
try {
    require_once 'service.php';
    require_once 'data-access.php';
    $svc = new MarcadorService(new MarcadorDao());
    $usr = $_SESSION['user_id'];
    $marcadores = $svc->getMarcadores($usr);
} catch (Exception $e) {
    halt($e->getMessage());
}

// Add Marcador form
echo '<div class="row">';
echo '<div class="column">';
echo '<div class="add-marcador main-buttons">';
echo '<form action="add-markador.php" method="post">';
echo '<label class="marcadores-paragraph" for="new_uri">Save a new Marcador to Bolsillo</label>';
echo '<input type="text" id="new_uri" name="uri" placeholder="Marcador URL"/>';
echo '<input type="submit" value="Add"/>';
echo '<input type="reset" value="Clear"/>';
echo '</form>';
echo '</div>';
echo '</div>';
echo '</div>';

// Bookmarks paragraph
echo '<div class="row">';
echo '<div class="column">';
echo '<p class="marcadores-paragraph">';
if ($marcadores):
    echo 'There are Marcadores that you saved.';
else:
    echo 'There is no Marcadores yet.';
endif;
echo '</p>';
echo '</div>';
echo '</div>';

if ($marcadores):
    foreach ($marcadores as $i => $bm):

        // Marcador Link (anchor element)
        echo '<div class="row">';

        echo '<div class="column-9-10">';
        echo '<div class="marcador-container">';
        echo '<a href="'.$bm->getUri().'">';
        echo '<span>'.htmlspecialchars($bm->getUri()).'</span>';
        echo '</a>';
        echo '</div>';
        echo '</div>';

        echo '<div class="column-1-10">';
        echo '<div class="marcador-container">';
        // Delete Marcador form
        echo '<form class="main-buttons" action="delete-markador.php" method="POST">';
        echo '<input type="hidden" name="bm_id" value="'.$bm->getId().'"/>';
        echo '<input type="submit" value="Delete"/>';
        echo '</form>';

        // Update Marcador form
        echo '<form class="main-buttons" action="update-marcador-form.php" method="GET">';
        echo '<input type="hidden" name="id" value="'.$bm->getId().'"/>';
        echo '<input type="submit" value="Update"/>';
        echo '</form>';

        echo '</div>';
        echo '</div>';

        echo '</div>';
    endforeach;
endif;

// Footer
print_footer();
