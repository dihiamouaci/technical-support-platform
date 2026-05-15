# Gestion des Techniciens – Plateforme Web de Gestion Technique

## Présentation

Gestion des Techniciens est une plateforme web développée dans le cadre d’un projet de soutenance de Licence 3.

L’application permet la gestion des techniciens, des tâches techniques, du support utilisateur ainsi que l’administration globale du système via un tableau de bord moderne et interactif.

Le projet a été développé avec PHP, MySQL, Bootstrap et JavaScript.

---

# Fonctionnalités

## Authentification
- Connexion administrateur
- Connexion technicien
- Gestion des sessions
- Sécurisation des mots de passe

## Dashboard Administrateur
- Vue globale du système
- Gestion des techniciens
- Gestion des tâches
- Gestion du support
- Paramètres de l’application

## Dashboard Technicien
- Consultation des tâches
- Suivi des interventions
- Gestion des tickets support

## Gestion des Tâches
- Ajouter une tâche
- Modifier une tâche
- Supprimer une tâche
- Suivi du statut des tâches

## Support Technique
- Création de tickets
- Gestion des demandes
- Suivi des statuts

## Paramètres
- Changement du thème clair/sombre
- Modification du mot de passe
- Configuration SMTP
- Gestion des modules

## Exports
- Export PDF
- Export Excel

---

# Technologies Utilisées

- PHP
- MySQL
- HTML5
- CSS3
- Bootstrap
- JavaScript
- FPDF
- XAMPP

---

# Structure du Projet

```bash
gestion_tech/
│
├── admin_dashboard.php
├── technicien_dashboard.php
├── login.php
├── register.php
├── db.php
├── support.php
├── planning.php
├── export_pdf.php
├── export_excel.php
├── style.css
├── theme.js
├── gestion_tech_database.sql
├── vendor/
└── README.md