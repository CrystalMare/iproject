CREATE TRIGGER [trgMax2Telefoon]
    ON [dbo].[Gebruikerstelefoon]
    FOR INSERT, UPDATE 
    AS 
    BEGIN
    	IF EXISTS
		(
			SELECT *
			FROM Gebruikerstelefoon
			GROUP BY gebruikersnaam
			HAVING COUNT(*) > 2
		)
		BEGIN
			RAISERROR('Maximaal 2 telefoonnummers',18,1)
			ROLLBACK
		END
    END
