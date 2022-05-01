<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(30, "mmi_inicio_php", $Language->MenuPhrase("30", "MenuText"), "inicio.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(84, "mmi_view_lista_whatsapp", $Language->MenuPhrase("84", "MenuText"), "view_lista_whatsapplist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, "mmi_empresas", $Language->MenuPhrase("4", "MenuText"), "empresaslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(21, "mmi_pessoas", $Language->MenuPhrase("21", "MenuText"), "pessoaslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(79, "mmci_Sf3cios", $Language->MenuPhrase("79", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(29, "mmi_socios", $Language->MenuPhrase("29", "MenuText"), "socioslist.php", 79, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(41, "mmi_view_socios_vencendo", $Language->MenuPhrase("41", "MenuText"), "view_socios_vencendolist.php", 79, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(40, "mmi_view_socios_a_vencer", $Language->MenuPhrase("40", "MenuText"), "view_socios_a_vencerlist.php", 79, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(22, "mmi_regioes", $Language->MenuPhrase("22", "MenuText"), "regioeslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(5, "mmi_funcionarios", $Language->MenuPhrase("5", "MenuText"), "funcionarioslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(7, "mmi_permissoes", $Language->MenuPhrase("7", "MenuText"), "permissoeslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(3, "mmi_config", $Language->MenuPhrase("3", "MenuText"), "configlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
