<?php
//resend updated data
require("lib.php");
$u = use_token($_POST["sessionToken"]);
if (!$u["isStaff"]) die(json_encode(["ok" => false, "data" => []]));
$data = wrapperStaffOrders();
switch ($_POST["action"]) {
    case "delete":
        if (!deleteOrder($_POST["sessionToken"], $_POST["id"]))  die(json_encode(["ok" => false, "data" => $data]));
        $data = wrapperStaffOrders();
        die(json_encode(["ok" => true, "data" => $data]));
        break;
    case "pay":
        setPayed($_POST["sessionToken"], $_POST["id"], true);
        $data = wrapperStaffOrders();
        die(json_encode(["ok" => true, "data" => $data]));
        break;
    case "unpay":
        setPayed($_POST["sessionToken"], $_POST["id"], false);
        $data = wrapperStaffOrders();
        die(json_encode(["ok" => true, "data" => $data]));
        break;
}
