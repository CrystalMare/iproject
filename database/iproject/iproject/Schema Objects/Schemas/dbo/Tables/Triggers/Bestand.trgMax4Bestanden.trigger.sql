CREATE TRIGGER [trgMax4Bestanden]
    ON [dbo].[Bestand]
    FOR INSERT, UPDATE 
    AS 
    BEGIN
    	IF EXISTS
		(
			SELECT *
			FROM Bestand
			GROUP BY voorwerpnummer
			HAVING COUNT(*) > 4
		)
		BEGIN
			RAISERROR('Maximaal 4 Afbeeldingen',18,1)
			ROLLBACK
		END
    END
