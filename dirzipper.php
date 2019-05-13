<?php
$the_folder = '.';
$zip_file_name = 'sun-device-backup'.date('Ymd-His').'[generated].zip';

class FlxZipArchive extends ZipArchive {
        /** Add a Dir with Files and Subdirs to the archive;;;;; @param string $location Real Location;;;;  @param string $name Name in Archive;;; @author Nicolas Heimann;;;; @access private  **/
    public function addDir($location, $name) {
        $this->addEmptyDir($name);
        $this->addDirDo($location, $name);
     } // EO addDir;

        /**  Add Files & Dirs to archive;;;; @param string $location Real Location;  @param string $name Name in Archive;;;;;; @author Nicolas Heimann * @access private   **/
    private function addDirDo($location, $name) {
        $name .= '/';         $location .= '/';
      // Read all Files in Dir
        $dir = opendir ($location);
        while ($file = readdir($dir))    {
            if ($file == '.' || $file == '..') continue;
          // Rekursiv, If dir: FlxZipArchive::addDir(), else ::File();
            $do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
            $this->$do($location . $file, $name . $file);
			// sleep(1);
        }
    } 
}

$za = new FlxZipArchive;
$res = $za->open($zip_file_name, ZipArchive::CREATE);
if($res === TRUE) {
	$concernedFolders = array('adminSunDevice','modules','translations','upload','themes','img','override','pdf','mails','controllers','js');
	// $concernedFolders = array('upload');
	foreach($concernedFolders as $the_folder){
		$za->addDir($the_folder, basename($the_folder));
	}	
	$za->close();
	echo 'Zip archive OK!';
}
else  { echo 'Could not create a zip archive';}
?>