ALTER TABLE [dbo].[Voorwerp]
	ADD CONSTRAINT [fk_looptijd_van_voorwerp] 
	FOREIGN KEY (looptijd)
	REFERENCES Looptijd (looptijd)	

