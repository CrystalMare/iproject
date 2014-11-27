ALTER TABLE [dbo].[Gebruiker]
	ADD CONSTRAINT [fk_land_van_gebruiker] 
	FOREIGN KEY (land)
	REFERENCES Land (landnaam)	

