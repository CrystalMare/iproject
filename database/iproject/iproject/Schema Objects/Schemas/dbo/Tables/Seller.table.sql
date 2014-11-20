CREATE TABLE [dbo].[Seller]
(
	username		VARCHAR(16)			NOT NULL,
	bankname		VARCHAR(16)			NULL,
	bankaccount		VARCHAR(43)			NOT NULL,
	verifyoption	VARCHAR(10)			NOT NULL, -- double check with casus
	creditcard		VARCHAR(16)			NULL
	CONSTRAINT pk_seller PRIMARY KEY (username),
	CONSTRAINT chk_verifyoption CHECK (verifyoption IN ('creditcard', 'postal'))
)
