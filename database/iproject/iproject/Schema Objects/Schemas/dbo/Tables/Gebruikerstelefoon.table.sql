CREATE TABLE [dbo].[Gebruikerstelefoon]
(
	gebruikersnaam		VARCHAR(16)			NOT NULL,
	telefoonnummer		VARCHAR(15)			NOT NULL

	CONSTRAINT pk_uniek_nummer PRIMARY KEY (gebruikersnaam, telefoonnummer)
)
