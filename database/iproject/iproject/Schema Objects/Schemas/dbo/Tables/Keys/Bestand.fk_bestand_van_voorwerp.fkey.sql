ALTER TABLE [dbo].[Bestand]
	ADD CONSTRAINT [fk_bestand_van_voorwerp] 
	FOREIGN KEY (voorwerpnummer)
	REFERENCES Voorwerp (voorwerpnummer)	

