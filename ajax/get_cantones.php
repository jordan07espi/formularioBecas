<?php
include("../config/db.php");

if (isset($_GET['id_provincia'])) {
    $id_provincia = intval($_GET['id_provincia']);
    
    $sql = "SELECT id, canton FROM tbl_canton WHERE id_provincia = ? ORDER BY canton";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_provincia);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<option value=''>Seleccione Cantón</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['id'] . "'>" . $row['canton'] . "</option>";
    }

    $stmt->close();
    $conn->close();
}
?>
