CREATE TABLE [dbo].[Looptijd]
(
	looptijd		INT					NOT NULL,
	actief			BIT					NOT NULL	DEFAULT CAST(1 AS BIT)

	CONSTRAINT pk_looptijd				PRIMARY KEY (looptijd),
	CONSTRAINT chk_lengte_meer_dan_nul	CHECK		(looptijd > 0)
)
