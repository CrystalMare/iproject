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
    [land]           VARCHAR (44)  NOT NULL,
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
PRINT N'Creating [dbo].[Land]...';


GO
CREATE TABLE [dbo].[Land] (
    [landnaam] VARCHAR (44) NOT NULL,
    CONSTRAINT [pk_land] PRIMARY KEY CLUSTERED ([landnaam] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating [dbo].[Looptijd]...';


GO
CREATE TABLE [dbo].[Looptijd] (
    [looptijd] TINYINT NOT NULL,
    [actief]   BIT     NOT NULL,
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
    ADD DEFAULT 'Netherlands' FOR [land];


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
PRINT N'Creating fk_bod_gebruiker...';


GO
ALTER TABLE [dbo].[Bod] WITH NOCHECK
    ADD CONSTRAINT [fk_bod_gebruiker] FOREIGN KEY ([gebruikersnaam]) REFERENCES [dbo].[Gebruiker] ([gebruikersnaam]) ON DELETE NO ACTION ON UPDATE NO ACTION;


GO
PRINT N'Creating fk_land_van_gebruiker...';


GO
ALTER TABLE [dbo].[Gebruiker] WITH NOCHECK
    ADD CONSTRAINT [fk_land_van_gebruiker] FOREIGN KEY ([land]) REFERENCES [dbo].[Land] ([landnaam]) ON DELETE NO ACTION ON UPDATE NO ACTION;


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
PRINT N'Creating fk_rubriek_van_voorwerp...';


GO
ALTER TABLE [dbo].[Voorwerpinrubriek] WITH NOCHECK
    ADD CONSTRAINT [fk_rubriek_van_voorwerp] FOREIGN KEY ([rubrieknummer]) REFERENCES [dbo].[Rubriek] ([rubrieknummer]) ON DELETE NO ACTION ON UPDATE NO ACTION;


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
PRINT N'Creating [dbo].[trgMax2Telefoon]...';


GO
CREATE TRIGGER [trgMax2Telefoon]
    ON [dbo].[Gebruikerstelefoon]
    FOR INSERT, UPDATE 
    AS 
    BEGIN
    	IF EXISTS
		(
			SELECT *
			FROM Gebruikerstelefoon
			GROUP BY gebruikersnaam
			HAVING COUNT(*) > 2
		)
		BEGIN
			RAISERROR('Maximaal 2 telefoonnummers',18,1)
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
		SELECT *
		FROM Voorwerp
		WHERE voorwerpnummer = @voorwerpnummer AND gesloten = 1
	) AND EXISTS
	(
		SELECT *
		FROM Bod
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
PRINT N'Creating [dbo].[fnVerkoopPrijs]...';


GO
CREATE FUNCTION [dbo].[fnVerkoopPrijs]
(
	@voorwerpnummer INT
)
RETURNS NUMERIC(7,2)
BEGIN
	IF EXISTS
	(
		SELECT *
		FROM Voorwerp
		WHERE voorwerpnummer = @voorwerpnummer AND gesloten = 1
	) AND EXISTS
	(
		SELECT *
		FROM Bod
		WHERE voorwerpnummer = @voorwerpnummer
	)
	BEGIN
		RETURN
		(
			SELECT TOP 1 bodbedrag
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
    [land]                VARCHAR (44)   NOT NULL,
    [looptijd]            TINYINT        NOT NULL,
    [looptijdbeginmoment] DATETIME       NOT NULL,
    [verzendkosten]       NUMERIC (7, 2) NULL,
    [verzendinstructies]  VARCHAR (255)  NULL,
    [verkoper]            VARCHAR (16)   NOT NULL,
    [looptijdeindmoment]  AS             DATEADD(DAY, looptijd, looptijdbeginmoment),
    [gesloten]            AS             CASE WHEN GETDATE() > DATEADD(DAY, looptijd, looptijdbeginmoment) THEN CAST (1 AS BIT) ELSE CAST (0 AS BIT) END,
    [koper]               AS             dbo.fnKoper(voorwerpnummer),
    [verkoopprijs]        AS             dbo.fnVerkoopPrijs(voorwerpnummer),
    CONSTRAINT [pk_voorwerp] PRIMARY KEY CLUSTERED ([voorwerpnummer] ASC) WITH (ALLOW_PAGE_LOCKS = ON, ALLOW_ROW_LOCKS = ON, PAD_INDEX = OFF, IGNORE_DUP_KEY = OFF, STATISTICS_NORECOMPUTE = OFF)
);


GO
PRINT N'Creating fk_bestand_van_voorwerp...';


GO
ALTER TABLE [dbo].[Bestand] WITH NOCHECK
    ADD CONSTRAINT [fk_bestand_van_voorwerp] FOREIGN KEY ([voorwerpnummer]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON DELETE NO ACTION ON UPDATE NO ACTION;


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
PRINT N'Creating fk_land_van_voorwerp...';


GO
ALTER TABLE [dbo].[Voorwerp] WITH NOCHECK
    ADD CONSTRAINT [fk_land_van_voorwerp] FOREIGN KEY ([land]) REFERENCES [dbo].[Land] ([landnaam]) ON DELETE NO ACTION ON UPDATE NO ACTION;


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
PRINT N'Creating fk_voorwerp_in_rubriek...';


GO
ALTER TABLE [dbo].[Voorwerpinrubriek] WITH NOCHECK
    ADD CONSTRAINT [fk_voorwerp_in_rubriek] FOREIGN KEY ([voorwerpnummer]) REFERENCES [dbo].[Voorwerp] ([voorwerpnummer]) ON DELETE NO ACTION ON UPDATE NO ACTION;


GO
PRINT N'Creating On column: betalingswijze...';


GO
ALTER TABLE [dbo].[Voorwerp]
    ADD DEFAULT 'Bank/Giro' FOR [betalingswijze];


GO
PRINT N'Creating On column: land...';


GO
ALTER TABLE [dbo].[Voorwerp]
    ADD DEFAULT 'Netherlands' FOR [land];


GO
PRINT N'Creating On column: looptijdbeginmoment...';


GO
ALTER TABLE [dbo].[Voorwerp]
    ADD DEFAULT GETDATE() FOR [looptijdbeginmoment];


GO
PRINT N'Creating chk_startprijs_minimaal_1...';


GO
ALTER TABLE [dbo].[Voorwerp] WITH NOCHECK
    ADD CONSTRAINT [chk_startprijs_minimaal_1] CHECK (startprijs >= 1.00);


GO
PRINT N'Creating chk_titel_lang_genoeg...';


GO
ALTER TABLE [dbo].[Voorwerp] WITH NOCHECK
    ADD CONSTRAINT [chk_titel_lang_genoeg] CHECK (LEN(titel) > 2);


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

INSERT INTO Land (landnaam) VALUES ('Andorra');
INSERT INTO Land (landnaam) VALUES ('United Arab Emirates');
INSERT INTO Land (landnaam) VALUES ('Afghanistan');
INSERT INTO Land (landnaam) VALUES ('Antigua and Barbuda');
INSERT INTO Land (landnaam) VALUES ('Anguilla');
INSERT INTO Land (landnaam) VALUES ('Albania');
INSERT INTO Land (landnaam) VALUES ('Armenia');
INSERT INTO Land (landnaam) VALUES ('Angola');
INSERT INTO Land (landnaam) VALUES ('Antarctica');
INSERT INTO Land (landnaam) VALUES ('Argentina');
INSERT INTO Land (landnaam) VALUES ('American Samoa');
INSERT INTO Land (landnaam) VALUES ('Austria');
INSERT INTO Land (landnaam) VALUES ('Australia');
INSERT INTO Land (landnaam) VALUES ('Aruba');
INSERT INTO Land (landnaam) VALUES ('Åland');
INSERT INTO Land (landnaam) VALUES ('Azerbaijan');
INSERT INTO Land (landnaam) VALUES ('Bosnia and Herzegovina');
INSERT INTO Land (landnaam) VALUES ('Barbados');
INSERT INTO Land (landnaam) VALUES ('Bangladesh');
INSERT INTO Land (landnaam) VALUES ('Belgium');
INSERT INTO Land (landnaam) VALUES ('Burkina Faso');
INSERT INTO Land (landnaam) VALUES ('Bulgaria');
INSERT INTO Land (landnaam) VALUES ('Bahrain');
INSERT INTO Land (landnaam) VALUES ('Burundi');
INSERT INTO Land (landnaam) VALUES ('Benin');
INSERT INTO Land (landnaam) VALUES ('Saint Barthélemy');
INSERT INTO Land (landnaam) VALUES ('Bermuda');
INSERT INTO Land (landnaam) VALUES ('Brunei');
INSERT INTO Land (landnaam) VALUES ('Bolivia');
INSERT INTO Land (landnaam) VALUES ('Bonaire');
INSERT INTO Land (landnaam) VALUES ('Brazil');
INSERT INTO Land (landnaam) VALUES ('Bahamas');
INSERT INTO Land (landnaam) VALUES ('Bhutan');
INSERT INTO Land (landnaam) VALUES ('Bouvet Island');
INSERT INTO Land (landnaam) VALUES ('Botswana');
INSERT INTO Land (landnaam) VALUES ('Belarus');
INSERT INTO Land (landnaam) VALUES ('Belize');
INSERT INTO Land (landnaam) VALUES ('Canada');
INSERT INTO Land (landnaam) VALUES ('Cocos [Keeling] Islands');
INSERT INTO Land (landnaam) VALUES ('Democratic Republic of the Congo');
INSERT INTO Land (landnaam) VALUES ('Central African Republic');
INSERT INTO Land (landnaam) VALUES ('Republic of the Congo');
INSERT INTO Land (landnaam) VALUES ('Switzerland');
INSERT INTO Land (landnaam) VALUES ('Ivory Coast');
INSERT INTO Land (landnaam) VALUES ('Cook Islands');
INSERT INTO Land (landnaam) VALUES ('Chile');
INSERT INTO Land (landnaam) VALUES ('Cameroon');
INSERT INTO Land (landnaam) VALUES ('China');
INSERT INTO Land (landnaam) VALUES ('Colombia');
INSERT INTO Land (landnaam) VALUES ('Costa Rica');
INSERT INTO Land (landnaam) VALUES ('Cuba');
INSERT INTO Land (landnaam) VALUES ('Cape Verde');
INSERT INTO Land (landnaam) VALUES ('Curacao');
INSERT INTO Land (landnaam) VALUES ('Christmas Island');
INSERT INTO Land (landnaam) VALUES ('Cyprus');
INSERT INTO Land (landnaam) VALUES ('Czech Republic');
INSERT INTO Land (landnaam) VALUES ('Germany');
INSERT INTO Land (landnaam) VALUES ('Djibouti');
INSERT INTO Land (landnaam) VALUES ('Denmark');
INSERT INTO Land (landnaam) VALUES ('Dominica');
INSERT INTO Land (landnaam) VALUES ('Dominican Republic');
INSERT INTO Land (landnaam) VALUES ('Algeria');
INSERT INTO Land (landnaam) VALUES ('Ecuador');
INSERT INTO Land (landnaam) VALUES ('Estonia');
INSERT INTO Land (landnaam) VALUES ('Egypt');
INSERT INTO Land (landnaam) VALUES ('Western Sahara');
INSERT INTO Land (landnaam) VALUES ('Eritrea');
INSERT INTO Land (landnaam) VALUES ('Spain');
INSERT INTO Land (landnaam) VALUES ('Ethiopia');
INSERT INTO Land (landnaam) VALUES ('Finland');
INSERT INTO Land (landnaam) VALUES ('Fiji');
INSERT INTO Land (landnaam) VALUES ('Falkland Islands');
INSERT INTO Land (landnaam) VALUES ('Micronesia');
INSERT INTO Land (landnaam) VALUES ('Faroe Islands');
INSERT INTO Land (landnaam) VALUES ('France');
INSERT INTO Land (landnaam) VALUES ('Gabon');
INSERT INTO Land (landnaam) VALUES ('United Kingdom');
INSERT INTO Land (landnaam) VALUES ('Grenada');
INSERT INTO Land (landnaam) VALUES ('Georgia');
INSERT INTO Land (landnaam) VALUES ('French Guiana');
INSERT INTO Land (landnaam) VALUES ('Guernsey');
INSERT INTO Land (landnaam) VALUES ('Ghana');
INSERT INTO Land (landnaam) VALUES ('Gibraltar');
INSERT INTO Land (landnaam) VALUES ('Greenland');
INSERT INTO Land (landnaam) VALUES ('Gambia');
INSERT INTO Land (landnaam) VALUES ('Guinea');
INSERT INTO Land (landnaam) VALUES ('Guadeloupe');
INSERT INTO Land (landnaam) VALUES ('Equatorial Guinea');
INSERT INTO Land (landnaam) VALUES ('Greece');
INSERT INTO Land (landnaam) VALUES ('South Georgia and the South Sandwich Islands');
INSERT INTO Land (landnaam) VALUES ('Guatemala');
INSERT INTO Land (landnaam) VALUES ('Guam');
INSERT INTO Land (landnaam) VALUES ('Guinea-Bissau');
INSERT INTO Land (landnaam) VALUES ('Guyana');
INSERT INTO Land (landnaam) VALUES ('Hong Kong');
INSERT INTO Land (landnaam) VALUES ('Heard Island and McDonald Islands');
INSERT INTO Land (landnaam) VALUES ('Honduras');
INSERT INTO Land (landnaam) VALUES ('Croatia');
INSERT INTO Land (landnaam) VALUES ('Haiti');
INSERT INTO Land (landnaam) VALUES ('Hungary');
INSERT INTO Land (landnaam) VALUES ('Indonesia');
INSERT INTO Land (landnaam) VALUES ('Ireland');
INSERT INTO Land (landnaam) VALUES ('Israel');
INSERT INTO Land (landnaam) VALUES ('Isle of Man');
INSERT INTO Land (landnaam) VALUES ('India');
INSERT INTO Land (landnaam) VALUES ('British Indian Ocean Territory');
INSERT INTO Land (landnaam) VALUES ('Iraq');
INSERT INTO Land (landnaam) VALUES ('Iran');
INSERT INTO Land (landnaam) VALUES ('Iceland');
INSERT INTO Land (landnaam) VALUES ('Italy');
INSERT INTO Land (landnaam) VALUES ('Jersey');
INSERT INTO Land (landnaam) VALUES ('Jamaica');
INSERT INTO Land (landnaam) VALUES ('Jordan');
INSERT INTO Land (landnaam) VALUES ('Japan');
INSERT INTO Land (landnaam) VALUES ('Kenya');
INSERT INTO Land (landnaam) VALUES ('Kyrgyzstan');
INSERT INTO Land (landnaam) VALUES ('Cambodia');
INSERT INTO Land (landnaam) VALUES ('Kiribati');
INSERT INTO Land (landnaam) VALUES ('Comoros');
INSERT INTO Land (landnaam) VALUES ('Saint Kitts and Nevis');
INSERT INTO Land (landnaam) VALUES ('North Korea');
INSERT INTO Land (landnaam) VALUES ('South Korea');
INSERT INTO Land (landnaam) VALUES ('Kuwait');
INSERT INTO Land (landnaam) VALUES ('Cayman Islands');
INSERT INTO Land (landnaam) VALUES ('Kazakhstan');
INSERT INTO Land (landnaam) VALUES ('Laos');
INSERT INTO Land (landnaam) VALUES ('Lebanon');
INSERT INTO Land (landnaam) VALUES ('Saint Lucia');
INSERT INTO Land (landnaam) VALUES ('Liechtenstein');
INSERT INTO Land (landnaam) VALUES ('Sri Lanka');
INSERT INTO Land (landnaam) VALUES ('Liberia');
INSERT INTO Land (landnaam) VALUES ('Lesotho');
INSERT INTO Land (landnaam) VALUES ('Lithuania');
INSERT INTO Land (landnaam) VALUES ('Luxembourg');
INSERT INTO Land (landnaam) VALUES ('Latvia');
INSERT INTO Land (landnaam) VALUES ('Libya');
INSERT INTO Land (landnaam) VALUES ('Morocco');
INSERT INTO Land (landnaam) VALUES ('Monaco');
INSERT INTO Land (landnaam) VALUES ('Moldova');
INSERT INTO Land (landnaam) VALUES ('Montenegro');
INSERT INTO Land (landnaam) VALUES ('Saint Martin');
INSERT INTO Land (landnaam) VALUES ('Madagascar');
INSERT INTO Land (landnaam) VALUES ('Marshall Islands');
INSERT INTO Land (landnaam) VALUES ('Macedonia');
INSERT INTO Land (landnaam) VALUES ('Mali');
INSERT INTO Land (landnaam) VALUES ('Myanmar [Burma]');
INSERT INTO Land (landnaam) VALUES ('Mongolia');
INSERT INTO Land (landnaam) VALUES ('Macao');
INSERT INTO Land (landnaam) VALUES ('Northern Mariana Islands');
INSERT INTO Land (landnaam) VALUES ('Martinique');
INSERT INTO Land (landnaam) VALUES ('Mauritania');
INSERT INTO Land (landnaam) VALUES ('Montserrat');
INSERT INTO Land (landnaam) VALUES ('Malta');
INSERT INTO Land (landnaam) VALUES ('Mauritius');
INSERT INTO Land (landnaam) VALUES ('Maldives');
INSERT INTO Land (landnaam) VALUES ('Malawi');
INSERT INTO Land (landnaam) VALUES ('Mexico');
INSERT INTO Land (landnaam) VALUES ('Malaysia');
INSERT INTO Land (landnaam) VALUES ('Mozambique');
INSERT INTO Land (landnaam) VALUES ('Namibia');
INSERT INTO Land (landnaam) VALUES ('New Caledonia');
INSERT INTO Land (landnaam) VALUES ('Niger');
INSERT INTO Land (landnaam) VALUES ('Norfolk Island');
INSERT INTO Land (landnaam) VALUES ('Nigeria');
INSERT INTO Land (landnaam) VALUES ('Nicaragua');
INSERT INTO Land (landnaam) VALUES ('Netherlands');
INSERT INTO Land (landnaam) VALUES ('Norway');
INSERT INTO Land (landnaam) VALUES ('Nepal');
INSERT INTO Land (landnaam) VALUES ('Nauru');
INSERT INTO Land (landnaam) VALUES ('Niue');
INSERT INTO Land (landnaam) VALUES ('New Zealand');
INSERT INTO Land (landnaam) VALUES ('Oman');
INSERT INTO Land (landnaam) VALUES ('Panama');
INSERT INTO Land (landnaam) VALUES ('Peru');
INSERT INTO Land (landnaam) VALUES ('French Polynesia');
INSERT INTO Land (landnaam) VALUES ('Papua New Guinea');
INSERT INTO Land (landnaam) VALUES ('Philippines');
INSERT INTO Land (landnaam) VALUES ('Pakistan');
INSERT INTO Land (landnaam) VALUES ('Poland');
INSERT INTO Land (landnaam) VALUES ('Saint Pierre and Miquelon');
INSERT INTO Land (landnaam) VALUES ('Pitcairn Islands');
INSERT INTO Land (landnaam) VALUES ('Puerto Rico');
INSERT INTO Land (landnaam) VALUES ('Palestine');
INSERT INTO Land (landnaam) VALUES ('Portugal');
INSERT INTO Land (landnaam) VALUES ('Palau');
INSERT INTO Land (landnaam) VALUES ('Paraguay');
INSERT INTO Land (landnaam) VALUES ('Qatar');
INSERT INTO Land (landnaam) VALUES ('Réunion');
INSERT INTO Land (landnaam) VALUES ('Romania');
INSERT INTO Land (landnaam) VALUES ('Serbia');
INSERT INTO Land (landnaam) VALUES ('Russia');
INSERT INTO Land (landnaam) VALUES ('Rwanda');
INSERT INTO Land (landnaam) VALUES ('Saudi Arabia');
INSERT INTO Land (landnaam) VALUES ('Solomon Islands');
INSERT INTO Land (landnaam) VALUES ('Seychelles');
INSERT INTO Land (landnaam) VALUES ('Sudan');
INSERT INTO Land (landnaam) VALUES ('Sweden');
INSERT INTO Land (landnaam) VALUES ('Singapore');
INSERT INTO Land (landnaam) VALUES ('Saint Helena');
INSERT INTO Land (landnaam) VALUES ('Slovenia');
INSERT INTO Land (landnaam) VALUES ('Svalbard and Jan Mayen');
INSERT INTO Land (landnaam) VALUES ('Slovakia');
INSERT INTO Land (landnaam) VALUES ('Sierra Leone');
INSERT INTO Land (landnaam) VALUES ('San Marino');
INSERT INTO Land (landnaam) VALUES ('Senegal');
INSERT INTO Land (landnaam) VALUES ('Somalia');
INSERT INTO Land (landnaam) VALUES ('Suriname');
INSERT INTO Land (landnaam) VALUES ('South Sudan');
INSERT INTO Land (landnaam) VALUES ('São Tomé and Príncipe');
INSERT INTO Land (landnaam) VALUES ('El Salvador');
INSERT INTO Land (landnaam) VALUES ('Sint Maarten');
INSERT INTO Land (landnaam) VALUES ('Syria');
INSERT INTO Land (landnaam) VALUES ('Swaziland');
INSERT INTO Land (landnaam) VALUES ('Turks and Caicos Islands');
INSERT INTO Land (landnaam) VALUES ('Chad');
INSERT INTO Land (landnaam) VALUES ('French Southern Territories');
INSERT INTO Land (landnaam) VALUES ('Togo');
INSERT INTO Land (landnaam) VALUES ('Thailand');
INSERT INTO Land (landnaam) VALUES ('Tajikistan');
INSERT INTO Land (landnaam) VALUES ('Tokelau');
INSERT INTO Land (landnaam) VALUES ('East Timor');
INSERT INTO Land (landnaam) VALUES ('Turkmenistan');
INSERT INTO Land (landnaam) VALUES ('Tunisia');
INSERT INTO Land (landnaam) VALUES ('Tonga');
INSERT INTO Land (landnaam) VALUES ('Turkey');
INSERT INTO Land (landnaam) VALUES ('Trinidad and Tobago');
INSERT INTO Land (landnaam) VALUES ('Tuvalu');
INSERT INTO Land (landnaam) VALUES ('Taiwan');
INSERT INTO Land (landnaam) VALUES ('Tanzania');
INSERT INTO Land (landnaam) VALUES ('Ukraine');
INSERT INTO Land (landnaam) VALUES ('Uganda');
INSERT INTO Land (landnaam) VALUES ('U.S. Minor Outlying Islands');
INSERT INTO Land (landnaam) VALUES ('United States');
INSERT INTO Land (landnaam) VALUES ('Uruguay');
INSERT INTO Land (landnaam) VALUES ('Uzbekistan');
INSERT INTO Land (landnaam) VALUES ('Vatican City');
INSERT INTO Land (landnaam) VALUES ('Saint Vincent and the Grenadines');
INSERT INTO Land (landnaam) VALUES ('Venezuela');
INSERT INTO Land (landnaam) VALUES ('British Virgin Islands');
INSERT INTO Land (landnaam) VALUES ('U.S. Virgin Islands');
INSERT INTO Land (landnaam) VALUES ('Vietnam');
INSERT INTO Land (landnaam) VALUES ('Vanuatu');
INSERT INTO Land (landnaam) VALUES ('Wallis and Futuna');
INSERT INTO Land (landnaam) VALUES ('Samoa');
INSERT INTO Land (landnaam) VALUES ('Kosovo');
INSERT INTO Land (landnaam) VALUES ('Yemen');
INSERT INTO Land (landnaam) VALUES ('Mayotte');
INSERT INTO Land (landnaam) VALUES ('South Africa');
INSERT INTO Land (landnaam) VALUES ('Zambia');
INSERT INTO Land (landnaam) VALUES ('Zimbabwe');

GO
PRINT N'Checking existing data against newly created constraints';


GO
USE [$(DatabaseName)];


GO
ALTER TABLE [dbo].[Bod] WITH CHECK CHECK CONSTRAINT [fk_bod_gebruiker];

ALTER TABLE [dbo].[Gebruiker] WITH CHECK CHECK CONSTRAINT [fk_land_van_gebruiker];

ALTER TABLE [dbo].[Gebruiker] WITH CHECK CHECK CONSTRAINT [fk_vraag];

ALTER TABLE [dbo].[Gebruikerstelefoon] WITH CHECK CHECK CONSTRAINT [fk_telefoon_van_gebruiker];

ALTER TABLE [dbo].[Rubriek] WITH CHECK CHECK CONSTRAINT [fk_subrubriek];

ALTER TABLE [dbo].[Verkoper] WITH CHECK CHECK CONSTRAINT [fk_gebruikersnaam];

ALTER TABLE [dbo].[Voorwerpinrubriek] WITH CHECK CHECK CONSTRAINT [fk_rubriek_van_voorwerp];

ALTER TABLE [dbo].[Bod] WITH CHECK CHECK CONSTRAINT [chk_bodbedrag_meer_dan_1];

ALTER TABLE [dbo].[Feedback] WITH CHECK CHECK CONSTRAINT [chk_feedbacktype];

ALTER TABLE [dbo].[Feedback] WITH CHECK CHECK CONSTRAINT [chk_gebruikersoort];

ALTER TABLE [dbo].[Gebruiker] WITH CHECK CHECK CONSTRAINT [chk_gebruikersnaam_spaties];

ALTER TABLE [dbo].[Gebruiker] WITH CHECK CHECK CONSTRAINT [chk_mailbox];

ALTER TABLE [dbo].[Looptijd] WITH CHECK CHECK CONSTRAINT [chk_lengte_meer_dan_nul];

ALTER TABLE [dbo].[Rubriek] WITH CHECK CHECK CONSTRAINT [chk_ouderrubriek_niet_zelf];

ALTER TABLE [dbo].[Verkoper] WITH CHECK CHECK CONSTRAINT [chk_controleoptie];

ALTER TABLE [dbo].[Bestand] WITH CHECK CHECK CONSTRAINT [fk_bestand_van_voorwerp];

ALTER TABLE [dbo].[Bod] WITH CHECK CHECK CONSTRAINT [fk_bod_van_voorwerp];

ALTER TABLE [dbo].[Feedback] WITH CHECK CHECK CONSTRAINT [fk_feedback_op_voorwerp];

ALTER TABLE [dbo].[Voorwerp] WITH CHECK CHECK CONSTRAINT [fk_land_van_voorwerp];

ALTER TABLE [dbo].[Voorwerp] WITH CHECK CHECK CONSTRAINT [fk_looptijd_van_voorwerp];

ALTER TABLE [dbo].[Voorwerp] WITH CHECK CHECK CONSTRAINT [fk_voorwerp_van_verkoper];

ALTER TABLE [dbo].[Voorwerpinrubriek] WITH CHECK CHECK CONSTRAINT [fk_voorwerp_in_rubriek];

ALTER TABLE [dbo].[Voorwerp] WITH CHECK CHECK CONSTRAINT [chk_startprijs_minimaal_1];

ALTER TABLE [dbo].[Voorwerp] WITH CHECK CHECK CONSTRAINT [chk_titel_lang_genoeg];


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
