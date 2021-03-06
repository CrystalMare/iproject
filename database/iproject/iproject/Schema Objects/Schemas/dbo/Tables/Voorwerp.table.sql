﻿CREATE TABLE [dbo].[Voorwerp]
(
	voorwerpnummer			INT IDENTITY		NOT NULL,
	titel					VARCHAR(64)			NOT NULL,
	beschrijving			VARCHAR(MAX)		NOT NULL,
	startprijs				NUMERIC(7,2)		NOT NULL,
	betalingswijze			VARCHAR(255)		NOT NULL	DEFAULT 'Bank/Giro',
	betalingsinstructie		VARCHAR(255)		NULL,
	plaatsnaam				VARCHAR(35)			NOT NULL,
	land					VARCHAR(44)			NOT NULL	DEFAULT 'Netherlands',
	looptijd				TINYINT				NOT NULL,
	looptijdbeginmoment		DATETIME			NOT NULL	DEFAULT GETDATE(),
	verzendkosten			NUMERIC(7,2)		NULL,
	verzendinstructies		VARCHAR(255)		NULL,
	verkoper				VARCHAR(16)			NOT NULL,
	looptijdeindmoment		AS DATEADD(DAY, looptijd, looptijdbeginmoment),
	gesloten				AS CASE WHEN GETDATE() > DATEADD(DAY, looptijd, looptijdbeginmoment)
									THEN CAST(1 AS BIT)	ELSE CAST(0 AS BIT)	END,
	koper					AS dbo.fnKoper(voorwerpnummer),
	verkoopprijs			AS dbo.fnVerkoopPrijs(voorwerpnummer),
	email						BIT NOT NULL DEFAULT 0
	
	CONSTRAINT pk_voorwerp					PRIMARY KEY (voorwerpnummer),
	CONSTRAINT chk_titel_lang_genoeg		CHECK (LEN(titel) > 2),
	CONSTRAINT chk_startprijs_minimaal_1	CHECK (startprijs >= 1.00)
)
