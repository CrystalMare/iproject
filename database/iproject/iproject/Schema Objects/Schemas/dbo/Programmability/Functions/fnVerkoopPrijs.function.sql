﻿CREATE FUNCTION [dbo].[fnVerkoopPrijs]
(
	@voorwerpnummer INT
)
RETURNS NUMERIC(7,2)
BEGIN
	IF EXISTS
	(
		SELECT *
		FROM Voorwerp
		WHERE voorwerpnummer = @voorwerpnummer AND gesloten = 1
	) AND EXISTS
	(
		SELECT *
		FROM Bod
		WHERE voorwerpnummer = @voorwerpnummer
	)
	BEGIN
		RETURN
		(
			SELECT TOP 1 bodbedrag
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