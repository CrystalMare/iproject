CREATE TABLE [dbo].[Verkoperverificatie]
(
	gebruikersnaam	VARCHAR(16)			NOT NULL,
	aanvraagmoment	DATETIME			NOT NULL	DEFAULT GETDATE()

	CONSTRAINT pk_aanvraag_van_gebruiker	PRIMARY KEY (gebruikersnaam)
)
