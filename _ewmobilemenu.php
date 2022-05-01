<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(3, "mmi_config", $Language->MenuPhrase("3", "MenuText"), "configlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, "mmi_empresas", $Language->MenuPhrase("4", "MenuText"), "empresaslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(5, "mmi_funcionarios", $Language->MenuPhrase("5", "MenuText"), "funcionarioslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(7, "mmi_permissoes", $Language->MenuPhrase("7", "MenuText"), "permissoeslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
