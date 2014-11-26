ALTER TABLE [dbo].[Bod]
	ADD CONSTRAINT [fk_bod_gebruiker] 
	FOREIGN KEY (gebruikersnaam)
	REFERENCES Gebruiker (gebruikersnaam)	

