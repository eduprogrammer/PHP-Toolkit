<?php

/*

    Copyright 2021. Eduardo Programador
    consultoria@eduardoprogramador.com
    All rights reserved.

    Library for working with Files (upload,file information, hash functions, etc.);

    1. To use this library, include it on your Php Script; require 'FileJob.php';
    2. Initiate the class with the constructor (the name of the input file element): eg.: $job = new FileJob('nameofinputfileelement');
    3. Use the get operations for retrieve file informations. Eg.: $size = $job->getFileSize();
    4. Optionally, set the file size limit in bytes. Eg.: $job->setFileLimit(1000); - 1000bytes or 1kb;
    5. Optionally, set the array string that contains the file extensions. Eg.: $job->setExtensionsSupported(array('jpg','png'));
    6. If you want to upload the file, simply call $job->upload('new_full_path_of_file');
    7. Optionally, use the static methods without initialize the class for working with server files. Eg.: FileJob->getLastAccess('server_path');

*/

class FileJob {

    /*
        private variables
    */
    private $file;
    private $size;
    private $file_extensions;
    private $procceed;
    
    /* 
        Default constructor.
        It requires a string that contains
        the file name inside input tag.
        Eg.: (HTML): <input type="file" name="myfile">
            (PHP): $fileJob = new FileJob('myFile');
    */
    function __construct($file) {
        $this->file = $file;
        $this->file_extensions = array();
        $this->procceed = FALSE;
        $this->size = 0;
    }

    
    /*
        This function informs if the file
        already exists at server side.
        TRUE or FALSE
    */
    public function exists() : bool {
        if(file_exists($this->file)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /* 
        Informs the maximum file size in Bytes (int argument).
        If this function is not called, then there won't be
        limitation for size limit.
    */
    public function setFileLimit($size_bytes) : void {
        $this->size = $size_bytes;
    } 

    /* 
        Retrive the Fie Size that the user upload in int!
    */
    public function getFileSize() : int {
        return $_FILES[$this->file]['size'];
    }

    /*
        This function informs the extensions allowed by the server.
        The argument must be an array:
        Eg.: $args = array('png','jpeg','pdf','exe');
        $fileJob->setExtensionsSupported($args);
        With that, extensions outside the array will not be allowed.
        If this function is not called, then all typed will be allowed.
    */
    public function setExtensionsSupported($array_of_extensions) : void {
        $this->file_extensions = $array_of_extensions;
    }

    /* 
        Retrive the file extension in String
    */
    public function getFileType() : string {
        return strtolower(pathinfo(basename($_FILES[$this->file]['name']),PATHINFO_EXTENSION));
    }

    /* 
        Upload the file to the server.
        The directory of the path must be provided,
        with the string type.
        Eg.: $path = "/home/user/myself/uploads/";
        $fileJob->upload($path);

        if the function succeeds, returns TRUE,
        else, returns FALSE;
    */
    public function upload($new_path) : bool {

        $destination = "";
        $destination = $new_path . basename($_FILES[$this->file]['name']);
        
        if($this->exists()) {
            return FALSE;
        } else {

            if($this->getFileSize() > $this->size && $this->size != 0) {
                return FALSE;
            } else {                

                if($this->file_extensions != NULL) {

                    $extension = $this->getFileType();

                    foreach($this->file_extensions as $ext) {

                        if(strcmp($extension,$ext) == 0) {
                            $this->procceed = TRUE;
                        }

                    }

                    if(!$this->procceed) {
                        return FALSE;
                    } else {
                        if(move_uploaded_file($_FILES[$this->file]['tmp_name'],$destination)) {
                            return TRUE;
                        } else {
                            return FALSE;
                        }
                    }

                } else {

                    if(move_uploaded_file($_FILES[$this->file]['tmp_name'],$destination)) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                }                
            }            
        }        
    }

    /* 
        Retrieve the content of the user File in String.
    */
    public function getStringContent() : string {        
        return file_get_contents($_FILES[$this->file]['tmp_name']);
    }    

    /*
        Calculates the MD5 Hashes Algorithm of the file
        that the user has uploaded.
    */
    public function getMd5() : string {
        return hash_file('md5',$_FILES[$this->file]['tmp_name']);
    }

    /*
        Calculates the Sha1 Hash Algorithm of the file
        that the user has uploaded.
    */
    public function getSha1() : string {
        return hash_file('sha1',$_FILES[$this->file]['tmp_name']);
    }

    /*
        Calculates the SHA256 Hash Algorithm of the file
        that the user has uploaded.
    */
    public function getSha256() : string {
        return hash_file('sha256',$_FILES[$this->file]['tmp_name']);
    }

    /*
        Calculates the Sha512 Hash Algorithm of the file
        that the user has uploaded.
    */
    public function getSha512() : string {
        return hash_file('sha512',$_FILES[$this->file]['tmp_name']);
    }

    /* 
        Get the file content of a server file
    */
    public static function getText($path) : string {
        return file_get_contents($path);
    }

    /*
        Copies a file to another location on server side
    */
    public static function clone($path_src, $path_dst) : void {
        copy($path_src,$path_dst);
    }

    /*
        Erases a file at server side.
    */
    public static function erase($path) : void {
        unlink($path);
    }    

    /*
        Gets the last access time of a file at server side
    */
    public static function getLastAccess($path) : string {
        return "" . date("F d Y H:i:s", fileatime($path));
    }

    /*
        Gets the last achange time of a file at server side
    */
    public static function getLastChange($path) : string {
        return "" . date("F d Y H:i:s",filectime($path));
    }

    /*
        Gets the last modification time of a file at server side
    */
    public static function getLastModification($path) : string {
        return "" . date("F d Y H:i:s",filemtime($path));
    }


}

?>