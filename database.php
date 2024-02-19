<?php
// Databaseconfiguratie
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bestellingen";

// Verbinding maken met de database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleren op verbinding
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Functie om een nieuwe bestelling toe te voegen
function addOrder($orderName, $orderData) {
    global $conn;

    // Bestelling toevoegen aan Orders-tabel
    $sql = "INSERT INTO Orders (OrderName) VALUES ('$orderName')";
    $conn->query($sql);

    // ID van de toegevoegde bestelling ophalen
    $orderID = $conn->insert_id;

    // Details van de bestelling toevoegen aan OrderDetails-tabel
    foreach ($orderData as $data) {
        $productName = $data['productName'];
        $quantity = $data['quantity'];
        $imageURL = $data['imageURL'];
        $sql = "INSERT INTO OrderDetails (OrderID, ProductName, Quantity, ImageURL) VALUES ('$orderID', '$productName', '$quantity', '$imageURL')";
        $conn->query($sql);
    }
}

// Functie om bestellingen op te halen
function getOrders() {
    global $conn;

    // Query om bestellingen op te halen
    $sql = "SELECT * FROM Orders";
    $result = $conn->query($sql);

    $orders = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orderID = $row['OrderID'];
            $orderName = $row['OrderName'];

            // Details van de bestelling ophalen
            $orderDetails = array();
            $sql = "SELECT * FROM OrderDetails WHERE OrderID='$orderID'";
            $detailsResult = $conn->query($sql);
            if ($detailsResult->num_rows > 0) {
                while ($detailRow = $detailsResult->fetch_assoc()) {
                    $orderDetails[] = array(
                        'productName' => $detailRow['ProductName'],
                        'quantity' => $detailRow['Quantity'],
                        'imageURL' => $detailRow['ImageURL']
                    );
                }
            }

            // Bestelling toevoegen aan resultaatarray
            $orders[] = array(
                'orderID' => $orderID,
                'orderName' => $orderName,
                'orderDetails' => $orderDetails
            );
        }
    }

    return $orders;
}

// Functie om een nieuw sjabloon toe te voegen
function addTemplate($templateName, $templateData) {
    global $conn;

    // Sjabloon toevoegen aan Templates-tabel
    $sql = "INSERT INTO Templates (TemplateName) VALUES ('$templateName')";
    $conn->query($sql);

    // ID van het toegevoegde sjabloon ophalen
    $templateID = $conn->insert_id;

    // Details van het sjabloon toevoegen aan TemplateDetails-tabel
    foreach ($templateData as $data) {
        $productName = $data['productName'];
        $quantity = $data['quantity'];
        $imageURL = $data['imageURL'];
        $sql = "INSERT INTO TemplateDetails (TemplateID, ProductName, Quantity, ImageURL) VALUES ('$templateID', '$productName', '$quantity', '$imageURL')";
        $conn->query($sql);
    }
}

// Verbinding met de database sluiten
function closeConnection() {
    global $conn;
    $conn->close();
}
?>
