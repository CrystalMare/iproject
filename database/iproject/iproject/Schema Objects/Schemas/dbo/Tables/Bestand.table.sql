CREATE TABLE [dbo].[Bestand]
(
	filenaam			VARCHAR(24)			NOT NULL,
	voorwerpnummer		INT					NOT NULL

	CONSTRAINT pk_filenaam					PRIMARY KEY	(filenaam)
)