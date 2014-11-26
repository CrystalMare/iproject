CREATE FUNCTION [dbo].[fnKoper]
(
	@voorwerpnummer INT
)
RETURNS VARCHAR(16)
BEGIN
	IF EXISTS
	(
		SELECT gesloten
		FROM Voorwerp
		WHERE voorwerpnummer = @voorwerpnummer
	)
	BEGIN
		RETURN
		(
			SELECT TOP 1 gebruikersnaam
			FROM Bod
			WHERE voorwerpnummer = @voorwerpnummer
			ORDER BY bodbedrag DESC
		)
	END
	BEGIN
		RETURN
		(
			NULL
		)
	END
END