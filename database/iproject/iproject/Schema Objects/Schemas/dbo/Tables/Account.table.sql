CREATE TABLE [dbo].[Account]
(
	username		VARCHAR(16)			NOT NULL,
	firstname		VARCHAR(32)			NOT NULL,
	lastname		VARCHAR(32)			NOT NULL,
	address1		VARCHAR(64)			NOT NULL,
	address2		VARCHAR(64)			NOT NULL,
	zipcode			VARCHAR(16)			NOT NULL,
	city			VARCHAR(64)			NOT NULL,
	country			VARCHAR(32)			NOT NULL	DEFAULT 'Nederland',
	birthdate		DATE				NOT NULL,
	email			VARCHAR(32)			NOT NULL,
	pass			VARCHAR(64)			NOT NULL,
	questionanswer	VARCHAR(255)		NOT NULL,
	seller			BIT					NOT NULL,
	salt			CHAR(8)				NOT NULL
	CONSTRAINT pk_username PRIMARY KEY (username),
	CONSTRAINT un_email_already_exists UNIQUE (email),
	CONSTRAINT chk_nospaces_in_username CHECK (username NOT LIKE ('% %')),
	CONSTRAINT chk_email CHECK (email LIKE ('_%@_%._%') AND email NOT LIKE ('% %'))
)
