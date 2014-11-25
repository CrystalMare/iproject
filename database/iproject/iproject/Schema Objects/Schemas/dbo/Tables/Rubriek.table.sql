CREATE TABLE [dbo].[Categorie]
(
	rubrieknaam			VARCHAR(50)			NOT NULL,
	rubrieknummer		INT IDENTITY		NOT NULL,
	ouderrubriek		INT					NULL,
	volgnummer			INT					NOT NULL

	CONSTRAINT pk_rubriek					PRIMARY KEY (rubrieknummer),
	CONSTRAINT chk_ouderrubriek_niet_zelf	CHECK		(ouderrubriek != rubrieknummer)
)