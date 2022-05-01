<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(3, "mi_config", $Language->MenuPhrase("3", "MenuText"), "configlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, "mi_empresas", $Language->MenuPhrase("4", "MenuText"), "empresaslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(5, "mi_funcionarios", $Language->MenuPhrase("5", "MenuText"), "funcionarioslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(7, "mi_permissoes", $Language->MenuPhrase("7", "MenuText"), "permissoeslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
