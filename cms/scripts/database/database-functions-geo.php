<?php

function get_countries_list() {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT * FROM Geo_Countries ORDER BY name ASC";
  $stmt = $db_pdo->prepare($query);
  if(!$stmt->execute())
    return NULL;
  else
    return $stmt->fetchAll();
}

// TODO refactor into ID
function get_country_cities($country_abbrev) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT * FROM Geo_Cities WHERE country_abbrev = ? ORDER BY name ASC";
  $stmt = $db_pdo->prepare($query);
  if(!$stmt->execute($country_abbrev))
    return NULL;
  else
    return $stmt->fetchAll();
}

function get_subdivision_cities($subdivision_abbrev) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT * FROM Geo_Cities WHERE subdivision_abbrev = ? ORDER BY name ASC";
  $stmt = $db_pdo->prepare($query);
  if(!$stmt->execute($subdivision_abbrev))
    return NULL;
  else
    return $stmt->fetchAll();
}

// TODO refactor into ID
function get_country_subdivisions($country_abbrev) {
  $db_pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

  $query = "SELECT * FROM Geo_SubDivisions WHERE country_abbrev = ?ORDER BY name ASC";
  $stmt = $db_pdo->prepare($query);
  if(!$stmt->execute($country_abbrev))
    return NULL;
  else
    return $stmt->fetchAll();
}

?>
