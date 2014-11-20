CREATE TABLE [dbo].[Phonenumber]
(
	username		VARCHAR(16)			NOT NULL,
	phone			VARCHAR(32)			NOT NULL
	CONSTRAINT pk_phonenumber PRIMARY KEY (username, phone)
)
