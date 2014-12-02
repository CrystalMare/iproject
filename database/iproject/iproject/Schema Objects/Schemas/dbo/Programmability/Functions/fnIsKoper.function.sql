CREATE FUNCTION [dbo].[fnIsKoper]
(
	@gebruikersnaam VARCHAR(16) 
	
)
RETURNS BIT
BEGIN
	IF EXISTS
	(
		SELECT *
		FROM Verkoper
		WHERE gebruikersnaam = @gebruikersnaam
	)
	BEGIN RETURN
	(
		CAST(1 AS BIT)
	)
	END

	BEGIN RETURN
	(
		CAST(0 AS BIT)
	)
	END
END