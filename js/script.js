function showHide(tableId) {
    var div = document.getElementById( tableId );
    if (div.style.display !== "none") {
        div.style.display = "none";
    } else {
        div.style.display = "block";
    }
}