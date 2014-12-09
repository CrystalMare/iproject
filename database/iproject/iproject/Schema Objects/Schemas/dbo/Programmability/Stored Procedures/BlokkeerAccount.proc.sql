CREATE PROCEDURE [dbo].[BlokkeerAccount]
	@gebruikersnaam VARCHAR(16)
AS
	UPDATE [dbo].[Gebruiker]
	SET voornaam = '--',
		achternaam = '--',
		adresregel1 = '--',
		adresregel2 = '--',
		postcode = '--',
		geboortedag = GETDATE(),
		verwijderd = CAST(1 AS BIT)
	WHERE gebruikersnaam = @gebruikersnaam;

	UPDATE [dbo].[Verkoper]
	SET bank = NULL,
		rekeningnummer = NULL,
		creditcard = NULL
	WHERE Verkoper.gebruikersnaam = @gebruikersnaam;

	DELETE FROM [dbo].[Gebruikerstelefoon]
	WHERE gebruikersnaam = @gebruikersnaam;