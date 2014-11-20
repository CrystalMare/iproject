/*
Deployment script for iproject
*/

GO
SET ANSI_NULLS, ANSI_PADDING, ANSI_WARNINGS, ARITHABORT, CONCAT_NULL_YIELDS_NULL, QUOTED_IDENTIFIER ON;

SET NUMERIC_ROUNDABORT OFF;


GO
:setvar DatabaseName "iproject"
:setvar DefaultDataPath ""
:setvar DefaultLogPath ""

GO
USE [master]

GO
:on error exit
GO
IF (DB_ID(N'$(DatabaseName)') IS NOT NULL
    AND DATABASEPROPERTYEX(N'$(DatabaseName)','Status') <> N'ONLINE')
BEGIN
    RAISERROR(N'The state of the target database, %s, is not set to ONLINE. To deploy to this database, its state must be set to ONLINE.', 16, 127,N'$(DatabaseName)') WITH NOWAIT
    RETURN
END

GO
IF (DB_ID(N'$(DatabaseName)') IS NOT NULL) 
BEGIN
    ALTER DATABASE [$(DatabaseName)]
    SET SINGLE_USER WITH ROLLBACK IMMEDIATE;
    DROP DATABASE [$(DatabaseName)];
END

GO
PRINT N'Creating $(DatabaseName)...'
GO
CREATE DATABASE [$(DatabaseName)] COLLATE SQL_Latin1_General_CP1_CI_AS
GO
EXECUTE sp_dbcmptlevel [$(DatabaseName)], 100;


GO
IF EXISTS (SELECT 1
           FROM   [master].[dbo].[sysdatabases]
           WHERE  [name] = N'$(DatabaseName)')
    BEGIN
        ALTER DATABASE [$(DatabaseName)]
            SET ANSI_NULLS ON,
                ANSI_PADDING ON,
                ANSI_WARNINGS ON,
                ARITHABORT ON,
                CONCAT_NULL_YIELDS_NULL ON,
                NUMERIC_ROUNDABORT OFF,
                QUOTED_IDENTIFIER ON,
                ANSI_NULL_DEFAULT ON,
                CURSOR_DEFAULT LOCAL,
                RECOVERY FULL,
                CURSOR_CLOSE_ON_COMMIT OFF,
                AUTO_CREATE_STATISTICS ON,
                AUTO_SHRINK OFF,
                AUTO_UPDATE_STATISTICS ON,
                RECURSIVE_TRIGGERS OFF 
            WITH ROLLBACK IMMEDIATE;
        ALTER DATABASE [$(DatabaseName)]
            SET AUTO_CLOSE OFF 
            WITH ROLLBACK IMMEDIATE;
    END


GO
IF EXISTS (SELECT 1
           FROM   [master].[dbo].[sysdatabases]
           WHERE  [name] = N'$(DatabaseName)')
    BEGIN
        ALTER DATABASE [$(DatabaseName)]
            SET ALLOW_SNAPSHOT_ISOLATION OFF;
    END


GO
IF EXISTS (SELECT 1
           FROM   [master].[dbo].[sysdatabases]
           WHERE  [name] = N'$(DatabaseName)')
    BEGIN
        ALTER DATABASE [$(DatabaseName)]
            SET READ_COMMITTED_SNAPSHOT OFF;
    END


GO
IF EXISTS (SELECT 1
           FROM   [master].[dbo].[sysdatabases]
           WHERE  [name] = N'$(DatabaseName)')
    BEGIN
        ALTER DATABASE [$(DatabaseName)]
            SET AUTO_UPDATE_STATISTICS_ASYNC OFF,
                PAGE_VERIFY NONE,
                DATE_CORRELATION_OPTIMIZATION OFF,
                DISABLE_BROKER,
                PARAMETERIZATION SIMPLE,
                SUPPLEMENTAL_LOGGING OFF 
            WITH ROLLBACK IMMEDIATE;
    END


GO
IF IS_SRVROLEMEMBER(N'sysadmin') = 1
    BEGIN
        IF EXISTS (SELECT 1
                   FROM   [master].[dbo].[sysdatabases]
                   WHERE  [name] = N'$(DatabaseName)')
            BEGIN
                EXECUTE sp_executesql N'ALTER DATABASE [$(DatabaseName)]
    SET TRUSTWORTHY OFF,
        DB_CHAINING OFF 
    WITH ROLLBACK IMMEDIATE';
            END
    END
ELSE
    BEGIN
        PRINT N'The database settings cannot be modified. You must be a SysAdmin to apply these settings.';
    END


GO
IF IS_SRVROLEMEMBER(N'sysadmin') = 1
    BEGIN
        IF EXISTS (SELECT 1
                   FROM   [master].[dbo].[sysdatabases]
                   WHERE  [name] = N'$(DatabaseName)')
            BEGIN
                EXECUTE sp_executesql N'ALTER DATABASE [$(DatabaseName)]
    SET HONOR_BROKER_PRIORITY OFF 
    WITH ROLLBACK IMMEDIATE';
            END
    END
ELSE
    BEGIN
        PRINT N'The database settings cannot be modified. You must be a SysAdmin to apply these settings.';
    END


GO
USE [$(DatabaseName)]

GO
IF fulltextserviceproperty(N'IsFulltextInstalled') = 1
    EXECUTE sp_fulltext_database 'enable';


GO
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

declare @procName varchar(500)
declare cur cursor 

for select [name] from sys.objects where type = 'p'
open cur
fetch next from cur into @procName
while @@fetch_status = 0
begin
    exec('drop procedure ' + @procName)
    fetch next from cur into @procName
end
close cur
deallocate cur
GO

DECLARE @SQLCmd nvarchar(1000) 
DECLARE @Trig varchar(500)
DECLARE @sch varchar(500)

DECLARE TGCursor CURSOR FOR

SELECT ISNULL(tbl.name, vue.name) AS [schemaName]
     , trg.name AS triggerName
FROM sys.triggers trg
LEFT OUTER JOIN (SELECT tparent.object_id, ts.name 
                 FROM sys.tables tparent 
                 INNER JOIN sys.schemas ts ON TS.schema_id = tparent.SCHEMA_ID) 
                 AS tbl ON tbl.OBJECT_ID = trg.parent_id
LEFT OUTER JOIN (SELECT vparent.object_id, vs.name 
                 FROM sys.views vparent 
                 INNER JOIN sys.schemas vs ON vs.schema_id = vparent.SCHEMA_ID) 
                 AS vue ON vue.OBJECT_ID = trg.parent_id
 
OPEN TGCursor
FETCH NEXT FROM TGCursor INTO @sch,@Trig
WHILE @@FETCH_STATUS = 0
BEGIN

SET @SQLCmd = N'DROP TRIGGER [' + @sch + '].[' + @Trig + ']'
EXEC sp_executesql @SQLCmd
PRINT @SQLCmd

FETCH next FROM TGCursor INTO @sch,@Trig
END

CLOSE TGCursor
DEALLOCATE TGCursor
GO

GO
PRINT N'Creating [dbo].[Account]...';


GO
CREATE TABLE [dbo].[Account] (
    [username]       VARCHAR (16)  NOT NULL,
    [firstname]      VARCHAR (32)  NOT NULL,
    [lastname]       VARCHAR (32)  NOT NULL,
    [address1]       VARCHAR (64)  NOT NULL,
    [address2]       VARCHAR (64)  NOT NULL,
    [zipcode]        VARCHAR (16)  NOT NULL,
    [city]           VARCHAR (64)  NOT NULL,
    [country]        VARCHAR (32)  NOT NULL,
    [birthdate]      DATE          NOT NULL,
    [email]          VARCHAR (32)  NOT NULL,
    [pass]           VARCHAR (64)  NOT NULL,
    [questionanswer] VARCHAR (255) NOT NULL,
    [seller]         BIT           NOT NULL,
    [salt]           CHAR (8)      NOT NULL,
    CONSTRAINT [pk_username] PRIMARY KEY CLUSTERED ([username] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF),
    CONSTRAINT [un_email_already_exists] UNIQUE NONCLUSTERED ([email] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating [dbo].[Auctionduration]...';


GO
CREATE TABLE [dbo].[Auctionduration] (
    [duration] INT NOT NULL,
    CONSTRAINT [pk_duration] PRIMARY KEY CLUSTERED ([duration] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating [dbo].[Bid]...';


GO
CREATE TABLE [dbo].[Bid] (
    [column_1] INT NOT NULL,
    [column_2] INT NULL
);


GO
PRINT N'Creating [dbo].[Category]...';


GO
CREATE TABLE [dbo].[Category] (
    [categoryname]    VARCHAR (64) NOT NULL,
    [categoryid]      INT          IDENTITY (1, 1) NOT NULL,
    [parrentcategory] INT          NULL,
    [sortid]          INT          NOT NULL,
    CONSTRAINT [pk_category] PRIMARY KEY CLUSTERED ([categoryid] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating [dbo].[Files]...';


GO
CREATE TABLE [dbo].[Files] (
    [bidid]      INT            IDENTITY (1, 1) NOT NULL,
    [bidammount] NUMERIC (7, 2) NOT NULL,
    [stamp]      DATETIME       NOT NULL,
    [username]   VARCHAR (16)   NOT NULL,
    [auctionid]  INT            NOT NULL,
    UNIQUE NONCLUSTERED ([bidid] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF),
    CONSTRAINT [pk_bid] PRIMARY KEY CLUSTERED ([auctionid] ASC, [bidammount] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating [dbo].[Phonenumber]...';


GO
CREATE TABLE [dbo].[Phonenumber] (
    [username] VARCHAR (16) NOT NULL,
    [phone]    VARCHAR (32) NOT NULL,
    CONSTRAINT [pk_phonenumber] PRIMARY KEY CLUSTERED ([username] ASC, [phone] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating [dbo].[Seller]...';


GO
CREATE TABLE [dbo].[Seller] (
    [username]     VARCHAR (16) NOT NULL,
    [bankname]     VARCHAR (16) NULL,
    [bankaccount]  VARCHAR (43) NOT NULL,
    [verifyoption] VARCHAR (10) NOT NULL,
    [creditcard]   VARCHAR (16) NULL,
    CONSTRAINT [pk_seller] PRIMARY KEY CLUSTERED ([username] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating On column: country...';


GO
ALTER TABLE [dbo].[Account]
    ADD DEFAULT 'Nederland' FOR [country];


GO
PRINT N'Creating chk_email...';


GO
ALTER TABLE [dbo].[Account] WITH NOCHECK
    ADD CONSTRAINT [chk_email] CHECK (email LIKE ('_%@_%._%') AND email NOT LIKE ('% %'));


GO
PRINT N'Creating chk_nospaces_in_username...';


GO
ALTER TABLE [dbo].[Account] WITH NOCHECK
    ADD CONSTRAINT [chk_nospaces_in_username] CHECK (username NOT LIKE ('% %'));


GO
PRINT N'Creating chk_duration_more_than_zero...';


GO
ALTER TABLE [dbo].[Auctionduration] WITH NOCHECK
    ADD CONSTRAINT [chk_duration_more_than_zero] CHECK (duration > 0);


GO
PRINT N'Creating chk_not_itself_as_parrent...';


GO
ALTER TABLE [dbo].[Category] WITH NOCHECK
    ADD CONSTRAINT [chk_not_itself_as_parrent] CHECK (parrentcategory != categoryid);


GO
PRINT N'Creating chk_atleast_1...';


GO
ALTER TABLE [dbo].[Files] WITH NOCHECK
    ADD CONSTRAINT [chk_atleast_1] CHECK (bidammount > 1.00);


GO
PRINT N'Creating chk_verifyoption...';


GO
ALTER TABLE [dbo].[Seller] WITH NOCHECK
    ADD CONSTRAINT [chk_verifyoption] CHECK (verifyoption IN ('creditcard', 'postal'));


GO
-- Refactoring step to update target server with deployed transaction logs
CREATE TABLE  [dbo].[__RefactorLog] (OperationKey UNIQUEIDENTIFIER NOT NULL PRIMARY KEY)
GO
sp_addextendedproperty N'microsoft_database_tools_support', N'refactoring log', N'schema', N'dbo', N'table', N'__RefactorLog'
GO

GO
/*
Post-Deployment Script Template							
--------------------------------------------------------------------------------------
 This file contains SQL statements that will be appended to the build script.		
 Use SQLCMD syntax to include a file in the post-deployment script.			
 Example:      :r .\myfile.sql								
 Use SQLCMD syntax to reference a variable in the post-deployment script.		
 Example:      :setvar TableName MyTable							
               SELECT * FROM [$(TableName)]					
--------------------------------------------------------------------------------------
*/

GO
PRINT N'Checking existing data against newly created constraints';


GO
USE [$(DatabaseName)];


GO
ALTER TABLE [dbo].[Account] WITH CHECK CHECK CONSTRAINT [chk_email];

ALTER TABLE [dbo].[Account] WITH CHECK CHECK CONSTRAINT [chk_nospaces_in_username];

ALTER TABLE [dbo].[Auctionduration] WITH CHECK CHECK CONSTRAINT [chk_duration_more_than_zero];

ALTER TABLE [dbo].[Category] WITH CHECK CHECK CONSTRAINT [chk_not_itself_as_parrent];

ALTER TABLE [dbo].[Files] WITH CHECK CHECK CONSTRAINT [chk_atleast_1];

ALTER TABLE [dbo].[Seller] WITH CHECK CHECK CONSTRAINT [chk_verifyoption];


GO
IF EXISTS (SELECT 1
           FROM   [master].[dbo].[sysdatabases]
           WHERE  [name] = N'$(DatabaseName)')
    BEGIN
        DECLARE @VarDecimalSupported AS BIT;
        SELECT @VarDecimalSupported = 0;
        IF ((ServerProperty(N'EngineEdition') = 3)
            AND (((@@microsoftversion / power(2, 24) = 9)
                  AND (@@microsoftversion & 0xffff >= 3024))
                 OR ((@@microsoftversion / power(2, 24) = 10)
                     AND (@@microsoftversion & 0xffff >= 1600))))
            SELECT @VarDecimalSupported = 1;
        IF (@VarDecimalSupported > 0)
            BEGIN
                EXECUTE sp_db_vardecimal_storage_format N'$(DatabaseName)', 'ON';
            END
    END


GO
ALTER DATABASE [$(DatabaseName)]
    SET MULTI_USER 
    WITH ROLLBACK IMMEDIATE;


GO
