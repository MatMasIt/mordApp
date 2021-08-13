<?php
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle)
    {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

function pdomake()
{
    return new PDO("sqlite:07abd9b090f514cbce89b2a932b2ec9f/f.sqlite3");
}
$GLOBALS["classesWhitelist"] = [
    "1A",
    "1B",
    "1C",
    "1D",
    "1E",
    "1F",
    "1G",
    "1H",
    "1I",
    "1J",
    "1K",
    "1L",
    "1M",
    "1N",
    "1O",
    "1P",
    "1Q",
    "1R",
    "1S",
    "1T",
    "1U",
    "1V",
    "1W",
    "1X",
    "1Y",
    "1Z",
    "2A",
    "2B",
    "2C",
    "2D",
    "2E",
    "2F",
    "2G",
    "2H",
    "2I",
    "2J",
    "2K",
    "2L",
    "2M",
    "2N",
    "2O",
    "2P",
    "2Q",
    "2R",
    "2S",
    "2T",
    "2U",
    "2V",
    "2W",
    "2X",
    "2Y",
    "2Z",
    "3A",
    "3B",
    "3C",
    "3D",
    "3E",
    "3F",
    "3G",
    "3H",
    "3I",
    "3J",
    "3K",
    "3L",
    "3M",
    "3N",
    "3O",
    "3P",
    "3Q",
    "3R",
    "3S",
    "3T",
    "3U",
    "3V",
    "3W",
    "3X",
    "3Y",
    "3Z",
    "4A",
    "4B",
    "4C",
    "4D",
    "4E",
    "4F",
    "4G",
    "4H",
    "4I",
    "4J",
    "4K",
    "4L",
    "4M",
    "4N",
    "4O",
    "4P",
    "4Q",
    "4R",
    "4S",
    "4T",
    "4U",
    "4V",
    "4W",
    "4X",
    "4Y",
    "4Z",
    "Docente",
    "Altro"
];
function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    return $length > 0 ? substr($haystack, -$length) === $needle : true;
}
function new_account($email, $name, $password, $classe)
{
    $email = trim(mb_strtolower($email));
    if (!in_array($classe, $GLOBALS["classesWhitelist"])) return "NO_CLASS";
    $p = pdomake();
    $q = $p->prepare("SELECT * FROM Users WHERE email=:email");
    $q->execute([":email" => $email]);
    if ($q->fetch() != null) return "ALREADY_EXISTS";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return "INVALID_EMAIL";
    if (!endsWith($email, "@liceococito.it")) return "WRONG_DOMAIN";
    $st = bin2hex(random_bytes(16));
    $q = $p->prepare("INSERT INTO Users(name,email,passwordHash,classe,verified,isStaff,createdAt,emailToken) VALUES (:name,:email,:passwordHash,:classe,:verified,:isStaff,:createdAt,:emailToken)");
    $q->execute([
        ":name" => $name,
        ":email" => $email,
        ":passwordHash" => password_hash($password, PASSWORD_DEFAULT),
        ":classe" => $classe,
        ":verified" => 0,
        ":createdAt" => time(),
        ":isStaff" => 0,
        ":emailToken" => $st
    ]);
    return $st;
}
function login($email, $password)
{
    $email=mb_strtolower(trim($email));
    $p = pdomake();
    $q = $p->prepare("SELECT * FROM Users WHERE email=:email");
    $q->execute([":email" => $email]);
    $u = $q->fetch();
    if ($u == null) return "NOT_FOUND";
    if (!password_verify($password, $u["passwordHash"])) return "INCORRECT_PASSWORD";
    if (!$u["verified"]) return "UNVERIFIED";
    $q = $p->prepare("UPDATE Users SET token=:token, lastLogin=:lastLogin WHERE id=:id");
    $st = bin2hex(random_bytes(16));
    $q->execute([
        ":id" => $u["id"],
        ":token" => $st,
        ":lastLogin" => time()
    ]);
    return $st;
}

function firstVerify($token)
{
    $p = pdomake();
    $q = $p->prepare("SELECT * FROM Users WHERE emailToken=:et");
    $q->execute([":et" => $token]);
    $u = $q->fetch();
    if (!$u) return false;
    $q = $p->prepare("UPDATE Users SET emailToken=:etN, verified=:verified WHERE emailToken=:et");
    $q->execute([":et" => $token, ":etN" => bin2hex(random_bytes(16)), ":verified" => time()]);
    return true;
}


function setPayed($token, $orderId, $payedHow = true)
{
    $p = pdomake();
    $u = use_token($token);
    if ($u == null || !$u["isStaff"]) return false;
    $q = $p->prepare("UPDATE Orders SET payed=:payed WHERE id=:id");
    $q->execute([
        ":id" => $orderId,
        ":payed" => (int)$payedHow
    ]);
}
function use_token($token)
{
    $p = pdomake();
    $q = $p->prepare("SELECT * FROM Users WHERE token=:token");
    $q->execute([":token" => $token]);
    $u = $q->fetch();
    return $u;
}

function userById($id)
{
    $p = pdomake();
    $q = $p->prepare("SELECT * FROM Users WHERE id=:id");
    $q->execute([":id" => $id]);
    $u = $q->fetch();
    return $u;
}
function invalidate_token($token)
{
    $p = pdomake();
    $q = $p->prepare("UPDATE Users SET token=:token WHERE token=:oldToken");
    $st = bin2hex(random_bytes(16));
    $q->execute([
        ":token" => $st,
        ":oldToken" => $token
    ]);
}
function listMyOrders($token)
{ // only for users, there will be a function for staff
    $p = pdomake();
    $u = use_token($token);
    if ($u == null) return false;
    $q = $p->prepare("SELECT * FROM Orders  WHERE userId=:id AND payed=0");
    $q->execute([
        ":id" => $u["id"]
    ]);
    $list = $q->fetchAll(PDO::FETCH_ASSOC);
    $final = [];
    foreach ($list as $e) {
        $e["dishes"] = [];
        $q = $p->prepare("SELECT * FROM OrderDishes  WHERE orderId=:id");
        $q->execute([
            ":id" => $e["id"]
        ]);
        $dList = $q->fetchAll(PDO::FETCH_ASSOC);
        foreach ($dList as $d) {
            $qI = $p->prepare("SELECT * FROM Dishes WHERE id=:id");
            $qI->execute([
                ":id" => $d["dishId"]
            ]);
            $dI = $qI->fetch(PDO::FETCH_ASSOC);
            if (!$dI) continue;
            unset($d["dishId"]);
            $e["dishes"][] = array_merge($d, $dI);
        }

        $final[] = $e;
    }
    return $final;
}


function listStaffOrders($token, $data)
{ // only for users, there will be a function for staff
    $p = pdomake();
    $u = use_token($token);
    if ($u == null || !$u["isStaff"]) return false;
    if (!empty($data["datetime"])) $addition = " AND datetime > " . ((int)$data["datetime"]) . " AND datetime < " . strtotime($data["day"] . " 23:59") . " ";
    if (isset($data["payed"]) && $data["payed"] != 2) {
        $addition .= " AND payed=" . ((int)$data["payed"]) . " ";
    }
    $sql = "SELECT * FROM Orders  WHERE 1=1 " . $addition;
    $q = $p->prepare($sql);
    $q->execute();
    $list = $q->fetchAll(PDO::FETCH_ASSOC);
    $final = [];
    foreach ($list as $e) {
        $e["dishes"] = [];
        $addition = "";
        $q = $p->prepare("SELECT * FROM OrderDishes  WHERE orderId=:id ");
        $q->execute([
            ":id" => $e["id"]
        ]);
        $dList = $q->fetchAll(PDO::FETCH_ASSOC);
        foreach ($dList as $d) {
            $qI = $p->prepare("SELECT * FROM Dishes WHERE id=:id");
            $qI->execute([
                ":id" => $d["dishId"]
            ]);
            $dI = $qI->fetch(PDO::FETCH_ASSOC);
            if (!$dI) continue;
            unset($d["dishId"]);
            $e["dishes"][] = array_merge($d, $dI);
        }

        $final[] = $e;
    }
    return $final;
}

function getOrder($token, $Oid)
{
    $p = pdomake();
    $u = use_token($token);
    if ($u == null) return false;
    $q = $p->prepare("SELECT * FROM Orders WHERE id=:Oid ");
    $q->execute([
        ":Oid" => $Oid
    ]);
    $list = $q->fetchAll(PDO::FETCH_ASSOC);
    $final = [];
    foreach ($list as $e) {
        $e["dishes"] = [];
        $q = $p->prepare("SELECT * FROM OrderDishes  WHERE orderId=:id");
        $q->execute([
            ":id" => $e["id"]
        ]);
        $dList = $q->fetchAll(PDO::FETCH_ASSOC);
        foreach ($dList as $d) {
            $qI = $p->prepare("SELECT * FROM Dishes WHERE id=:id");
            $qI->execute([
                ":id" => $d["dishId"]
            ]);
            $dI = $qI->fetch(PDO::FETCH_ASSOC);
            if (!$dI) continue;
            unset($d["dishId"]);
            $e["dishes"][] = array_merge($d, $dI);
        }

        $final[] = $e;
    }
    $r = $final[0];
    if (!$u["isStaff"] && $r["payed"]) return false;
    return $r;
}
function listDishes($orderId)
{
    $p = pdomake();
    $q = $p->prepare("SELECT * FROM OrderDishes WHERE orderId=:Oid");
    $st = bin2hex(random_bytes(16));
    $q->execute([
        ":Oid" => $orderId
    ]);
    return $q->fetchAll();
}

function email($title, $body, $email, $name)
{

    // Subject

    $message = file_get_contents("email.template");
    $message = str_replace("TITLE", $title, $message);
    $message = str_replace("CONTENT", $body, $message);
    $message = str_replace("DATE", date("d/m/Y"), $message);

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=UTF-8';

    // Additional headers
    $headers[] = 'To: ' . $name . ' <' . $email . '>';
    $headers[] = 'From: Mordapp <mordapp@altervista.org>';

    //file_put_contents("email/" . time() . ".html", $message);
    // Mail it
    @mail($email, $title, $message, implode("\r\n", $headers));
}

function timeFhours($d)
{
    $d *= 60;
    $hour = floor($d / 3600);
    $min = floor(($d / 60) % 60);
    $hour = str_pad($hour, 2, "0", STR_PAD_LEFT);
    $min = str_pad($min, 2, "0", STR_PAD_LEFT);
    return "$hour:$min";
}
function computeTimeframes()
{
    $f = file("timeframes.csv");
    $days = ["Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venrdì", "Sabato", "Domenica"];
    $months = ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"];
    $daysEng = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
    $whatDays = [];
    $perDay = [];
    $all = [];
    $i = 0;
    foreach ($f as $l) {
        if ($i == 0) {
            $i++;
            continue;
        }
        $ld = explode(",", $l);
        $whatDays[] = $daysEng[$ld[0]];
        $perDay[$daysEng[$ld[0]]][] = ["begin" => ["value" => (int)$ld[1], "label" => timeFhours((int)$ld[1])], "end" => ["value" => (int)$ld[2], "label" => timeFhours((int)$ld[2])], "block" => ["value" => (int)$ld[3], "label" => timeFhours((int)$ld[3])]];
        $i++;
    }
    $startDate = strtotime("today");
    $endDate = strtotime((date("Y") + 1) . "-01-01");
    for ($i = $startDate; $i <= $endDate; $i = strtotime('+1 day', $i)) {
        if (in_array(strtolower(date("l", $i)), $whatDays) && count($perDay[strtolower(date("l", $i))])) {
            if (date("d-m-Y") == date("d-m-Y", $i)) {
                $min = -100000;
                //is there at least one TimeFrame for today left?
                foreach ($perDay[strtolower(date("l", $i))] as $ed) {
                    $q = min($ed["begin"]["value"], $ed["end"]["value"]);
                    $min = max($min, $q);
                }
                $now = date("H") * 60 + date("m");
                if ($min > $now) continue;
            }
            $all[] = ["value" => $i, "label" => $days[date("N", $i) - 1] . " " . date("d", $i) . " " . $months[date("m", $i) - 1] . " " . date("Y", $i)];
        }
    }
    return ["all" => $all, "times" => $perDay];
}

function validateDatetimeBegin($dt)
{
    $all = computeTimeframes();
    $begin = strtotime("midnight", $dt);
    $found = false;
    foreach ($all["all"] as $a) {
        if ($a["value"] == $begin) {
            $found = true;
            break;
        }
    }
    if (!$found) return "NO_DAY";

    $found = false;
    foreach ($all["times"][strtolower(date("l", $dt))] as $tf) {
        if ($tf["begin"]["value"] * 60 == ($dt - $begin)) {
            $found = true;
            break;
        }
    }

    if (!$found) return "NO_TF";
    return "OK";
}
function processOrder($data)
{
    $all = computeTimeframes();
    $data["datetime"] = $data["day"] + $all["times"][strtolower(date("l", $data["day"]))][(int)$data["timeFrames"]]["begin"]["value"] * 60;
    $p = pdomake();
    $u = use_token($data["sessionToken"]);
    if ($u == null) return false;
    $vr = validateDatetimeBegin($data["datetime"]);
    if ($vr != "OK") return "INVALID_DATETIME_" . $vr;
    $dishes = [];
    $dishsesKeys = [];
    foreach ($data as $key => $val) {
        $n = explode("OF", $key)[1];
        if (!str_contains($key, "OF") || $data["qtyOF" . $n] < 1) continue;
        $dishsesKeys[] = $n;
    }
    $dishsesKeys = array_unique($dishsesKeys);
    foreach ($dishsesKeys as $d) {
        $q = $p->prepare("SELECT * FROM Dishes  WHERE id=:id");
        $q->execute([
            ":id" => $d
        ]);
        if (!count($q->fetchAll(PDO::FETCH_ASSOC))) return "ID_MISMATCH";
        $dishes[] = ["id" => $d, "qty" => $data["qtyOF" . $d] ?: 0, "notes" => $data["notesOF" . $d] ?: ""];
    }
    if (count($dishes) == 0) {
        deleteOrder($data["sessionToken"], $data["orderId"]);
        return true;
    }
    if ($data["orderId"] == "NEW") {
        $q = $p->prepare("INSERT INTO Orders(datetime,userId,notes,payed,createdAt) VALUES (:datetime,:userId,:notes,:payed,:createdAt)");
        $q->execute([
            ":datetime" => $data["datetime"],
            ":userId" => $u["id"],
            ":notes" => $data["orderNotes"],
            ":payed" => 0,
            ":createdAt" => time()
        ]);
        $lId = $p->lastInsertId();
        if (!$lId) return "ERROR_INSERT";
        foreach ($dishes as $d) {
            $q = $p->prepare("INSERT INTO OrderDishes(orderId,dishId,dishQty,dishNotes) VALUES (:orderId,:dishId,:dishQty,:dishNotes)");
            $q->execute([
                ":orderId" => $lId,
                ":dishId" => $d["id"],
                ":dishQty" => $d["qty"],
                ":dishNotes" => $d["notes"]
            ]);
        }
    } else {
        $o = getOrder($data["sessionToken"], $data["orderId"]);
        if (!$o) return "NO_ID";
        $q = $p->prepare("UPDATE Orders SET datetime=:datetime, userId=:userId, notes=:notes, payed=:payed, createdAt=:createdAt WHERE id=:Oid");
        $q->execute([
            ":datetime" => $data["datetime"],
            ":userId" => $u["id"],
            ":notes" => $data["orderNotes"],
            ":payed" => 0,
            ":createdAt" => time(),
            ":Oid" => $data["orderId"]
        ]);
        $q = $p->prepare("DELETE FROM OrderDishes WHERE orderId=:Oid");
        $q->execute([
            ":Oid" => $data["orderId"]
        ]);
        foreach ($dishes as $d) {
            $q = $p->prepare("INSERT INTO OrderDishes(orderId,dishId,dishQty,dishNotes) VALUES (:orderId,:dishId,:dishQty,:dishNotes)");
            $q->execute([
                ":orderId" => $data["orderId"],
                ":dishId" => $d["id"],
                ":dishQty" => $d["qty"],
                ":dishNotes" => $d["notes"]
            ]);
        }
    }
    return true;
}
function deleteOrder($token, $id)
{
    $p = pdomake();
    $u = use_token($token);
    if ($u == null) return false;
    $o = getOrder($token, $id);
    if ($o["userId"] != $u["id"] && !$u["isStaff"]) return false;
    $q = $p->prepare("DELETE FROM OrderDishes WHERE orderId=:Oid");
    $q->execute([
        ":Oid" => $id
    ]);
    $q = $p->prepare("DELETE FROM Orders WHERE id=:Oid");
    $q->execute([
        ":Oid" => $id
    ]);
    return true;
}
function manageSave($token, $data)
{
    $p = pdomake();
    $u = use_token($token);
    if ($u == null) return false;
    if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) return "INVALID_EMAIL";
    if (!endsWith($data["email"], "@liceococito.it") && endsWith($u["email"], "@liceococito.it")) return "WRONG_DOMAIN";
    if (!in_array($data["classe"], $GLOBALS["classesWhitelist"])) return "NO_CLASS";
    $q = $p->prepare("UPDATE Users SET name=:name, email=:email, classe=:classe WHERE id=:id");
    $q->execute([
        ":id" => $u["id"],
        ":name" => $data["name"] ?: $u["name"],
        ":email" => $data["email"] ?: $u["email"],
        ":classe" => $data["classe"] ?: $u["classe"]

    ]);
    return true;
}
function toNormalized($hhmm)
{
    $ar = explode(":", $hhmm);
    return $ar[0] * 60 + $ar[1];
}
function saveTimefs($st, $data)
{
    $u = use_token($st);
    if ($u == null || !$u["isStaff"]) return false;
    $a = json_decode($data, true);
    if (!$a) return false;
    $s = "Giorno,Inizio,Fine,Blocco\n";
    foreach ($a as $el) {
        $s .= (array_search($el["day"], ["Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato", "Domenica"]) ?: 0) . "," . toNormalized($el["begTime"]) . "," . toNormalized($el["endTime"]) . "," . toNormalized($el["blockTime"]) . "\n";
    }
    file_put_contents("timeframes.csv", $s);
    return true;
}

function wrapperStaffOrders()
{
    $lst = [];
    $_POST["datetime"] = strtotime($_POST["day"] . " " . $_POST["hour"]);
    $list = listStaffOrders($_POST["sessionToken"], $_POST);
    foreach ($list as $el) {
        $u = userById($el["userId"]);
        if ($u["classe"] != $_POST["classe"] && $_POST["classe"] != 0) continue;

        $dishes = [];
        $total = 0;
        foreach ($el["dishes"] as $d) {
            $d["total"] = $d["dishQty"] * $d["price"];
            if($d["deleted"]) $d["name"].=" (cancellato dal menù il ".date("d/m/Y H:i:s",$d["deleted"]).")";
            $dishes[] = $d;
            $total += $d["total"];
        }


        $lst[] = [
            "name" => htmlentities($u["name"]),
            "class" => htmlentities($u["classe"]),
            "datetime" => date("d/m/Y H:i:s", $el["datetime"]),
            "createdAt" => date("d/m/Y H:i:s", $el["createdAt"]),
            "dishes" => $dishes,
            "notes" => htmlentities($el["notes"]),
            "summary" => [[
                "payed" => (bool)$el["payed"],
                "total" => $total,
                "id" => $el["id"]
            ]]
        ];
    }
    return $lst;
}
function processDishes($data)
{
    $u = use_token($data["sessionToken"]);
    if (!$u || !$u["isStaff"]) return UIauth("<h2 style=\"color:red\">Sessione invalida</h2>");
    $a = json_decode($data["data"], true);
    if (!$a) return false;
    $p = pdomake();
    $q = $p->prepare("SELECT * FROM Dishes WHERE deleted=0");
    $q->execute();
    $r = $q->fetchAll(PDO::FETCH_ASSOC);
    $ids = [];
    foreach ($a as $el) {
        if (empty($el["id"])) {
            $q = $p->prepare("INSERT INTO Dishes(name,price,deleted) VALUES(:name,:price,0)");
            $q->execute([":name" => $el["name"], ":price" => $el["price"]]);
        } else {
            $q = $p->prepare("UPDATE Dishes SET name=:name, price=:price, deleted=0 WHERE id=:id");
            $q->execute([":name" => $el["name"], ":price" => $el["price"], ":id"=>$el["id"]]);
            $ids[]=(int)$el["id"];
        }
    }
    foreach($r as $el){
        if(!in_array((int)$el["id"],$ids)){
            $q = $p->prepare("UPDATE Dishes SET deleted=:d WHERE id=:id");
            $q->execute([":id"=>$el["id"],":d"=>time()]);
        }
    }
    return true;
}

function newViewCheck($token,$view)
{
    if(empty($token) || empty($view)) return "EMPTY";
    $p = pdomake();
    $q = $p->prepare("SELECT * FROM Users WHERE token=:token");
    $q->execute([
        ":token" => $token
    ]);
    $r = $q->fetchAll(PDO::FETCH_ASSOC)[0];
    if(!$r) return true;
    if($view == $r["lastView"]) return "SAME";
    $q = $p->prepare("UPDATE Users SET lastView=:lastView WHERE token=:token");
    $q->execute([
        ":token" => $token,
        ":lastView" => $view
    ]);
    return true;
}

function deleteMe($token)
{
    $u = use_token($token);
    $p = pdomake();
    if ($u == null || $u["isStaff"]) return false;
    $q = $p->prepare("DELETE FROM Users WHERE token=:token");
    $q->execute([
        ":token" => $token
    ]);
}
