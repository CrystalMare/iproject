ALTER TABLE [dbo].[Voorwerpinrubriek]
	ADD CONSTRAINT [fk_voorwerp_in_rubriek] 
	FOREIGN KEY (voorwerpnummer)
	REFERENCES Voorwerp (voorwerpnummer)	

