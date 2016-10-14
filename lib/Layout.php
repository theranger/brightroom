<?php

include_once "img/ImageHandler.php";
include_once "img/ExifParser.php";

class Layout {

	private $fileSystemHandler;
	private $session;
	private $settings;
	private $urlParser;

	private $exifParser;
	private $isImage;
	private $isRoot;

	public function __construct(FileSystemHandler &$fileSystemHandler, Session &$session, URLParser &$urlParser, Settings &$settings) {
		$this->fileSystemHandler = $fileSystemHandler;
		$this->session = $session;
		$this->urlParser = $urlParser;
		$this->settings = $settings;
		$this->isImage = !$this->fileSystemHandler->isDirectory($urlParser->getURL());
		$this->isRoot = $this->urlParser->isRoot();
	}

	public function isShowExif(): bool {
		return $this->settings->showExif;
	}

	public function isImage(): bool {
		return $this->isImage;
	}

	public function isRoot(): bool {
		return $this->isRoot;
	}

	public function getExif(): ExifParser {
		if($this->exifParser == null) {
			if(!$this->isImage) return null;

			$this->exifParser = new ExifParser($this->fileSystemHandler->getFullPath($this->urlParser->getURL()));
		}

		return $this->exifParser;
	}

	/**
	 * Retrieve local filesystem path of current theme directory
	 * @return String to file system path of the current theme
	 */
	public function getThemePath(): string {
		return dirname(__FILE__)."/../themes/".$this->getTheme();
	}

	public function printVersion() {
		print $this->settings->version;
	}

	public function printDirectoryURL() {
		print $this->urlParser->getDocumentRoot().$this->urlParser->getDirectory();
	}

	public function printBreadcrumb() {

		print '<a href="'.$this->urlParser->getDocumentRoot().'/" class="sfg-breadcrumb">http'.(isset($_SERVER["HTTPS"])?"s":"").'://'.$_SERVER["SERVER_NAME"].$this->urlParser->getDocumentRoot().'</a>';

		$url="";
		$path = explode("/",$this->urlParser->getURL());
		foreach($path as $el) {
			if(empty($el)) continue;

			$url.='/'.$el;
			print '<a href="'.$this->urlParser->getDocumentRoot().$url.'" class="sfg-breadcrumb">/'.$el.'</a>';
		}
	}



	public function printFolderContents(bool $folders = true, bool $files = true) {
		$directory = $this->urlParser->getDirectory();
		$file = $this->urlParser->getImage();
		$items = $this->fileSystemHandler->getFilesArray($directory);

		print '<div class="sfg-imagelist">';

		// If url is not empty, we are in a subgallery. Show link to parent gallery
		if($folders == true && !empty($directory) && file_exists($this->getThemePath().'/images/upfolder.png'))
			$this->renderImage($this->getThemeURL().'/images/upfolder.png', dirname($directory), "", "..", false);

		$k=count($items);
		for($i=0;$i<$k;$i++) {

			//Anchor some images backwards, this way some previous images can also be seen from listing
			$anchor = $items[$i-$this->settings->anchorOffset>=0?$i-$this->settings->anchorOffset:0]["name"];
			$name = $items[$i]["name"];

			if($items[$i]["type"]=="directory" && $folders == true && $this->session->authorize($directory."/".$name) && (!defined("VETO_FOLDERS") || strpos(VETO_FOLDERS, '/'.$name.'/') === false))
				$this->renderImage($this->urlParser->getImagePrefix().$directory.'/'.$name.'?sfg-size='.$this->settings->thumbnailSize, $directory."/".$name, "", $name, $name == $file);
			elseif($items[$i]["type"]=="image" && $files == true)
				$this->renderImage($this->urlParser->getImagePrefix().$directory.'/'.$name.'?sfg-size='.$this->settings->thumbnailSize, $directory."/".$name, $anchor, "", $name == $file);
			elseif($this->settings->showImagesOnly == false && $files == true)
				$this->renderImage($this->urlParser->getImagePrefix().$directory.'/'.$name.'?sfg-size='.$this->settings->thumbnailSize, $directory."/".$name, $anchor, $name, $name == $file);
		}
		print '</div>';
	}

	public function printFolderTree(array &$items = null) {
		if($items != null && $items["count"] == 0) return;
		$level="";

		if($items == null) {
			$items = $this->fileSystemHandler->getFolderArray($this->urlParser->getDirectory());
			$level = "root";
		}

		print '<ul class="sfg-foldertree '.$level.'">';
		for($i = 0; $i < $items["count"]; $i++) {
			if(!$this->session->authorize($items[$i]["link"])) continue;
			if(defined("VETO_FOLDERS") && strpos(VETO_FOLDERS, '/'.$items[$i]["name"].'/') !== false) continue;

			print '<li>';
			print '<a href="'.$items[$i]["link"].'">'.$items[$i]["name"].'</a>';
			$this->printFolderTree($items[$i]["items"]);
			print '</li>';
		}
		print '</ul>';
	}

	public function getImage(int $size, string $url = "") {
		if(empty($url)) $url = $this->urlParser->getURL();
		if($this->fileSystemHandler->isDirectory($url)) return;

		$mimeType = dirname($this->fileSystemHandler->getMimeType($url));
		if($this->settings->showImagesOnly == true && $mimeType != "image") return;

		$directory = $this->urlParser->getDirectory();
		$file = $this->urlParser->getImage();

		$previousFile = $this->fileSystemHandler->getIndexOf($directory, $file, -1, false);
		$previousBookmark =  $this->fileSystemHandler->getIndexOf($directory, $file, -($this->settings->anchorOffset+1), false);

		$nextFile = $this->fileSystemHandler->getIndexOf($directory, $file, 1, false);
		$nextBookmark = $this->fileSystemHandler->getIndexOf($directory, $file, -($this->settings->anchorOffset-1), false);

		print '<div class="sfg-single">';
		print '<img src="'.$this->urlParser->getImagePrefix().$url.'?sfg-size='.($size>0?$size:$this->settings->imageSize).'" />';
		print '<a class="sfg-previous" href="'.$this->urlParser->getDocumentRoot().$directory."/".$previousFile.'#'.$previousBookmark.'"></a>';
		print '<a class="sfg-next" href="'.$this->urlParser->getDocumentRoot().$directory."/".$nextFile.'#'.$nextBookmark.'"></a>';
		if($this->settings->overlayTitle && $this->getExif()->getDescription() != "") print '<h1 class="sfg-alpha20">'.$this->getExif()->getDescription().'</h1>';
		print '</div>';
	}

	public function readFile(string $url): string {
		return $this->fileSystemHandler->readFile($url);
	}

	// Always returns Content-Type: image/...
	// Not for regular HTML output!
	public function getFile(string $url, int $size) {
		$mimeType = $this->fileSystemHandler->getMimeType($url);
		header("Content-Type: ".$mimeType);

		if(is_numeric($size) && $size > 0) {
			$ih = new ImageHandler($mimeType, $this->settings);

			$exif = new ExifParser($this->fileSystemHandler->getFullPath($url));
			$ih->resizeImage($this->fileSystemHandler->getFullPath($url), $size, $exif->getOrientation());
		}
		else {
			$this->fileSystemHandler->getFile($url);
		}
	}

	public function getBadge(string $directoryURL, int $size) {
		header("Content-Type: image/jpeg");
		if(is_numeric($size) && $size > 0) {
			$ih = new ImageHandler("image/jpeg", $this->settings);
			$ih->assembleImage($this->fileSystemHandler, $directoryURL, $size, $this->getThemePath().'/images/directory.jpg');
		}
		else {
			$this->fileSystemHandler->getFile($this->getThemeURL().'/images/directory.jpg');
		}
	}

	private function renderImage(string $imageURL, string $linkURL, string $anchorName, string $imageText, bool $isCurrent) {
		print '<div class="sfg-image'.($isCurrent?" sfg-selected":"").'">';

		if($anchorName == null)
			print '<a href="'.$this->urlParser->getDocumentRoot().$linkURL.'"><img src="'.$imageURL.'" /></a>';
		else
			print '<a class="sfg-image" name="'.basename($linkURL).'" href="'.$this->urlParser->getDocumentRoot().$linkURL.'#'.$anchorName.'"><img src="'.$imageURL.'" /></a>';

		print '<p>'.$imageText.'</p>';
		print '</div>';
	}

	public function printReadme() {
		print $this->fileSystemHandler->readFile($this->urlParser->getURL()."/".$this->settings->readmeFile);
	}

	public function printFileCount() {
		print $this->fileSystemHandler->getFileCount($this->urlParser->getURL());
	}

	public function printDirectorySize() {
		print $this->fileSystemHandler->getDirectorySizeHuman($this->urlParser->getURL());
	}
}
