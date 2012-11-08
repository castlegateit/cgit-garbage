<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Name: CGIT Garbage Collection
 *
 * Author:
 * Andy Reading
 * Castlegate IT
 * http://www.castlegateit.co.uk
 *
 * Copyright 2012 Castlegate IT Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Description: A simple garbage collection library
 * Released: 08/11/2012
 * Requirements: PHP5 or above and Codeigniter 2.0+
 */

class Cgit_garbage {

    // --------------------------------------------------------------------

    /**
     * Constructor - load the config file
     *
     * @access  public
     * @author  Andy Reading
     * @return  void
     */
    public function __construct()
    {
        $this->config->load('garbage_collection');
    }

    // --------------------------------------------------------------------

    /**
     * Access the CI supergloabl
     *
     * @access  public
     * @param   mixed
     * @author  Andy Reading
     * @return  mixed
     */
    public function __get($var)
    {
        return get_instance()->$var;
    }

    // --------------------------------------------------------------------

    /**
     * Start the garbage collection based on the chosen probability. 
     * Generally called via page requests.
     *
     * @access  public
     * @author  Andy Reading
     * @return  boolean
     */
    public function start()
    {
        if ($this->_dice_roll())
        {
            $this->run();
            return TRUE;
        }
        return FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Run garbage collection - use this method when you want it to run
     * every time. Use this method for cron jobs etc.
     *
     * @access  public
     * @author  Andy Reading
     * @return  boolean
     */
    public function run()
    {
        return $this->_collect($this->config->item('garbage_path'));
    }

    // --------------------------------------------------------------------

    /**
     * Recursivly check the directory for files and folders to be deleted
     *
     * @access  private
     * @param   string
     * @author  Andy Reading
     * @return  boolean
     */
    private function _collect($directory)
    {
        // Assume the directory is empty
        $empty = TRUE;

        if ($handle = opendir($directory))
        {
            // Loop through directory contents
            while (false !== ($file_folder = readdir($handle)))
            {
                if ($file_folder != '..' && $file_folder != '.')
                {
                    if (is_file($directory . '/' . $file_folder))
                    {
                        // If this file was not deleted then this directory not empty
                        if (!$this->_delete_file($directory . '/' . $file_folder))
                        {
                            $empty = FALSE;
                        }
                    }
                    elseif (is_dir($directory . '/' . $file_folder))
                    {
                        // If this sub-directory is not empty then the current directory is not either!
                        if (!$this->_collect($directory . '/' . $file_folder))
                        {
                            $empty = FALSE;
                        }
                        else
                        {
                            $this->_delete_directory($directory . '/' . $file_folder);
                        }
                    }
                    
                }
            }
            @closedir($handle);
        }

        // Returns whether the scanned directory is now empty
        return $empty;
    }

    // --------------------------------------------------------------------

    /**
     * Delete an empty directory
     *
     * @access  private
     * @param   string
     * @author  Andy Reading
     * @return  boolean
     */
    private function _delete_directory($directory)
    {
        return @rmdir($directory);
    }

    // --------------------------------------------------------------------

    /**
     * Delete a file if it's exceeded its lifespan
     *
     * @access  private
     * @param   string
     * @author  Andy Reading
     * @return  void
     */
    private function _delete_file($file)
    {
        if ((filemtime($file) + $this->config->item('garbage_lifespan')) < time())
        {
            return @unlink($file);
        }
        return FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Decide if we are to collect the garbage on this run or not
     *
     * @access  private
     * @author  Andy Reading
     * @return  boolean
     */
    private function _dice_roll()
    {
        return mt_rand(0, 100) <= $this->config->item('garbage_probability');
    }

    // --------------------------------------------------------------------

}

/* End of file garbage_collection.php */
/* Location: ./application/libraries/Garbage_collection.php */