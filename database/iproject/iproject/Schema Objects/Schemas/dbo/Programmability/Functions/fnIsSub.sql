ALTER FUNCTION fnIsSub
(
  @Kind INT,
  @Ouder INT,
  @Turn TINYINT
)
RETURNS BIT
BEGIN
  IF (@Turn > 10)
    RETURN 0;
  IF (@Kind = @Ouder)
    BEGIN
      RETURN 1
    END
  ELSE
    BEGIN
      SET @Kind = (SELECT ouderrubriek FROM Rubriek WHERE rubrieknummer = @Kind)
      IF (dbo.fnIsSub(@Kind, @Ouder, @Turn + 1) = 1)
        RETURN 1
      ELSE
        RETURN 0;
    END
  RETURN 0
END