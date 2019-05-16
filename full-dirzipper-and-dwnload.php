<?php
class FlxZipArchive extends ZipArchive {
	/** Add a Dir with Files and Subdirs to the archive;;;;; @param string $location Real Location;;;;  @param string $name Name in Archive;;; @author Nicolas Heimann;;;; @access private  **/
    public function addDir($location, $name) {
        $this->addEmptyDir($name);
        $this->addDirDo($location, $name);
    } // EO addDir;

	/**  Add Files & Dirs to archive;;;; @param string $location Real Location;  @param string $name Name in Archive;;;;;; @author Nicolas Heimann * @access private   **/
    private function addDirDo($location, $name) {
        $name .= '/';         
		$location .= '/';
        $dir = opendir ($location);
        while ($file = readdir($dir))    {
            if ($file == '.' || $file == '..') continue;
          // Rekursiv, If dir: FlxZipArchive::addDir(), else ::File();
            $do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
            $this->$do($location . $file, $name . $file);
			// sleep(1);
        }
    } 
	
	public function downloadProject() {
		$path = $_GET['pth'];
		$project = $_GET['dir'];
		$the_folder = $_SERVER['DOCUMENT_ROOT'] . '/' . $project . '/' . $path;
		$zip_file_name = $path . '_' . time() . '.zip';
		$za = new FlxZipArchive();
		$res = $za->open($zip_file_name, ZipArchive::CREATE);
		if ($res === TRUE) {
		$za->addDir($the_folder, basename($the_folder));
		$za->close();
		} else {
		echo 'Could not create a zip archive';
		}
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-type: application/zip");
		header("Content-Disposition: attachment; filename=\"" . $zip_file_name . "\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: " . filesize($zip_file_name));
		readfile($zip_file_name);
		unlink($zip_file_name);
		die;
	}
}

$za = new FlxZipArchive;
// $za->downloadProject();

var_dump($_SERVER['DOCUMENT_ROOT']);die;
?>