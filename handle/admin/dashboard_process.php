<?php
    header('Content-Type: application/json');
    $act = isset($_GET["act"]) ? $_GET["act"] : "home";

    $response =[];
    switch ($act) {
        case "users":
            $response['html']= '<?php require_once __DIR__."/../../admin/dashboard/dashboard_'.$act.'.php";?>';
            break;
        case "vouchers":
            $response["html"]= '<?php require_once __DIR__."/../../admin/dashboard/dashboard_'.$act.'.php";?>';
            break;
        case "bills":
            $response["html"]= '<?php require_once __DIR__."/../../admin/dashboard/dashboard_'.$act.'.php";?>';
            break;
        case "paymentmethods":
            $response["html"]= '<?php require_once __DIR__."/../../admin/dashboard/dashboard_'.$act.'.php";?>';
            break;
        case  "products":
            $response["html"] = '<?php require_once __DIR__."/../../admin/dashboard/dashboard_'.$act.'.php";?>';
            break;
        case "typeproducts":
            $response["html"] = '<?php require_once __DIR__."/../../admin/dashboard/dashboard_'.$act.'.php";?>';
            break;     
        default:
        $response["html"] = '<?php require_once __DIR__."/../../admin/dashboard/dashboard_'.$act.'.php";?>';
                      
    }