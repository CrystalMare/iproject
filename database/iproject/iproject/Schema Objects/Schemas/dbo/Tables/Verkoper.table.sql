CREATE TABLE [dbo].[Verkoper]
(
	gebruikersnaam		VARCHAR(16)			NOT NULL,
	bank				VARCHAR(16)			NULL,
	rekeningnummer		VARCHAR(31)			NULL,
	controleoptie		VARCHAR(10)			NOT NULL,
	creditcard			CHAR(16)			NULL

	CONSTRAINT pk_verkoper					PRIMARY KEY (gebruikersnaam),
	CONSTRAINT chk_controleoptie			CHECK		(controleoptie IN ('creditcard', 'post'))
)
