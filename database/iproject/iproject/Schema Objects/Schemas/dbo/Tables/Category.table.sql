CREATE TABLE [dbo].[Category]
(
	categoryname	VARCHAR(64)			NOT NULL,
	categoryid		INT IDENTITY		NOT NULL,
	parrentcategory INT					NULL,
	sortid			INT					NOT NULL
	CONSTRAINT pk_category PRIMARY KEY (categoryid),
	CONSTRAINT chk_not_itself_as_parrent CHECK (parrentcategory != categoryid)
)
