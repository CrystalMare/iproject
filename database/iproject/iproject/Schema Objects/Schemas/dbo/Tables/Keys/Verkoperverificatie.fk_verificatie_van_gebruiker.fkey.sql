ALTER TABLE [dbo].[Verkoperverificatie]
	ADD CONSTRAINT [fk_verificatie_van_gebruiker] 
	FOREIGN KEY (gebruikersnaam)
	REFERENCES Gebruiker (gebruikersnaam)	

