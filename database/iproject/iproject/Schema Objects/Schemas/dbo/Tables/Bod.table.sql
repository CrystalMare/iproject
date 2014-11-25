CREATE TABLE [dbo].[Bod]
(
	bodnummer		INT	IDENTITY		NOT NULL,
	bodbedrag		NUMERIC(7,2)		NOT NULL,
	datumtijd		DATETIME			NOT NULL	DEFAULT GETDATE(),
	gebruikersnaam	VARCHAR(16)			NOT NULL,
	voorwerpnummer	INT					NOT NULL

	CONSTRAINT un_bodnummer				UNIQUE		(bodnummer),
	CONSTRAINT pk_bod					PRIMARY KEY (voorwerpnummer, bodbedrag),
	CONSTRAINT chk_bodbedrag_meer_dan_1 CHECK		(bodbedrag > 1.00)
)