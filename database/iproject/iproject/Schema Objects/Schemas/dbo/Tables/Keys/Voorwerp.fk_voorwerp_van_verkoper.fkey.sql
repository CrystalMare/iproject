ALTER TABLE [dbo].[Voorwerp]
	ADD CONSTRAINT [fk_voorwerp_van_verkoper] 
	FOREIGN KEY (verkoper)
	REFERENCES Verkoper (gebruikersnaam)	

