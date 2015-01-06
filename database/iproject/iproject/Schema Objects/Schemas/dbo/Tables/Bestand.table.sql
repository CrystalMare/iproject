CREATE TABLE [dbo].[Bestand]
(
	filenaam			VARCHAR(100)			NOT NULL,
	voorwerpnummer		INT					NOT NULL

	CONSTRAINT pk_filenaam					PRIMARY KEY	(filenaam),
	CONSTRAINT fk_file_van_voorwerp FOREIGN KEY (voorwerpnummer) REFERENCES Voorwerp (voorwerpnummer)
)