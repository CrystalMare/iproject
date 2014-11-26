ALTER TABLE [dbo].[Verkoper]
	ADD CONSTRAINT [fk_gebruikersnaam] 
	FOREIGN KEY (gebruikersnaam)
	REFERENCES Gebruiker (gebruikersnaam)	

