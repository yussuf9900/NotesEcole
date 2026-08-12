CREATE TABLE anneeScolaires(
    id SERIAL PRIMARY KEY,
    nom VARCHAR (30) UNIQUE NOT NULL,
    date DATE DEFAULT CURRENT_DATE
);

ALTER TABLE anneeScolaires
ADD COLUMN actif int;

UPDATE anneeScolaires
set actif = 0
WHERE id != 3;



SELECT * FROM anneeScolaires;

 CREATE TABLE eleves(
    id SERIAL PRIMARY KEY,
    nom VARCHAR (50) NOT NULL,
    prenom VARCHAR (50) NOT NULL,
    matricule VARCHAR (30) UNIQUE NOT NULL
);


CREATE TABLE classes(
    id SERIAL PRIMARY KEY,
    nomClasse VARCHAR (30) UNIQUE NOT NULL
);

SELECT * FROM classes;

 CREATE TABLE inscriptions(
    id SERIAL PRIMARY KEY,
    annee_id INT REFERENCES anneeScolaires(id) ON DELETE CASCADE,
    eleve_id INT REFERENCES eleves(id) ON DELETE CASCADE,
    classe_id INT REFERENCES classes(id) ON DELETE CASCADE
);

SELECT * FROM inscriptions;

 CREATE TABLE roles(
    id SERIAL PRIMARY KEY,
    nomRole VARCHAR (50) NOT NULL
);

SELECT * FROM roles;


 CREATE TABLE utilisateurs(
    id SERIAL PRIMARY KEY,
    nom VARCHAR (50) NOT NULL,
    prenom VARCHAR (50) NOT NULL,
    telephone VARCHAR (30) UNIQUE NOT NULL,
    email VARCHAR (30) UNIQUE NOT NULL,
    role_id INT REFERENCES roles(id)
);

SELECT * FROM utilisateurs;

 CREATE TABLE matieres(
    id SERIAL PRIMARY KEY,
    nomMatiere VARCHAR (40) UNIQUE NOT NULL
);

SELECT * FROM matieres;

 CREATE TABLE matiere_classes(
    id SERIAL PRIMARY KEY,
    classe_id INT REFERENCES classes(id) ON DELETE CASCADE,
    matiere_id INT REFERENCES matieres(id) ON DELETE CASCADE
);

SELECT * FROM matiere_classes;

 CREATE TABLE periodes(
    id SERIAL PRIMARY KEY,
    nomPeriode VARCHAR (50) UNIQUE NOT NULL
);

SELECT * FROM periodes;

 CREATE TABLE evaluations(
    id SERIAL PRIMARY KEY,
    inscription_id INT REFERENCES inscriptions(id) ON DELETE CASCADE,
    matiere_id INT REFERENCES matieres(id) ON DELETE CASCADE,
    periode_id INT REFERENCES periodes(id) ON DELETE CASCADE,
    devoir1 NUMERIC(4,2),
    devoir2 NUMERIC(4,2),
    composition NUMERIC(4,2)
);



SELECT ROUND(COALESCE(AVG(moyenne_eleve), 0), 2) AS moyenne_classe 
FROM (
    SELECT 
        ev.inscription_id,
        ROUND(AVG((COALESCE(ev.devoir1, 0) + COALESCE(ev.devoir2, 0) + 2 * COALESCE(ev.composition, 0)) / 4.0), 2) AS moyenne_eleve
    FROM evaluations ev
    INNER JOIN inscriptions i ON i.id = ev.inscription_id
    WHERE i.annee_id = 1
      AND i.classe_id = 2
      AND ev.matiere_id = 1
      AND ev.periode_id = 1
    GROUP BY ev.inscription_id
) AS moyennes_eleves;




SELECT * FROM evaluations;


INSERT INTO roles(nomRole)
VALUES ('Direction D etablissement'), ('Proffeseur'), ('Surveillant');


INSERT INTO utilisateurs(nom, prenom, telephone, email, role_id)
VALUES ('Fatou', 'Sall', '77888888', 'fatouSall@gmail.com', 1);

UPDATE utilisateurs SET password = 'password' WHERE id=1;


INSERT INTO anneeScolaires (nom) VALUES ('2025-2026');


INSERT INTO classes (nomClasse) VALUES 
('3em A'), 
('3em B'), 
('4em A'), 
('4em B');


INSERT INTO eleves (nom, prenom, matricule) VALUES
('Fall', 'Moussa', 'JE-26002'),
('Ndiaye', 'Fatou', 'JE-26003'),
('Diallo', 'Ibrahima', 'JE-26004'),
('Sow', 'Khadija', 'JE-26005'),
('Faye', 'Ousmane', 'JE-26006');


INSERT INTO inscriptions (annee_id, eleve_id, classe_id) VALUES
(1, 1, 3),
(1, 2, 3),
(1, 3, 3),
(1, 4, 1),
(1, 5, 1);

 INSERT INTO matieres (nomMatiere) VALUES
('Mathematique'),
('Francais'),
('Histoire-Geographie'),
('Science-physique');

alter table utilisateurs 
add column password VARCHAR  UNIQUE;
 INSERT INTO matiere_classes (classe_id, matiere_id) VALUES
(3, 1),
(3, 2),
(3, 3),
(3, 4);

 INSERT INTO periodes (nomPeriode) VALUES
('Trimestre 1'),
('Trimestre 2'),
('Trimestre 3');

 INSERT INTO evaluations (inscription_id, matiere_id, periode_id, devoir1, devoir2, composition) VALUES
(1, 1, 1, 10, 10, 15),
(2, 1, 1, 13, 13, 19),
(3, 1, 1, 5, 10, 17),
(4, 1, 1, 10, 18, 12),
(5, 1, 1, 5, 8, 10);


SELECT u.*,r.* from utilisateurs u
inner join roles r on u.role_id=r.id
 WHERE
email=:email 
;
select * from anneeScolaires;

INSERT INTO anneescolaires (nom)
VALUES  ('2023-2024'),
        ('2024-2025'),
        ('2025-2026')
;

DROP TABLE anneescolaires;

