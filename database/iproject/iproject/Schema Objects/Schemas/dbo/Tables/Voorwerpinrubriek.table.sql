CREATE TABLE [dbo].[Voorwerpinrubriek]
(
	voorwerpnummer			INT			NOT NULL,
	rubrieknummer			INT			NOT NULL

	CONSTRAINT pk_voorwerpinrubriek		PRIMARY KEY (voorwerpnummer, rubrieknummer)
)
