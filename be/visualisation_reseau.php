<!DOCTYPE html>
<html>
<head>
    <title>Visualisation du réseau</title>
    <script type="text/javascript" src="https://visjs.github.io/vis-network/standalone/umd/vis-network.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style type="text/css">
        #mynetwork {
            width: 1200px;
            height: 400px;
            border: 1px solid lightgray;
        }
    </style>
</head>
<body>
    <!-- Bouton de retour à l'accueil -->
    <a href="index.php">Retour à l'accueil</a>
    <div id="mynetwork"></div>
<div id="info"></div>
    <?php
    // Fichier de configuration pour la connexion à la base de données
    include 'config.php';

    // Récupérer tous les équipements de la base de données
    $sql = "SELECT * FROM equipement";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $equipements = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les interfaces de tous les équipements
    $sql = "SELECT * FROM interface";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $interfaces = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les routes de la base de données
    $sql = "SELECT * FROM route";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $routes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <script type="text/javascript">
    // Convertir les données PHP en JSON pour les utiliser dans le script JavaScript
    var equipements = <?php echo json_encode($equipements); ?>;
    var interfaces = <?php echo json_encode($interfaces); ?>;
    var routes = <?php echo json_encode($routes); ?>;

    // Créer un réseau avec des nœuds pour chaque équipement et des arêtes pour chaque route
    var nodes = new vis.DataSet();
    
    var edges = new vis.DataSet();

    

    var mapEquipements = {};

    

    for (var i = 0; i < equipements.length; i++) {
    var color;
    var shape;
    var borderWidth;
    if (equipements[i].typeequipement === 'Ordinateur') {
        color = {
            background: 'lightblue',
            border: 'black'
        };
        shape = 'square';
        borderWidth = 1;
    } else if (equipements[i].typeequipement === 'Routeur') {
        color = {
            background: 'grey',
            border: 'black'
        };
        shape = 'circle';
        borderWidth = 2;
    }
    nodes.add({
        id: equipements[i].idequipement, 
        label: equipements[i].nomeq, 
        color: color, 
        shape: shape,
        borderWidth: borderWidth
    });
    mapEquipements[equipements[i].idequipement] = equipements[i].idequipement;
}

    for (var i = 0; i < routes.length; i++) {
        var equipementOrigine = routes[i].idequipementroute;
        var prochainSaut = routes[i].prochainsautroute;
        if (mapEquipements.hasOwnProperty(equipementOrigine)) {
            for (var j = 0; j < interfaces.length; j++) {
                var adresseIPInterface = interfaces[j].adresseipinterface.split('/')[0];
                if (prochainSaut == adresseIPInterface) {
                    edges.add({from: mapEquipements[equipementOrigine], to: mapEquipements[interfaces[j].idequipementinterface]});
                }
            }
        }
    }
// Identifier les ordinateurs et les routeurs dans la base de données
var ordinateurs = equipements.filter(function(equipement) {
    return equipement.typeequipement === 'Ordinateur';
});
var routeurs = equipements.filter(function(equipement) {
    return equipement.typeequipement === 'Routeur';
});

// Pour chaque ordinateur, trouver sa passerelle
for (var i = 0; i < ordinateurs.length; i++) {
    var ordinateurId = ordinateurs[i].idequipement;
    var interfaceTrouvee = interfaces.find(function(interface) {
    return interface.idequipementinterface === ordinateurId;
});
var passerelle = interfaceTrouvee ? interfaceTrouvee.passerelleinterface : 'Pas de passerelle';
  

    // Pour chaque routeur, vérifier si la passerelle de l'ordinateur correspond à l'une de ses interfaces
    for (var j = 0; j < routeurs.length; j++) {
        var routeurId = routeurs[j].idequipement;
        var routeurInterfaces = interfaces.filter(function(interface) {
            return interface.idequipementinterface === routeurId;
        });

        for (var k = 0; k < routeurInterfaces.length; k++) {
            var routeurInterfaceIp = routeurInterfaces[k].adresseipinterface.split('/')[0];
            if (passerelle === routeurInterfaceIp) {
                edges.add({from: ordinateurId, to: routeurId});
            }
        }
    }
}
    var container = document.getElementById('mynetwork');
    var data = {
        nodes: nodes,
        edges: edges
    };

    var options = {
    physics: false,
    edges: {
        smooth: {
            enabled: false
        }
    }
};

var network = new vis.Network(container, data, options);

var infoContainer = document.getElementById('info');
var computerSection = document.createElement('div');
var routerSection = document.createElement('div');

computerSection.innerHTML = '<h2>Ordinateurs</h2>';
routerSection.innerHTML = '<h2>Routeurs</h2>';

equipements.forEach(function(equip) {
    var equipInfo = document.createElement('p');
    var equipInterfaces = interfaces.filter(function(intf) {
        return intf.idequipementinterface === equip.idequipement;
    });

    if (equip.typeequipement === 'Ordinateur' && equipInterfaces.length > 0) {
        equipInfo.textContent = 'Ordinateur: ' + equip.nomeq + '\n';
        equipInfo.textContent += 'Adresse IP et masque CIDR: ' + equipInterfaces[0].adresseipinterface + '\n';

        var passerelle = equipInterfaces[0].passerelleinterface;
        var routeurInterface = interfaces.find(function(intf) {
            return intf.adresseipinterface.split('/')[0] === passerelle;
        });
        if (routeurInterface) {
            var routeurEquipement = equipements.find(function(eq) {
                return eq.idequipement === routeurInterface.idequipementinterface;
            });
            if (routeurEquipement) {
                equipInfo.textContent += 'Passerelle: ' + passerelle + ' (Routeur: ' + routeurEquipement.nomeq + ')';
            } else {
                equipInfo.textContent += 'Passerelle: ' + passerelle + ' (Routeur non trouvé)';
            }
        } else {
            equipInfo.textContent += 'Passerelle non définie ou non trouvée dans les interfaces';
        }
        computerSection.appendChild(equipInfo);
    } else if (equip.typeequipement === 'Routeur') {
        equipInfo.textContent = 'Routeur: ' + equip.nomeq + '\n';
        equipInterfaces.forEach(function(intf, index) {
            equipInfo.textContent += 'Interface ' + (index + 1) + ': ' + intf.adresseipinterface + '\n';
        });
        routerSection.appendChild(equipInfo);
    }
});

infoContainer.appendChild(computerSection);
infoContainer.appendChild(routerSection);
    </script>
</body>
</html>

<style>
#mynetwork {
        width: 100%;
        max-width: 1200px;
        height: 500px;
        margin: auto;
        border: 2px solid #007BFF;
        background-color: #f4f4f4;
    }
    #info {
        padding: 15px;
        margin-top: 20px;
        background-color: #fff;
        border: 1px solid #ccc;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    a {
        padding: 10px 15px;
        background-color: #007BFF;
        color: white;
        border-radius: 5px;
        text-decoration: none;
    }
    a:hover {
        background-color: #0056b3;
    }
    @media (max-width: 768px) {
        #mynetwork {
            height: 300px;
        }
    }
</style>