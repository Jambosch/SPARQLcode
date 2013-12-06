<!--John Holländer, AB6860-->
<?php
//Cities will be retreived from the database
$city = "Amsterdam";
$dataPortal=new OpenDataPortal();

$abstract = $dataPortal->getAbstract($city);
$clues = $dataPortal->getClues($abstract, $city);
$randomClue = $dataPortal->getRandomClue($clues);

echo $randomClue;

class OpenDataPortal extends CI_Model {
	//Gets the abstract from dbpedia
	function getAbstract($in_city)
	{
		$city = $in_city;

		require_once('sparqllib.php');
		$db = sparql_connect('http://dbpedia.org/sparql');
		$query = "SELECT * WHERE {
		  ?x rdfs:label '".$city."'@en.
		  ?x dbpedia-owl:abstract ?abstract
		  BIND(STRAFTER(STR(?x), 'http://dbpedia.org/resource/') AS ?name)
		  FILTER (LANG(?abstract) = 'en')
		}";

		$result = sparql_query($query);
		$fields = sparql_field_array($result);
		$strAbstract = "";

		while($row = sparql_fetch_array($result))
		{
		  foreach($fields as $field)
		  { 
			$strAbstract .= $row[$field];
		  }
		}
		return $strAbstract;
	}
	//Returns an array of clues
	function getClues($strAbstract, $city)
	{
		$strModAbstract = str_replace($city, "X", $strAbstract);

		$arrayClues = (explode(". ", $strModAbstract));

		return $arrayClues;
	}
	//Returns a random clue
	function getRandomClue($arrayClues)
	{
		$item = $arrayClues[rand(0, count($arrayClues)-1)];
		return $item;
	}
	
}
 




	

/*


/*
SELECT * WHERE {
  ?x rdfs:label "Venice"@en.
  ?x dbpedia-owl:abstract ?abstract.
  FILTER (LANG(?abstract) = 'en')
}
*/
/*
SELECT DISTINCT ?label WHERE {
    ?place rdf:type yago:EuropeanCountries .
    ?place rdf:type dbpedia-owl:Country 
	BIND(STRAFTER(STR(?place), 'resource/') AS ?label)
}
*/

/*
getRandomItem($items);


function getRandomItem($items)
{
	$item = $items[rand(0, count($items)-1)];
	echo $item;
	//return $fields[rand(0, count($fields)-1)];
}
	*/
?>