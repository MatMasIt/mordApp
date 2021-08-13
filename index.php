<?php
require("lib.php");
require("ui.php");
?>
<!DOCTYPE html>
<html>
<title>Mordapp</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-teal.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link href="https://unpkg.com/tabulator-tables@4.1.4/dist/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.1.4/dist/js/tabulator.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-DHE2KP17BV"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-DHE2KP17BV');
</script>
<link rel="preload" as="script" href="https://cdn.iubenda.com/cs/iubenda_cs.js"/>
<link rel="preconnect" href="https://www.iubenda.com"/>
<link rel="preconnect" href="https://iubenda.mgr.consensu.org"/>
<link rel="preconnect" href="https://hits-i.iubenda.com"/>
<link rel="preload" as="script" href="https://cdn.iubenda.com/cs/tcf/stub-v2.js"/>
<script src="https://cdn.iubenda.com/cs/tcf/stub-v2.js"></script>
<script>
(_iub=self._iub||[]).csConfiguration={
	cookiePolicyId: 16543360,
	siteId: 2323189,
	localConsentDomain: 'mordapp.altervista.org',
	timeoutLoadConfiguration: 30000,
	lang: 'it',
	enableTcf: true,
	tcfVersion: 2,
	googleAdditionalConsentMode: true,
	consentOnContinuedBrowsing: false,
	banner: {
		position: "bottom",
		acceptButtonDisplay: true,
		customizeButtonDisplay: true,
		closeButtonDisplay: false,
		fontSizeBody: "14px",
	},
}
</script>
<script async src="https://cdn.iubenda.com/cs/iubenda_cs.js"></script>
<style>
@media (max-width: 639px) {
	#iubenda-cs-banner.iubenda-cs-default .iubenda-cs-rationale {
		height: 55vh !important;
		min-height: 320px !important;
	}
}
</style>
<body>
  <?php
  $nww=newViewCheck($_POST["sessionToken"],$_POST["action"]);
  $newView=$nww===true;
  $action = $_POST["action"] ?: $_GET["action"];
  switch ($action) {
    default:
      UIauth();
      break;
    case "signUp":
      UIsignedUp($_POST);
      break;
    case "verifyFirst":
      if (firstVerify($_GET["token"])) echo '
      <div class="w3-container w3-padding-32 w3-theme-d1">
        <h1>Verifica effettuata</h1>
      </div>
      Account verificato e pronto all\'accesso <br /><a href=".">Ok</a>';
      break;
    case "signIn":
      $token = login($_POST["email"], $_POST["password"]);
      if ($res == "NOT_FOUND" || $res == "INCORRECT_PASSWORD") {
        UIauth("<h2 style=\"color:red\">Credenziali errate</h2>");
        break;
      }
      if ($res == "UNVERIFIED") {
        UIauth("<h2 style=\"color:red\">Account non verificato</h2><a href=\"resend.php?email=" . htmlentities($_POST["email"]) . "\">Reinviare la mail di verifica?</a>");
        break;
      }
      mainMenu($token);

      break;
    case "showOrders":
      showOrders($_POST["sessionToken"]);
      break;
    case "editOrder":
      editOrder($_POST["sessionToken"], $_POST["orderId"]);
      break;
    case "mainMenu":
      mainMenu($_POST["sessionToken"]);
      break;
    case "saveOrder":
      if(!$newView){
          showOrders($_POST["sessionToken"]);
          break;
      }
      $res = processOrder($_POST);
      if ($res === true) {
        showOrders($_POST["sessionToken"], '<h2 style="color:green">Salvato</h2>');
        break;
      }
      if ($res === false) {
        showOrders($_POST["sessionToken"], '<h2 style="color:red">Errore</h2>');
        break;
      }
      if ($res === "INVALID_DATETIME_NO_DAY") {
        showOrders($_POST["sessionToken"], '<h2 style="color:green">Giorno errato</h2>');
        break;
      }
      if ($res === "INVALID_DATETIME_NO_TF") {
        showOrders($_POST["sessionToken"], '<h2 style="color:green">Ora errata</h2>');
        break;
      }
      if ($res === "WRONG_DOMAIN") {
        showOrders($_POST["sessionToken"], '<h2 style="color:green">L\'email deve essere @liceococito.it</h2>');
        break;
      }
      if ($res === "NO_CLASS") {
        showOrders($_POST["sessionToken"], '<h2 style="color:green">Classe errata</h2>');
        break;
      }
      break;
    case "deleteOrder":
      $res = deleteOrder($_POST["sessionToken"], $_POST["orderId"]);
      if ($res === true) {
        showOrders($_POST["sessionToken"], '<h2 style="color:green">Cancellato</h2>');
        break;
      }
      if ($res === false) {
        showOrders($_POST["sessionToken"], '<h2 style="color:red">Errore</h2>');
        break;
      }
      break;
    case "manageAccount":
      manageAccount($_POST["sessionToken"]);
      break;
    case "manageSave":
      if(!$newView){
          mainMenu($_POST["sessionToken"]);
          break;
      }
      $res = manageSave($_POST["sessionToken"], $_POST);
      if ($res === false) {
        mainMenu($_POST["sessionToken"], '<h2 style="color:green">Errore</h2>');
        break;
      }
      if ($res === "INVALID_EMAIL") {
        mainMenu($_POST["sessionToken"], '<h2 style="color:green">Email non valida</h2>');
        break;
      }
      if ($res === "WRONG_DOMAIN") {
        mainMenu($_POST["sessionToken"], '<h2 style="color:green">L\'email deve essere @liceococito.it</h2>');
        break;
      }
      if ($res === "NO_CLASS") {
        mainMenu($_POST["sessionToken"], '<h2 style="color:green">Classe errata</h2>');
        break;
      }
      mainMenu($_POST["sessionToken"]);
      break;
    case "showOrdersSTAFF":
      showOrdersSTAFF($_POST["sessionToken"]);
      break;
    case "timeframesSTAFF":
      timeframesView($_POST["sessionToken"]);
      break;
    case "saveTimefs":
      if(!$newView){
          mainMenu($_POST["sessionToken"]);
          break;
      }
      $res = saveTimefs($_POST["sessionToken"], $_POST["data"]);
      if ($res === false) {
        mainMenu($_POST["sessionToken"], '<h2 style="color:green">Errore</h2>');
        break;
      }
      mainMenu($_POST["sessionToken"]);
      break;
    case "menuSTAFF":
      dishesStaff($_POST["sessionToken"]);
      break;
    case "processDishes":
      if(!$newView){
          mainMenu($_POST["sessionToken"]);
          break;
      }
      $res = processDishes($_POST);
      if ($res === false) {
        mainMenu($_POST["sessionToken"], '<h2 style="color:green">Errore</h2>');
        break;
      }
      mainMenu($_POST["sessionToken"]);
      break;
  }
  ?>
  <div class="w3-container w3-theme-d4">
    <p class="w3-large">Mattia Mascarello, <?php echo date("Y"); ?></p>
    <a href="https://www.iubenda.com/privacy-policy/16543360" rel="noreferrer nofollow" target="_blank">Privacy Policy</a>
- <a href="#" role="button" class="iubenda-advertising-preferences-link">Personalizza tracciamento</a>
  </div>

</body>

</html>