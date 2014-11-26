ALTER TABLE [dbo].[Voorwerpinrubriek]
	ADD CONSTRAINT [fk_rubriek_van_voorwerp] 
	FOREIGN KEY (rubrieknummer)
	REFERENCES Rubriek (rubrieknummer)	

