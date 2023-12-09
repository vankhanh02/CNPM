<?php
$conn = new mysqli("localhost", "root", "", "db");
$has_type=0;
$has_style=0;
$has_num=0;
$has_pos=0;
$has_date=0;
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
if (!empty($_POST))
{
    $dropquery = "DELETE FROM printorder WHERE orderid = '".$_POST['orderid']."'";
    $conn->query($dropquery);
}
$searchquery = "SELECT * from printorder left join printdetails on printdetails.orderid = printorder.orderid left join printer on printorder.orderedprinter = printer.printerid WHERE studentid = '1234567'";
print_r($_GET);
if (!empty($_GET))
{
    if (array_key_exists("files-type",$_GET))
    {
        if ($_GET["files-type"] == "word") $searchquery .= " AND documenttype = 'Word'";
        if ($_GET["files-type"] == "pdf") $searchquery .= " AND documenttype = 'PDF'";
        if ($_GET["files-type"] == "jpg") $searchquery .= " AND documenttype = 'JPG";
    }
    if (array_key_exists("prints-style",$_GET))
    {
        if ($_GET["prints-style"] == "black-white") $searchquery .= " AND colorused = 'Black'";
        if ($_GET["prints-style"] == "color") $searchquery .= " AND colorused = 'Color'";
    }
    if (array_key_exists("number-prints",$_GET))
    {
        if ($_GET["number-prints"] == "1") $searchquery .= " AND pagesperpaper = 1";
        if ($_GET["number-prints"] == "2") $searchquery .= " AND pagesperpaper = 2";
    }
    if (array_key_exists("position",$_GET))
    {
        if ($_GET["position"] == "H1") $searchquery .= " AND buildingid = 'H1'";
        if ($_GET["position"] == "H2") $searchquery .= " AND buildingid = 'H2'";
        if ($_GET["position"] == "H3") $searchquery .= " AND buildingid = 'H3'";
    }
    if (array_key_exists("orderdate",$_GET) && !empty($_GET["orderdate"]))
    {
        $searchquery .= " AND CAST(orderdate as DATE) = '".$_GET['orderdate']."'";
    }
}
$res = $conn->query($searchquery);
print_r($res->fetch_assoc());
?>