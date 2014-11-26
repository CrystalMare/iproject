CREATE TABLE [dbo].[Feedback]
(
	commentaar		VARCHAR(MAX)		NULL,	
	datumtijd		DATETIME			NOT NULL,
	feedbacktype	CHAR(8)				NOT NULL,
	gebruikersoort	VARCHAR(8)			NOT NULL,
	voorwerpnummer	INT					NOT NULL

	CONSTRAINT pk_feedback				PRIMARY KEY (voorwerpnummer, gebruikersoort),
	CONSTRAINT chk_feedbacktype			CHECK		(feedbacktype	 IN ('positief', 'negatief', 'neutraal')),
	CONSTRAINT chk_gebruikersoort		CHECK		(gebruikersoort IN ('koper', 'verkoper'))

)
