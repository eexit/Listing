<?php
/**
* Listing
* @author Joris Berthelot <admin@eexit.net>
* @version 3.2 2010-12-06
* @since 2007-07-02
*/
class Listing
{
    /**
     * Application version
     */
    const VERSION = 3.2;

    /**
     * Default sorting
     */
    const SORT = 'alpha';

    /**
     * Working directory
     */
    protected $_dir;

    /**
     * File stack
     */
    protected $_files = array();

    /**
     * Files to exclude from scanning
     */
    protected $_exclude = array('index.htm', 'index.html', 'index.php');

    /**
     * Class constructor
     */
    public function __construct($dir, array $exclude = NULL)
    {
        if (is_dir($dir) && is_readable($dir)) {
            $this->_dir = $dir;

            if (!empty($exclude)) {
                $this->_exclude = $exclude;
            }

            $this->_scanDir();
        }
    }

    /**
     * Default behavior for class printing
     */
    public function __toString()
    {
        return is_null($this->listing()) ? '' : $this->listing();
    }

    /**
     * Outputs the file listing
     */
    public function listing()
    {
        if (!empty($this->_files)) {
            $this->_sort();

            $buffer = '<ul>';
            
            foreach ($this->_files as $filename => $filesize) {
                $buffer .= vsprintf('<li><a href="%s%s%s">%s</a> <span class="left">&nbsp;&nbsp;%s</span></li>' . "\n", array(
                    $this->_dir, DIRECTORY_SEPARATOR, rawurlencode($filename), htmlentities($filename), $this->_size($filesize)
                ));
            }
            
            $buffer .= '</ul>';
            return $buffer;
        }
    }

    /**
     * Formats the file size for easy-reading
     */
    protected function _size($size) {
        if ($size < 1e3) { // 0 octets > 9999 octets
            $sext = ' octets';
        } elseif ($size >= 1e3 && $size < 1e6) { // 1 Ko > 9999 Ko
            $size = round($size / 1e3);
            $sext = ' Ko';
        } elseif ($size >= 1e6 && $size < 1e7) { // 1 Mo > 9 Mo
            $size = round($size / 1e6, 3);
            $sext = ' Mo';
        } elseif ($size >= 1e7 && $size < 1e8) { // 10 Mo > 99 Mo
            $size = round($size / 1e6, 2);
            $sext = ' Mo';
        } elseif ($size >= 1e8 && $size < 1e9) { // 100 Mo > 999 Mo
            $size = round($size / 1e6, 1);
            $sext = ' Mo';
        } elseif ($size >= 1e9) { // 1000 Mo et +
            $size = round($size / 1e6);
            $sext = ' Go';
        }
        return sprintf('%d %s', $size, $sext);
    }

    /**
     * Scans the given directory
     */
    protected function _scanDir()
    {
        if ($dir = opendir($this->_dir)) {
            while ($currentfile = readdir($dir)) {
                if (!in_array($currentfile, $this->_exclude)) {
                    $this->_files[$currentfile] = filesize($this->_dir . DIRECTORY_SEPARATOR . $currentfile);
                }
            }
            closedir($dir);
        }
        $this->_files = array_splice($this->_files, 2);
    }

    /**
     * Sorts the file stack following the given arg
     */
    protected function _sort()
    {
        $sort = self::SORT;

        if (isset($_GET['s']) && !empty($_GET['s'])) {
            $sort = htmlentities(strtolower(trim((string)$_GET['s'])));
        }

        switch ($sort) {
            case 'alpha' :
                ksort($this->_files);
                break;
            case 'ralpha' :
                krsort($this->_files);
                break;
            case 'isize' :
                asort($this->_files);
                break;
            case 'dsize' :
                arsort($this->_files);
                break;
            default : 
                ksort($this->_files);
        }
    }
}
?>