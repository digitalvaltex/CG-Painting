CG Painting – klantenreviews (data/reviews.json)
================================================

Dit bestand bevat alle reviews die via het formulier op de site zijn binnengekomen.
U kunt het met een teksteditor bewerken (UTF-8).

• Een review VERWIJDEREN: verwijder het hele object { ... } uit de JSON-array (let op komma’s).
• Een review VERBERGEN zonder te wissen: zet  "published": false  (in plaats van true).
• Na wijzigingen: geldige JSON bewaren — anders werkt de site niet tot het hersteld is.

Voorbeeld van één review:
{
  "id": "abc123",
  "created_at": "2025-03-07T12:00:00+01:00",
  "naam": "Jan",
  "plaats": "Hasselt",
  "email": "intern@alleen-voor-admin",
  "sterren": 5,
  "tekst": "Zeer tevreden.",
  "published": true
}

Het veld "email" wordt niet op de website getoond.
