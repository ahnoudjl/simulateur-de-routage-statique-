<?php
if (isset($_POST['ip']) && isset($_POST['source'])) {
    $ip = escapeshellarg($_POST['ip']);
    $source = escapeshellarg($_POST['source']);
    // Assurez-vous de valider et de nettoyer les entrées pour éviter les problèmes de sécurité

    // Exécutez la commande traceroute
    $output = shell_exec("traceroute -i $source $ip");
    echo $output;
} else {
    echo "No IP address or source provided";
}
?>