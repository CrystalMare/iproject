ALTER TABLE [dbo].[Rubriek]
	ADD CONSTRAINT [fk_subrubriek] 
	FOREIGN KEY (ouderrubriek)
	REFERENCES Rubriek (rubrieknummer)	

