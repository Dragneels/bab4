<?php
	$conn = mysql_connect("localhost","root","");
	$db = mysql_select_db("mahasiswa");
	$query = "select * from mahasiswa";
	$result = mysql_query($query);
	if ($conn) {
		echo "Berhasil Koneksi Ke Database. </br>";
	}
	if ($db) {
		echo "Database ditemukan. </br>";
	}

	$datamahasiswa = array();

	while ($data = mysql_fetch_array($result)) {
		$datamahasiswa [] = array('nim' => $data['nim'], 'nama' => $data['nama'],'alamat' => $data['alamat'],'prodi' => $data['prodi']);
	}

		$document = new domdocument;
		$document->formatOutput = true;
		$root = $document->createELement("data");
		$document->appendChild($root);
		foreach ($datamahasiswa as $mahasiswa) {
			$block = $document->createElement("mahasiswa");
			$nim = $document->createElement("nim");
			$nim->appendChild($document->createTextNode($mahasiswa['nim']));
			$block->appendChild($nim);

			$nama = $document->createELement("nama");
			$nama->appendChild($document->createTextNode($mahasiswa['nama']));
			$block->appendChild($nama);

			$alamat = $document->createELement("alamat");
			$alamat->appendChild($document->createTextNode($mahasiswa['alamat']));
			$block->appendChild($alamat);

			$prodi = $document->createELement("prodi");
			$prodi->appendChild($document->createTextNode($mahasiswa['prodi']));
			$block->appendChild($prodi);

			$root->appendChild($block);
		}

		$generateXML = $document->save("mahasiswa.xml");
		if ($generateXML) {
			echo "Berhasil Menggenerate mahasiswa.xml dari database";
		}

		$url = "http://localhost/sit/xml/mahasiswa.xml";
		$client = curl_init($url);
		curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($client);
		curl_close($client);

		$datamahasiswaxml = simplexml_load_string($response);
		echo "<table border='1'>
				<tr>
					<td>NIM</td>
					<td>Nama</td>
					<td>Alamat</td>
					<td>Prodi</td>
				</tr>	
		";
		foreach ($datamahasiswaxml->mahasiswa as $mahasiswa) {
			echo "<tr>
					<td>".$mahasiswa->nim."</td>
					<td>".$mahasiswa->nama."</td>
					<td>".$mahasiswa->alamat."</td>
					<td>".$mahasiswa->prodi."</td>
				</tr>";
		}
		echo "</table>";
?>