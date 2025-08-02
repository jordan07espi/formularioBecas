<?php
include("../config/db.php");

if (isset($_GET['id_canton'])) {
    $id_canton = intval($_GET['id_canton']);
    
    $sql = "SELECT id, parroquia FROM tbl_parroquia WHERE id_canton = ? ORDER BY parroquia";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_canton);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<option value=''>Seleccione Parroquia</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['id'] . "'>" . $row['parroquia'] . "</option>";
    }

    $stmt->close();
    $conn->close();
}
?>
