<?php

function updateConnections($pdo) {
    $pdo->query("DELETE FROM NetworkConnections");
    $stmt = $pdo->query("SELECT i1.adresseIPInterface AS sourceIP, i2.adresseIPInterface AS destinationIP
                         FROM Interface i1
                         JOIN Interface i2 ON i1.passerelleInterface = i2.adresseIPInterface");
    $connections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($connections as $conn) {
        $pdo->prepare("INSERT INTO NetworkConnections (sourceIP, destinationIP) VALUES (?, ?)")
            ->execute([$conn['sourceIP'], $conn['destinationIP']]);
    }
}

function addOrUpdateInterface($pdo, $ip, $gateway, $equipementId) {
    $stmt = $pdo->prepare("INSERT INTO Interface (adresseIPInterface, passerelleInterface, idEquipementInterface) VALUES (?, ?, ?)
                           ON CONFLICT (adresseIPInterface) DO UPDATE SET passerelleInterface = EXCLUDED.passerelleInterface, idEquipementInterface = EXCLUDED.idEquipementInterface");
    $stmt->execute([$ip, $gateway, $equipementId]);
    updateConnections($pdo);
}

function deleteInterface($pdo, $ip) {
    $stmt = $pdo->prepare("DELETE FROM Interface WHERE adresseIPInterface = ?");
    $stmt->execute([$ip]);
    updateConnections($pdo);
}
?>
