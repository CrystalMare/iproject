CREATE TABLE [dbo].[Auctionduration]
(
	duration		INT					NOT NULL
	CONSTRAINT pk_duration PRIMARY KEY (duration),
	CONSTRAINT chk_duration_more_than_zero CHECK (duration > 0)
)
