﻿CREATE TABLE [dbo].[Gebruiker]
(
	gebruikersnaam	VARCHAR(16)			NOT NULL,
	voornaam		VARCHAR(35)			NOT NULL,
	achternaam		VARCHAR(35)			NOT NULL,
	adresregel1		VARCHAR(35)			NOT NULL,
	adresregel2		VARCHAR(35)			NOT NULL,
	postcode		VARCHAR(9)			NOT NULL,
	plaatsnaam		VARCHAR(35)			NOT NULL,
	land			VARCHAR(44)			NOT NULL	DEFAULT 'Netherlands',
	geboortedag		DATE				NOT NULL,
	mailbox			VARCHAR(255)		NOT NULL,
	wachtwoord		CHAR(64)			NOT NULL,
	antwoordtekst	CHAR(64)			NOT NULL,
	vraag			INT					NOT NULL,
	verkoper		AS dbo.fnIsKoper(gebruikersnaam),
	salt			CHAR(8)				NOT NULL,
	registratie		DATE				NOT NULL	DEFAULT GETDATE(),
	verwijderd		BIT					NOT NULL	DEFAULT CAST(0 AS BIT)

	CONSTRAINT pk_gebruikersnaam			PRIMARY KEY (gebruikersnaam),
	CONSTRAINT chk_mailbox					CHECK		(mailbox LIKE ('_%@_%._%') AND mailbox NOT LIKE ('% %')),
	CONSTRAINT un_email 					UNIQUE (mailbox),
	CONSTRAINT un_salt						UNIQUE		(salt)
)
