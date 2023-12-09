function chooseBuilding() {
     var searchBuildingName = document.getElementById("search-building").value.toLowerCase();
    var listBuildingName = document.getElementsByClassName("building-name");

    for (var i = 0; i < listBuildingName.length; i++) {
        var buildingName = listBuildingName[i].innerHTML.toLowerCase();
        var liTagParent = listBuildingName[i].closest('li');
        if (buildingName.includes(searchBuildingName)) {
            liTagParent.style.display = "block";
        } else {
            liTagParent.style.display = "none";
        }
    }
}

function choosePrinter() {
    var searchPrinterInfo = document.getElementById("search-printer").value.toLowerCase();
    var listPrinterInfo = document.getElementsByClassName("printer-info");

    for (var i = 0; i < listPrinterInfo.length; i++) {
        var childEle = listPrinterInfo[i].children;
        var printerId = childEle[0].innerHTML.toLowerCase();
        var printerModel = childEle[1].innerHTML.toLowerCase();
        var liTagParent = listPrinterInfo[i].closest('li');

        if (printerId.includes(searchPrinterInfo) || printerModel.includes(searchPrinterInfo)) {
            liTagParent.style.display = "block";
        } else {
            liTagParent.style.display = "none";
        }
    }
}

function goToDetail(orderId) {
    window.location.href = './ChiTietCuocHen.php?id=' + orderId;
}

function chooseOrder() {
    var searchIdOrder = document.getElementById("search-order").value.toLowerCase();
    var listOrder = document.getElementsByClassName("order-row");

    for (var i = 0; i < listOrder.length; i++) {
        var childEle = listOrder[i].children;
        var orderId = childEle[1].innerHTML.toLowerCase();

        if (orderId.startsWith(searchIdOrder)) {
            listOrder[i].style.display = "table-row";
        } else {
            listOrder[i].style.display = "none";
        }
    }
}