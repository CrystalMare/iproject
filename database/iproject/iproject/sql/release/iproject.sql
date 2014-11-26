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
IF (DB_ID(N'$(DatabaseName)') IS NOT NULL) 
BEGIN
    ALTER DATABASE [$(DatabaseName)]
    SET SINGLE_USER WITH ROLLBACK IMMEDIATE;
    DROP DATABASE [$(DatabaseName)];
END

GO
PRINT N'Creating $(DatabaseName)...'
GO
CREATE DATABASE [$(DatabaseName)]
    ON 
    PRIMARY(NAME = [iproject], FILENAME = N'$(DefaultDataPath)iproject.mdf')
    LOG ON (NAME = [iproject_log], FILENAME = N'$(DefaultLogPath)iproject_log.ldf') COLLATE SQL_Latin1_General_CP1_CI_AS
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

GO
PRINT N'Dropping Permission...';


GO
REVOKE CONNECT TO [dbo]
    AS [dbo];


GO
PRINT N'Dropping [AutoCreatedLocal]...';


GO
DROP ROUTE [AutoCreatedLocal];


GO
PRINT N'Creating [dbo].[Bestand]...';


GO
CREATE TABLE [dbo].[Bestand] (
    [filenaam]       VARCHAR (24) NOT NULL,
    [voorwerpnummer] INT          NOT NULL,
    CONSTRAINT [pk_filenaam] PRIMARY KEY CLUSTERED ([filenaam] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating [dbo].[Bod]...';


GO
CREATE TABLE [dbo].[Bod] (
    [bodnummer]      INT            IDENTITY (1, 1) NOT NULL,
    [bodbedrag]      NUMERIC (7, 2) NOT NULL,
    [datumtijd]      DATETIME       NOT NULL,
    [gebruikersnaam] VARCHAR (16)   NOT NULL,
    [voorwerpnummer] INT            NOT NULL,
    CONSTRAINT [pk_bod] PRIMARY KEY CLUSTERED ([voorwerpnummer] ASC, [bodbedrag] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF),
    CONSTRAINT [un_bodnummer] UNIQUE NONCLUSTERED ([bodnummer] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating [dbo].[Feedback]...';


GO
CREATE TABLE [dbo].[Feedback] (
    [commentaar]     VARCHAR (MAX) NULL,
    [datumtijd]      DATETIME      NOT NULL,
    [feedbacktype]   CHAR (8)      NOT NULL,
    [gebruikersoort] VARCHAR (8)   NOT NULL,
    [voorwerpnummer] INT           NOT NULL,
    CONSTRAINT [pk_feedback] PRIMARY KEY CLUSTERED ([voorwerpnummer] ASC, [gebruikersoort] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating [dbo].[Gebruiker]...';


GO
CREATE TABLE [dbo].[Gebruiker] (
    [gebruikersnaam] VARCHAR (16)  NOT NULL,
    [voornaam]       VARCHAR (35)  NOT NULL,
    [achternaam]     VARCHAR (35)  NOT NULL,
    [adresregel1]    VARCHAR (35)  NOT NULL,
    [adresregel2]    VARCHAR (35)  NOT NULL,
    [postcode]       VARCHAR (9)   NOT NULL,
    [plaatsnaam]     VARCHAR (35)  NOT NULL,
    [land]           VARCHAR (35)  NOT NULL,
    [geboortedag]    DATE          NOT NULL,
    [mailbox]        VARCHAR (255) NOT NULL,
    [wachtwoord]     CHAR (64)     NOT NULL,
    [antwoordtekst]  VARCHAR (255) NOT NULL,
    [vraag]          INT           NOT NULL,
    [verkoper]       BIT           NOT NULL,
    [salt]           CHAR (8)      NOT NULL,
    [registratie]    DATE          NOT NULL,
    CONSTRAINT [pk_gebruikersnaam] PRIMARY KEY CLUSTERED ([gebruikersnaam] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF),
    CONSTRAINT [un_email_bestaat] UNIQUE NONCLUSTERED ([mailbox] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF),
    CONSTRAINT [un_salt] UNIQUE NONCLUSTERED ([salt] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating [dbo].[Gebruikerstelefoon]...';


GO
CREATE TABLE [dbo].[Gebruikerstelefoon] (
    [gebruikersnaam] VARCHAR (16) NOT NULL,
    [telefoonnummer] VARCHAR (15) NOT NULL,
    CONSTRAINT [pk_uniek_nummer] PRIMARY KEY CLUSTERED ([gebruikersnaam] ASC, [telefoonnummer] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating [dbo].[Looptijd]...';


GO
CREATE TABLE [dbo].[Looptijd] (
    [looptijd] INT NOT NULL,
    [actief]   BIT NOT NULL,
    CONSTRAINT [pk_looptijd] PRIMARY KEY CLUSTERED ([looptijd] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating [dbo].[Rubriek]...';


GO
CREATE TABLE [dbo].[Rubriek] (
    [rubrieknaam]   VARCHAR (50) NOT NULL,
    [rubrieknummer] INT          IDENTITY (1, 1) NOT NULL,
    [ouderrubriek]  INT          NULL,
    [volgnummer]    INT          NOT NULL,
    CONSTRAINT [pk_rubriek] PRIMARY KEY CLUSTERED ([rubrieknummer] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating [dbo].[Verkoper]...';


GO
CREATE TABLE [dbo].[Verkoper] (
    [gebruikersnaam] VARCHAR (16) NOT NULL,
    [bank]           VARCHAR (16) NULL,
    [rekeningnummer] VARCHAR (31) NULL,
    [controleoptie]  VARCHAR (10) NOT NULL,
    [creditcard]     CHAR (16)    NULL,
    CONSTRAINT [pk_verkoper] PRIMARY KEY CLUSTERED ([gebruikersnaam] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating [dbo].[Voorwerp]...';


GO
CREATE TABLE [dbo].[Voorwerp] (
    [voorwerpnummer]      INT            IDENTITY (1, 1) NOT NULL,
    [titel]               VARCHAR (64)   NOT NULL,
    [beschrijving]        VARCHAR (MAX)  NOT NULL,
    [startprijs]          NUMERIC (7, 2) NOT NULL,
    [betalingswijze]      VARCHAR (255)  NOT NULL,
    [betalingsinstructie] VARCHAR (255)  NULL,
    [plaatsnaam]          VARCHAR (35)   NOT NULL,
    [land]                VARCHAR (35)   NOT NULL,
    [looptijd]            INT            NOT NULL,
    [looptijdbeginmoment] DATETIME       NOT NULL,
    [verzendkosten]       NUMERIC (7, 2) NULL,
    [verzendinstructies]  VARCHAR (255)  NULL,
    [verkoper]            VARCHAR (16)   NOT NULL,
    [looptijdeindmoment]  AS             DATEADD(DAY, looptijd, looptijdbeginmoment),
    [gesloten]            AS             CASE WHEN GETDATE() > DATEADD(DAY, looptijd, looptijdbeginmoment) THEN CAST (1 AS BIT) ELSE CAST (0 AS BIT) END,
    CONSTRAINT [pk_voorwerp] PRIMARY KEY CLUSTERED ([voorwerpnummer] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating [dbo].[Voorwerpinrubriek]...';


GO
CREATE TABLE [dbo].[Voorwerpinrubriek] (
    [voorwerpnummer] INT NOT NULL,
    [rubrieknummer]  INT NOT NULL,
    CONSTRAINT [pk_voorwerpinrubriek] PRIMARY KEY CLUSTERED ([voorwerpnummer] ASC, [rubrieknummer] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating [dbo].[Vraag]...';


GO
CREATE TABLE [dbo].[Vraag] (
    [vraagnummer] INT           IDENTITY (1, 1) NOT NULL,
    [vraag]       VARCHAR (255) NOT NULL,
    CONSTRAINT [pk_vraag] PRIMARY KEY CLUSTERED ([vraagnummer] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF),
    CONSTRAINT [un_vraag] UNIQUE NONCLUSTERED ([vraag] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating On column: datumtijd...';


GO
ALTER TABLE [dbo].[Bod]
    ADD DEFAULT GETDATE() FOR [datumtijd];


GO
PRINT N'Creating On column: land...';


GO
ALTER TABLE [dbo].[Gebruiker]
    ADD DEFAULT 'Nederland' FOR [land];


GO
PRINT N'Creating On column: registratie...';


GO
ALTER TABLE [dbo].[Gebruiker]
    ADD DEFAULT GETDATE() FOR [registratie];


GO
PRINT N'Creating On column: actief...';


GO
ALTER TABLE [dbo].[Looptijd]
    ADD DEFAULT CAST(1 AS BIT) FOR [actief];


GO
PRINT N'Creating On column: looptijdbeginmoment...';


GO
ALTER TABLE [dbo].[Voorwerp]
    ADD DEFAULT GETDATE() FOR [looptijdbeginmoment];


GO
PRINT N'Creating fk_bestand_van_voorwerp...';


GO
ALTER TABLE [dbo].[Bestand] WITH NOCHECK
    ADD CONSTRAINT [fk_bestand_van_voorwerp] FOREIGN KEY ([voorwerpnummer]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON DELETE NO ACTION ON UPDATE NO ACTION;


GO
PRINT N'Creating fk_bod_gebruiker...';


GO
ALTER TABLE [dbo].[Bod] WITH NOCHECK
    ADD CONSTRAINT [fk_bod_gebruiker] FOREIGN KEY ([gebruikersnaam]) REFERENCES [dbo].[Gebruiker] ([gebruikersnaam]) ON DELETE NO ACTION ON UPDATE NO ACTION;


GO
PRINT N'Creating fk_bod_van_voorwerp...';


GO
ALTER TABLE [dbo].[Bod] WITH NOCHECK
    ADD CONSTRAINT [fk_bod_van_voorwerp] FOREIGN KEY ([voorwerpnummer]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON DELETE NO ACTION ON UPDATE NO ACTION;


GO
PRINT N'Creating fk_feedback_op_voorwerp...';


GO
ALTER TABLE [dbo].[Feedback] WITH NOCHECK
    ADD CONSTRAINT [fk_feedback_op_voorwerp] FOREIGN KEY ([voorwerpnummer]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON DELETE NO ACTION ON UPDATE NO ACTION;


GO
PRINT N'Creating fk_vraag...';


GO
ALTER TABLE [dbo].[Gebruiker] WITH NOCHECK
    ADD CONSTRAINT [fk_vraag] FOREIGN KEY ([vraag]) REFERENCES [dbo].[Vraag] ([vraagnummer]) ON DELETE NO ACTION ON UPDATE NO ACTION;


GO
PRINT N'Creating fk_telefoon_van_gebruiker...';


GO
ALTER TABLE [dbo].[Gebruikerstelefoon] WITH NOCHECK
    ADD CONSTRAINT [fk_telefoon_van_gebruiker] FOREIGN KEY ([gebruikersnaam]) REFERENCES [dbo].[Gebruiker] ([gebruikersnaam]) ON DELETE NO ACTION ON UPDATE NO ACTION;


GO
PRINT N'Creating fk_subrubriek...';


GO
ALTER TABLE [dbo].[Rubriek] WITH NOCHECK
    ADD CONSTRAINT [fk_subrubriek] FOREIGN KEY ([ouderrubriek]) REFERENCES [dbo].[Rubriek] ([rubrieknummer]) ON DELETE NO ACTION ON UPDATE NO ACTION;


GO
PRINT N'Creating fk_gebruikersnaam...';


GO
ALTER TABLE [dbo].[Verkoper] WITH NOCHECK
    ADD CONSTRAINT [fk_gebruikersnaam] FOREIGN KEY ([gebruikersnaam]) REFERENCES [dbo].[Gebruiker] ([gebruikersnaam]) ON DELETE NO ACTION ON UPDATE NO ACTION;


GO
PRINT N'Creating fk_looptijd_van_voorwerp...';


GO
ALTER TABLE [dbo].[Voorwerp] WITH NOCHECK
    ADD CONSTRAINT [fk_looptijd_van_voorwerp] FOREIGN KEY ([looptijd]) REFERENCES [dbo].[Looptijd] ([looptijd]) ON DELETE NO ACTION ON UPDATE NO ACTION;


GO
PRINT N'Creating fk_voorwerp_van_verkoper...';


GO
ALTER TABLE [dbo].[Voorwerp] WITH NOCHECK
    ADD CONSTRAINT [fk_voorwerp_van_verkoper] FOREIGN KEY ([verkoper]) REFERENCES [dbo].[Verkoper] ([gebruikersnaam]) ON DELETE NO ACTION ON UPDATE NO ACTION;


GO
PRINT N'Creating fk_rubriek_van_voorwerp...';


GO
ALTER TABLE [dbo].[Voorwerpinrubriek] WITH NOCHECK
    ADD CONSTRAINT [fk_rubriek_van_voorwerp] FOREIGN KEY ([rubrieknummer]) REFERENCES [dbo].[Rubriek] ([rubrieknummer]) ON DELETE NO ACTION ON UPDATE NO ACTION;


GO
PRINT N'Creating fk_voorwerp_in_rubriek...';


GO
ALTER TABLE [dbo].[Voorwerpinrubriek] WITH NOCHECK
    ADD CONSTRAINT [fk_voorwerp_in_rubriek] FOREIGN KEY ([voorwerpnummer]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON DELETE NO ACTION ON UPDATE NO ACTION;


GO
PRINT N'Creating chk_bodbedrag_meer_dan_1...';


GO
ALTER TABLE [dbo].[Bod] WITH NOCHECK
    ADD CONSTRAINT [chk_bodbedrag_meer_dan_1] CHECK (bodbedrag > 1.00);


GO
PRINT N'Creating chk_feedbacktype...';


GO
ALTER TABLE [dbo].[Feedback] WITH NOCHECK
    ADD CONSTRAINT [chk_feedbacktype] CHECK (feedbacktype	 IN ('positief', 'negatief', 'neutraal'));


GO
PRINT N'Creating chk_gebruikersoort...';


GO
ALTER TABLE [dbo].[Feedback] WITH NOCHECK
    ADD CONSTRAINT [chk_gebruikersoort] CHECK (gebruikersoort IN ('koper', 'verkoper'));


GO
PRINT N'Creating chk_gebruikersnaam_spaties...';


GO
ALTER TABLE [dbo].[Gebruiker] WITH NOCHECK
    ADD CONSTRAINT [chk_gebruikersnaam_spaties] CHECK (gebruikersnaam NOT LIKE ('% %'));


GO
PRINT N'Creating chk_mailbox...';


GO
ALTER TABLE [dbo].[Gebruiker] WITH NOCHECK
    ADD CONSTRAINT [chk_mailbox] CHECK (mailbox LIKE ('_%@_%._%') AND mailbox NOT LIKE ('% %'));


GO
PRINT N'Creating chk_lengte_meer_dan_nul...';


GO
ALTER TABLE [dbo].[Looptijd] WITH NOCHECK
    ADD CONSTRAINT [chk_lengte_meer_dan_nul] CHECK (looptijd > 0);


GO
PRINT N'Creating chk_ouderrubriek_niet_zelf...';


GO
ALTER TABLE [dbo].[Rubriek] WITH NOCHECK
    ADD CONSTRAINT [chk_ouderrubriek_niet_zelf] CHECK (ouderrubriek != rubrieknummer);


GO
PRINT N'Creating chk_controleoptie...';


GO
ALTER TABLE [dbo].[Verkoper] WITH NOCHECK
    ADD CONSTRAINT [chk_controleoptie] CHECK (controleoptie IN ('creditcard', 'post'));


GO
PRINT N'Creating chk_startprijs_minimaal_1...';


GO
ALTER TABLE [dbo].[Voorwerp] WITH NOCHECK
    ADD CONSTRAINT [chk_startprijs_minimaal_1] CHECK (startprijs >= 1.00);


GO
PRINT N'Creating [dbo].[trgMax4Bestanden]...';


GO
CREATE TRIGGER [trgMax4Bestanden]
    ON [dbo].[Bestand]
    FOR INSERT, UPDATE 
    AS 
    BEGIN
    	IF EXISTS
		(
			SELECT *
			FROM Bestand
			GROUP BY voorwerpnummer
			HAVING COUNT(*) > 4
		)
		BEGIN
			RAISERROR('Maximaal 4 Afbeeldingen',18,1)
			ROLLBACK
		END
    END
GO
PRINT N'Creating [dbo].[fnKoper]...';


GO
CREATE FUNCTION [dbo].[fnKoper]
(
	@voorwerpnummer INT
)
RETURNS VARCHAR(16)
BEGIN
	IF EXISTS
	(
		SELECT gesloten
		FROM Voorwerp
		WHERE voorwerpnummer = @voorwerpnummer
	)
	BEGIN
		RETURN
		(
			SELECT TOP 1 gebruikersnaam
			FROM Bod
			WHERE voorwerpnummer = @voorwerpnummer
			ORDER BY bodbedrag DESC
		)
	END
	BEGIN
		RETURN
		(
			NULL
		)
	END
END
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
ALTER TABLE [dbo].[Bestand] WITH CHECK CHECK CONSTRAINT [fk_bestand_van_voorwerp];

ALTER TABLE [dbo].[Bod] WITH CHECK CHECK CONSTRAINT [fk_bod_gebruiker];

ALTER TABLE [dbo].[Bod] WITH CHECK CHECK CONSTRAINT [fk_bod_van_voorwerp];

ALTER TABLE [dbo].[Feedback] WITH CHECK CHECK CONSTRAINT [fk_feedback_op_voorwerp];

ALTER TABLE [dbo].[Gebruiker] WITH CHECK CHECK CONSTRAINT [fk_vraag];

ALTER TABLE [dbo].[Gebruikerstelefoon] WITH CHECK CHECK CONSTRAINT [fk_telefoon_van_gebruiker];

ALTER TABLE [dbo].[Rubriek] WITH CHECK CHECK CONSTRAINT [fk_subrubriek];

ALTER TABLE [dbo].[Verkoper] WITH CHECK CHECK CONSTRAINT [fk_gebruikersnaam];

ALTER TABLE [dbo].[Voorwerp] WITH CHECK CHECK CONSTRAINT [fk_looptijd_van_voorwerp];

ALTER TABLE [dbo].[Voorwerp] WITH CHECK CHECK CONSTRAINT [fk_voorwerp_van_verkoper];

ALTER TABLE [dbo].[Voorwerpinrubriek] WITH CHECK CHECK CONSTRAINT [fk_rubriek_van_voorwerp];

ALTER TABLE [dbo].[Voorwerpinrubriek] WITH CHECK CHECK CONSTRAINT [fk_voorwerp_in_rubriek];

ALTER TABLE [dbo].[Bod] WITH CHECK CHECK CONSTRAINT [chk_bodbedrag_meer_dan_1];

ALTER TABLE [dbo].[Feedback] WITH CHECK CHECK CONSTRAINT [chk_feedbacktype];

ALTER TABLE [dbo].[Feedback] WITH CHECK CHECK CONSTRAINT [chk_gebruikersoort];

ALTER TABLE [dbo].[Gebruiker] WITH CHECK CHECK CONSTRAINT [chk_gebruikersnaam_spaties];

ALTER TABLE [dbo].[Gebruiker] WITH CHECK CHECK CONSTRAINT [chk_mailbox];

ALTER TABLE [dbo].[Looptijd] WITH CHECK CHECK CONSTRAINT [chk_lengte_meer_dan_nul];

ALTER TABLE [dbo].[Rubriek] WITH CHECK CHECK CONSTRAINT [chk_ouderrubriek_niet_zelf];

ALTER TABLE [dbo].[Verkoper] WITH CHECK CHECK CONSTRAINT [chk_controleoptie];

ALTER TABLE [dbo].[Voorwerp] WITH CHECK CHECK CONSTRAINT [chk_startprijs_minimaal_1];


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
