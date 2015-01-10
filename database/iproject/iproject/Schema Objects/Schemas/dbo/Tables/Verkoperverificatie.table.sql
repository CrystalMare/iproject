CREATE TABLE [dbo].[Verkoperverificatie]
(
	gebruikersnaam	VARCHAR(16)			NOT NULL,
	aanvraagmoment	DATETIME			NOT NULL	DEFAULT GETDATE(),
	verstuurd 			BIT						NOT NULL DEFAULT 0

	CONSTRAINT pk_aanvraag_van_gebruiker	PRIMARY KEY (gebruikersnaam)
)
