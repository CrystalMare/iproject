USE iproject;

INSERT INTO Vraag (vraag) VALUES 
	('Hoe heette je beste vriend of vriendin uit je tienertijd?'),
	('Hoeveel kinderen heeft u?'),
	('Wat is uw lievelingskleur?'),
	('Wat was de naam van uw middelbare school?'),
	('Wat is het merk van uw eerste auto?'),
	('Wat is uw lievelings kinderboek?');
INSERT INTO Gebruiker (gebruikersnaam, voornaam, achternaam, adresregel1, adresregel2, postcode, plaatsnaam, geboortedag, mailbox, 
						wachtwoord, antwoordtekst, vraag, salt) VALUES (
'crystalmare', 'Sven', 'Olderaan', 'Ijsselweg 41A', '', '7061XV', 'Terborg', CONVERT(DATE, '1995-07-10'), 'admin@heaven-craft.net',
'a134bc4ab79b3e5f7c3fdb0064e46f427a75a4baf40bb550a93f0b6cb5fa5d3f',
'4c7bf86c7e0005438d505955cfa0761d65a3d30f60b9a78c706c3a3ac9d53e50', 4, 'IFyBnVTZ'),

('xxdek', 'Xander', 'de Kievit', 'Bingerdensedijk 20', '', '6987EA', 'Giesbeek', CONVERT(DATE, '1992-10-19'), 'xdek@hotmail.com',
'23b085323411537e43936f482268e0d8a6084bd860891e72a9027a881eebcd72',
'154470fe68096297736dc6c6b97b238e683377556c9044a95db87ca01ed6180b', 2, 'qy5cJam9'),

('sesamstraat', 'Dick', 'Pino', 'Denekamp 69', '', '6969XX', 'Ergens', CONVERT(DATE, '1990-10-10'), 'sven@heaven-craft.net',
'996168e64f0eb17136c661c833cf9f685768db40b38d825114ab36bb7db0d083',
'c152d6186fd45e7f34f7f0ed225328bf7109ed398af966089714bfe440926ac3', 2, 'qOGMWbzN'),

('satan', 'Jasper', 'Klaassen', 'Kerkstraat 19', '', '5638BU', 'Lathum', CONVERT(DATE, '1996-09-09'), 'jslim@gmail.com',
'a7e5c50576a73c2f5bb73e6ad078070d6fd4e0fd060f47d38e6405fa1d598ada', 
'067b38455a3bacb2a60fabd17e78eedd2ee70980478bed88156c682e03ee1857', 2, 'IYKsapfD');

INSERT INTO Gebruikerstelefoon VALUES ('crystalmare', '0031648582615'), ('sesamstraat', '0906CALLME'), ('xxdek', '062139823'), ('satan', '0204543543'),
										('crystalmare', '063434543543'), ('satan', '239384734');

INSERT INTO Verkoper (gebruikersnaam, bank, rekeningnummer, controleoptie, creditcard)
VALUES ('xxdek', 'Rabobank', '956386931', 'creditcard', '5498531863917649'),
	   ('crystalmare', 'ING', 'NL05INGB0003449347', 'post', NULL);

INSERT INTO Looptijd (looptijd) VALUES (1), (3), (5), (7), (10);
INSERT INTO Looptijd (looptijd, actief) VALUES (4, 0), (20, 0);

INSERT INTO Voorwerp (titel, beschrijving, startprijs, betalingsinstructie, plaatsnaam, land, looptijd, verzendkosten, verzendinstructies, verkoper)
VALUES 
('Opel Astra Twintop Cosmo mooie luxe uitvoering!', 'BOMVOLLE ASTRA TWINTOP MET EEN 150 PK STERKE DIESEL. NAVIGATIE EUROPA, XENON, LEER, KLIMAATREGELING ETC. PRIJS NU 8.900,- INCLUSIEF NIEUWE APK BIJ AFLEVERING.
PRIJS INCLUSIEF BTW! BTW AFTREKBAAR', 8900.00, NULL, 'Amsterdam', 'Netherlands', 10, 0, 'Ophalen in Amsterdam', 'crystalmare'),

('Peugeot 307SW 2.0 PACK De ideale caravantrekker!', 'Ideale caravantrekker, mag 1500kg trekken! Goed onderhouden Peugeot 307SW (historie aanwezig). Zo is bij 103dkm de distributiesnaar + waterpomp vervangen en bij 125dkm de koppelingset,
        remschijven voor en remblokken rondom vervangen.', 3100.00, NULL, 'Hellevoetsluis', 'Netherlands', 7, 0, 'Ophalen in Hellevoetsluis', 'xxdek'),

('Volkswagen Polo 1.4 44KW 2000 Zwart', 'VW POLO 1.4 MPI BJ 2000  APK TOT 8-2015, AIRCO,CENTR.VERGRENDELING MET COMFORT SLUITING.
ELECT.RAMEN EN SPIEGELS, HOOGTE VERSTELBARE STOELEN,RADIO CD, B UMPERS EN SPIEGELS IN KLEUR, ALLE BOEKJES EN SLEUTELS ERBIJ.', 
1550.00, NULL, 'Enschede', 'Netherlands', 5, 0, 'Ophalen in Enschede', 'crystalmare')

INSERT INTO Rubriek (rubrieknaam, volgnummer) VALUES
('Auto''s', 2),
('Kleding', 1),
('Binnenhuis', 3),
('Buitenhuis', 4),
('Speelgoed', 5);

INSERT INTO Rubriek (rubrieknaam, ouderrubriek, volgnummer) VALUES
('Opel', 1, 1),
('Peugeot', 1, 2),
('Mercedes', 1, 3),
('Volkswagen', 1, 4),
('Volvo', 1, 5);

INSERT INTO Rubriek (rubrieknaam, ouderrubriek, volgnummer) VALUES
('Tuin', 4, 1);

INSERT INTO Rubriek (rubrieknaam, ouderrubriek, volgnummer) VALUES
('Tuin Meubelen', 11, 1);

INSERT INTO Voorwerpinrubriek (voorwerpnummer, rubrieknummer) VALUES
(1, 6),
(2, 7),
(3, 9);

INSERT INTO Verkoperverificatie (gebruikersnaam) VALUES ('sesamstraat'), ('satan');

INSERT INTO Bod (gebruikersnaam, voorwerpnummer, bodbedrag) VALUES ('satan', 1, 9000);
INSERT INTO Bod (gebruikersnaam, voorwerpnummer, bodbedrag) VALUES ('sesamstraat', 1, 9100);
INSERT INTO Bod (gebruikersnaam, voorwerpnummer, bodbedrag) VALUES ('satan', 2, 3500.50);

INSERT INTO Bestand (filenaam, voorwerpnummer) VALUES (
('auction_1_1.jpg', 1), ('auction_1_2.jpg', 1), ('auction_1_3.jpg', 1), ('auction_1_4.jpg', 1),
('auction_2_1.jpg', 2), ('auction_2_2.jpg', 2), ('auction_2_3.jpg', 2), ('auction_2_4.jpg', 2),
('auction_3_1.jpg', 3), ('auction_3_2.jpg', 3), ('auction_3_3.jpg', 3);