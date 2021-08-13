<?php
function UIauth($append = "")
{
?>
    <!--
<div class="w3-row w3-padding w3-theme-d2 w3-xlarge">
  <div class="w3-quarter">
    <div class="w3-bar">
      <a href="#" class="w3-bar-item w3-button"><i class="fa fa-bars"></i></a>
    </div>
  </div>

  <div class="w3-half">
    <input type="text" class="w3-amber w3-border-0 w3-padding" style="width:100%">
  </div>

  <div class="w3-quarter">
    <div class="w3-bar w3-xlarge">
      <a href="#" class="w3-bar-item w3-button w3-left"><i class="fa fa-search"></i></a>
      <a href="#" class="w3-bar-item w3-button w3-right"><img class="w3-hide-small w3-circle" src="img_avtar.jpg" style="height:40px;"></a>
    </div>

  </div>
</div>
-->
    <div class="w3-container w3-padding-32 w3-theme-d1">
        <h1>MordApp</h1>
        <?php echo $append; ?>
    </div>

    <form class="w3-container w3-card-4" method="POST">
        <input type="hidden" name="action" value="signIn">
        <h2 class="w3-text-teal">Accesso</h2>
        <p>
            <label class="w3-text-teal"><b>Email </b></label>
            <input class="w3-input w3-border" name="email" type="email" requried>
        </p>
        <p>
            <label class="w3-text-teal"><b>Password</b></label>
            <input class="w3-input w3-border" name="password" type="password" requried>
        </p>
        <p>
            <button class="w3-btn w3-teal">Accedi</button>
        </p>
    </form>

    <form class="w3-container w3-card-4" method="POST" action="">
        <input type="hidden" name="action" value="signUp">
        <h2 class="w3-text-teal">Registrazione</h2>
        <p>
            <label class="w3-text-teal"><b>Nome e Cognome</b></label>
            <input class="w3-input w3-border" name="name" type="text" requried>
        </p>
        <p>
        <p>
            <label class="w3-text-teal"><b>Email <sub><i>(@liceococito.it)</i></sub></b></label>
            <input class="w3-input w3-border" name="email" type="email" requried>
        </p>
        <p>
        <p>
            <label class="w3-text-teal"><b>Categoria</b></label>
            <input class="w3-radio" type="radio" name="cat" value="student" checked>
            <label>Studente</label>

            <input class="w3-radio" type="radio" name="cat" value="teacher">
            <label>Docente</label>

            <input class="w3-radio" type="radio" name="cat" value="other">
            <label>Personale non docente</label>
        </p>
        <p id="classSelect">
            <label class="w3-text-teal"><b>Classe</b></label> <br />
            <select class="w3-select" name="classe" style="display: inline-block;width:20vw">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <select class="w3-select" name="sezione" style="display: inline-block;width:20vw">
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="E">E</option>
                <option value="F">F</option>
                <option value="G">G</option>
                <option value="H">H</option>
                <option value="I">I</option>
                <option value="J">J</option>
                <option value="K">K</option>
                <option value="L">L</option>
                <option value="M">M</option>
                <option value="N">N</option>
                <option value="O">O</option>
                <option value="P">P</option>
                <option value="Q">Q</option>
                <option value="R">R</option>
                <option value="S">S</option>
                <option value="T">T</option>
                <option value="U">U</option>
                <option value="V">V</option>
                <option value="W">W</option>
                <option value="X">X</option>
                <option value="Y">Y</option>
                <option value="Z">Z</option>
            </select>
        <p>
            <label class="w3-text-teal"><b>Password</b></label>
            <input class="w3-input w3-border" name="password" type="password" requried>
        </p>
        <p>
        <p>
            <label class="w3-text-teal"><b>Conferma password</b></label>
            <input class="w3-input w3-border" name="passwordtwo" type="password" requried>
        </p>
        <p>
            <button class="w3-btn w3-teal">Registrati</button>
        </p>
    </form>
    <script>
        $('input[type=radio][name=cat]').change(function() {
            if (this.value == 'student' && !$("#classSelect").is(":visible")) {
                $("#classSelect").fadeIn();
            } else {
                $("#classSelect").fadeOut();
            }
        });
    </script>
<?php
}

function UIsignedUp($data)
{
    $proceed = false;
    if ($data["password"] != $data["passwordtwo"]) return UIauth("<h2 style=\"color:red\">Le due password non coincidono</h2>");
    if (!in_array($data["cat"], ["student", "teacher", "other"]))  return UIauth("<h2 style=\"color:red\">Selezione una categoria</h2>");
    if ($data["cat"] == "student" && !in_array($data["classe"], str_split("12345"))) return UIauth("<h2 style=\"color:red\">Selezione una classe</h2>");
    if ($data["cat"] == "student" && !in_array($data["sezione"], str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ"))) return UIauth("<h2 style=\"color:red\">Selezione una sezione</h2>");
    if (empty($data["name"]) || empty($data["email"]) || empty($data["password"])) return UIauth("<h2 style=\"color:red\">Compila tutti i campi</h2>");
    if ($data["cat"] == "student") $classe = $data["classe"] . $data["sezione"];
    elseif ($data["cat"] == "teacher") $classe = "Docente";
    else $classe = "altro";
    $res = new_account($data["email"], $data["name"], $data["password"], $classe);
    switch ($res) {
        case "ALREADY_EXISTS":
            return UIauth("<h2 style=\"color:red\">Esiste gi&agrave; un account con questa email</h2>");
            break;
        case "INVALID_EMAIL":
            return UIauth("<h2 style=\"color:red\">Email non valida</h2>");
            break;
        case "WRONG_DOMAIN":
            return UIauth("<h2 style=\"color:red\">Occorre una email @liceococito.it</h2>");
            break;
    }
    email("Nuovo Account", 'Grazie per esserti registratə su MordApp<br /><a class="w3-btn w3-teal" href="http://127.0.0.1:9999/?action=verifyFirst&token=' . $res . '">Verifica il mio account</a>', $data["email"], $data["name"]);
?>
    <div class="w3-container w3-padding-32 w3-theme-d1">
        <h1>Mordapp</h1>
    </div>
    <div class="w3-container w3-card-4" method="POST" action="">
        Account registrato.<br />Ti arriverà una email di conferma a breve per l'attivazione
        <br />
        <a href=".">Ok</a>
    </div>
<?php
}



function mainMenu($token, $addition = "")
{
    $u = use_token($token);
    if (!$u) return UIauth("<h2 style=\"color:red\">Sessione invalida</h2>");
?>
    <div class="w3-container w3-padding-32 w3-theme-d1">
        <h1>Benvenutə</h1>
        <p>Mordapp</p>
        <?php echo $addition; ?>
        <img class=" w3-circle w3-right" src="https://eu.ui-avatars.com/api/?name=<?php echo htmlentities($u["name"]); ?>" style="height:40px;">
    </div>
    <br />

    <?php
    if (!$u["isStaff"]) {
    ?>

        <div class="w3-container w3-row">

            <div class="w3-card-4 w3-third">
                <center> <img src="images/food.jpg" alt="Food" style="width:75%"> </center>
                <div class="w3-container w3-center">
                    <br />
                    <form method="POST">
                        <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                        <input type="hidden" name="action" value="showOrders">
                        <input type="submit" class="w3-btn w3-teal" value="Ordini">
                    </form>
                </div>
                <br />
            </div>
            <div class="w3-card-4 w3-third">
                <center> <img src="images/personal.png" alt="Utente" style="width:50%"> </center>
                <div class="w3-container w3-center">
                    <br />
                    <form method="POST">
                        <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                        <input type="hidden" name="action" value="manageAccount">
                        <input type="submit" class="w3-btn w3-teal" value="Gestione Account">
                    </form>
                </div>
                <br />
            </div>
            <div class="w3-card-4 w3-third">
                <center> <img src="images/logout.png" alt="Esci" style="width:50%"> </center>
                <div class="w3-container w3-center">
                    <br />
                    <form method="POST">
                        <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                        <input type="hidden" name="action" value="logout">
                        <input type="submit" class="w3-btn w3-teal" value="Esci">
                    </form>
                </div>
                <br />
            </div>

        </div>
    <?php
    } else {
    ?>
        <div class="w3-container w3-row">

            <div class="w3-card-4 w3-third">
                <center> <img src="images/food.jpg" alt="Food" style="width:73%"> </center>
                <div class="w3-container w3-center">
                    <br />
                    <form method="POST">
                        <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                        <input type="hidden" name="action" value="showOrdersSTAFF">
                        <input type="submit" class="w3-btn w3-teal" value="Ordini">
                    </form>
                </div>
                <br />
            </div>
            <div class="w3-card-4 w3-third">
                <center> <img src="images/stopwatch.png" alt="Fasce Orarie" style="width:101%"> </center>
                <div class="w3-container w3-center">
                    <br />
                    <form method="POST">
                        <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                        <input type="hidden" name="action" value="timeframesSTAFF">
                        <input type="submit" class="w3-btn w3-teal" value="Fasce orarie">
                    </form>
                </div>
                <br />
            </div>
            <div class="w3-card-4 w3-third">
                <center> <img src="images/menu.gif" alt="Menu" style="width:70%"> </center>
                <div class="w3-container w3-center">
                    <br />
                    <form method="POST">
                        <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                        <input type="hidden" name="action" value="menuSTAFF">
                        <input type="submit" class="w3-btn w3-teal" value="Menu">
                    </form>
                </div>
                <br />
            </div>
        </div>

        <div class="w3-container w3-row">
            <div class="w3-card-4 w3-third">
                <center> <img src="images/personal.png" alt="Utente" style="width:50%"> </center>
                <div class="w3-container w3-center">
                    <br />
                    <form method="POST">
                        <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                        <input type="hidden" name="action" value="manageAccount">
                        <input type="submit" class="w3-btn w3-teal" value="Gestisci questo account">
                    </form>
                </div>
                <br />
            </div>
            <div class="w3-card-4 w3-third">
                <center> <img src="images/logout.png" alt="Esci" style="width:50%"> </center>
                <div class="w3-container w3-center">
                    <br />
                    <form method="POST">
                        <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                        <input type="hidden" name="action" value="logout">
                        <input type="submit" class="w3-btn w3-teal" value="Esci">
                    </form>
                </div>
            <?php
        }
            ?>
            <br />
            </div>

        </div>
        <br />
    <?php
}

function showOrders($token, $add = "")
{
    $list = listMyOrders($token);
    $u = use_token($token);
    if (!$u) return UIauth("<h2 style=\"color:red\">Sessione invalida</h2>");
    ?>
        <div class="w3-container w3-padding-32 w3-theme-d1">
            <h1>Ordini</h1>
            <p>MordApp</p>
            <img class=" w3-circle w3-right" src="https://eu.ui-avatars.com/api/?name=<?php echo htmlentities($u["name"]); ?>" style="height:40px;">
            <br />
            <?php echo $add; ?>
            <br /><br />

            <form method="POST" class="w3-left">
                <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                <input type="hidden" name="action" value="mainMenu">
                <input type="submit" class="w3-btn w3-red" value="Indietro">
            </form>
            <form method="POST" class="w3-right">
                <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                <input type="hidden" name="action" value="editOrder">
                <input type="hidden" name="orderId" value="NEW">
                <input type="submit" class="w3-btn w3-white" value="Nuovo Ordine">
            </form>
        </div>
        <br />
        <div class="w3-container w3-row">
            <?php
            $tot = 0;
            foreach ($list as $el) {

            ?>
                <div class="w3-card-4 w3-third">
                    <div class="w3-container w3-center">
                        <br />
                        <b>Data</b>: <?php echo date("d/m/Y", $el["datetime"]); ?><br />
                        <b>Ora</b>: <?php echo date("H:i:s", $el["datetime"]); ?><br v />
                        <hr />
                        <sub>Creato il <?php echo date("d/m/Y H:i:s", $el["createdAt"]); ?></sub>
                        <hr />
                        <?php
                        foreach ($el["dishes"] as $d) {
                            echo "<b>" . htmlentities($d["name"]) . "</b> &times" . ((int)$d["dishQty"]) . "<br />";
                            $subtot = ((int)$d["dishQty"]) * $d["price"];
                            echo "<i> &euro;" . number_format($d["price"], "2", ",", "'") . " &times; " . ((int)$d["dishQty"]) . " = &euro; " . number_format($subtot, "2", ",", "'") . "<br />";
                            if ($d["dishNotes"]) echo "<br /><p><b>Note:</b><br />" . htmlentities($d["dishNotes"]) . "</p><br />";
                            $tot += $subtot;
                        ?>
                            <hr /><?php
                                }
                                    ?>
                        <hr />
                        <hr />
                        Totale: &euro; <?php echo number_format($tot, "2", ",", "'"); ?>
                        <?php
                        if ($el["notes"]) echo "<br /><p><b>Note:</b><br />" . htmlentities($el["notes"]) . "</p><br />";
                        ?>
                        <br />
                        <br />
                        <div class="w3-row">
                            <form method="POST" class="w3-half">
                                <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                                <input type="hidden" name="action" value="editOrder">
                                <input type="hidden" name="orderId" value="<?php echo htmlentities($el["id"]); ?>">
                                <input type="submit" class="w3-btn w3-teal" value="Modifica">
                            </form>
                            <form method="POST" class="w3-half">
                                <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                                <input type="hidden" name="action" value="deleteOrder">
                                <input type="hidden" name="orderId" value="<?php echo htmlentities($el["id"]); ?>">
                                <input type="submit" class="w3-btn w3-red" value="Elimina">
                            </form>
                        </div>
                    </div>
                    <br />
                </div>
            <?php
            }
            ?>

        </div>

        <br />
    <?php
}


function editOrder($token, $orderId)
{
    $u = use_token($token);
    if (!$u) return UIauth("<h2 style=\"color:red\">Sessione invalida</h2>");
    if ($orderId != "NEW") {
        $o = getOrder($token, $orderId);
        if (!$o) return showOrders($token);
        $new = false;
    } else {
        $new = true;
    }
    ?>
        <div class="w3-container w3-padding-32 w3-theme-d1">
            <?php
            if (!$new) {
            ?><h1>Ordine per il <?php echo date("d/m/Y H:i:s", $o["datetime"]); ?></h1><?php
                                                                                    } else {
                                                                                        ?><h1>Nuovo Ordine</h1><?php
                                                                                                            }
                                                                                                                ?>
            <p>MordApp</p>
            <img class=" w3-circle w3-right" src="https://eu.ui-avatars.com/api/?name=<?php echo htmlentities($u["name"]); ?>" style="height:40px;">
            <br />
            <br /><br />

            <form method="POST" class="w3-left">
                <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                <input type="hidden" name="action" value="showOrders">
                <input type="submit" class="w3-btn w3-red" value="Annulla">
            </form>


        </div>
        <br />
        <form method="POST">
            <input type="hidden" name="action" value="saveOrder">
            <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
            <input type="hidden" name="orderId" value="<?php echo $orderId; ?>">
            <div class="w3-container w3-row">
                <?php
                $TOT = 0;
                $p = pdomake();
                $q = $p->prepare("SELECT * FROM Dishes WHERE deleted=0 ORDER BY name ASC");
                $q->execute();
                $list = $q->fetchAll(PDO::FETCH_ASSOC);
                foreach ($list as $el) {
                    $val = 0;
                    $total = "0,00";
                    $notes = "";
                    foreach ($o["dishes"] as $di) {
                        if ($di["id"] == $el["id"]) {
                            $val = $di["dishQty"];
                            $total = number_format($val * $el["price"], 2, ",", "'");
                            $TOT += $val * $el["price"];
                            $notes = $di["dishNotes"];
                            break;
                        }
                    }
                ?>
                    <div class="w3-card-4 w3-third">

                        <div class="w3-container w3-center">

                            <b><?php echo htmlentities($el["name"]); ?></b><br />
                            <i> &euro;<?php echo number_format($el["price"], "2", ",", "'"); ?> &times; <input type="number" min="0" step="1" class="w3-input price" name="qtyOF<?php echo (int) $el["id"]; ?>" value="<?php echo (int) $val; ?>" data-cpu="<?php echo (float)$el["price"]; ?>"> = € <span class="priceThis"><?php echo $total; ?></span></i>
                            <br />
                            <hr /><br />
                            <lablel>Note</lablel>
                            <textarea name="notesOF<?php echo (int) $el["id"]; ?>" class="w3-input"><?php echo htmlentities($notes); ?></textarea>
                        </div>
                        <br />
                    </div>
                <?php
                }
                ?>

            </div>
            <center>
                <hr />
                <b>Totale:</b> &euro; <span id="tot"><?php echo number_format($TOT, 2, ",", "'"); ?></span>
                <?php
                $data = computeTimeframes();
                ?>
                <br />
                <label>Giorno</label>
                <select class="w3-input" name="day">
                    <?php
                    if ($new) {
                    ?>
                        <option selected>Seleziona un giorno</option>
                    <?php
                    }
                    foreach ($data["all"] as $d) {
                        $addition = "";
                        if (!$new && $d["value"] == strtotime("midnight", $o["datetime"])) $addition = "selected";
                        echo "<option value=\"" . htmlentities($d["value"]) . "\" data-day=\"" . strtolower(date("l", $d["value"])) . "\" " . $addition . ">" . htmlentities($d["label"]) . "</option>\n";
                    }
                    ?>
                </select>
                <label>Fascia Oraria</label>

                <select class="w3-input" name="timeFrames">
                    <?php
                    if ($new) {
                    ?>
                        <option selected>Seleziona un giorno</option>
                    <?php
                    }
                    ?>
                </select>
                <br />
                <label>Note</label>
                <textarea name="orderNotes" class="w3-input"><?php echo htmlentities($o["notes"]); ?></textarea>
                <br />
                <br />
                <?php
                if($new){
                    ?><input type="submit" class="w3-button w3-teal w3-right" value="Ordina"><?php
                }
                else{
                    ?><input type="submit" class="w3-button w3-teal w3-right" value="Aggiorna"><?php
                }
                ?>
                <br />
                <br />
                <br />
                <script>
                    function updateTimeFrames(a, ind = -1) {
                        var day = a.find(":selected").attr("data-day");
                        var thtml = "";
                        data[day].forEach(function(value, index) {
                            var addition = "";
                            if (ind != (-1) && index == ind) addition = "selected";
                            thtml += "<option value=\"" + index + "\" " + addition + " >" + value["begin"]["label"] + " - " + value["end"]["label"] + "</option>";
                        });
                        $("[name=timeFrames]").html(thtml);
                    }
                    var data = <?php echo json_encode($data["times"], JSON_PRETTY_PRINT); ?>;
                    <?php
                    $begin = $o["datetime"] - strtotime("midnight", $o["datetime"]);
                    $i = 0;
                    foreach ($data["times"][strtolower(date("l", $o["datetime"]))] as $tf) {
                        if (($tf["begin"]["value"] * 60) == $begin) {
                            echo "updateTimeFrames($('select[name=day]'),$i);";
                            break;
                        }
                        $i++;
                    }
                    ?>
                    $('select[name=day]').on('change', function() {
                        updateTimeFrames($(this));

                    });
                </script>
            </center>
        </form>
        <script>
            var tot = 0;
            tot = 0;

            function numberWithCommas(x) {
                return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, "'");
            }

            function cleanupMeasures() {
                $(".priceThis").each(function() {
                    $(this).html(numberWithCommas($(this).html()).replace(".", ","));
                });
                $("#tot").html(numberWithCommas($("#tot").html()).replace(".", ","));
            }
            $("input.price").on("input", function() {
                var val = ($(this).val() * $(this).attr("data-cpu")).toFixed(2);
                $(this).closest("i").children(".priceThis").html(val);
                tot = 0;
                $(".priceThis").each(function() {
                    tot += parseFloat($(this).html());
                });
                $("#tot").html(tot.toFixed(2));
                cleanupMeasures();
            });
        </script>


        <br />
    <?php
}


function manageAccount($token)
{
    $u = use_token($token);
    if (!$u) return UIauth("<h2 style=\"color:red\">Sessione invalida</h2>");
    ?>
        <div class="w3-container w3-padding-32 w3-theme-d1">
            <h1>Gestione account</h1>
            <p>Mordapp</p>
            <img class=" w3-circle w3-right" src="https://eu.ui-avatars.com/api/?name=<?php echo htmlentities($u["name"]); ?>" style="height:40px;">

            <br />
            <br />
            <form method="POST" class="w3-left">
                <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                <input type="hidden" name="action" value="mainMenu">
                <input type="submit" class="w3-btn w3-red" value="Indietro">
            </form>
        </div>
        <br />
        <br />


        <form class="w3-container w3-card-4" method="POST">
            <input type="hidden" name="action" value="manageSave">
            <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
            <h2 class="w3-text-teal">Accesso</h2>
            <p>
                <label class="w3-text-teal"><b>Nome</b></label>
                <input class="w3-input w3-border" name="name" type="text" value="<?php echo htmlentities($u["name"]); ?>" requried>
            </p>
            <p>
                <label class="w3-text-teal"><b>Email </b></label>
                <input class="w3-input w3-border" name="email" type="email" value="<?php echo htmlentities($u["email"]); ?>" requried>
            </p>
            <p>
                <label class="w3-text-teal"><b>Classe </b></label>
                <select name="classe" class="w3-input">
                    <?php
                    foreach ($GLOBALS["classesWhitelist"] as $wle) {
                        if ($wle == "Docenti") {
                    ?>
                            <option value="teacher" <?php if($u["classe"]==$wle){ echo "selected";} ?>><?php echo htmlentities($wle); ?></option>
                        <?php
                            continue;
                        }
                        if ($wle == "altro") {
                        ?>
                            <option value="other" <?php if($u["classe"]==$wle){ echo "selected";} ?>>Altro</option>
                        <?php
                            continue;
                        }
                        ?>
                        <option value="<?php echo htmlentities($wle); ?>" <?php if($u["classe"]==$wle){ echo "selected";} ?>><?php echo htmlentities($wle); ?></option>
                    <?php

                    }
                    ?>
                </select>
            </p>
            <p>
                <button class="w3-btn w3-teal">Salva</button>
            </p>
        </form>
        <br />
    <?php
}
function showOrdersSTAFF($token, $add = "")
{
    $u = use_token($token);
    if (!$u || !$u["isStaff"]) return UIauth("<h2 style=\"color:red\">Sessione invalida</h2>");
    $data = computeTimeframes();
    ?>
        <div class="w3-container w3-padding-32 w3-theme-d1">
            <h1>Ordini</h1>
            <p>MordApp</p>
            <img class=" w3-circle w3-right" src="https://eu.ui-avatars.com/api/?name=<?php echo htmlentities($u["name"]); ?>" style="height:40px;">
            <br />
            <?php echo $add; ?>
            <br /><br />

            <form method="POST" class="w3-left">
                <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                <input type="hidden" name="action" value="mainMenu">
                <input type="submit" class="w3-btn w3-red" value="Indietro">
            </form>
            <form method="POST" class="w3-right">
                <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                <input type="hidden" name="action" value="editOrder">
                <input type="hidden" name="orderId" value="NEW">
                <input type="submit" class="w3-btn w3-white" value="Nuovo Ordine">
            </form>
            <br />
            <center>
                <h2>Parametri di ricerca</h2>
                <form method="POST">
                    <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                    <input type="hidden" name="action" value="showOrdersSTAFF">
                    <br /><label>Data</label><br />
                    <input type="date" class="w3-input" name="day" value="<?php if (isset($_POST["day"])) {
                                                                                echo htmlentities($_POST["day"]);
                                                                            } ?>">
                    <button id="today" class="w3-btn w3-white" type="button">Imposta a Oggi</button>
                    <br /><label>Ora</label><br />
                    <input type="time" class="w3-input" name="hour" value="<?php if (isset($_POST["hour"])) {
                                                                                echo htmlentities($_POST["hour"]);
                                                                            } ?>">
                    <button id="nowTime" class="w3-btn w3-white" type="button">Imposta ad Adesso</button>
                    <br /><label>Classe</label><br />
                    <select class="w3-input" name="classe">
                        <option <?php if (!isset($_POST["classe"]) || $_POST["classe"] == 0) {
                                    echo "selected";
                                } ?> value="0">Tutte</option>
                        <?php
                        foreach ($GLOBALS["classesWhitelist"] as $wle) {
                            if ($wle == "Docenti") {
                        ?>
                                <option value="teacher" <?php if ($_POST["classe"] == "teacher") {
                                                            echo "selected";
                                                        } ?>><?php echo htmlentities($wle); ?></option>
                            <?php
                                continue;
                            }
                            if ($wle == "altro") {
                            ?>
                                <option value="other" <?php if ($_POST["classe"] == "teacher") {
                                                            echo "other";
                                                        } ?>>Altro</option>
                            <?php
                                continue;
                            }
                            ?>
                            <option value="<?php echo htmlentities($wle); ?>" <?php if ($_POST["classe"] == $wle) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo htmlentities($wle); ?></option>
                        <?php

                        }
                        ?>
                    </select>
                    <br /><label>Pagato</label><br />
                    <select class="w3-input" name="payed">
                        <option value="0" <?php if (!isset($_POST["payed"]) || $_POST["payed"] == 0) {
                                                echo "selected";
                                            } ?>>No</option>
                        <option value="1" <?php if ($_POST["payed"] == 1) {
                                                echo "selected";
                                            } ?>>Sì</option>
                        <option value="2" <?php if ($_POST["payed"] == 2) {
                                                echo "selected";
                                            } ?>>Entrambi</option>
                    </select><br />
                    <input class="w3-btn w3-white" type="submit" value="Ricerca">
                </form>
            </center>
        </div>
        <br />
        <div class="w3-container w3-row">
            <?php
            $lst = [];
            if (!empty($_POST["day"])) {
                $lst = wrapperStaffOrders();
                if (count($lst) == 0) echo "<center><b>Nessun risultato</b></center>";
            ?>
                <div id="table"></div>
                <script>
                    var setPayed = function(cell, formatterParams) { //plain text value
                        return '<button class="w3-btn w3-teal"><i class="fa fa-check"></i>Imposta come pagato</button>';
                    };
                    var setPayedRev = function(cell, formatterParams) { //plain text value
                        return '<button class="w3-btn w3-orange"><i class="fa fa-times"></i>Imposta come non pagato</button>';
                    };
                    var deleteR = function(cell, formatterParams) { //plain text value
                        return '<button class="w3-btn w3-red"><i class="fa fa-trash"></i>Elimina</button>';
                    };
                    var nestedData = <?php echo json_encode($lst, JSON_PRETTY_PRINT); ?>;
                    var post = <?php echo json_encode($_POST); ?>;

                    function draw() {
                        var table = new Tabulator("#table", {
                            layout: "fitColumns",
                            resizableColumns: false,
                            data: nestedData,
                            initialSort: [ //set the initial sort order of the data
                                {
                                    column: "datetime",
                                    dir: "asc"
                                },
                            ],
                            columns: [
                                /*{
                                    formatter: setPayed,
                                    width: 40,
                                    align: "center",
                                    minWidth: 80,
                                    cellClick: function(e, cell) {
                                        alert("Printing row data for: " + cell.getRow().getData().name)
                                    }
                                },*/
                                {
                                    title: "Data e Ora",
                                    field: "datetime",
                                    sorter: "date"
                                },
                                {
                                    title: "Classe",
                                    field: "class"
                                },
                                {
                                    title: "Nome",
                                    field: "name"
                                },
                                {
                                    title: "Note",
                                    field: "notes",
                                    formatter: "textarea"
                                },

                                {
                                    title: "Data di creazione",
                                    field: "createdAt"
                                },
                            ],
                            rowFormatter: function(row) {
                                //create and style holder elements
                                var holderEl = document.createElement("div");
                                var tableEl = document.createElement("div");
                                var tableElTwo = document.createElement("div");

                                holderEl.style.boxSizing = "border-box";
                                holderEl.style.padding = "10px 30px 10px 10px";
                                holderEl.style.borderTop = "1px solid #333";
                                holderEl.style.borderBotom = "1px solid #333";
                                holderEl.style.background = "#ddd";

                                tableEl.style.border = "1px solid #333";

                                holderEl.appendChild(tableEl);
                                holderEl.appendChild(tableElTwo);

                                row.getElement().appendChild(holderEl);

                                var subTable = new Tabulator(tableEl, {
                                    layout: "fitColumns",
                                    data: row.getData().dishes,
                                    columns: [{
                                            title: "Nome",
                                            field: "name"
                                        },
                                        {
                                            title: "Quantità",
                                            field: "dishQty",
                                            bottomCalc: "sum"
                                        },
                                        {
                                            title: "Prezzo Unitario",
                                            field: "price",
                                            formatter: "money",
                                            formatterParams: {
                                                decimal: ",",
                                                thousand: "'",
                                                symbol: "€"
                                            }
                                        },
                                        {
                                            title: "Prezzo Totale",
                                            field: "total",
                                            formatter: "money",
                                            formatterParams: {
                                                decimal: ",",
                                                thousand: "'",
                                                symbol: "€",
                                            },
                                            bottomCalc: "sum",
                                            bottomCalcFormatter: "money",
                                            bottomCalcFormatterParams: {
                                                decimal: ",",
                                                thousand: "'",
                                                symbol: "€",
                                            },
                                        },
                                        {
                                            title: "Note",
                                            field: "dishNotes",
                                            formatter: "textarea"
                                        }
                                    ]
                                });
                                var subTableTwo = new Tabulator(tableElTwo, {
                                    layout: "fitColumns",
                                    data: row.getData().summary,
                                    columns: [{
                                            title: "Pagato",
                                            field: "payed",
                                            align: "center",
                                            formatter: "tickCross",
                                            width: 160
                                        },
                                        {
                                            title: "Prezzo Totale",
                                            field: "total",
                                            formatter: "money",
                                            formatterParams: {
                                                decimal: ",",
                                                thousand: "'",
                                                symbol: "€"
                                            }
                                        },
                                        {
                                            formatter: setPayed,
                                            field: "payBtn",
                                            align: "center",
                                            width: 260,
                                            cellClick: function(e, cell) {
                                                var idN = cell.getData().id;
                                                if (confirm("Impostare come  pagato?")) {
                                                    $.post("ajax.php", {
                                                        sessionToken: "<?php echo $_POST["sessionToken"]; ?>",
                                                        id: idN,
                                                        action: "pay",
                                                        classe: post.classe,
                                                        day: post.day,
                                                        hour: post.hour
                                                    }, function(data) {
                                                        if (data.ok) {
                                                            nestedData = data.data;
                                                            alert("Fatto");
                                                            draw();
                                                        } else {
                                                            nestedData = data.data;
                                                            alert("Errore");
                                                            draw();
                                                        }
                                                    }, "json");
                                                }
                                            }
                                        },
                                        {
                                            formatter: setPayedRev,
                                            field: "payBtnRev",
                                            align: "center",
                                            width: 260,

                                            cellClick: function(e, cell) {
                                                var idN = cell.getData().id;
                                                if (confirm("Impostare come NON pagato?")) {
                                                    $.post("ajax.php", {
                                                        sessionToken: "<?php echo $_POST["sessionToken"]; ?>",
                                                        id: idN,
                                                        action: "unpay",
                                                        classe: post.classe,
                                                        day: post.day,
                                                        hour: post.hour
                                                    }, function(data) {
                                                        if (data.ok) {
                                                            nestedData = data.data;
                                                            alert("Fatto");
                                                            draw();
                                                        } else {
                                                            nestedData = data.data;
                                                            alert("Errore");
                                                            draw();
                                                        }
                                                    }, "json");
                                                }
                                            }
                                        },
                                        {
                                            formatter: deleteR,
                                            align: "center",
                                            width: 260,
                                            cellClick: function(e, cell) {
                                                var idN = cell.getData().id;
                                                if (confirm("Eliminare ?")) {
                                                    $.post("ajax.php", {
                                                        sessionToken: "<?php echo $_POST["sessionToken"]; ?>",
                                                        id: idN,
                                                        action: "delete",
                                                        classe: post.classe,
                                                        day: post.day,
                                                        hour: post.hour
                                                    }, function(data) {
                                                        if (data.ok) {
                                                            alert("Fatto");
                                                            nestedData = data.data;
                                                            draw();
                                                        } else {
                                                            alert("Errore");
                                                            nestedData = data.data;
                                                            draw();
                                                        }
                                                    }, "json");
                                                }
                                            }
                                        },
                                        {
                                            field: "id"
                                        }
                                    ]
                                });
                                subTableTwo.hideColumn("id");
                                if (row.getData().summary[0].payed) subTableTwo.hideColumn("payBtn");
                                else subTableTwo.hideColumn("payBtnRev");
                            },
                        });
                    }
                    draw();
                </script>
            <?php
            } else {
                echo "<h2><i>Seleziona il giorno</i></h2>";
            }
            ?>

        </div>
        <script>
            function checkTime(i) {
                if (i < 10) {
                    i = "0" + i;
                }
                return i;
            }
            Date.prototype.toDateInputValue = (function() {
                var local = new Date(this);
                local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
                return local.toJSON().slice(0, 10);
            });
            $("#today").click(function() {
                $('input[name=day]').val(new Date().toDateInputValue());
            });
            $("#nowTime").click(function() {
                var today = new Date();
                var h = today.getHours();
                var m = today.getMinutes();
                var s = today.getSeconds();
                h = checkTime(h);
                m = checkTime(m);
                s = checkTime(s);
                $('input[name=hour]').val(h + ":" + m);
            });
        </script>
        <br />
    <?php
}

function timeframesView($token, $add = "")
{
    $u = use_token($token);
    if (!$u || !$u["isStaff"]) return UIauth("<h2 style=\"color:red\">Sessione invalida</h2>");
    ?>

        <div class="w3-container w3-padding-32 w3-theme-d1">
            <h1>Fasce Orarie</h1>
            <p>MordApp</p>
            <img class=" w3-circle w3-right" src="https://eu.ui-avatars.com/api/?name=<?php echo htmlentities($u["name"]); ?>" style="height:40px;">
            <br />
            <?php echo $add; ?>
            <br /><br />

            <form method="POST" class="w3-left">
                <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                <input type="hidden" name="action" value="mainMenu">
                <input type="submit" class="w3-btn w3-red" value="Indietro">
            </form>
        </div>
        <a href="#" id="add-row" class="w3-btn w3-teal"><i class='fa fa-plus'></i> Aggiungi fascia oraria</a>
        <div id="example-table"></div>
        <br />
        <be />
        <a href="#" id="save" class="w3-btn w3-teal w3-right"><i class='fa fa-save'></i> Salva</a>
        <form id="submit" method="POST">
            <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
            <input type="hidden" name="action" value="saveTimefs">
            <input type="hidden" name="data" id="tfd">
        </form>
        <script>
            function saveCallback(text) {
                $("#tfd").val(text);
                $("#submit").submit();
            }
            $("#save").click(function() {
                table.download("json", "dummy.json");
            });
            var tabledata = <?php
                            $f = file("timeframes.csv");
                            $arr = [];
                            for ($i = 1; $i < count($f); $i++) {
                                $vals = explode(",", $f[$i]);
                                $arr[] = ["id" => $i, "day" => ["Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato", "Domenica"][$vals[0]], "blockTime" => timeFhours($vals[3]), "begTime" => timeFhours($vals[1]), "endTime" => timeFhours($vals[2])];
                            }
                            echo json_encode($arr, JSON_PRETTY_PRINT);
                            ?>;
            var printIcon = function(cell, formatterParams) { //plain text value
                return "<i class='fa fa-trash' style='color:red'></i>";
            };

            var table = new Tabulator("#example-table", {
                data: tabledata, //load row data from array
                layout: "fitColumns", //fit columns to width of table
                responsiveLayout: "hide", //hide columns that dont fit on the table
                tooltips: true, //show tool tips on cells
                addRowPos: "top", //when adding a new row, add it to the top of the table
                history: true, //allow undo and redo actions on the table
                movableRows: true, //allow column order to be changed
                downloadReady: function(fileContents, blob) {
                    //fileContents - the unencoded contents of the file
                    //blob - the blob object for the download

                    //custom action to send blob to server could be included here

                    blob.text().then(function(text) {
                        saveCallback(text);

                    });
                },
                initialSort: [ //set the initial sort order of the data
                    {
                        column: "name",
                        dir: "asc"
                    },
                ],
                columns: [ //define the table columns

                    {
                        rowHandle: true,
                        formatter: "handle",
                        headerSort: false,
                        frozen: true,
                        width: 30,
                        minWidth: 30
                    },
                    {
                        formatter: printIcon,
                        width: 40,
                        hozAlign: "center",
                        cellClick: function(e, cell) {
                            cell.getRow().delete();
                        }
                    },
                    {
                        title: "Giorno",
                        field: "day",
                        editor: "select",
                        editorParams: {

                            values: {
                                "Lunedì": "Lunedì",
                                "Martedì": "Martedì",
                                "Mercoledì": "Mercoledì",
                                "Giovedì": "Giovedì",
                                "Venerdì": "Venerdì",
                                "Sabato": "Sabato",
                                "Domenica": "Domenica"
                            }

                        }
                    },
                    {
                        title: "Ora di blocco",
                        field: "blockTime",
                        hozAlign: "left",
                        formatter: "datetime",
                        formatterParams: {
                            inputFormat: "HH:mm",
                            outputFormat: "HH:mm",
                            invalidPlaceholder: "Ora invalida",
                            timezone: "Europe/Rome",
                        },
                        editor: true
                    },
                    {
                        title: "Ora di inizio",
                        field: "begTime",
                        hozAlign: "left",
                        formatter: "datetime",
                        formatterParams: {
                            inputFormat: "HH:mm",
                            outputFormat: "HH:mm",
                            invalidPlaceholder: "Ora invalida",
                            timezone: "Europe/Rome",
                        },
                        editor: true
                    },
                    {
                        title: "Ora di fine",
                        field: "endTime",
                        hozAlign: "left",
                        formatter: "datetime",
                        formatterParams: {
                            inputFormat: "HH:mm",
                            outputFormat: "HH:mm",
                            invalidPlaceholder: "Ora invalida",
                            timezone: "Europe/Rome",
                        },
                        editor: true
                    }
                ]
            });
            table.setLocale("it");
            //Add row on "Add Row" button click
            $("#add-row").click(function() {
                table.addRow({});
            });
        </script>
    <?php
}
function dishesStaff($token, $add = "")
{

    $u = use_token($token);
    if (!$u || !$u["isStaff"]) return UIauth("<h2 style=\"color:red\">Sessione invalida</h2>");
    ?>

        <div class="w3-container w3-padding-32 w3-theme-d1">
            <h1>Fasce Orarie</h1>
            <p>MordApp</p>
            <img class=" w3-circle w3-right" src="https://eu.ui-avatars.com/api/?name=<?php echo htmlentities($u["name"]); ?>" style="height:40px;">
            <br />
            <?php echo $add; ?>
            <br /><br />

            <form method="POST" class="w3-left">
                <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
                <input type="hidden" name="action" value="mainMenu">
                <input type="submit" class="w3-btn w3-red" value="Indietro">
            </form>
        </div>
        <a href="#" id="add-row" class="w3-btn w3-teal"><i class='fa fa-plus'></i> Aggiungi Piatto</a>
        <div id="example-table"></div>
        <br />
        <be />
        <a href="#" id="save" class="w3-btn w3-teal w3-right"><i class='fa fa-save'></i> Salva</a>
        <form id="submit" method="POST">
            <input type="hidden" name="sessionToken" value="<?php echo htmlentities($token); ?>">
            <input type="hidden" name="action" value="processDishes">
            <input type="hidden" name="data" id="tfd">
        </form>
        <script>
            function saveCallback(text) {
                $("#tfd").val(text);
                $("#submit").submit();
            }
            $("#save").click(function() {
                table.download("json", "dummy.json");
            });
            var tabledata = <?php
                            $p = pdomake();
                            $q = $p->prepare("SELECT * FROM Dishes WHERE deleted=0  ORDER BY name ASC");
                            $q->execute();
                            $arr = [];
                            foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $r) {
                                $arr[] = ["id" => $r["id"], "name" => $r["name"], "price" => $r["price"]];
                            }
                            echo json_encode($arr);
                            ?>;
            var printIcon = function(cell, formatterParams) { //plain text value
                return "<i class='fa fa-trash' style='color:red'></i>";
            };

            var table = new Tabulator("#example-table", {
                data: tabledata, //load row data from array
                layout: "fitColumns", //fit columns to width of table
                responsiveLayout: "hide", //hide columns that dont fit on the table
                tooltips: true, //show tool tips on cells
                addRowPos: "top", //when adding a new row, add it to the top of the table
                history: true, //allow undo and redo actions on the table
                movableRows: true, //allow column order to be changed
                downloadReady: function(fileContents, blob) {
                    //fileContents - the unencoded contents of the file
                    //blob - the blob object for the download

                    //custom action to send blob to server could be included here

                    blob.text().then(function(text) {
                        saveCallback(text);

                    });
                },
                initialSort: [ //set the initial sort order of the data
                    {
                        column: "name",
                        dir: "asc"
                    },
                ],
                columns: [ //define the table columns

                    {
                        formatter: printIcon,
                        width: 40,
                        hozAlign: "center",
                        cellClick: function(e, cell) {
                            cell.getRow().delete();
                        }
                    },
                    {
                        title: "Nome",
                        editor:true,
                        field: "name",
                    }, {
                        title: "Prezzo Totale",
                        editor:true,
                        field: "price",
                        formatter: "money",
                        formatterParams: {
                            decimal: ",",
                            thousand: "'",
                            symbol: "€"
                        }
                    }

                ]
            });
            table.setLocale("it");
            //Add row on "Add Row" button click
            $("#add-row").click(function() {
                table.addRow({});
            });
        </script>
    <?php
}