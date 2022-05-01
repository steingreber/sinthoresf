<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(30, "mi_inicio_php", $Language->MenuPhrase("30", "MenuText"), "inicio.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(84, "mi_view_lista_whatsapp", $Language->MenuPhrase("84", "MenuText"), "view_lista_whatsapplist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, "mi_empresas", $Language->MenuPhrase("4", "MenuText"), "empresaslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(21, "mi_pessoas", $Language->MenuPhrase("21", "MenuText"), "pessoaslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(79, "mci_Sf3cios", $Language->MenuPhrase("79", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(29, "mi_socios", $Language->MenuPhrase("29", "MenuText"), "socioslist.php", 79, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(41, "mi_view_socios_vencendo", $Language->MenuPhrase("41", "MenuText"), "view_socios_vencendolist.php", 79, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(40, "mi_view_socios_a_vencer", $Language->MenuPhrase("40", "MenuText"), "view_socios_a_vencerlist.php", 79, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(22, "mi_regioes", $Language->MenuPhrase("22", "MenuText"), "regioeslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(5, "mi_funcionarios", $Language->MenuPhrase("5", "MenuText"), "funcionarioslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(7, "mi_permissoes", $Language->MenuPhrase("7", "MenuText"), "permissoeslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(3, "mi_config", $Language->MenuPhrase("3", "MenuText"), "configlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
