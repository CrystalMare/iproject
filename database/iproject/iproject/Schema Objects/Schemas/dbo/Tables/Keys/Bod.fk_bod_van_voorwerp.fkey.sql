ALTER TABLE [dbo].[Bod]
	ADD CONSTRAINT [fk_bod_van_voorwerp] 
	FOREIGN KEY (voorwerpnummer)
	REFERENCES Voorwerp (voorwerpnummer)	

