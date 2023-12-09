<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
$conn = new mysqli("localhost", "root", "", "db");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
#Nhắc tất cả định dạng ngày tháng năm thống nhất dùng 'd-m-Y: H:i:s' nhé
class inventory{#Hoàn thành
    private $studentID='';
    private $numPaperTypes=0;
    private $details = array();

    private function updateDatabase(string $studentID, string $paperName, int $quantity) {
        global $conn;
        $sql = "UPDATE inventorydetails SET quantity = ? WHERE studentid = ? AND papername = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iss", $quantity, $studentID, $paperName);
            $stmt->execute();
            $stmt->close();
        } else {
            die("Lỗi truy vấn: " . $conn->error);
        }
    }

    private function insertDatabase(string $studentID, string $paperName, int $quantity) {
        global $conn;
        $sql = "INSERT INTO inventorydetails (studentid, papername, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssi", $studentID, $paperName, $quantity);
            $stmt->execute();
            $stmt->close();
        } else {
            die("Lỗi truy vấn: " . $conn->error);
        }
    }

    private function databaseAction(string $paperName, int $quantity) {
        global $conn;
        //echo "action: $paperName, $quantity<br>";
        $sql = "SELECT * FROM inventorydetails WHERE studentid = ? AND papername = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ss", $this->studentID, $paperName);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $this->updateDatabase($this->studentID, $paperName, $quantity);
            } else {
                $this->insertDatabase($this->studentID, $paperName, $quantity);
            }
            $stmt->close();
        } else {
            die("Lỗi truy vấn: " . $conn->error);
        }
    }

    public function __construct(string $studentID){
        $this->studentID=$studentID;
        global $conn;
        global $default_paper_can_be_used;
        $sql = "SELECT * FROM inventorydetails WHERE studentid = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $this->studentID);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $paperName = $row['papername'];
                    $quantity = $row['quantity'];
                    $paper = null;
                    switch ($paperName) {
                        case 'A5':
                            $paper = $default_paper_can_be_used[0];
                            break;
                        case 'A4':
                            $paper = $default_paper_can_be_used[1];
                            break;
                        case 'A3':
                            $paper = $default_paper_can_be_used[2];
                            break;
                        case 'A2':
                            $paper = $default_paper_can_be_used[3];
                            break;
                        case 'A1':
                            $paper = $default_paper_can_be_used[4];
                            break;
                        case 'A0':
                            $paper = $default_paper_can_be_used[5];
                            break;
                    }
                    $detail = new inventory_details($paper, $quantity);
                    $this->numPaperTypes += 1;
                    array_push($this->details, $detail);
                }
            }
            $stmt->close();
        } else {
            die("Lỗi truy vấn: " . $conn->error);
        }

    }
    public function getID() {return $this->studentID;}
    public function getNumPaperTypes() {return $this->numPaperTypes;}
    public function getdetails() {return $this->details;}
    public function show(){
        #Cái này ai làm show inventory thì làm thêm hàm này nha
        if ($this->getNumPaperTypes() == 0) echo "Hiện tại bạn không còn giấy in nào để sử dụng";
        else
        {   echo "Bạn đang sở hữu $this->numPaperTypes loại giấy. Chi tiết các loại giấy là:<br>";
            for ($_=0;$_<$this->getNumPaperTypes();$_++)
            {
                $this->details[$_]->show();
            }
        }
    }
    #Thêm trừ đều được, parameter là loại giấy và quantity, chưa kiểm nếu giấy có về <0 không.
    public function addPaper(paper $paperType,int $quantity){
        for ($_=0;$_<$this->numPaperTypes;$_++){
            $ppType = $this->details[$_]->getPaperType();
            if($ppType->getName() === $paperType->getName()) {
                $tempQuantity = $this->details[$_]->getQuantity() + $quantity;
                $this->details[$_]->setQuantity($tempQuantity);
                $this->databaseAction($paperType->getName(), $tempQuantity);
                return;
            }
        }
        $this->numPaperTypes +=1 ;
        $tmpDetail = new inventory_details($paperType,$quantity);
        array_push($this->details,$tmpDetail);
        $this->databaseAction($paperType->getName(), $quantity);
    }
}
class inventory_details{#Hoàn thành
    private $paperType = null;
    private $quantity = 0;
    public function __construct(paper $paperType,int $quantity){
        $this->paperType=$paperType;
        $this->quantity=$quantity;
    }
    public function getPaperType() {return $this->paperType;}
    public function getQuantity() {return $this->quantity;}
    public function setQuantity(int $newQuantity) {
        $this->quantity = $newQuantity;
    }
    public function show(){
        #Ai làm show detail làm hàm này nha
        if (!empty($this->paperType)) {
            echo " - Paper Type: {$this->paperType->getName()}, Quantity: {$this->quantity}<br>";
        }
        
    }

}
class paper{#Hoàn thành
    private $name = '';
    private $xlength = 0.0;
    private $ylength = 0.0;
    private $thickness = 0.0;
    private $color = '';
    private $price = 0.0;
    public function __construct(string $name, float $xlength, float $ylength, float $thickness, string $color, float $price)
    {
        $this->name = $name;
        $this->xlength = $xlength;
        $this->ylength = $ylength;
        $this->thickness = $thickness;
        $this->color = $color;
        $this->price = $price;
    }
    public function getName() {return $this->name;}
    public function getinfo() {return array($this->xlength,$this->ylength,$this->thickness,$this->color);}
    public function getPrice() {return $this->price;}
    public function showstats()
    {
        #Ai làm thì đập đi xây lại nha
        echo $this->name;
        echo $this->xlength;
        echo $this->ylength;
        echo $this->thickness;
        echo $this->color;
        echo $this->price;
    }    
}
class building{#Tiền thân là printer_list ở trong class diagram
    private $id = '';
    private $numPrinters = 0;
    private $printerArray = array();
    public function __construct($id,$numPrinters,$printerArray)
    {
        $this->id = $id;
        $this->numPrinters = $numPrinters;
        $this->printerArray = $printerArray;
    }
    public function getID() {return $this->id;}
    public function getNumPrinter() {return $this->numPrinters;}
    public function getprinterArray() {return $this->printerArray;}
    public function show(){
        #Ai làm thì bổ sung
    }
    #Thêm printer vào building
    public function addPrinter(Printer $newPrinter){
        $this->numPrinters+=1;
        array_push($this->printerArray,$newPrinter);
    }
}
class Printer{
    private $id = '';
    private $name = '';
    private $type = '';
    private $printColor = array();
    private $paperCanBeUsed =  array();
    private $status="Available";
    public function getID() {return $this->id;}
    public function getName() {return $this->name;}
    public function getType() {return $this->type;} 
    public function getPrintableColors() {return $this->printColor;}
    public function getPaperCanBeUsed() {return $this->paperCanBeUsed;}
    public function getStatus() {return $this->status;}
    public function __construct(string $id, string $name, string $type, array $printColor, array  $paperCanBeUsed){
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->printColor = $printColor;
        $this->paperCanBeUsed = $paperCanBeUsed;
    }
}
class Color{#Thành phần màu trong máy in cụ thể
    private $name = '';
    private $quantity = 0.0;
    private $Availablity = false;
    public function __construct(string $name,float $quantity){
        $this->name = $name;
        $this->quantity = $quantity;
        $this->Availablity = ($this->quantity == 0)? false:true ;
    }
    public function getName() {return $this->name;}
    public function getQuantity() {return $this->quantity;}
    public function getAvailablity() {return $this->Availablity;}
    #Add là tiếp màu vào máy in, cũng có thể lấy hàm này để trừ màu khi xong yêu cầu đặt in
    public function add(float $quantity)
    {
        if ($this->quantity + $quantity < 0) return false;
        $this->quantity += $quantity;
        $this->Availablity = ($this->quantity == 0)? false:true;
    }
}
class Order{
    private $id = '';
    private $studentID = '';
    private $orderedPrinter = null;
    private $numDetails = 0;
    private $details = array();
    private $overallPrice = 0.0;
    private $payed = false;
    private $orderDate = '';
    private $payedDate = '';

    private function insertDatabase() {
        global $conn;
        $sql = "INSERT INTO printorder (orderid, studentid, orderedprinter, 
        overallprice, payed, orderdate, payeddate) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $orderedPrinterID = $this->orderedPrinter->getID();
            $stmt->bind_param("sssdiss", $this->id, $this->studentID, $orderedPrinterID, 
            $this->overallPrice, $this->payed, $this->orderDate, $this->payedDate);
            $stmt->execute();
            $stmt->close();
        } else {
            die("Lỗi truy vấn: " . $conn->error);
        }
    }

    public function __construct($id,$studentID,$orderedPrinter,$orderDate)
    {
        $this->id = $id;
        $this->studentID = $studentID;
        $this->orderedPrinter = $orderedPrinter;
        $this->orderDate = $orderDate;
    }
    public function getID() {return $this->id;}
    public function getStudentID() {return $this->studentID;}
    public function getOrderedPrinter() {return $this->orderedPrinter;}
    public function getOverAllPrice() {return $this->overallPrice;}
    public function getPayed() {return $this->payed;}
    public function getOrderDate() {return $this->orderDate;}
    public function getPayedDate() {return $this->payedDate;}
    public function addDetails(string $id, paper $usedPaper,int $quantity,string $documentType, string $colorUsed, int $pagesPerPaper){
        $detail = new OrderDetails($id,$usedPaper,$quantity,$documentType,$colorUsed,$pagesPerPaper);
        $this->numDetails += 1;
        array_push($this->details,$detail);
        $this->overallPrice += $detail->getPrice();
    }
    public function pay(){
        $this->payed = true;
        $this->payedDate = date('d-m-Y: H:i:s');
    }
    public function show() {
        echo "Order ID: {$this->id}<br>";
        echo "Student ID: {$this->studentID}<br>";
        echo "Ordered Printer: {$this->orderedPrinter->getID()}<br>";
        echo "Order Date: {$this->orderDate}<br>";
        echo "Payed: {$this->payed}<br>";
        echo "Payed Date: {$this->payedDate}<br>";
        echo "Overall Price: {$this->overallPrice}<br>";
        echo "Number of Details: {$this->numDetails}<br>";
        for ($_=0;$_<$this->numDetails;$_++)
        {
            $this->details[$_]->show();
        }

    }

    public function databaseAction() {
        global $conn;
        $conn->begin_transaction();
        try {
            $this->insertDatabase();
            for ($_=0;$_<$this->numDetails;$_++) {
                $this->details[$_]->insertDatabase($this->id);
            }
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            echo $e->getMessage();
        }
    }

}
class OrderDetails{
    private $id = '';
    private $usedPaper = null;
    private $quantity = 0;
    private $documentType = '';
    private $colorUsed = '';
    private $price = 0.0;
    private $pagesPerPaper = 1;
    public function getID() {return $this->id;}
    public function getUsedPaper() {return $this->usedPaper;}
    public function getQuantity() {return $this->quantity;}
    public function getDocumentType() {return $this->documentType;}
    public function getColorUsed() {return $this->colorUsed;}
    public function getPrice() {return $this->price;}
    public function getPagesPerPaper() {return $this->pagesPerPaper;}
    public function __construct(string $id, paper $usedPaper,int $quantity,string $documentType, string $colorUsed, int $pagesPerPaper)
    {
        $this->id = $id;
        $this->usedPaper = $usedPaper;
        $this->quantity = $quantity;
        $this->documentType = $documentType;
        $this->price= $usedPaper->getPrice()*$this->quantity;
        $this->colorUsed = $colorUsed;
        $this->pagesPerPaper = $pagesPerPaper;
    }
    public function show() {
        
    }
    public function insertDatabase(string $orderID) {
        global $conn;
        $sql = "INSERT INTO printdetails (id, orderid, usedpaper, 
        documenttype, colorused, price, pagesperpaper, numofcopies) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $usedPaperName = $this->usedPaper->getName();
            $stmt->bind_param("sssssdii", $this->id, $orderID, $usedPaperName, 
            $this->documentType, $this->colorUsed, $this->price, $this->pagesPerPaper, $this->quantity);
            $stmt->execute();
            $stmt->close();
        } else {
            die("Lỗi truy vấn: " . $conn->error);
        }
    }
}
class printHistory{
    private $id = '';
    private $printTime = '';
    public function __construct(string $id,string $printTime)
    {
        $this->id = $id;
        $this->printTime = $printTime;
    }
    public function getID() {return $this->id;}
    public function getPrintTime() {return $this->printTime;}
    public function show() {
        #Ai làm phần history hiện thực hàm show
    }
}





#phần này là database.

$A5= new paper("A5",21,14.8,0.01,"White",500.0);
$A4= new paper("A4",29.7,21,0.01,"White",1000.0);
$A3= new paper("A3",42,29.7,0.01,"White",2000.0);
$A2= new paper("A2",59.4,42,0.01,"White",5000.0);
$A1= new paper("A1",84.1,59.4,0.01,"White",10000.0);
$A0= new paper("A0",118.9,84.1,0.01,"White",20000.0);
$default_paper_can_be_used = array($A5,$A4,$A3,$A2,$A1,$A0);
// print_r ($default_paper_can_be_used);
// echo "<br />";

$black= new color("black",10.0);
$white= new color("white",10.0);
$blue= new color("blue",10.0);
$red= new color("red",10.0);
$yellow= new color("yellow",10.0);
$default_printer_color_array = array($black,$white,$blue,$red,$yellow);
// print_r ($default_printer_color_array);
// echo "<br />";

$h101 = new printer("H101","EPSON AB12","EPSON",$default_printer_color_array,$default_paper_can_be_used);
$h102 = new printer("H102","EPSON AB23","EPSON",$default_printer_color_array,$default_paper_can_be_used);
$h103 = new printer("H103","EPSON AB34","EPSON",$default_printer_color_array,$default_paper_can_be_used);
$h104 = new printer("H104","EPSON AB45","EPSON",$default_printer_color_array,$default_paper_can_be_used);

$h1 = new building("H1",0,array());
$h1->addPrinter($h101);
// print_r ($h1);
$h1->addPrinter($h102);
$h1->addPrinter($h103);
$h1->addPrinter($h104);
//print_r ($h1);
// echo "<br />";

// $myInventory=new inventory('1234567');
// $myInventory->addPaper($A4,10);
// print_r($myInventory);
// echo "<br />";

$myOrder = new Order("0474","1234567",$h101,"13-02-2023: 00:00:00");#tương đương h101
$myOrder->addDetails("0474",$A4,1,"Word","Black",2);
$myOrder->pay();
// print_r($myOrder);
// echo "<br />";

$myPrintHistory = new printHistory('1111','13-02-2023: 00:00:00');
// print_r($myPrintHistory);