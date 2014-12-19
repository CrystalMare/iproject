CREATE FUNCTION CreateSearch( @titel VARCHAR(255), @rubrieknummer INT)
  RETURNS @SimG TABLE
  (
  titel         VARCHAR(255),
  rubrieknummer INT,
  voorwerpnummer INT
  )
AS
  BEGIN
    INSERT @SimG
      SELECT V.titel, VR.rubrieknummer, V.voorwerpnummer
      FROM Voorwerp V INNER JOIN Voorwerpinrubriek VR ON V.voorwerpnummer = VR.voorwerpnummer
      WHERE v.titel LIKE ('%' + @titel + '%') AND VR.rubrieknummer = @rubrieknummer
    RETURN
  END
