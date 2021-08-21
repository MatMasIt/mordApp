<?php
require("lib.php");
$p = pdomake();
$q = $p->prepare("SELECT * FROM Users WHERE email=:email");
$q->execute([
   ":email" => $_GET["email"]
]);
$u = $q->fetch();
if(!$u["verified"] && $u){
	email("Nuovo Account", 'Grazie per esserti registratə su MordApp<br /><a class="w3-btn w3-teal" href="https://mordapp.altervista.org/app/1/?action=verifyFirst&token=' . $u["emailToken"] . '">Verifica il mio account</a>', $u["email"], $u["name"]);
    ?>
    
   <html>
    <title>Mordapp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-teal.css">
      <h1>Email re-inviata</h1>
      <a href=".">OK</a>
    </html>
    <?php
}
elseif($_POST["action"]=="chpass"){
	$q = $p->prepare("UPDATE Users SET passwordHash=:ph, emailToken=:ett, token=:sessionToken WHERE emailToken=:et ");
    $q->execute([
        ":ph" => password_hash($_POST["password"],PASSWORD_DEFAULT),
        ":et" => $_POST["tk"],
        ":ett" => bin2hex(random_bytes(16)),
        ":sessionToken" => bin2hex(random_bytes(16))
    ]);
    http_response_code(302);
    header("Location: .");
}
elseif(!empty($_GET["recover"])){
	?>
    <!DOCTYPE html>
    <html>
    <title>Mordapp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-teal.css">
      <h1>Recupero password</h1> <br />
      <form method="POST">
          <input type="password" name="password" placeholder="Nuova password" class="w3-input">
          <input type="hidden" name="action" value="chpass" >
          <input type="submit" value="Salva" class="w3-btn">
          <input type="hidden" name="tk" value="<?php echo htmlentities($_GET["recover"]);?>" >
      </form>
    </html>
    <?php
}
else{
	if(empty($_GET["email"])){
    	http_response_code(302);
        header("Location: .");
        exit;
    }
    if(!$u) die("Errore");
	$tk=bin2hex(random_bytes(16));
    $q = $p->prepare("UPDATE Users SET emailToken=:et WHERE email=:email");
    $q->execute([
        ":email" => $_GET["email"],
        ":et" => $tk
    ]);
    
    email("Recupero Account", 'Devi recuperare la tua password?<br /><a class="w3-btn w3-teal" href="https://mordapp.altervista.org/app/1/resend.php?recover='.$tk.'">Recupera il mio account</a>', $u["email"], $u["name"]);
   ?>
   <html>
    <title>Mordapp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-teal.css">
      <h1>Email inviata</h1>
      <a href=".">OK</a>
    </html>
   <?php
}
