CREATE TABLE idrewrite (
  original BIGINT NOT NULL,
  new INT IDENTITY NOT NULL
    PRIMARY KEY (new),
  UNIQUE (original)
);
DROP TABLE idrewrite;
TRUNCATE TABLE Users;
DROP TABLE Illustraties;
TRUNCATE TABLE Users;
DROP TABLE Items;
DROP TABLE Users;

DROP TABLE Bestand;

INSERT INTO idrewrite (original)
  SELECT id FROM Items;

-- DELETE FROM Feedback WHERE 1=1;
-- DELETE FROM Voorwerpinrubriek WHERE 1=1;
-- DELETE FROM Bod WHERE 1=1;
-- DELETE FROM Bestand WHERE 1=1;
-- DELETE FROM Voorwerp WHERE 1=1;

SELECT titel, new, original FROM Items JOIN idrewrite ON ID = idrewrite.original ORDER BY new ASC;

SELECT DISTINCT Country FROM Users;

CREATE TABLE countrymatch (
  code CHAR(2) NOT NULL,
  fullname VARCHAR(44) NOT NULL,
  PRIMARY KEY (code),
  FOREIGN KEY (fullname) REFERENCES Land(landnaam),
  UNIQUE (code, fullname)
);

SELECT COUNT(*) FROM Users;

-- INSERT INTO countrymatch VALUES ('AT', 'Austria');
-- INSERT INTO countrymatch VALUES ('DE', 'Germany');
-- INSERT INTO countrymatch VALUES ('GB', 'United Kingdom');
-- INSERT INTO countrymatch VALUES ('BE', 'Belgium');
-- INSERT INTO countrymatch VALUES ('IT', 'Italy');
-- INSERT INTO countrymatch VALUES ('NL', 'Netherlands');
-- INSERT INTO countrymatch VALUES ('HK', 'Hong Kong');
-- INSERT INTO countrymatch VALUES ('MX', 'Mexico');
-- INSERT INTO countrymatch VALUES ('CA', 'Canada');
-- INSERT INTO countrymatch VALUES ('DK', 'Denmark');
-- INSERT INTO countrymatch VALUES ('BG', 'Bulgaria');
-- INSERT INTO countrymatch VALUES ('PL', 'Poland');
-- INSERT INTO countrymatch VALUES ('FR', 'France');
-- INSERT INTO countrymatch VALUES ('AS', 'American Samoa');
-- INSERT INTO countrymatch VALUES ('US', 'United States');
-- INSERT INTO countrymatch VALUES ('CN', 'China');

SELECT substring(CONVERT(VARCHAR(255), NEWID()), 1, 8);
SELECT NEWID();

SELECT LEN(SUBSTRING('1234567890', 1, 16));
SELECT 'HAI';

SELECT TOP  1 LEN(Location) length FROM Users ORDER BY length DESC;

SELECT TOP 1 LEN(Username) AS length FROM Users ORDER BY length DESC;

DELETE FROM Gebruikerstelefoon WHERE 1=1;
DELETE FROM Verkoperverificatie WHERE 1=1;
DELETE FROM Verkoper WHERE 1=1;
DELETE FROM Gebruiker WHERE 1=1;

DELETE FROM Gebruiker WHERE 1=1;

DELETE FROM Bestand;

DELETE FROM Users WHERE Username LIKE ('%rutger%') AND Rating = 91.0;
DELETE FROM Users WHERE Username LIKE ('%marion%');
DELETE FROM Users WHERE Username LIKE ('%6585jonathan%');


DELETE FROM Users WHERE 1=1;

DELETE FROM Gebruiker WHERE 1=1;

INSERT INTO Gebruiker (gebruikersnaam, voornaam, achternaam, adresregel1, adresregel2, postcode, plaatsnaam, land,
                       geboortedag, mailbox, wachtwoord, antwoordtekst, vraag, salt)
  SELECT tbl1.gebruikersnaam gebruikersnaam, SUBSTRING(Users.username, 1, 35) voornaam, SUBSTRING(Users.username, 1, 35) achternaam,
         'adresregel1' adresregel1, 'adresregel2' adresregel2, '1234' postcode, Location plaatsaam,
         countrymatch.fullname land, DATEADD(YEAR, -20, GETDATE()) geboortedag,
         Users.username + '@databatch.test' mailbox,
         '9a0b6c3f5d631af37eeb9f7f37d2c0f23a0087e0b089407d631c9bfcfee60571' wachtwoord,
         '9a0b6c3f5d631af37eeb9f7f37d2c0f23a0087e0b089407d631c9bfcfee60571' antwoordtekst, 1 vraag,
         substring(CONVERT(VARCHAR(255), NEWID()), 1, 8) salt
  FROM Users JOIN ( SELECT DISTINCT SUBSTRING(username, 1, 16) gebruikersnaam, username FROM Users) AS tbl1
      ON Users.Username = tbl1.Username
    JOIN countrymatch ON code = Country


INSERT INTO Verkoper (gebruikersnaam, controleoptie)
  SELECT gebruikersnaam, 'post' AS controleoptie FROM Gebruiker;

UPDATE Items SET prijs = 1.00 WHERE CONVERT(NUMERIC(7,2), prijs) < 1.00;

DELETE FROM Voorwerp WHERE 1=1;

SET IDENTITY_INSERT Voorwerp ON;
INSERT INTO Voorwerp (voorwerpnummer, titel, beschrijving, startprijs, betalingswijze, plaatsnaam, land, looptijd,
                      looptijdbeginmoment, verkoper)
  SELECT idrewrite.new as voorwerpnummer, CONVERT(VARCHAR(64), Titel) titel, Beschrijving, CONVERT(NUMERIC(7,2),
                                                                                                   Prijs) prijs, 'Bank/Giro' betalingswijze,  CONVERT(VARCHAR(35), Locatie) plaatsnaam,
    countrymatch.fullname land, 7 looptijd, GETDATE() looptijdbeginmoment, CONVERT(VARCHAR(16), Verkoper) verkoper

  FROM Items
    JOIN idrewrite ON idrewrite.original = Items.ID
    JOIN countrymatch ON countrymatch.code = Items.Land

SET IDENTITY_INSERT Voorwerp OFF;

INSERT INTO Voorwerpinrubriek (voorwerpnummer, rubrieknummer)
  SELECT idrewrite.new voorwerpnummer, CONVERT(INT, Items.Categorie) rubrieknummer
  FROM idrewrite
    JOIN Items ON Items.ID = idrewrite.original;

INSERT INTO Bestand (filenaam, voorwerpnummer)
  SELECT 'pics/' + IllustratieFile, idrewrite.new
  FROM idrewrite
    JOIN Illustraties ON Illustraties.ItemID = idrewrite.original


SELECT * FROM Bestand WHERE voorwerpnummer = 7;

DROP TRIGGER trgMax4Bestanden;

SELECT * FROM Bestand WHERE voorwerpnummer = 7;
DELETE FROM Bestand WHERE 1=1;

SELECT * FROM Items;

CREATE TABLE Users
(
  Username VARCHAR(200),
  Postalcode VARCHAR(9),
  Location VARCHAR(MAX),
  Country VARCHAR(100),
  Rating NUMERIC(4,1)
    PRIMARY KEY (Username)
);

ALTER TABLE Voorwerp ADD email BIT NOT NULL DEFAULT 0;



CREATE TABLE Items
(
  ID bigint NOT NULL,
  Titel varchar(max) NULL,
  Beschrijving nvarchar(max) NULL,
  Categorie int NULL,
  Postcode varchar(max) NULL,
  Locatie varchar(max) NULL,
  Land varchar(max) NULL,
  Verkoper varchar(max) NULL,
  Prijs varchar(max) NULL,
  Valuta varchar(max) NULL,
  Conditie varchar(max) NULL,
  Thumbnail varchar(max) NULL,
  CONSTRAINT PK_Items PRIMARY KEY (ID),
  CONSTRAINT FK_Items_In_Categorie FOREIGN KEY (Categorie) REFERENCES Categorieen (ID)
)

CREATE TABLE Illustraties
(
  ItemID bigint NOT NULL,
  IllustratieFile varchar(100) NOT NULL,
  CONSTRAINT PK_ItemPlaatjes PRIMARY KEY (ItemID, IllustratieFile),
  CONSTRAINT [ItemsVoorPlaatje] FOREIGN KEY(ItemID) REFERENCES Items (ID)
)


CREATE INDEX IX_Items_Categorie ON Items (Categorie)
CREATE INDEX IX_Categorieen_Parent ON Categorieen (Parent)

SELECT * FROM Bestand WHERE voorwerpnummer = 5174;


CREATE TABLE tijdelijk (
  nummer INT NOT NULL
)



DELETE FROM Voorwerp WHERE voorwerpnummer = (
  SELECT nummer
  FROM tijdelijk
  WHERE Voorwerp.voorwerpnummer = tijdelijk.nummer
)


SELECT * FROM Bestand WHERE voorwerpnummer = 5176;
SELECT * FROM Gebruiker WHERE gebruikersnaam = 'Nico';

DROP TABLE Items;
DROP TABLE Illustraties


SELECT DISTINCT TOP 10 Voorwerp.voorwerpnummer, looptijdeindmoment, verkoper, titel, startprijs, beschrijving,
                 bodbedrag = (
    SELECT TOP 1 bodbedrag
    FROM Bod
    WHERE Bod.voorwerpnummer = voorwerpnummer
    ORDER BY bodbedrag DESC
  )
FROM Voorwerp
  INNER JOIN Voorwerpinrubriek
    ON Voorwerp.voorwerpnummer = Voorwerpinrubriek.voorwerpnummer AND rubrieknummer = 160
WHERE titel LIKE ('%%') AND gesloten = 0
ORDER BY looptijdeindmoment ASC;

SELECT dbo.fnWelkeCatIsHoofd(78769);