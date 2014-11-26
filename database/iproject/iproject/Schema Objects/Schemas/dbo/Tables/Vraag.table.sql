CREATE TABLE [dbo].[Vraag]
(
	vraagnummer		INT IDENTITY		NOT NULL,
	vraag			VARCHAR(255)		NOT NULL

	CONSTRAINT pk_vraag					PRIMARY KEY (vraagnummer),
	CONSTRAINT un_vraag					UNIQUE		(vraag)
)
