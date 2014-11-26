ALTER TABLE [dbo].[Gebruikerstelefoon]
	ADD CONSTRAINT [fk_telefoon_van_gebruiker] 
	FOREIGN KEY (gebruikersnaam)
	REFERENCES Gebruiker (gebruikersnaam)	

