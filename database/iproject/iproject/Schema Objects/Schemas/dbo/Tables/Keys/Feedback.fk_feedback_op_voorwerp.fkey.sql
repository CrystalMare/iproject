ALTER TABLE [dbo].[Feedback]
	ADD CONSTRAINT [fk_feedback_op_voorwerp] 
	FOREIGN KEY (voorwerpnummer)
	REFERENCES Voorwerp (voorwerpnummer)	

