ALTER TABLE [dbo].[Gebruiker]
	ADD CONSTRAINT [fk_vraag] 
	FOREIGN KEY (vraag)
	REFERENCES Vraag (vraagnummer)	

