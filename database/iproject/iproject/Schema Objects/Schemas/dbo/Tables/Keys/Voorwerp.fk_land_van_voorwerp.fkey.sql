ALTER TABLE [dbo].[Voorwerp]
	ADD CONSTRAINT [fk_land_van_voorwerp] 
	FOREIGN KEY (land)
	REFERENCES Land (landnaam)	

