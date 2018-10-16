<?php

require_once (dirname(__FILE__) . '/couchsimple.php');
require_once (dirname(__FILE__) . '/lib.php');


	
// taxon 
if (1)
{
	$url = 'http://www.boldsystems.org/index.php/API_Public/combined';

	$parameters = array(
		//'taxon' => 'Agnotecous',
		//'taxon' => 'Limnonectes',
//		'taxon' => 'Xenopus',
//		'taxon' => 'Biomphalaria',
		//'taxon' => 'Pristimantis',
		//'taxon' => 'Oreobates',
		'taxon' => 'Pingasa',
		'marker' => 'COI-5P',
		'format' => 'json'
		);	
}

// BIN
if (0)
{
	$url = 'http://www.boldsystems.org/index.php/API_Public/combined';

	$parameters = array(
		'bin' => 'BOLD:AAD6226',
		'marker' => 'COI-5P',
		'format' => 'json'
		);	
}

// geo
if (0)
{
	$url = 'http://www.boldsystems.org/index.php/API_Public/combined';

	$parameters = array(
		'geo' => 'New Caledonia',
		'marker' => 'COI-5P',
		'format' => 'json'
		);	
}
	
$url .= '?' . http_build_query($parameters);

$hash = md5($url);

$filename = 'cache/' . $hash . '.tsv';

if (file_exists(filename))
{
	$data = file_get_contents($filename);
}
else
{
	$data = get($url);
	file_put_contents($filename, $data);
}

if ($data)
{
	//echo $data;
	
	$force = true;
	
	$obj = json_decode($data);
	
	//print_r($obj);
	
	if (isset($obj->bold_records))
	{
		if (isset($obj->bold_records->records))
		{
			foreach ($obj->bold_records->records as $id => $record)
			{
				$_id = 'http://boldsystems.org/index.php/Public_RecordView?processid=' . $id;
	
				$exists = $couch->exists($_id);

				$go = true;
				if ($exists && !$force)
				{
					echo "Have already\n";
					$go = false;
				}

				if ($go)
				{
					$doc = $record;
					$doc->_id = $_id;
				
					if ($doc)
					{
		
						if (!$exists)
						{
							$couch->add_update_or_delete_document($doc, $doc->_id, 'add');	
						}
						else
						{
							if ($force)
							{
								$couch->add_update_or_delete_document($doc, $doc->_id, 'update');
							}
						}
					}
				}					
				
				
				
			}
		
		}
	}
	

}
	

?>
