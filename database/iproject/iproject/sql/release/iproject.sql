/*
Deployment script for iproject
*/

GO
SET ANSI_NULLS, ANSI_PADDING, ANSI_WARNINGS, ARITHABORT, CONCAT_NULL_YIELDS_NULL, QUOTED_IDENTIFIER ON;

SET NUMERIC_ROUNDABORT OFF;


GO
:setvar DatabaseName "iproject"
:setvar DefaultDataPath "e:\Program Files\Microsoft SQL Server\MSSQL10_50.SQLEXPRESS\MSSQL\DATA\"
:setvar DefaultLogPath "e:\Program Files\Microsoft SQL Server\MSSQL10_50.SQLEXPRESS\MSSQL\DATA\"

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

IF NOT EXISTS (SELECT 1 FROM [master].[dbo].[sysdatabases] WHERE [name] = N'$(DatabaseName)')
BEGIN
    RAISERROR(N'You cannot deploy this update script to target SVENLAPTOP\SQLEXPRESS. The database for which this script was built, iproject, does not exist on this server.', 16, 127) WITH NOWAIT
    RETURN
END

GO

IF (@@servername != 'SVENLAPTOP\SQLEXPRESS')
BEGIN
    RAISERROR(N'The server name in the build script %s does not match the name of the target server %s. Verify whether your database project settings are correct and whether your build script is up to date.', 16, 127,N'SVENLAPTOP\SQLEXPRESS',@@servername) WITH NOWAIT
    RETURN
END

GO

IF CAST(DATABASEPROPERTY(N'$(DatabaseName)','IsReadOnly') as bit) = 1
BEGIN
    RAISERROR(N'You cannot deploy this update script because the database for which it was built, %s , is set to READ_ONLY.', 16, 127, N'$(DatabaseName)') WITH NOWAIT
    RETURN
END

GO
USE [$(DatabaseName)]

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

GO
PRINT N'Dropping chk_email...';


GO
ALTER TABLE [dbo].[Account] DROP CONSTRAINT [chk_email];


GO
PRINT N'Dropping chk_nospaces_in_username...';


GO
ALTER TABLE [dbo].[Account] DROP CONSTRAINT [chk_nospaces_in_username];


GO
PRINT N'Dropping chk_verifyoption...';


GO
ALTER TABLE [dbo].[Seller] DROP CONSTRAINT [chk_verifyoption];


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
PRINT N'Creating chk_verifyoption...';


GO
ALTER TABLE [dbo].[Seller] WITH NOCHECK
    ADD CONSTRAINT [chk_verifyoption] CHECK (verifyoption IN ('creditcard', 'postal'));


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

ALTER TABLE [dbo].[Seller] WITH CHECK CHECK CONSTRAINT [chk_verifyoption];


GO
