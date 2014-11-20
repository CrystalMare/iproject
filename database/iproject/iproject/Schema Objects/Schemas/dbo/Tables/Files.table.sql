CREATE TABLE [dbo].[Files]
(
	bidid			INT IDENTITY UNIQUE	NOT NULL,
	bidammount		NUMERIC(7,2)		NOT NULL,
	stamp			DATETIME			NOT NULL,
	username		VARCHAR(16)			NOT NULL,
	auctionid		INT					NOT NULL
	CONSTRAINT pk_bid PRIMARY KEY (auctionid, bidammount),
	CONSTRAINT chk_atleast_1 CHECK (bidammount > 1.00)
)
