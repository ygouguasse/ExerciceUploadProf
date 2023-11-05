<nav class="navbar navbar-expand-sm bg-primary navbar-dark w-100 rounded">
    <ul class="navbar-nav px-3 w-100">
        <li class="nav-item">
            <a  class="nav-link <?php ParDefaut(); NavClass("Accueil"); ?>"
                href="index.php?action=Accueil">
                Accueil
            </a>
        </li>
        <li class="nav-item">
            <a  class="nav-link <?php NavClass("FormulaireUpload"); ?>"
                href="index.php?action=FormulaireUpload">
                Formulaire upload
            </a>
        </li>
        <li class="nav-item">
            <a  class="nav-link <?php NavClass("AjaxUpload"); ?>"
                href="index.php?action=AjaxUpload">
                AJAX upload
            </a>
        </li>
        <li class="nav-item">
            <a  class="nav-link <?php NavClass("Images"); ?>"
                href="index.php?action=Images">
                Images
            </a>
        </li>
        <li class="nav-item">
            <a  class="nav-link <?php NavClass("Connexion"); ?>"
                href="index.php?action=Connexion">
                Connexion
            </a>
        </li>
    </ul>
</nav>

<?php
function ParDefaut() {
    if (!isset($_GET["action"])) {
        echo "active";
    }
}

function NavClass($menu) {
    if (isset($_GET["action"]) &&
        $_GET["action"] === $menu) {
        echo ' active ';
    }
}
?>
