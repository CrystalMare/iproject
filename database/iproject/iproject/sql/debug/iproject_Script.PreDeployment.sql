/*
 Pre-Deployment Script Template							
--------------------------------------------------------------------------------------
 This file contains SQL statements that will be executed before the build script.	
 Use SQLCMD syntax to include a file in the pre-deployment script.			
 Example:      :r .\myfile.sql								
 Use SQLCMD syntax to reference a variable in the pre-deployment script.		
 Example:      :setvar TableName MyTable							
               SELECT * FROM [$(TableName)]					
--------------------------------------------------------------------------------------
*/

USE iproject


DECLARE @table_schema varchar(100)
       ,@table_name varchar(100)
       ,@constraint_schema varchar(100)
       ,@constraint_name varchar(100)
       ,@cmd nvarchar(200)
 
 
--
-- drop all the constraints
--
DECLARE constraint_cursor CURSOR FOR
  select CONSTRAINT_SCHEMA, CONSTRAINT_NAME, TABLE_SCHEMA, TABLE_NAME
    from INFORMATION_SCHEMA.TABLE_CONSTRAINTS
   where TABLE_NAME != 'sysdiagrams'
   order by CONSTRAINT_TYPE asc -- FOREIGN KEY, then PRIMARY KEY
      
 
OPEN constraint_cursor
FETCH NEXT FROM constraint_cursor INTO @constraint_schema, @constraint_name, @table_schema, @table_name
 
WHILE @@FETCH_STATUS = 0 
BEGIN
     SELECT @cmd = 'ALTER TABLE [' + @table_schema + '].[' + @table_name + '] DROP CONSTRAINT [' + @constraint_name + ']'
     --select @cmd
     EXEC sp_executesql @cmd
 
     FETCH NEXT FROM constraint_cursor INTO @constraint_schema, @constraint_name, @table_schema, @table_name
END
 
CLOSE constraint_cursor
DEALLOCATE constraint_cursor
 
 
 
--
-- drop all the tables
--
DECLARE table_cursor CURSOR FOR
  select TABLE_SCHEMA, TABLE_NAME
    from INFORMATION_SCHEMA.TABLES
   where TABLE_NAME != 'sysdiagrams'
     and TABLE_TYPE != 'VIEW'
 
OPEN table_cursor
FETCH NEXT FROM table_cursor INTO @table_schema, @table_name
 
WHILE @@FETCH_STATUS = 0 
BEGIN
     SELECT @cmd = 'DROP TABLE [' + @table_schema + '].[' + @table_name + ']'
     --select @cmd
     EXEC sp_executesql @cmd
 
 
     FETCH NEXT FROM table_cursor INTO @table_schema, @table_name
END
 
CLOSE table_cursor 
DEALLOCATE table_cursor
GO

CREATE TABLE Files (
	fileid			VARCHAR(64)			NOT NULL,
	itemid			INT					NOT NULL
);

CREATE TABLE Bid (
	bidid			INT IDENTITY UNIQUE	NOT NULL,
	bidammount		NUMERIC(7,2)		NOT NULL,
	stamp			DATETIME			NOT NULL,
	username		VARCHAR(16)			NOT NULL,
	itemid			INT					NOT NULL
	CONSTRAINT pk_bid PRIMARY KEY (itemid, bidammount),
	CONSTRAINT chk_atleast_1 CHECK (bidammount > 1.00)
);

CREATE TABLE Feedback (
	comment			VARCHAR(MAX)		NOT NULL,
	stamp			DATETIME			NOT NULL,
	feedbacktype	CHAR(1)				NOT NULL,
	seller			BIT					NOT NULL,
	itemid			INT					NOT NULL
	CONSTRAINT pk_feedback PRIMARY KEY (itemid, seller),
	CONSTRAINT chk_feedbacktype CHECK (feedbacktype IN ('+', '-', '|'))
);

CREATE TABLE Account (
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
);
