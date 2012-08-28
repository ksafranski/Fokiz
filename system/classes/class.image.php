<?php 

/*
 * This file is part of the Fokiz Content Management System 
 * <http://www.fokiz.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class Image{
    
    //////////////////////////////////////////////////////////////////
    // PROPERTIES
    ////////////////////////////////////////////////////////////////// 
    
    public $image         = ""; // Full orig. img path (ex: $_SERVER['DOCUMENT_ROOT'] . "/path/to/image.jpg")   
    public $type          = "width"; // Resize type ('width' or 'height')    
    public $max           = 100; // Max width or height (applied to $type)    
    public $quality       = 8; // Image quality (1-10)    
    public $square        = false; // Crop the image in a square (based on center)    
    public $prefix        = ""; // Prefix added to resized images        
    public $folder        = "_sized/"; // Folder for resized images (inside source folder, trailing slash req.)    
    
    
    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////
    
    // ------------------------------------------------------------ //
    
    //////////////////////////////////////////////////////////////////
    // RESIZE
    //////////////////////////////////////////////////////////////////
    
    public function Resize(){        
    
        // Split image parts (path and file)
        $imgSplit = explode("/",$this->image);
        $srcName = end($imgSplit);
        $srcUrl = str_ireplace($srcName, "", $this->image);
        $srcPath = $_SERVER['DOCUMENT_ROOT'] . str_ireplace($srcName, "", $this->image);
        
        // Get extension
        $split = explode(".",$srcName);
        $ext = strtolower($split[1]);

        // Misc variables
        $rszUrl = $srcUrl . $this->folder;
        $rszName = $this->prefix . $srcName;
        $rszPath = $srcPath . $this->folder;
        $rszQuality = $this->quality;
        
        // If save path doesn't exist, create it
        
        if (!file_exists($rszPath)){ mkdir($rszPath, 0777);    }
            
        // If the resized img doesn't exist, create it
        
        if(!file_exists("$rszPath/$rszName")){
        
            switch($ext){
                case('jpg'): $srcImage = imagecreatefromjpeg("$srcPath$srcName"); break;
                case('jpeg'): $srcImage = imagecreatefromjpeg("$srcPath$srcName"); break;
                case('png'): $srcImage = imagecreatefrompng("$srcPath$srcName"); if($rszQuality==10){ $rszQuality=9; } break;
                case('gif'): $srcImage = imagecreatefromgif("$srcPath$srcName"); break;
            }
            
            $srcWidth = imagesx($srcImage);
            $srcHeight = imagesy($srcImage);
            
            // Determine specs based on type
            
            if(strtolower($this->type)=="width"){
                $rszWidth = $this->max;
                $rszHeight = $srcHeight/($srcWidth/$rszWidth);
            }
            else{
                $rszHeight = $this->max;
                $rszWidth = $srcWidth/($srcHeight/$rszHeight);
            }
            
            // Determine specs if crop applied
            
            $srcX = 0; $srcY = 0;
            $srcNewWidth = $srcWidth; $srcNewHeight = $srcHeight;
            $dest = $srcImage;
            
            // Square crop
            
            if($this->square){
                $rszWidth = $this->max;
                $rszHeight = $this->max;
                
                if($srcHeight>$srcWidth){
                    $srcX = 0;
                    $srcY = floor(($srcHeight-$srcWidth)/2);
                    $srcNewHeight = $srcWidth;
                    $srcNewWidth = $srcWidth;
                }
                
                if($srcWidth>$srcHeight){
                    $srcX = floor(($srcWidth-$srcHeight)/2);
                    $srcY = 0;
                    $srcNewHeight = $srcHeight;
                    $srcNewWidth = $srcHeight;
                }
                // Create new image with a new width and height.
                $dest = imagecreatetruecolor($srcNewWidth, $srcNewHeight);
                
                // Copy new image to memory after cropping.
                imagecopy($dest, $srcImage, 0, 0, $srcX, $srcY, $srcNewWidth, $srcNewHeight);
            }
                
            $targetImage = imagecreatetruecolor($rszWidth,$rszHeight);    
            imagecopyresampled($targetImage,$dest,0,0,0,0,$rszWidth,$rszHeight,$srcNewWidth,$srcNewHeight);
            
            switch($ext){
                case('jpg'): imagejpeg($targetImage, "$rszPath/$rszName", $rszQuality * 10); break;
                case('jpeg'): imagejpeg($targetImage, "$rszPath/$rszName", $rszQuality * 10);    break;
                case('png'): imagepng($targetImage, "$rszPath/$rszName", $rszQuality); break;
                case('gif'): imagegif($targetImage, "$rszPath/$rszName"); break;
            }
            
        }
        
        // Return the resized image
        
        return($rszUrl . $rszName);
        
        // Clear temps
        imagedestroy($dest);
        imagedestroy($targetImage);
        
    }
    
}

?>
