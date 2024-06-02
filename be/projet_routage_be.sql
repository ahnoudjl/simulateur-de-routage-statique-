-- I. Création de la BD

-- Création de la table Utilisateur
CREATE TABLE Utilisateur(
   nomUtilisateur VARCHAR(50) PRIMARY KEY,
   mdpUtilisateur TEXT NOT NULL
);

-- Création de la table Equipement
CREATE TABLE Equipement(
   idEquipement SERIAL PRIMARY KEY,
   nomEq VARCHAR(100) NOT NULL UNIQUE,
   typeEquipement VARCHAR(50) NOT NULL CHECK (typeEquipement IN ('Routeur', 'Ordinateur')),
   nomUtilisateurEquipement VARCHAR NOT NULL,
   FOREIGN KEY(nomUtilisateurEquipement) REFERENCES Utilisateur(nomUtilisateur)
);

-- Création de la table Interface
CREATE TABLE Interface(
   macInterface SERIAL PRIMARY KEY,
   adresseIPInterface INET NOT NULL,
   passerelleInterface INET,
   idEquipementInterface INT NOT NULL,
   FOREIGN KEY(idEquipementInterface) REFERENCES Equipement(idEquipement)
);

-- Création de la table Route
CREATE TABLE Route(
   idRoute SERIAL PRIMARY KEY,
   reseauDestRoute CIDR NOT NULL,
   prochainSautRoute INET NOT NULL,
   idEquipementRoute INT NOT NULL,
   FOREIGN KEY(idEquipementRoute) REFERENCES Equipement(idEquipement)
);

CREATE TABLE Connexions (
    sourceIP INET NOT NULL,
    destinationIP INET NOT NULL,
    PRIMARY KEY (sourceIP, destinationIP)
);




-- Insérer un utilisateur avec un mot de passe hashé (simulé ici par la fonction md5, qui n'est PAS recommandée pour la production)
INSERT INTO Utilisateur (nomUtilisateur, mdpUtilisateur)
VALUES ('PapaMike', md5('MotDePasseSecret'));


-- Extraire les réseaux  à partir des adresses IP créées et des masques associés
--SELECT DISTINCT
  --network(adresseIPInterface) AS adresseReseau
--FROM
  --Interface;